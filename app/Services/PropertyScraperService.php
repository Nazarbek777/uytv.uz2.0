<?php

namespace App\Services;

use App\Models\AiFraudDetection;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\Api as TelegramApi;

class PropertyScraperService
{
    protected ?TelegramApi $telegram = null;
    protected ?string $openAiKey;
    protected string $openAiModel;
    protected array $channels;
    protected array $priceAnalysisConfig = [];
    protected ?int $defaultProviderId = null;
    protected int $days = 7; // Necha kunga qadar eski postlarni olish

    public function __construct()
    {
        // OpenAI API Key'ni config yoki env'dan olish
        $this->openAiKey = config('openai.api_key') ?: env('OPENAI_API_KEY');
        $this->openAiModel = env('OPENAI_MODEL', 'gpt-4o-mini') ?: config('openai.model', 'gpt-4o-mini');
        $this->channels = collect(explode(',', (string) env('SCRAPER_TELEGRAM_CHANNELS', '')))
            ->map(fn ($channel) => Str::lower(ltrim(trim($channel), '@')))
            ->filter()
            ->values()
            ->all();
        $this->priceAnalysisConfig = config('scraper.price_analysis', [
            'default_currency' => 'UZS',
            'usd_to_uzs_rate' => 12500,
            'min_price_per_m2' => ['UZS' => 4000000, 'USD' => 320],
            'max_price_per_m2' => ['UZS' => 40000000, 'USD' => 3200],
        ]);

        $token = env('TELEGRAM_BOT_TOKEN');
        if ($token) {
            $this->telegram = new TelegramApi($token);
        }
    }

    /**
     * Kanal ro'yxatini o'rnatish
     */
    public function setChannels(array $channels): self
    {
        $this->channels = collect($channels)
            ->map(fn ($channel) => Str::lower(ltrim(trim($channel), '@')))
            ->filter()
            ->values()
            ->all();
        return $this;
    }

    /**
     * Kunlar sonini o'rnatish
     */
    public function setDays(int $days): self
    {
        $this->days = max(1, min(365, $days));
        return $this;
    }

    /**
     * Telegram postlardan property ma'lumotlarini yig'ish.
     */
    public function scrapeProperties(int $limit = 100): array
    {
        if (!$this->openAiKey) {
            Log::warning('PropertyScraperService: OPENAI_API_KEY topilmadi.');
            return [];
        }

        $posts = $this->fetchTelegramPosts($limit);
        $results = [];

        foreach ($posts as $post) {
            if (count($results) >= $limit) {
                break;
            }

            $parsed = $this->parsePostWithOpenAI($post);
            if ($parsed) {
                $results[] = $parsed;
            }
        }

        return $results;
    }

