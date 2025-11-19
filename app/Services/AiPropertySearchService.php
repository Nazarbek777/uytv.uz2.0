<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiPropertySearchService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->model = env('OPENAI_MODEL', 'gpt-4o-mini');
    }

    /**
     * AI orqali property qidirish
     * Natural language query'ni property features'ga map qiladi
     */
    public function search(string $query, string $locale = 'uz'): array
    {
        if (empty($this->apiKey)) {
            Log::warning('AI Search: OPENAI_API_KEY topilmadi');
            return $this->fallbackSearch($query, $locale);
        }

        try {
            // Cache key yaratish
            $cacheKey = 'ai_search_' . md5($query . $locale);
            
            // Cache'dan olish (5 daqiqa)
            return Cache::remember($cacheKey, 300, function () use ($query, $locale) {
                return $this->performAiSearch($query, $locale);
            });
        } catch (\Exception $e) {
            Log::error('AI Search xatosi: ' . $e->getMessage());
            return $this->fallbackSearch($query, $locale);
        }
    }

    /**
     * OpenAI API orqali qidirish
     */
    protected function performAiSearch(string $query, string $locale): array
    {
        // Database'dan property'lar haqida ma'lumot olish
        $propertiesSample = Property::published()
            ->with('translations')
            ->limit(10)
            ->get()
            ->map(function ($property) use ($locale) {
                return [
                    'id' => $property->id,
                    'title' => $property->translate($locale)->title ?? $property->title_uz ?? 'N/A',
                    'property_type' => $property->property_type,
                    'listing_type' => $property->listing_type,
                    'city' => $property->city,
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                    'area' => $property->area,
                    'price' => $property->price,
                    'currency' => $property->currency ?? 'UZS',
                ];
            })
            ->toArray();

        // OpenAI'ga yuboriladigan prompt
        $systemPrompt = $this->getSystemPrompt($locale);
        $userPrompt = $this->getUserPrompt($query, $propertiesSample, $locale);

        // OpenAI API'ga so'rov
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt,
                ],
            ],
            'temperature' => 0.3,
            'max_tokens' => 1000,
        ]);

        if (!$response->successful()) {
            Log::error('OpenAI API xatosi: ' . $response->body());
            return $this->fallbackSearch($query, $locale);
        }

        $responseData = $response->json();
        $aiResponse = $responseData['choices'][0]['message']['content'] ?? null;

        if (!$aiResponse) {
            return $this->fallbackSearch($query, $locale);
        }

        // AI javobini parse qilish
        $filters = $this->parseAiResponse($aiResponse);
        
        // Agar filterlar bo'sh bo'lsa, fallback - so'rovdan to'g'ridan-to'g'ri extract qilish
        if (empty($filters)) {
            $filters = $this->extractFiltersFromQueryFallback($query, $locale);
        }

        // Property'larni filter qilish
        return $this->applyFilters($filters, $locale);
    }

    /**
     * System prompt yaratish
     */
    protected function getSystemPrompt(string $locale): string
    {
        $localeTexts = [
            'uz' => [
                'title' => 'Siz uy-joy qidiruv yordamchisisiz. Foydalanuvchi so\'rovini tahlil qilib, quyidagi formatda JSON javob qaytaring:',
                'fields' => [
                    'property_type' => 'uy-joy turi (apartment, house, villa, land, commercial, office)',
                    'listing_type' => 'sotuv yoki ijara (sale, rent)',
                    'city' => 'shahar nomi (faqat asosiy nom, masalan: "Toshkentda" -> "Toshkent", "Samarqand shahri" -> "Samarqand")',
                    'bedrooms' => 'xonalar soni (raqam, masalan: "3 xonali" -> 3)',
                    'bathrooms' => 'hammomlar soni (raqam)',
                    'min_price' => 'minimal narx (raqam, UZS formatida)',
                    'max_price' => 'maksimal narx (raqam, UZS formatida, masalan: "100 mln" -> 100000000)',
                    'min_area' => 'minimal maydon (raqam)',
                    'max_area' => 'maksimal maydon (raqam)',
                    'keywords' => 'qidiruv kalit so\'zlari (array)',
                ],
            ],
            'ru' => [
                'title' => 'Вы помощник по поиску недвижимости. Проанализируйте запрос пользователя и верните JSON ответ в следующем формате:',
                'fields' => [
                    'property_type' => 'тип недвижимости (apartment, house, villa, land, commercial, office)',
                    'listing_type' => 'продажа или аренда (sale, rent)',
                    'city' => 'название города (только основное название, например: "в Ташкенте" -> "Ташкент", "город Самарканд" -> "Самарканд")',
                    'bedrooms' => 'количество комнат (число, например: "3-комнатная" -> 3)',
                    'bathrooms' => 'количество ванных (число)',
                    'min_price' => 'минимальная цена (число, в UZS)',
                    'max_price' => 'максимальная цена (число, в UZS, например: "100 млн" -> 100000000)',
                    'min_area' => 'минимальная площадь (число)',
                    'max_area' => 'максимальная площадь (число)',
                    'keywords' => 'ключевые слова поиска (массив)',
                ],
            ],
            'en' => [
                'title' => 'You are a property search assistant. Analyze the user query and return a JSON response in the following format:',
                'fields' => [
                    'property_type' => 'property type (apartment, house, villa, land, commercial, office)',
                    'listing_type' => 'sale or rent',
                    'city' => 'city name',
                    'bedrooms' => 'number of bedrooms (number)',
                    'bathrooms' => 'number of bathrooms (number)',
                    'min_price' => 'minimum price (number)',
                    'max_price' => 'maximum price (number)',
                    'min_area' => 'minimum area (number)',
                    'max_area' => 'maximum area (number)',
                    'keywords' => 'search keywords (array)',
                ],
            ],
        ];

        $texts = $localeTexts[$locale] ?? $localeTexts['uz'];

        $prompt = $texts['title'] . "\n\n";
        $prompt .= "JSON format:\n";
        $prompt .= "{\n";
        foreach ($texts['fields'] as $field => $description) {
            $prompt .= "  \"$field\": null, // $description\n";
        }
        $prompt .= "}\n\n";
        $prompt .= "MUHIM: Faqat JSON qaytaring, boshqa matn yo'q. JSON to'liq va yaroqli bo'lishi kerak.\n";
        $prompt .= "Masalan: \"uy qidiryapman\" -> {\"property_type\": \"house\"}, \"2 xonali uy Toshkentda\" -> {\"property_type\": \"house\", \"bedrooms\": 2, \"city\": \"Toshkent\"}\n";
        $prompt .= "Agar ma'lumot topilmasa, null qaytaring, lekin JSON struktura to'liq bo'lishi kerak.";

        return $prompt;
    }

    /**
     * User prompt yaratish
     */
    protected function getUserPrompt(string $query, array $propertiesSample, string $locale): string
    {
        $localeTexts = [
            'uz' => "Foydalanuvchi so'rovi: \"$query\"\n\n",
            'ru' => "Запрос пользователя: \"$query\"\n\n",
            'en' => "User query: \"$query\"\n\n",
        ];

        $text = $localeTexts[$locale] ?? $localeTexts['uz'];
        $text .= "Database'dagi property'lar misoli (formatni tushunish uchun):\n";
        $text .= json_encode($propertiesSample, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
        $text .= "Foydalanuvchi so'rovini tahlil qilib, JSON formatda javob qaytaring.";

        return $text;
    }

    /**
     * AI javobini parse qilish
     */
    protected function parseAiResponse(string $aiResponse): array
    {
        // JSON'ni extract qilish - nested braces bilan ishlash
        $jsonMatch = [];
        // Code block ichidagi JSON'ni topish
        if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/s', $aiResponse, $jsonMatch)) {
            $json = json_decode($jsonMatch[1], true);
            if ($json && is_array($json)) {
                return $this->normalizeFilters($json);
            }
        }
        
        // Oddiy JSON'ni topish (yaxshiroq pattern)
        if (preg_match('/\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}/s', $aiResponse, $jsonMatch)) {
            // Bir necha marta urinib ko'rish - har bir urinishda to'liqroq JSON olish
            $attempts = [
                $jsonMatch[0],
                substr($aiResponse, strpos($aiResponse, '{')),
                substr($aiResponse, strrpos($aiResponse, '{')),
            ];
            
            foreach ($attempts as $attempt) {
                // Yopilmayotgan brace'larni yopish
                $openBraces = substr_count($attempt, '{');
                $closeBraces = substr_count($attempt, '}');
                if ($openBraces > $closeBraces) {
                    $attempt .= str_repeat('}', $openBraces - $closeBraces);
                }
                
                $json = json_decode($attempt, true);
                if ($json && is_array($json) && !empty($json)) {
                    return $this->normalizeFilters($json);
                }
            }
        }

        // Agar JSON topilmasa, butun javobni parse qilishga harakat qilish
        $json = json_decode($aiResponse, true);
        if ($json && is_array($json) && !empty($json)) {
            return $this->normalizeFilters($json);
        }

        // Fallback - bo'sh array
        return [];
    }

    /**
     * Filter'larni normalize qilish (null va bo'sh qiymatlarni tozalash)
     */
    protected function normalizeFilters(array $filters): array
    {
        $normalized = [];
        
        // Property type
        if (!empty($filters['property_type']) && $filters['property_type'] !== 'null' && $filters['property_type'] !== null) {
            $normalized['property_type'] = trim($filters['property_type']);
        }
        
        // Listing type
        if (!empty($filters['listing_type']) && $filters['listing_type'] !== 'null' && $filters['listing_type'] !== null) {
            $normalized['listing_type'] = trim($filters['listing_type']);
        }
        
        // City
        if (!empty($filters['city']) && $filters['city'] !== 'null' && $filters['city'] !== null) {
            $city = trim($filters['city']);
            // City nomini tozalash
            $city = preg_replace('/\s+shahri$|\s+shahar$|\s+город$|\s+gorod$|да$|da$|даги$|dagi$|dan$|dan$/i', '', $city);
            $city = trim($city);
            if (!empty($city)) {
                $normalized['city'] = $city;
            }
        }
        
        // Bedrooms
        if (isset($filters['bedrooms']) && $filters['bedrooms'] !== null && $filters['bedrooms'] !== 'null') {
            $bedrooms = is_numeric($filters['bedrooms']) ? (int)$filters['bedrooms'] : null;
            if ($bedrooms !== null && $bedrooms > 0) {
                $normalized['bedrooms'] = $bedrooms;
            }
        }
        
        // Bathrooms
        if (isset($filters['bathrooms']) && $filters['bathrooms'] !== null && $filters['bathrooms'] !== 'null') {
            $bathrooms = is_numeric($filters['bathrooms']) ? (int)$filters['bathrooms'] : null;
            if ($bathrooms !== null && $bathrooms > 0) {
                $normalized['bathrooms'] = $bathrooms;
            }
        }
        
        // Price range
        if (isset($filters['min_price']) && $filters['min_price'] !== null && $filters['min_price'] !== 'null') {
            $minPrice = is_numeric($filters['min_price']) ? (float)$filters['min_price'] : null;
            if ($minPrice !== null && $minPrice > 0) {
                $normalized['min_price'] = $minPrice;
            }
        }
        
        if (isset($filters['max_price']) && $filters['max_price'] !== null && $filters['max_price'] !== 'null') {
            $maxPrice = is_numeric($filters['max_price']) ? (float)$filters['max_price'] : null;
            if ($maxPrice !== null && $maxPrice > 0) {
                $normalized['max_price'] = $maxPrice;
            }
        }
        
        // Area range
        if (isset($filters['min_area']) && $filters['min_area'] !== null && $filters['min_area'] !== 'null') {
            $minArea = is_numeric($filters['min_area']) ? (float)$filters['min_area'] : null;
            if ($minArea !== null && $minArea > 0) {
                $normalized['min_area'] = $minArea;
            }
        }
        
        if (isset($filters['max_area']) && $filters['max_area'] !== null && $filters['max_area'] !== 'null') {
            $maxArea = is_numeric($filters['max_area']) ? (float)$filters['max_area'] : null;
            if ($maxArea !== null && $maxArea > 0) {
                $normalized['max_area'] = $maxArea;
            }
        }
        
        // Keywords
        if (!empty($filters['keywords'])) {
            if (is_array($filters['keywords'])) {
                $keywords = array_filter(array_map('trim', $filters['keywords']));
                if (!empty($keywords)) {
                    $normalized['keywords'] = array_values($keywords);
                }
            } elseif (is_string($filters['keywords']) && !empty(trim($filters['keywords']))) {
                $normalized['keywords'] = [trim($filters['keywords'])];
            }
        }
        
        return $normalized;
    }

    /**
     * Fallback - so'rovdan to'g'ridan-to'g'ri filter extract qilish (AI ishlamasa)
     */
    protected function extractFiltersFromQueryFallback(string $query, string $locale): array
    {
        $filters = [];
        $queryLower = strtolower(trim($query));
        
        // Property type extract
        if (preg_match('/\buy\b|\bдом\b|\bhouse\b/i', $query)) {
            $filters['property_type'] = 'house';
        } elseif (preg_match('/\bkvartira\b|\bквартира\b|\bapartment\b/i', $query)) {
            $filters['property_type'] = 'apartment';
        } elseif (preg_match('/\bvilla\b|\bвилла\b/i', $query)) {
            $filters['property_type'] = 'villa';
        } elseif (preg_match('/\byer\b|\bучасток\b|\bland\b/i', $query)) {
            $filters['property_type'] = 'land';
        } elseif (preg_match('/\bcommercial\b|\bкоммерческая\b|\btijorat\b/i', $query)) {
            $filters['property_type'] = 'commercial';
        } elseif (preg_match('/\boffice\b|\bофис\b|\bofis\b/i', $query)) {
            $filters['property_type'] = 'office';
        }
        
        // Bedrooms extract (masalan: "2 xonali", "3 xona", "2 bedrooms")
        if (preg_match('/(\d+)\s*(?:xona|комнат|bedroom|bed|room)/i', $query, $matches)) {
            $bedrooms = (int)$matches[1];
            if ($bedrooms > 0 && $bedrooms <= 10) {
                $filters['bedrooms'] = $bedrooms;
            }
        } elseif (preg_match('/(?:xona|комнат|bedroom|bed|room).*?(\d+)/i', $query, $matches)) {
            $bedrooms = (int)$matches[1];
            if ($bedrooms > 0 && $bedrooms <= 10) {
                $filters['bedrooms'] = $bedrooms;
            }
        } elseif (preg_match('/\b(\d+)\s*xonali\b/i', $query, $matches)) {
            $bedrooms = (int)$matches[1];
            if ($bedrooms > 0 && $bedrooms <= 10) {
                $filters['bedrooms'] = $bedrooms;
            }
        }
        
        // City extract
        $cities = ['toshkent', 'ташкент', 'tashkent', 'samarqand', 'самарканд', 'samarkand', 
                   'buxoro', 'бухара', 'bukhara', 'andijon', 'андижан', 'andijan',
                   'namangan', 'наманган', 'fergana', 'фергана', 'qarshi', 'карши',
                   'navoiy', 'навои', 'navoi', 'urganch', 'ургенч', 'jizzax', 'джизак'];
        
        foreach ($cities as $city) {
            $cityVariants = [
                $city . 'da', $city . 'да', $city . ' shahri', $city . ' шахри',
                $city . ' shahar', $city . ' шаҳар', 'v ' . $city, 'в ' . $city
            ];
            
            foreach ($cityVariants as $variant) {
                if (preg_match('/\b' . preg_quote($variant, '/') . '\b/i', $query) || 
                    preg_match('/\b' . preg_quote($city, '/') . '\b/i', $query)) {
                    // City nomini tozalash
                    $cleanCity = preg_replace('/\s+shahri$|\s+shahar$|\s+город$|да$|da$/i', '', $city);
                    $cleanCity = ucfirst(trim($cleanCity));
                    
                    // To'liq shahar nomlarini map qilish
                    $cityMap = [
                        'toshkent' => 'Toshkent',
                        'ташкент' => 'Toshkent',
                        'tashkent' => 'Toshkent',
                        'samarqand' => 'Samarqand',
                        'самарканд' => 'Samarqand',
                        'samarkand' => 'Samarqand',
                        'buxoro' => 'Buxoro',
                        'бухара' => 'Buxoro',
                        'bukhara' => 'Buxoro',
                        'andijon' => 'Andijon',
                        'андижан' => 'Andijon',
                        'andijan' => 'Andijon',
                        'namangan' => 'Namangan',
                        'наманган' => 'Namangan',
                        'fergana' => 'Farg\'ona',
                        'фергана' => 'Farg\'ona',
                        'qarshi' => 'Qarshi',
                        'карши' => 'Qarshi',
                        'navoiy' => 'Navoiy',
                        'навои' => 'Navoiy',
                        'navoi' => 'Navoiy',
                        'urganch' => 'Urganch',
                        'ургенч' => 'Urganch',
                        'jizzax' => 'Jizzax',
                        'джизак' => 'Jizzax',
                    ];
                    
                    $cityKey = strtolower($cleanCity);
                    if (isset($cityMap[$cityKey])) {
                        $filters['city'] = $cityMap[$cityKey];
                    } else {
                        $filters['city'] = $cleanCity;
                    }
                    break 2;
                }
            }
        }
        
        // Listing type extract
        if (preg_match('/\bsotuv\b|\bпродажа\b|\bsale\b|\bsell\b|\bsotmoq\b/i', $query)) {
            $filters['listing_type'] = 'sale';
        } elseif (preg_match('/\bijara\b|\bаренда\b|\brent\b|\brental\b|\bijaraga\b/i', $query)) {
            $filters['listing_type'] = 'rent';
        }
        
        // Price extract (masalan: "100 mln", "100 million", "100 000 000")
        if (preg_match('/(\d+(?:\s*\d+)*)\s*(?:mln|million|млн|миллион|млн\.)/i', $query, $matches)) {
            $price = (float)str_replace(' ', '', $matches[1]) * 1000000;
            $filters['max_price'] = $price;
        } elseif (preg_match('/gacha|до|до\s+(\d+)|up\s+to\s+(\d+)/i', $query, $matches)) {
            if (isset($matches[1])) {
                $price = (float)$matches[1] * 1000000; // mln deb faraz qilamiz
                $filters['max_price'] = $price;
            }
        }
        
        return $filters;
    }

    /**
     * Filter'larni qo'llash
     */
    protected function applyFilters(array $filters, string $locale): array
    {
        $query = Property::with('translations')
            ->published()
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc');

        // Property type
        if (!empty($filters['property_type'])) {
            $query->where('property_type', $filters['property_type']);
        }

        // Listing type
        if (!empty($filters['listing_type'])) {
            $query->where('listing_type', $filters['listing_type']);
        }

        // City - nomini tozalash va qidirish
        if (!empty($filters['city'])) {
            $city = trim($filters['city']);
            // City nomini tozalash (masalan, "Toshkentda" -> "Toshkent", "Toshkent shahri" -> "Toshkent")
            $city = preg_replace('/\s+shahri$|\s+shahar$|да$|da$|даги$|dagi$|dan$|dan$/i', '', $city);
            $city = trim($city);
            
            if (!empty($city)) {
                $query->where(function($q) use ($city) {
                    $q->where('city', 'like', '%' . $city . '%')
                      ->orWhere('city', $city);
                });
            }
        }

        // Bedrooms - >= ishlatish (masalan, 3 deyilganda 3 va undan ko'p xonalilar)
        if (!empty($filters['bedrooms']) && is_numeric($filters['bedrooms'])) {
            $bedrooms = (int)$filters['bedrooms'];
            if ($bedrooms > 0) {
                $query->where('bedrooms', '>=', $bedrooms);
            }
        }

        // Bathrooms
        if (!empty($filters['bathrooms']) && is_numeric($filters['bathrooms'])) {
            $query->where('bathrooms', '>=', (int)$filters['bathrooms']);
        }

        // Price range
        if (!empty($filters['min_price']) && is_numeric($filters['min_price'])) {
            $query->where('price', '>=', (float)$filters['min_price']);
        }
        if (!empty($filters['max_price']) && is_numeric($filters['max_price'])) {
            $query->where('price', '<=', (float)$filters['max_price']);
        }

        // Area range
        if (!empty($filters['min_area']) && is_numeric($filters['min_area'])) {
            $query->where('area', '>=', (float)$filters['min_area']);
        }
        if (!empty($filters['max_area']) && is_numeric($filters['max_area'])) {
            $query->where('area', '<=', (float)$filters['max_area']);
        }

        // Keywords search
        if (!empty($filters['keywords']) && is_array($filters['keywords'])) {
            $keywords = implode(' ', $filters['keywords']);
            $query->where(function($q) use ($keywords, $locale) {
                $q->whereHas('translations', function($tq) use ($keywords) {
                    $tq->where('title', 'like', "%{$keywords}%")
                       ->orWhere('description', 'like', "%{$keywords}%");
                })->orWhere('city', 'like', "%{$keywords}%");
            });
        }

        $properties = $query->limit(20)->get();

        return [
            'properties' => $properties,
            'filters_applied' => $filters,
            'count' => $properties->count(),
        ];
    }

    /**
     * Fallback search (AI ishlamasa)
     */
    protected function fallbackSearch(string $query, string $locale): array
    {
        $properties = Property::with('translations')
            ->published()
            ->where(function($q) use ($query, $locale) {
                $q->whereHas('translations', function($tq) use ($query) {
                    $tq->where('title', 'like', "%{$query}%")
                       ->orWhere('description', 'like', "%{$query}%");
                })->orWhere('city', 'like', "%{$query}%");
            })
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return [
            'properties' => $properties,
            'filters_applied' => [],
            'count' => $properties->count(),
        ];
    }
}


namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiPropertySearchService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->model = env('OPENAI_MODEL', 'gpt-4o-mini');
    }

    /**
     * AI orqali property qidirish
     * Natural language query'ni property features'ga map qiladi
     */
    public function search(string $query, string $locale = 'uz'): array
    {
        if (empty($this->apiKey)) {
            Log::warning('AI Search: OPENAI_API_KEY topilmadi');
            return $this->fallbackSearch($query, $locale);
        }

        try {
            // Cache key yaratish
            $cacheKey = 'ai_search_' . md5($query . $locale);
            
            // Cache'dan olish (5 daqiqa)
            return Cache::remember($cacheKey, 300, function () use ($query, $locale) {
                return $this->performAiSearch($query, $locale);
            });
        } catch (\Exception $e) {
            Log::error('AI Search xatosi: ' . $e->getMessage());
            return $this->fallbackSearch($query, $locale);
        }
    }

    /**
     * OpenAI API orqali qidirish
     */
    protected function performAiSearch(string $query, string $locale): array
    {
        // Database'dan property'lar haqida ma'lumot olish
        $propertiesSample = Property::published()
            ->with('translations')
            ->limit(10)
            ->get()
            ->map(function ($property) use ($locale) {
                return [
                    'id' => $property->id,
                    'title' => $property->translate($locale)->title ?? $property->title_uz ?? 'N/A',
                    'property_type' => $property->property_type,
                    'listing_type' => $property->listing_type,
                    'city' => $property->city,
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                    'area' => $property->area,
                    'price' => $property->price,
                    'currency' => $property->currency ?? 'UZS',
                ];
            })
            ->toArray();

        // OpenAI'ga yuboriladigan prompt
        $systemPrompt = $this->getSystemPrompt($locale);
        $userPrompt = $this->getUserPrompt($query, $propertiesSample, $locale);

        // OpenAI API'ga so'rov
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt,
                ],
            ],
            'temperature' => 0.3,
            'max_tokens' => 1000,
        ]);

        if (!$response->successful()) {
            Log::error('OpenAI API xatosi: ' . $response->body());
            return $this->fallbackSearch($query, $locale);
        }

        $responseData = $response->json();
        $aiResponse = $responseData['choices'][0]['message']['content'] ?? null;

        if (!$aiResponse) {
            return $this->fallbackSearch($query, $locale);
        }

        // AI javobini parse qilish
        $filters = $this->parseAiResponse($aiResponse);
        
        // Agar filterlar bo'sh bo'lsa, fallback - so'rovdan to'g'ridan-to'g'ri extract qilish
        if (empty($filters)) {
            $filters = $this->extractFiltersFromQueryFallback($query, $locale);
        }

        // Property'larni filter qilish
        return $this->applyFilters($filters, $locale);
    }

    /**
     * System prompt yaratish
     */
    protected function getSystemPrompt(string $locale): string
    {
        $localeTexts = [
            'uz' => [
                'title' => 'Siz uy-joy qidiruv yordamchisisiz. Foydalanuvchi so\'rovini tahlil qilib, quyidagi formatda JSON javob qaytaring:',
                'fields' => [
                    'property_type' => 'uy-joy turi (apartment, house, villa, land, commercial, office)',
                    'listing_type' => 'sotuv yoki ijara (sale, rent)',
                    'city' => 'shahar nomi (faqat asosiy nom, masalan: "Toshkentda" -> "Toshkent", "Samarqand shahri" -> "Samarqand")',
                    'bedrooms' => 'xonalar soni (raqam, masalan: "3 xonali" -> 3)',
                    'bathrooms' => 'hammomlar soni (raqam)',
                    'min_price' => 'minimal narx (raqam, UZS formatida)',
                    'max_price' => 'maksimal narx (raqam, UZS formatida, masalan: "100 mln" -> 100000000)',
                    'min_area' => 'minimal maydon (raqam)',
                    'max_area' => 'maksimal maydon (raqam)',
                    'keywords' => 'qidiruv kalit so\'zlari (array)',
                ],
            ],
            'ru' => [
                'title' => 'Вы помощник по поиску недвижимости. Проанализируйте запрос пользователя и верните JSON ответ в следующем формате:',
                'fields' => [
                    'property_type' => 'тип недвижимости (apartment, house, villa, land, commercial, office)',
                    'listing_type' => 'продажа или аренда (sale, rent)',
                    'city' => 'название города (только основное название, например: "в Ташкенте" -> "Ташкент", "город Самарканд" -> "Самарканд")',
                    'bedrooms' => 'количество комнат (число, например: "3-комнатная" -> 3)',
                    'bathrooms' => 'количество ванных (число)',
                    'min_price' => 'минимальная цена (число, в UZS)',
                    'max_price' => 'максимальная цена (число, в UZS, например: "100 млн" -> 100000000)',
                    'min_area' => 'минимальная площадь (число)',
                    'max_area' => 'максимальная площадь (число)',
                    'keywords' => 'ключевые слова поиска (массив)',
                ],
            ],
            'en' => [
                'title' => 'You are a property search assistant. Analyze the user query and return a JSON response in the following format:',
                'fields' => [
                    'property_type' => 'property type (apartment, house, villa, land, commercial, office)',
                    'listing_type' => 'sale or rent',
                    'city' => 'city name',
                    'bedrooms' => 'number of bedrooms (number)',
                    'bathrooms' => 'number of bathrooms (number)',
                    'min_price' => 'minimum price (number)',
                    'max_price' => 'maximum price (number)',
                    'min_area' => 'minimum area (number)',
                    'max_area' => 'maximum area (number)',
                    'keywords' => 'search keywords (array)',
                ],
            ],
        ];

        $texts = $localeTexts[$locale] ?? $localeTexts['uz'];

        $prompt = $texts['title'] . "\n\n";
        $prompt .= "JSON format:\n";
        $prompt .= "{\n";
        foreach ($texts['fields'] as $field => $description) {
            $prompt .= "  \"$field\": null, // $description\n";
        }
        $prompt .= "}\n\n";
        $prompt .= "MUHIM: Faqat JSON qaytaring, boshqa matn yo'q. JSON to'liq va yaroqli bo'lishi kerak.\n";
        $prompt .= "Masalan: \"uy qidiryapman\" -> {\"property_type\": \"house\"}, \"2 xonali uy Toshkentda\" -> {\"property_type\": \"house\", \"bedrooms\": 2, \"city\": \"Toshkent\"}\n";
        $prompt .= "Agar ma'lumot topilmasa, null qaytaring, lekin JSON struktura to'liq bo'lishi kerak.";

        return $prompt;
    }

    /**
     * User prompt yaratish
     */
    protected function getUserPrompt(string $query, array $propertiesSample, string $locale): string
    {
        $localeTexts = [
            'uz' => "Foydalanuvchi so'rovi: \"$query\"\n\n",
            'ru' => "Запрос пользователя: \"$query\"\n\n",
            'en' => "User query: \"$query\"\n\n",
        ];

        $text = $localeTexts[$locale] ?? $localeTexts['uz'];
        $text .= "Database'dagi property'lar misoli (formatni tushunish uchun):\n";
        $text .= json_encode($propertiesSample, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
        $text .= "Foydalanuvchi so'rovini tahlil qilib, JSON formatda javob qaytaring.";

        return $text;
    }

    /**
     * AI javobini parse qilish
     */
    protected function parseAiResponse(string $aiResponse): array
    {
        // JSON'ni extract qilish - nested braces bilan ishlash
        $jsonMatch = [];
        // Code block ichidagi JSON'ni topish
        if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/s', $aiResponse, $jsonMatch)) {
            $json = json_decode($jsonMatch[1], true);
            if ($json && is_array($json)) {
                return $this->normalizeFilters($json);
            }
        }
        
        // Oddiy JSON'ni topish (yaxshiroq pattern)
        if (preg_match('/\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}/s', $aiResponse, $jsonMatch)) {
            // Bir necha marta urinib ko'rish - har bir urinishda to'liqroq JSON olish
            $attempts = [
                $jsonMatch[0],
                substr($aiResponse, strpos($aiResponse, '{')),
                substr($aiResponse, strrpos($aiResponse, '{')),
            ];
            
            foreach ($attempts as $attempt) {
                // Yopilmayotgan brace'larni yopish
                $openBraces = substr_count($attempt, '{');
                $closeBraces = substr_count($attempt, '}');
                if ($openBraces > $closeBraces) {
                    $attempt .= str_repeat('}', $openBraces - $closeBraces);
                }
                
                $json = json_decode($attempt, true);
                if ($json && is_array($json) && !empty($json)) {
                    return $this->normalizeFilters($json);
                }
            }
        }

        // Agar JSON topilmasa, butun javobni parse qilishga harakat qilish
        $json = json_decode($aiResponse, true);
        if ($json && is_array($json) && !empty($json)) {
            return $this->normalizeFilters($json);
        }

        // Fallback - bo'sh array
        return [];
    }

    /**
     * Filter'larni normalize qilish (null va bo'sh qiymatlarni tozalash)
     */
    protected function normalizeFilters(array $filters): array
    {
        $normalized = [];
        
        // Property type
        if (!empty($filters['property_type']) && $filters['property_type'] !== 'null' && $filters['property_type'] !== null) {
            $normalized['property_type'] = trim($filters['property_type']);
        }
        
        // Listing type
        if (!empty($filters['listing_type']) && $filters['listing_type'] !== 'null' && $filters['listing_type'] !== null) {
            $normalized['listing_type'] = trim($filters['listing_type']);
        }
        
        // City
        if (!empty($filters['city']) && $filters['city'] !== 'null' && $filters['city'] !== null) {
            $city = trim($filters['city']);
            // City nomini tozalash
            $city = preg_replace('/\s+shahri$|\s+shahar$|\s+город$|\s+gorod$|да$|da$|даги$|dagi$|dan$|dan$/i', '', $city);
            $city = trim($city);
            if (!empty($city)) {
                $normalized['city'] = $city;
            }
        }
        
        // Bedrooms
        if (isset($filters['bedrooms']) && $filters['bedrooms'] !== null && $filters['bedrooms'] !== 'null') {
            $bedrooms = is_numeric($filters['bedrooms']) ? (int)$filters['bedrooms'] : null;
            if ($bedrooms !== null && $bedrooms > 0) {
                $normalized['bedrooms'] = $bedrooms;
            }
        }
        
        // Bathrooms
        if (isset($filters['bathrooms']) && $filters['bathrooms'] !== null && $filters['bathrooms'] !== 'null') {
            $bathrooms = is_numeric($filters['bathrooms']) ? (int)$filters['bathrooms'] : null;
            if ($bathrooms !== null && $bathrooms > 0) {
                $normalized['bathrooms'] = $bathrooms;
            }
        }
        
        // Price range
        if (isset($filters['min_price']) && $filters['min_price'] !== null && $filters['min_price'] !== 'null') {
            $minPrice = is_numeric($filters['min_price']) ? (float)$filters['min_price'] : null;
            if ($minPrice !== null && $minPrice > 0) {
                $normalized['min_price'] = $minPrice;
            }
        }
        
        if (isset($filters['max_price']) && $filters['max_price'] !== null && $filters['max_price'] !== 'null') {
            $maxPrice = is_numeric($filters['max_price']) ? (float)$filters['max_price'] : null;
            if ($maxPrice !== null && $maxPrice > 0) {
                $normalized['max_price'] = $maxPrice;
            }
        }
        
        // Area range
        if (isset($filters['min_area']) && $filters['min_area'] !== null && $filters['min_area'] !== 'null') {
            $minArea = is_numeric($filters['min_area']) ? (float)$filters['min_area'] : null;
            if ($minArea !== null && $minArea > 0) {
                $normalized['min_area'] = $minArea;
            }
        }
        
        if (isset($filters['max_area']) && $filters['max_area'] !== null && $filters['max_area'] !== 'null') {
            $maxArea = is_numeric($filters['max_area']) ? (float)$filters['max_area'] : null;
            if ($maxArea !== null && $maxArea > 0) {
                $normalized['max_area'] = $maxArea;
            }
        }
        
        // Keywords
        if (!empty($filters['keywords'])) {
            if (is_array($filters['keywords'])) {
                $keywords = array_filter(array_map('trim', $filters['keywords']));
                if (!empty($keywords)) {
                    $normalized['keywords'] = array_values($keywords);
                }
            } elseif (is_string($filters['keywords']) && !empty(trim($filters['keywords']))) {
                $normalized['keywords'] = [trim($filters['keywords'])];
            }
        }
        
        return $normalized;
    }

    /**
     * Fallback - so'rovdan to'g'ridan-to'g'ri filter extract qilish (AI ishlamasa)
     */
    protected function extractFiltersFromQueryFallback(string $query, string $locale): array
    {
        $filters = [];
        $queryLower = strtolower(trim($query));
        
        // Property type extract
        if (preg_match('/\buy\b|\bдом\b|\bhouse\b/i', $query)) {
            $filters['property_type'] = 'house';
        } elseif (preg_match('/\bkvartira\b|\bквартира\b|\bapartment\b/i', $query)) {
            $filters['property_type'] = 'apartment';
        } elseif (preg_match('/\bvilla\b|\bвилла\b/i', $query)) {
            $filters['property_type'] = 'villa';
        } elseif (preg_match('/\byer\b|\bучасток\b|\bland\b/i', $query)) {
            $filters['property_type'] = 'land';
        } elseif (preg_match('/\bcommercial\b|\bкоммерческая\b|\btijorat\b/i', $query)) {
            $filters['property_type'] = 'commercial';
        } elseif (preg_match('/\boffice\b|\bофис\b|\bofis\b/i', $query)) {
            $filters['property_type'] = 'office';
        }
        
        // Bedrooms extract (masalan: "2 xonali", "3 xona", "2 bedrooms")
        if (preg_match('/(\d+)\s*(?:xona|комнат|bedroom|bed|room)/i', $query, $matches)) {
            $bedrooms = (int)$matches[1];
            if ($bedrooms > 0 && $bedrooms <= 10) {
                $filters['bedrooms'] = $bedrooms;
            }
        } elseif (preg_match('/(?:xona|комнат|bedroom|bed|room).*?(\d+)/i', $query, $matches)) {
            $bedrooms = (int)$matches[1];
            if ($bedrooms > 0 && $bedrooms <= 10) {
                $filters['bedrooms'] = $bedrooms;
            }
        } elseif (preg_match('/\b(\d+)\s*xonali\b/i', $query, $matches)) {
            $bedrooms = (int)$matches[1];
            if ($bedrooms > 0 && $bedrooms <= 10) {
                $filters['bedrooms'] = $bedrooms;
            }
        }
        
        // City extract
        $cities = ['toshkent', 'ташкент', 'tashkent', 'samarqand', 'самарканд', 'samarkand', 
                   'buxoro', 'бухара', 'bukhara', 'andijon', 'андижан', 'andijan',
                   'namangan', 'наманган', 'fergana', 'фергана', 'qarshi', 'карши',
                   'navoiy', 'навои', 'navoi', 'urganch', 'ургенч', 'jizzax', 'джизак'];
        
        foreach ($cities as $city) {
            $cityVariants = [
                $city . 'da', $city . 'да', $city . ' shahri', $city . ' шахри',
                $city . ' shahar', $city . ' шаҳар', 'v ' . $city, 'в ' . $city
            ];
            
            foreach ($cityVariants as $variant) {
                if (preg_match('/\b' . preg_quote($variant, '/') . '\b/i', $query) || 
                    preg_match('/\b' . preg_quote($city, '/') . '\b/i', $query)) {
                    // City nomini tozalash
                    $cleanCity = preg_replace('/\s+shahri$|\s+shahar$|\s+город$|да$|da$/i', '', $city);
                    $cleanCity = ucfirst(trim($cleanCity));
                    
                    // To'liq shahar nomlarini map qilish
                    $cityMap = [
                        'toshkent' => 'Toshkent',
                        'ташкент' => 'Toshkent',
                        'tashkent' => 'Toshkent',
                        'samarqand' => 'Samarqand',
                        'самарканд' => 'Samarqand',
                        'samarkand' => 'Samarqand',
                        'buxoro' => 'Buxoro',
                        'бухара' => 'Buxoro',
                        'bukhara' => 'Buxoro',
                        'andijon' => 'Andijon',
                        'андижан' => 'Andijon',
                        'andijan' => 'Andijon',
                        'namangan' => 'Namangan',
                        'наманган' => 'Namangan',
                        'fergana' => 'Farg\'ona',
                        'фергана' => 'Farg\'ona',
                        'qarshi' => 'Qarshi',
                        'карши' => 'Qarshi',
                        'navoiy' => 'Navoiy',
                        'навои' => 'Navoiy',
                        'navoi' => 'Navoiy',
                        'urganch' => 'Urganch',
                        'ургенч' => 'Urganch',
                        'jizzax' => 'Jizzax',
                        'джизак' => 'Jizzax',
                    ];
                    
                    $cityKey = strtolower($cleanCity);
                    if (isset($cityMap[$cityKey])) {
                        $filters['city'] = $cityMap[$cityKey];
                    } else {
                        $filters['city'] = $cleanCity;
                    }
                    break 2;
                }
            }
        }
        
        // Listing type extract
        if (preg_match('/\bsotuv\b|\bпродажа\b|\bsale\b|\bsell\b|\bsotmoq\b/i', $query)) {
            $filters['listing_type'] = 'sale';
        } elseif (preg_match('/\bijara\b|\bаренда\b|\brent\b|\brental\b|\bijaraga\b/i', $query)) {
            $filters['listing_type'] = 'rent';
        }
        
        // Price extract (masalan: "100 mln", "100 million", "100 000 000")
        if (preg_match('/(\d+(?:\s*\d+)*)\s*(?:mln|million|млн|миллион|млн\.)/i', $query, $matches)) {
            $price = (float)str_replace(' ', '', $matches[1]) * 1000000;
            $filters['max_price'] = $price;
        } elseif (preg_match('/gacha|до|до\s+(\d+)|up\s+to\s+(\d+)/i', $query, $matches)) {
            if (isset($matches[1])) {
                $price = (float)$matches[1] * 1000000; // mln deb faraz qilamiz
                $filters['max_price'] = $price;
            }
        }
        
        return $filters;
    }

    /**
     * Filter'larni qo'llash
     */
    protected function applyFilters(array $filters, string $locale): array
    {
        $query = Property::with('translations')
            ->published()
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc');

        // Property type
        if (!empty($filters['property_type'])) {
            $query->where('property_type', $filters['property_type']);
        }

        // Listing type
        if (!empty($filters['listing_type'])) {
            $query->where('listing_type', $filters['listing_type']);
        }

        // City - nomini tozalash va qidirish
        if (!empty($filters['city'])) {
            $city = trim($filters['city']);
            // City nomini tozalash (masalan, "Toshkentda" -> "Toshkent", "Toshkent shahri" -> "Toshkent")
            $city = preg_replace('/\s+shahri$|\s+shahar$|да$|da$|даги$|dagi$|dan$|dan$/i', '', $city);
            $city = trim($city);
            
            if (!empty($city)) {
                $query->where(function($q) use ($city) {
                    $q->where('city', 'like', '%' . $city . '%')
                      ->orWhere('city', $city);
                });
            }
        }

        // Bedrooms - >= ishlatish (masalan, 3 deyilganda 3 va undan ko'p xonalilar)
        if (!empty($filters['bedrooms']) && is_numeric($filters['bedrooms'])) {
            $bedrooms = (int)$filters['bedrooms'];
            if ($bedrooms > 0) {
                $query->where('bedrooms', '>=', $bedrooms);
            }
        }

        // Bathrooms
        if (!empty($filters['bathrooms']) && is_numeric($filters['bathrooms'])) {
            $query->where('bathrooms', '>=', (int)$filters['bathrooms']);
        }

        // Price range
        if (!empty($filters['min_price']) && is_numeric($filters['min_price'])) {
            $query->where('price', '>=', (float)$filters['min_price']);
        }
        if (!empty($filters['max_price']) && is_numeric($filters['max_price'])) {
            $query->where('price', '<=', (float)$filters['max_price']);
        }

        // Area range
        if (!empty($filters['min_area']) && is_numeric($filters['min_area'])) {
            $query->where('area', '>=', (float)$filters['min_area']);
        }
        if (!empty($filters['max_area']) && is_numeric($filters['max_area'])) {
            $query->where('area', '<=', (float)$filters['max_area']);
        }

        // Keywords search
        if (!empty($filters['keywords']) && is_array($filters['keywords'])) {
            $keywords = implode(' ', $filters['keywords']);
            $query->where(function($q) use ($keywords, $locale) {
                $q->whereHas('translations', function($tq) use ($keywords) {
                    $tq->where('title', 'like', "%{$keywords}%")
                       ->orWhere('description', 'like', "%{$keywords}%");
                })->orWhere('city', 'like', "%{$keywords}%");
            });
        }

        $properties = $query->limit(20)->get();

        return [
            'properties' => $properties,
            'filters_applied' => $filters,
            'count' => $properties->count(),
        ];
    }

    /**
     * Fallback search (AI ishlamasa)
     */
    protected function fallbackSearch(string $query, string $locale): array
    {
        $properties = Property::with('translations')
            ->published()
            ->where(function($q) use ($query, $locale) {
                $q->whereHas('translations', function($tq) use ($query) {
                    $tq->where('title', 'like', "%{$query}%")
                       ->orWhere('description', 'like', "%{$query}%");
                })->orWhere('city', 'like', "%{$query}%");
            })
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return [
            'properties' => $properties,
            'filters_applied' => [],
            'count' => $properties->count(),
        ];
    }
}

