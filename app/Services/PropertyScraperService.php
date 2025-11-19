<?php

namespace App\Services;

use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;
use Telegram\Bot\Api as TelegramApi;

class PropertyScraperService
{
    protected ?TelegramApi $telegram = null;
    protected ?string $openAiKey;
    protected string $openAiModel;
    protected array $channels;
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
                        'content' => 'Siz tajribali ko\'chmas mulk agentisiz. Telegram kanaldagi postlarni tahlil qilib, uy-joy e\'lonlarini JSON formatida qaytaring. Har bir e\'lon alohida object bo\'lsin. Faqat uy-joy e\'lonlarini qaytaring, boshqa postlarni e\'tiborsiz qoldiring.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Telegram kanal kontenti (https://t.me/{$username}):\n<<<\n{$channelContent}\n>>>\n\n" . (!empty($channelImages) ? "Kanal rasmlari: " . implode(', ', array_slice($channelImages, 0, 5)) . "\n\n" : "") . "Bu kanaldagi SO'NGGI {$limit} ta uy-joy e'lonlarini tahlil qilib, quyidagi JSON formatida qaytaring. Har bir e'lon alohida object bo'lsin:\n\n[\n  {\n    \"title\": \"Uy-joy sarlavhasi\",\n    \"description\": \"Tavsif\",\n    \"address\": \"Manzil\",\n    \"city\": \"Toshkent\",\n    \"price\": 100000000,\n    \"currency\": \"UZS\",\n    \"area\": 50,\n    \"bedrooms\": 2,\n    \"listing_type\": \"sale\",\n    \"property_type\": \"apartment\",\n    \"source_id\": \"unique_id\",\n    \"source_url\": \"https://t.me/{$username}/123\",\n    \"image_urls\": [\"url1\", \"url2\"],\n    \"translations\": {\n      \"uz\": {\"title\": \"Sarlavha\", \"description\": \"Tavsif\", \"address\": \"Manzil\"},\n      \"ru\": {\"title\": \"Заголовок\", \"description\": \"Описание\", \"address\": \"Адрес\"},\n      \"en\": {\"title\": \"Title\", \"description\": \"Description\", \"address\": \"Address\"}\n    }\n  }\n]\n\nMUHIM: Faqat uy-joy e'lonlarini qaytaring. Har bir e'lon uchun to'liq ma'lumotlar bo'lsin. Agar e'lonlar topilmasa, bo'sh array qaytaring [].",
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

                    $results[] = [
                        'title' => $item['title'] ?? null,
                        'description' => $item['description'] ?? null,
                        'address' => $item['address'] ?? null,
                        'city' => $item['city'] ?? null,
                        'price' => isset($item['price']) ? (float) $item['price'] : null,
                        'currency' => $item['currency'] ?? 'UZS',
                        'area' => isset($item['area']) ? (float) $item['area'] : null,
                        'bedrooms' => $item['bedrooms'] ?? null,
                        'listing_type' => $item['listing_type'] ?? 'sale',
                        'property_type' => $item['property_type'] ?? 'apartment',
                        'status' => 'pending',
                        'source' => 'telegram',
                        'source_id' => $item['source_id'] ?? uniqid('tg_'),
                        'source_url' => $item['source_url'] ?? "https://t.me/{$username}",
                        'images' => $savedImages,
                        'featured_image' => !empty($savedImages) ? $savedImages[0] : null,
                        'translations' => $item['translations'] ?? [],
                    ];
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

            $property->fill([
                'user_id' => $payload['user_id'] ?? $this->resolveDefaultProviderId(),
                'price' => $payload['price'],
                'currency' => $payload['currency'] ?? 'USD',
                'area' => $payload['area'] ?? null,
                'property_type' => $payload['property_type'] ?? 'apartment',
                'listing_type' => $payload['listing_type'] ?? 'sale',
                'city' => $payload['city'] ?? null,
                'region' => $payload['region'] ?? null,
                'status' => $payload['status'] ?? 'pending',
                'source' => $payload['source'] ?? 'telegram',
                'source_url' => $payload['source_url'] ?? null,
                'images' => $payload['images'] ?? [],
                'featured_image' => $payload['featured_image'] ?? null,
            ]);

            $property->save();

            foreach (['uz', 'ru', 'en'] as $locale) {
                $translationPayload = $payload['translations'][$locale] ?? $payload['translations']['uz'] ?? null;
                if (!$translationPayload) {
                    continue;
                }

                $translation = $property->translateOrNew($locale);
                $translation->title = $translationPayload['title'] ?? $payload['title'];
                $translation->description = $translationPayload['description'] ?? $payload['description'] ?? null;
                $translation->address = $translationPayload['address'] ?? $payload['address'] ?? null;
                $translation->save();
            }
            $saved++;
        }

        return $saved;
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
                        'content' => 'Siz tajribali ko\'chmas mulk agentisiz. Matndan uy-joy ma\'lumotlarini JSON formatida ajrating. Har doim JSON qaytaring.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Matn:\n<<<\n{$post['text']}\n>>>\n\nNatija JSON:\n{\n  \"title\": \"\",\n  \"description\": \"\",\n  \"address\": \"\",\n  \"city\": \"\",\n  \"price\": 0,\n  \"currency\": \"USD\",\n  \"area\": 0,\n  \"bedrooms\": 0,\n  \"listing_type\": \"sale|rent\",\n  \"property_type\": \"apartment|house|villa|land|commercial\",\n  \"translations\": {\n     \"uz\": {\"title\": \"\", \"description\": \"\", \"address\": \"\"},\n     \"ru\": {...},\n     \"en\": {...}\n  }\n}",
                    ],
                ],
                'max_tokens' => 800,
            ]);

            $content = $response->choices[0]->message->content ?? '';
            $parsed = $this->extractJson($content);

            if (!$parsed) {
                return null;
            }

            return [
                'title' => $parsed['title'] ?? null,
                'description' => $parsed['description'] ?? null,
                'address' => $parsed['address'] ?? null,
                'city' => $parsed['city'] ?? null,
                'price' => isset($parsed['price']) ? (float) $parsed['price'] : null,
                'currency' => $parsed['currency'] ?? 'USD',
                'area' => isset($parsed['area']) ? (float) $parsed['area'] : null,
                'bedrooms' => $parsed['bedrooms'] ?? null,
                'listing_type' => $parsed['listing_type'] ?? 'sale',
                'property_type' => $parsed['property_type'] ?? 'apartment',
                'status' => 'pending',
                'source' => 'telegram',
                'source_id' => (string) $post['message_id'],
                'source_url' => $this->buildTelegramUrl($post['channel'], $post['message_id']),
                'images' => $this->resolvePhotoUrls($post['photos']),
                'translations' => $parsed['translations'] ?? [],
            ];
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