    /**
     * Kanal linkini yoki username'ni berib, OpenAI orqali kanaldagi postlarni tahlil qilish
     * Bot ishlatmasdan, faqat OpenAI orqali
     */
    public function scrapeFromChannelLink(string $channelLink, int $limit = 50): array
    {
        if (!$this->openAiKey) {
            Log::warning('PropertyScraperService: OPENAI_API_KEY topilmadi.');
            return [];
        }

        // Kanal linkini tozalash
        $username = $this->extractUsernameFromLink($channelLink);
        if (!$username) {
            Log::warning('PropertyScraperService: Kanal username topilmadi.', ['link' => $channelLink]);
            return [];
        }

        // Telegram kanal web sahifasini olish (matn va rasmlar bilan)
        $channelData = $this->fetchChannelWebPage($username);
        $channelContent = $channelData['text'] ?? '';
        $channelImages = $channelData['images'] ?? [];
        
        if (empty($channelContent)) {
            Log::warning('PropertyScraperService: Kanal web sahifasi olinmadi yoki bo\'sh.', ['channel' => $username]);
            return []; // Agar kontent bo'sh bo'lsa, hech narsa qaytarmaymiz
        }
        
        Log::info('PropertyScraperService: Kanal kontenti olindi', [
            'channel' => $username,
            'text_length' => strlen($channelContent),
            'images_count' => count($channelImages),
        ]);

        // OpenAI API Key tekshirish
        if (empty($this->openAiKey)) {
            Log::error('PropertyScraperService: OPENAI_API_KEY topilmadi. Iltimos, .env faylida OPENAI_API_KEY ni sozlang yoki admin panelda sozlamalarni yangilang.');
            return [];
        }

        // OpenAI orqali kanaldagi postlarni tahlil qilish
        $results = [];
        
        try {
            // OpenAI client yaratish
            $client = \OpenAI::client($this->openAiKey);

            // OpenAI'ga kanal kontentini yuborib, kanaldagi uy-joy e'lonlarini tahlil qilishni so'rash
            $response = $client->chat()->create([
                'model' => $this->openAiModel,
                'temperature' => 0.2,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Siz tajribali ko'chmas mulk agenti va moliyaviy analitiksiz. Telegram kanallaridagi uy-joy e'lonlarini chuqur tahlil qiling: narxlar, maydon (m²), valyuta, manzil va aloqa ma'lumotlarini aniqlang. Har bir e'lon uchun narx/m² hisoblang, valyuta aniqligining ishonchliligini baholang, telefon raqamlarini toping, manzilga mos keluvchi taxminiy koordinatalarni (latitude/longitude) chiqaring va shubhali holatlar uchun flag qo'ying. Agar raqamlar bo'yicha mantiqsizlik bo'lsa, buni `flags` arrayida aniq ko'rsating va `price_confidence` qiymatini pasaytiring. Har doim faqat to'liq JSON array qaytaring, boshqa matn yozmang.",
                    ],
                    [
                        'role' => 'user',
                        'content' => "Telegram kanal kontenti (https://t.me/{$username}):\n<<<\n{$channelContent}\n>>>\n\n" . (!empty($channelImages) ? "Kanal rasmlari: " . implode(', ', array_slice($channelImages, 0, 5)) . "\n\n" : "") . "Bu kanaldagi SO'NGGI {$limit} ta uy-joy e'lonlarini tahlil qilib, quyidagi JSON formatida qaytaring. Har bir e'lon alohida object bo'lsin va ko'rsatilgan maydonlar to'ldirilsin:\n\n[\n  {\n    \"title\": \"Uy-joy sarlavhasi\",\n    \"description\": \"Tavsif\",\n    \"address\": \"Manzil\",\n    \"city\": \"Toshkent\",\n    \"price\": 100000000,\n    \"currency\": \"UZS\",\n    \"price_per_m2\": 5000000,\n    \"price_confidence\": 0.82,\n    \"area\": 70,\n    \"bedrooms\": 3,\n    \"listing_type\": \"sale\",\n    \"property_type\": \"apartment\",\n    \"contact_phone\": \"+998 90 123 45 67\",\n    \"latitude\": 41.3111,\n    \"longitude\": 69.2797,\n    \"geo_confidence\": 0.7,\n    \"nearby_places\": [\"Chilonzor metro\", \"Makro supermarket\"],\n    \"flags\": [\"price_per_m2_below_market\"],\n    \"notes\": \"Narx juda past, ehtimol USD\",\n    \"source_id\": \"unique_id\",\n    \"source_url\": \"https://t.me/{$username}/123\",\n    \"image_urls\": [\"url1\", \"url2\"],\n    \"translations\": {\n      \"uz\": {\"title\": \"Sarlavha\", \"description\": \"Tavsif\", \"address\": \"Manzil\"},\n      \"ru\": {\"title\": \"Заголовок\", \"description\": \"Описание\", \"address\": \"Адрес\"},\n      \"en\": {\"title\": \"Title\", \"description\": \"Description\", \"address\": \"Address\"}\n    }\n  }\n]\n\nMUHIM: Faqat real uy-joy e'lonlarini qaytaring. Narx/m², telefon, koordinata va flaglarni to'ldirish shart. Agar e'lonlar topilmasa, bo'sh array qaytaring [].",
                    ],
                ],
                'max_tokens' => 4000,
            ]);

            $content = $response->choices[0]->message->content ?? '';
            
            Log::info('PropertyScraperService: OpenAI javobi olindi', [
                'channel' => $username,
                'content_length' => strlen($content),
                'content_preview' => substr($content, 0, 200),
            ]);
            
            $parsed = $this->extractJson($content);
            
            Log::info('PropertyScraperService: JSON parse natijasi', [
                'channel' => $username,
                'parsed' => $parsed ? 'success' : 'failed',
                'items_count' => is_array($parsed) ? count($parsed) : 0,
            ]);

            if ($parsed && is_array($parsed)) {
                foreach ($parsed as $item) {
                    if (empty($item['title']) || empty($item['price'])) {
                        Log::debug('PropertyScraperService: Item o\'tkazib yuborildi', [
                            'title' => $item['title'] ?? 'N/A',
                            'price' => $item['price'] ?? 'N/A',
                        ]);
                        continue;
                    }

                    // Rasmlarni olish va yuklab olish
                    $imageUrls = $item['image_urls'] ?? [];
                    
                    // Agar OpenAI'dan rasmlar kelmasa, kanal web sahifasidan olingan rasmlarni ishlatish
                    if (empty($imageUrls) && !empty($channelImages)) {
                        $imageUrls = array_slice($channelImages, 0, 5); // Faqat birinchi 5 ta
                    }
                    
                    $savedImages = $this->downloadAndSaveImages($imageUrls, $username);

                    $payload = [
                        'title' => $item['title'] ?? null,
                        'description' => $item['description'] ?? null,
                        'address' => $item['address'] ?? null,
                        'city' => $item['city'] ?? null,
                        'price' => isset($item['price']) ? (float) $item['price'] : null,
                        'currency' => $item['currency'] ?? 'UZS',
                        'price_per_m2' => isset($item['price_per_m2']) ? (float) $item['price_per_m2'] : null,
                        'price_confidence' => isset($item['price_confidence']) ? (float) $item['price_confidence'] : null,
                        'area' => isset($item['area']) ? (float) $item['area'] : null,
                        'bedrooms' => $item['bedrooms'] ?? null,
                        'listing_type' => $item['listing_type'] ?? 'sale',
                        'property_type' => $item['property_type'] ?? 'apartment',
                        'latitude' => isset($item['latitude']) ? (float) $item['latitude'] : null,
                        'longitude' => isset($item['longitude']) ? (float) $item['longitude'] : null,
                        'status' => 'pending',
                        'source' => 'telegram',
                        'source_id' => $item['source_id'] ?? uniqid('tg_'),
                        'source_url' => $item['source_url'] ?? "https://t.me/{$username}",
                        'images' => $savedImages,
                        'featured_image' => !empty($savedImages) ? $savedImages[0] : null,
                        'translations' => $item['translations'] ?? [],
                        'flags' => $item['flags'] ?? [],
                        'notes' => $item['notes'] ?? null,
                        'contact_phone' => $item['contact_phone'] ?? null,
                        'geo_confidence' => isset($item['geo_confidence']) ? (float) $item['geo_confidence'] : null,
                        'nearby_places' => $item['nearby_places'] ?? null,
                    ];

                    $results[] = $this->applyServerSideAnalysis($payload);
                }
            } else {
                Log::warning('PropertyScraperService: JSON parse qilinmadi yoki bo\'sh', [
                    'channel' => $username,
                    'content_preview' => substr($content, 0, 500),
                ]);
            }
        } catch (\Throwable $exception) {
            Log::error('PropertyScraperService: OpenAI kanal tahlili xatosi', [
                'message' => $exception->getMessage(),
                'channel' => $username,
                'trace' => substr($exception->getTraceAsString(), 0, 500),
            ]);
        }
        
        Log::info('PropertyScraperService: Scrape natijasi', [
            'channel' => $username,
            'results_count' => count($results),
        ]);

        return $results;
    }

    /**
     * Telegram kanal web sahifasini olish (matn va rasmlar bilan)
     */
    protected function fetchChannelWebPage(string $username): array
    {
        $result = ['text' => '', 'images' => []];
        
        try {
            $url = "https://t.me/s/{$username}";
            
            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                ])
                ->get($url);

            if ($response->successful()) {
                $html = $response->body();
                
                // Rasmlarni olish
                preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $imageMatches);
                if (!empty($imageMatches[1])) {
                    foreach ($imageMatches[1] as $imgUrl) {
                        // Telegram rasmlarini filtrlash
                        if (preg_match('/\/file\/|cdn\.telegram|t\.me\/file\//', $imgUrl)) {
                            // To'liq URL yaratish
                            if (strpos($imgUrl, 'http') !== 0) {
                                $imgUrl = 'https://t.me' . $imgUrl;
                            }
                            $result['images'][] = $imgUrl;
                        }
                    }
                    // Faqat birinchi 10 ta rasm
                    $result['images'] = array_slice(array_unique($result['images']), 0, 10);
                }
                
                // HTML'dan faqat matnli kontentni ajratib olish
                $html = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $html);
                $html = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/mi', '', $html);
                
                // Telegram kanal postlarini olish (data-post attribute'dan)
                preg_match_all('/data-post=["\']([^"\']+)["\']/i', $html, $postMatches);
                
                // Agar data-post topilmasa, barcha matnni olish
                if (empty($postMatches[1])) {
                    // Message text'larini olish
                    preg_match_all('/<div[^>]*class="[^"]*tgme_widget_message_text[^"]*"[^>]*>(.*?)<\/div>/is', $html, $messageMatches);
                    if (!empty($messageMatches[1])) {
                        foreach ($messageMatches[1] as $message) {
                            $messageText = strip_tags($message);
                            $messageText = preg_replace('/\s+/', ' ', $messageText);
                            $messageText = trim($messageText);
                            if (!empty($messageText) && strlen($messageText) > 30) {
                                $result['text'] .= $messageText . "\n\n";
                            }
                        }
                    } else {
                        // Oddiy text olish
                        $text = strip_tags($html);
                        $text = preg_replace('/\s+/', ' ', $text);
                        $text = trim($text);
                        $result['text'] = $text;
                    }
                } else {
                    // Data-post'dan ma'lumotlarni olish
                    foreach ($postMatches[1] as $postData) {
                        $result['text'] .= $postData . "\n";
                    }
                }
                
                // Faqat uy-joy bilan bog'liq qismlarni filtrlash (yumshoq filtrlash)
                if (!empty($result['text'])) {
                    $lines = explode("\n", $result['text']);
                    $relevantLines = [];
                    foreach ($lines as $line) {
                        $line = trim($line);
                        // Qisqa qatorlarni o'tkazib yuborish
                        if (empty($line) || strlen($line) < 15) {
                            continue;
                        }
                        // Reklama yoki boshqa postlarni filtrlash
                        if (preg_match('/(эълон|реклам|admin|админ|murojaat|обращени|instagram|tiktok|telegram|канал|channel)/i', $line) && strlen($line) < 100) {
                            continue; // Qisqa reklama postlarini o'tkazib yuborish
                        }
                        // Barcha qatorlarni olish (OpenAI o'zi filtrlash qiladi)
                        $relevantLines[] = $line;
                    }
                    
                    // Faqat birinchi 300 ta qator (OpenAI token limiti uchun)
                    $result['text'] = implode("\n\n", array_slice($relevantLines, 0, 300));
                }
            }
        } catch (\Throwable $e) {
            Log::debug('PropertyScraperService: Kanal web sahifasi olinmadi', [
                'channel' => $username,
                'error' => $e->getMessage(),
            ]);
        }

        return $result;
    }

    /**
     * Kanal linkidan username'ni ajratib olish
     */
    protected function extractUsernameFromLink(string $link): ?string
    {
        // Tozalash
        $link = trim($link);
        
        // Agar to'g'ridan-to'g'ri username bo'lsa
        if (preg_match('/^@?([a-zA-Z0-9_]+)$/', $link, $matches)) {
            return strtolower($matches[1]);
        }
        
        // Agar link bo'lsa
        if (preg_match('/t\.me\/([a-zA-Z0-9_]+)/', $link, $matches)) {
            return strtolower($matches[1]);
        }
        
        // Agar to'liq URL bo'lsa
        if (preg_match('/https?:\/\/t\.me\/([a-zA-Z0-9_]+)/', $link, $matches)) {
            return strtolower($matches[1]);
        }
        
        return null;
    }

    /**
     * Rasmlarni yuklab olish va saqlash
     */
    protected function applyServerSideAnalysis(array $payload, ?string $sourceText = null): array
    {
        $issues = [];
        $notes = [];
        $flags = collect($payload['flags'] ?? [])->filter()->values()->all();
        $score = 0;

        $priceConfidence = $payload['price_confidence'] ?? 0.85;
        $currency = strtoupper($payload['currency'] ?? ($this->priceAnalysisConfig['default_currency'] ?? 'UZS'));
        $payload['currency'] = $currency;

        $price = isset($payload['price']) ? (float) $payload['price'] : null;
        $area = isset($payload['area']) ? (float) $payload['area'] : null;
        $pricePerM2 = isset($payload['price_per_m2']) ? (float) $payload['price_per_m2'] : null;

        if ($price && $area && $area > 0) {
            if (!$pricePerM2) {
                $pricePerM2 = $price / max($area, 1);
            }

            $payload['price_per_m2'] = round($pricePerM2, 2);

            $minPerM2 = $this->priceAnalysisConfig['min_price_per_m2'][$currency] ?? null;
            $maxPerM2 = $this->priceAnalysisConfig['max_price_per_m2'][$currency] ?? null;

            if ($minPerM2 && $pricePerM2 < $minPerM2) {
                $issues[] = 'price_per_m2_below_market';
                $flags[] = 'price_per_m2_below_market';
                $notes[] = "Narx/m² juda past ({$pricePerM2} {$currency}).";
                $score += 35;
                $priceConfidence -= 0.25;
            }

            if ($maxPerM2 && $pricePerM2 > $maxPerM2) {
                $issues[] = 'price_per_m2_above_market';
                $flags[] = 'price_per_m2_above_market';
                $notes[] = "Narx/m² juda yuqori ({$pricePerM2} {$currency}).";
                $score += 25;
                $priceConfidence -= 0.2;
            }
        } elseif ($price && empty($area)) {
            $issues[] = 'area_missing_for_price_validation';
            $flags[] = 'area_missing_for_price_validation';
            $notes[] = 'Narx ko\'rsatilgan, ammo maydon (m²) yo\'q.';
            $score += 10;
            $priceConfidence -= 0.1;
        }

        if ($area) {
            $minArea = $this->priceAnalysisConfig['min_area'] ?? null;
            $maxArea = $this->priceAnalysisConfig['max_area'] ?? null;

            if ($minArea && $area < $minArea) {
                $issues[] = 'area_below_minimum';
                $flags[] = 'area_below_minimum';
                $notes[] = "Maydon juda kichik ({$area} m²).";
                $score += 10;
            }

            if ($maxArea && $area > $maxArea) {
                $issues[] = 'area_above_maximum';
                $flags[] = 'area_above_maximum';
                $notes[] = "Maydon odatiy diapazondan katta ({$area} m²).";
                $score += 10;
            }
        }

        if ($price) {
            $minAbsolute = $this->priceAnalysisConfig['min_price_absolute'][$currency] ?? null;
            if ($minAbsolute && $price < $minAbsolute) {
                $issues[] = 'price_below_city_floor';
                $flags[] = 'price_below_city_floor';
                $notes[] = "Narx juda past ({$price} {$currency}).";
                $score += 20;
                $priceConfidence -= 0.15;
            }
        }

        $payload['flags'] = array_values(array_unique($flags));
        $payload['price_confidence'] = round(max(0.05, min(1, $priceConfidence)), 2);

        $phones = $this->extractPhoneNumbers($payload, $sourceText);
        if (!empty($phones)) {
            $payload['contact_phone'] = $payload['contact_phone'] ?? ($phones[0] ?? null);
            $payload['phones'] = $phones;
        }

        if ((empty($payload['latitude']) || empty($payload['longitude'])) && $coords = $this->geocodeLocation($payload)) {
            $payload['latitude'] = $coords['lat'];
            $payload['longitude'] = $coords['lng'];
            $payload['geo_confidence'] = $payload['geo_confidence'] ?? $coords['confidence'];
            if (!empty($coords['notes'])) {
                $notes[] = $coords['notes'];
            }
        }

        $payload['analysis'] = [
            'issues' => array_values(array_unique($issues)),
            'notes' => array_values(array_filter($notes)),
            'score' => min(100, $score),
            'price_per_m2' => $payload['price_per_m2'] ?? null,
            'price_confidence' => $payload['price_confidence'],
            'currency' => $currency,
            'phones' => $phones,
        ];

        return $payload;
    }

    protected function extractPhoneNumbers(array $payload, ?string $sourceText = null): array
    {
        $candidates = [];
        $fields = [
            $payload['contact_phone'] ?? null,
            $payload['description'] ?? null,
            $payload['title'] ?? null,
            $payload['notes'] ?? null,
        ];

        if ($sourceText) {
            $fields[] = $sourceText;
        }

        foreach ($fields as $field) {
            if (empty($field) || !is_string($field)) {
                continue;
            }

            preg_match_all('/(\+?\d[\d\s\-\(\)]{6,}\d)/u', $field, $matches);
            if (!empty($matches[0])) {
                $candidates = array_merge($candidates, $matches[0]);
            }
        }

        $normalized = collect($candidates)
            ->map(fn ($phone) => $this->normalizePhoneNumber($phone))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $normalized;
    }

    protected function normalizePhoneNumber(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (empty($digits) || strlen($digits) < 7) {
            return null;
        }

        if (str_starts_with($digits, '998') && strlen($digits) === 12) {
            return '+' . $digits;
        }

        if (strlen($digits) === 9) {
            return '+998' . $digits;
        }

        if (strlen($digits) >= 11 && str_starts_with($digits, '00')) {
            return '+' . substr($digits, 2);
        }

        if (strlen($digits) >= 11 && $digits[0] !== '+') {
            return '+' . $digits;
        }

        return '+' . ltrim($digits, '+');
    }

    protected function geocodeLocation(array $payload): ?array
    {
        $addressParts = array_filter([
            $payload['address'] ?? null,
            $payload['city'] ?? null,
            $payload['region'] ?? null,
        ]);

        $query = trim(implode(', ', $addressParts));

        if (strlen($query) < 5) {
            return null;
        }

        $cacheKey = 'scraper:geocode:' . md5(mb_strtolower($query));

        return Cache::remember($cacheKey, now()->addDay(), function () use ($query) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)
                    ->withHeaders([
                        'User-Agent' => 'uytv.uz scraper/1.0',
                    ])
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $query,
                        'format' => 'json',
                        'limit' => 1,
                        'countrycodes' => 'uz',
                    ]);

                if (!$response->successful() || empty($response[0])) {
                    return null;
                }

                $item = $response[0];

                return [
                    'lat' => isset($item['lat']) ? (float) $item['lat'] : null,
                    'lng' => isset($item['lon']) ? (float) $item['lon'] : null,
                    'confidence' => 0.65,
                    'notes' => 'Geocode: ' . ($item['display_name'] ?? $query),
                ];
            } catch (\Throwable $exception) {
                Log::debug('PropertyScraperService: Geocode muvaffaqiyatsiz', [
                    'query' => $query,
                    'error' => $exception->getMessage(),
                ]);
                return null;
            }
        });
    }

    protected function downloadAndSaveImages(array $imageUrls, string $channelUsername): array
    {
        if (empty($imageUrls)) {
            return [];
        }

        $savedImages = [];
        $storagePath = 'properties/' . date('Y/m');

        foreach ($imageUrls as $imageUrl) {
            try {
                // URL'ni tozalash
                $imageUrl = trim($imageUrl);
                if (empty($imageUrl) || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    continue;
                }

                // Rasmni yuklab olish
                $response = \Illuminate\Support\Facades\Http::timeout(30)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    ])
                    ->get($imageUrl);

                if (!$response->successful()) {
                    Log::debug('PropertyScraperService: Rasm yuklab olinmadi', [
                        'url' => $imageUrl,
                        'status' => $response->status(),
                    ]);
                    continue;
                }

                // Rasm ma'lumotlarini olish
                $imageContent = $response->body();
                $imageSize = strlen($imageContent);

                // Rasm hajmini tekshirish (max 10MB)
                if ($imageSize > 10 * 1024 * 1024) {
                    Log::debug('PropertyScraperService: Rasm hajmi juda katta', [
                        'url' => $imageUrl,
                        'size' => $imageSize,
                    ]);
                    continue;
                }

                // Rasm formatini aniqlash
                $extension = 'jpg';
                $contentType = $response->header('Content-Type');
                if (strpos($contentType, 'png') !== false) {
                    $extension = 'png';
                } elseif (strpos($contentType, 'webp') !== false) {
                    $extension = 'webp';
                } elseif (strpos($contentType, 'gif') !== false) {
                    $extension = 'gif';
                }

                // Fayl nomini yaratish
                $filename = uniqid('tg_' . $channelUsername . '_', true) . '.' . $extension;
                $filePath = $storagePath . '/' . $filename;

                // Rasmni saqlash
                \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $imageContent);

                $savedImages[] = $filePath;

                // Faqat birinchi 10 ta rasm
                if (count($savedImages) >= 10) {
                    break;
                }
            } catch (\Throwable $e) {
                Log::debug('PropertyScraperService: Rasm yuklash xatosi', [
                    'url' => $imageUrl,
                    'error' => $e->getMessage(),
                ]);
                continue;
            }
        }

        return $savedImages;
    }

    protected function logFraudDetection(Property $property, array $analysis): void
    {
        $issues = $analysis['issues'] ?? [];

        if (empty($issues)) {
            return;
        }

        AiFraudDetection::updateOrCreate(
            [
                'model_type' => Property::class,
                'model_id' => $property->id,
            ],
            [
                'fraud_score' => $analysis['score'] ?? 0,
                'detected_issues' => $issues,
                'ai_analysis' => [
                    'price_per_m2' => $analysis['price_per_m2'] ?? null,
                    'price_confidence' => $analysis['price_confidence'] ?? null,
                    'notes' => $analysis['notes'] ?? [],
                ],
                'status' => 'pending',
            ]
        );
    }

    /**
     * Tayyorlangan property massivini database'ga saqlash.
     */
    public function saveProperties(array $properties): int
    {
        $saved = 0;

        foreach ($properties as $payload) {
            if (empty($payload['title']) || empty($payload['price']) || empty($payload['source_id'])) {
                continue;
            }

            $property = Property::firstOrNew([
                'source' => $payload['source'] ?? 'telegram',
                'source_id' => $payload['source_id'],
            ]);

            if ($property->exists && $property->status === 'published') {
                continue;
            }

            if (!$property->exists || empty($property->slug) || $property->slug === 'property-new') {
                $uniqueSlug = $this->generateUniqueSlugForProperty($payload);
                $property->slug = $uniqueSlug;
                $property->setCustomSlugSource($uniqueSlug);

                Log::info('Scraper slug override set', [
                    'source_id' => $payload['source_id'],
                    'slug_source' => $uniqueSlug,
                ]);
            }

            $property->fill([
                'user_id' => $payload['user_id'] ?? $this->resolveDefaultProviderId(),
                'owner_phone' => $payload['contact_phone'] ?? null,
                'price' => $payload['price'],
                'currency' => $payload['currency'] ?? 'USD',
                'area' => $payload['area'] ?? null,
                'property_type' => $payload['property_type'] ?? 'apartment',
                'listing_type' => $payload['listing_type'] ?? 'sale',
                'city' => $payload['city'] ?? null,
                'region' => $payload['region'] ?? null,
                'latitude' => $payload['latitude'] ?? null,
                'longitude' => $payload['longitude'] ?? null,
                'status' => $payload['status'] ?? 'pending',
                'source' => $payload['source'] ?? 'telegram',
                'source_url' => $payload['source_url'] ?? null,
                'images' => $payload['images'] ?? [],
                'featured_image' => $payload['featured_image'] ?? null,
            ]);

            foreach (['uz', 'ru', 'en'] as $locale) {
                $translationPayload = $payload['translations'][$locale] ?? null;

                if (!$translationPayload && $locale === 'uz') {
                    $translationPayload = [
                        'title' => $payload['title'] ?? null,
                        'description' => $payload['description'] ?? null,
                        'address' => $payload['address'] ?? null,
                    ];
                }

                if (!$translationPayload) {
                    continue;
                }

                $translation = $property->translateOrNew($locale);
                $translation->title = $translationPayload['title'] ?? $payload['title'];
                $translation->description = $translationPayload['description'] ?? $payload['description'] ?? null;
                $translation->address = $translationPayload['address'] ?? $payload['address'] ?? null;
            }

            $property->save();

            if (!empty($payload['analysis'])) {
                $this->logFraudDetection($property, $payload['analysis']);
            }

            $saved++;
        }

        return $saved;
    }

    /**
     * Scraper uchun unique slug yaratish
     */
    protected function generateUniqueSlugForProperty(array $payload): string
    {
        $candidates = [];

        if (!empty($payload['slug'])) {
            $candidates[] = $payload['slug'];
        }

        if (!empty($payload['title'])) {
            $candidates[] = $payload['title'];
        }

        if (!empty($payload['translations']) && is_array($payload['translations'])) {
            foreach (['uz', 'ru', 'en'] as $locale) {
                if (!empty($payload['translations'][$locale]['title'])) {
                    $candidates[] = $payload['translations'][$locale]['title'];
                }
            }
        }

        if (!empty($payload['source_id'])) {
            $candidates[] = 'property-' . $payload['source_id'];
        }

        $candidates[] = uniqid('property-');

        foreach ($candidates as $candidate) {
            $slugBase = Str::slug($candidate);
            if (empty($slugBase)) {
                continue;
            }

            $slug = $slugBase;
            $counter = 1;

            while (Property::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $counter;
                $counter++;
            }

            return $slug;
        }

        return uniqid('property-');
    }

    /**
     * Telegram kanallaridan xabarlarni olish.
     */
    protected function fetchTelegramPosts(int $limit): array
    {
        if (!$this->telegram) {
            Log::warning('PropertyScraperService: TELEGRAM_BOT_TOKEN topilmadi.');
            return [];
        }

        $offset = Cache::get('scraper:telegram_offset');
        $params = [
            'limit' => min($limit * 3, 100),
            'timeout' => 0,
        ];

        if ($offset) {
            $params['offset'] = $offset + 1;
        }

        $updates = $this->telegram->getUpdates($params);
        $posts = [];
        $lastUpdate = $offset;
        $cutoffDate = now()->subDays($this->days);

        foreach ($updates as $update) {
            $lastUpdate = max($lastUpdate ?? 0, $update->updateId ?? 0);
            $channelPost = $update->channelPost ?? $update->message ?? null;

            if (!$channelPost) {
                continue;
            }

            $chat = $channelPost->chat;
            $username = Str::lower(ltrim($chat->username ?? (string) $chat->id, '@'));

            if (!empty($this->channels) && !in_array($username, $this->channels, true)) {
                continue;
            }

            // Kunlar cheklovini tekshirish
            $postDate = isset($channelPost->date) ? \Carbon\Carbon::createFromTimestamp($channelPost->date) : null;
            if ($postDate && $postDate->lt($cutoffDate)) {
                continue; // Eski post, o'tkazib yuborish
            }

            $text = $channelPost->text ?? $channelPost->caption ?? null;
            if (!$text) {
                continue;
            }

            $photos = collect($channelPost->photo ?? [])
                ->map(function ($photo) {
                    if (is_object($photo) && method_exists($photo, 'toArray')) {
                        return $photo->toArray();
                    }
                    return (array) $photo;
                })
                ->toArray();

            $posts[] = [
                'message_id' => $channelPost->messageId,
                'channel' => $username,
                'chat_id' => $chat->id,
                'text' => $text,
                'photos' => $photos,
                'date' => $postDate,
            ];
        }

        if ($lastUpdate) {
            Cache::put('scraper:telegram_offset', $lastUpdate, now()->addHours(2));
        }

        return array_slice($posts, 0, $limit);
    }

    /**
     * OpenAI yordamida xabarni struktur ma'lumotga aylantirish.
     */
    protected function parsePostWithOpenAI(array $post): ?array
    {
        try {
            // OpenAI client yaratish
            $client = \OpenAI::client($this->openAiKey);

            $response = $client->chat()->create([
                'model' => $this->openAiModel,
                'temperature' => 0.2,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Siz tajribali ko'chmas mulk agenti va moliyaviy analitiksiz. Matndan uy-joy ma'lumotlarini ajratib oling, narx va maydonni solishtirib narx/m² hisoblang, telefon raqamlarini toping, manzilga mos koordinatalarni taxmin qiling, shubhali holatlar uchun flag qo'ying. Har doim faqat JSON qaytaring.",
                    ],
                    [
                        'role' => 'user',
                        'content' => "Matn:\n<<<\n{$post['text']}\n>>>\n\nNatija JSON (barcha maydonlarni to'ldiring):\n{\n  \"title\": \"\",\n  \"description\": \"\",\n  \"address\": \"\",\n  \"city\": \"\",\n  \"price\": 0,\n  \"currency\": \"USD\",\n  \"price_per_m2\": 0,\n  \"price_confidence\": 0.0,\n  \"area\": 0,\n  \"bedrooms\": 0,\n  \"listing_type\": \"sale|rent\",\n  \"property_type\": \"apartment|house|villa|land|commercial\",\n  \"contact_phone\": \"\",\n  \"latitude\": 0,\n  \"longitude\": 0,\n  \"geo_confidence\": 0.0,\n  \"nearby_places\": [],\n  \"flags\": [],\n  \"notes\": \"\",\n  \"translations\": {\n     \"uz\": {\"title\": \"\", \"description\": \"\", \"address\": \"\"},\n     \"ru\": {...},\n     \"en\": {...}\n  }\n}",
                    ],
                ],
                'max_tokens' => 800,
            ]);

            $content = $response->choices[0]->message->content ?? '';
            $parsed = $this->extractJson($content);

            if (!$parsed) {
                return null;
            }

            $payload = [
                'title' => $parsed['title'] ?? null,
                'description' => $parsed['description'] ?? null,
                'address' => $parsed['address'] ?? null,
                'city' => $parsed['city'] ?? null,
                'price' => isset($parsed['price']) ? (float) $parsed['price'] : null,
                'currency' => $parsed['currency'] ?? 'USD',
                'price_per_m2' => isset($parsed['price_per_m2']) ? (float) $parsed['price_per_m2'] : null,
                'price_confidence' => isset($parsed['price_confidence']) ? (float) $parsed['price_confidence'] : null,
                'area' => isset($parsed['area']) ? (float) $parsed['area'] : null,
                'bedrooms' => $parsed['bedrooms'] ?? null,
                'listing_type' => $parsed['listing_type'] ?? 'sale',
                'property_type' => $parsed['property_type'] ?? 'apartment',
                'latitude' => isset($parsed['latitude']) ? (float) $parsed['latitude'] : null,
                'longitude' => isset($parsed['longitude']) ? (float) $parsed['longitude'] : null,
                'status' => 'pending',
                'source' => 'telegram',
                'source_id' => (string) $post['message_id'],
                'source_url' => $this->buildTelegramUrl($post['channel'], $post['message_id']),
                'images' => $this->resolvePhotoUrls($post['photos']),
                'translations' => $parsed['translations'] ?? [],
                'flags' => $parsed['flags'] ?? [],
                'notes' => $parsed['notes'] ?? null,
                'contact_phone' => $parsed['contact_phone'] ?? null,
                'geo_confidence' => isset($parsed['geo_confidence']) ? (float) $parsed['geo_confidence'] : null,
                'nearby_places' => $parsed['nearby_places'] ?? null,
            ];

            return $this->applyServerSideAnalysis($payload, $post['text'] ?? null);
        } catch (\Throwable $exception) {
            Log::warning('PropertyScraperService: OpenAI parsing xatosi', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Telegram foto fayllarini URL ga o'girish.
     */
    protected function resolvePhotoUrls(array $photos): array
    {
        if (!$this->telegram || empty($photos)) {
            return [];
        }

        $token = env('TELEGRAM_BOT_TOKEN');
        $urls = [];

        foreach ($photos as $photo) {
            try {
                $file = $this->telegram->getFile(['file_id' => $photo['file_id'] ?? null]);
                if ($file && $file->filePath) {
                    $urls[] = "https://api.telegram.org/file/bot{$token}/{$file->filePath}";
                }
            } catch (\Throwable $exception) {
                Log::debug('PropertyScraperService: telegram file olinmadi', [
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return array_values(array_unique($urls));
    }

    /**
     * Matndan JSON ni ajratib olish.
     */
    protected function extractJson(string|array $content): ?array
    {
        if (is_array($content)) {
            $content = collect($content)->pluck('text')->implode('');
        }

        // Avval array formatini qidirish ([])
        if (preg_match('/\[\s*\{.*?\}\s*\]/s', $content, $arrayMatches)) {
            $content = $arrayMatches[0];
        } elseif (preg_match('/\{.*\}/s', $content, $matches)) {
            // Agar array topilmasa, object formatini qidirish
            $content = $matches[0];
        }

        $decoded = json_decode($content, true);

        // Agar decode qilishda xatolik bo'lsa, yana bir bor urinib ko'rish
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Markdown code block'lardan chiqarish
            $content = preg_replace('/```json\s*/', '', $content);
            $content = preg_replace('/```\s*/', '', $content);
            // Yana bir bor decode qilish
            $decoded = json_decode(trim($content), true);
        }

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Telegram post URL yaratish.
     */
    protected function buildTelegramUrl(string $channel, string $messageId): string
    {
        $channel = ltrim($channel, '@');
        return "https://t.me/{$channel}/{$messageId}";
    }

    /**
     * Default provider foydalanuvchisini aniqlash.
     */
    protected function resolveDefaultProviderId(): ?int
    {
        if ($this->defaultProviderId) {
            return $this->defaultProviderId;
        }

        $email = env('SCRAPER_PROVIDER_EMAIL');
        $user = null;

        if ($email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            $user = User::where('role', 'provider')->first();
        }

        if (!$user) {
            $user = User::first();
        }

        return $this->defaultProviderId = $user?->id;
    }
}
