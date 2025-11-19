<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiChatbotService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->model = env('OPENAI_MODEL', 'gpt-4o-mini');
    }

    /**
     * Chatbot conversation
     */
    public function chat(array $messages, string $locale = 'uz'): array
    {
        if (empty($this->apiKey)) {
            return $this->getFallbackResponse($locale);
        }

        try {
            // Son xabar'ni analiz qilish
            $lastMessage = end($messages);
            $userQuery = $lastMessage['content'] ?? '';

            // Property recommendation kerakmi?
            $needsRecommendation = $this->needsPropertyRecommendation($userQuery, $messages);

            if ($needsRecommendation) {
                return $this->generatePropertyRecommendation($messages, $userQuery, $locale);
            }

            // Oddiy conversation
            return $this->generateConversationResponse($messages, $locale);
        } catch (\Exception $e) {
            Log::error('AI Chatbot xatosi: ' . $e->getMessage());
            return $this->getFallbackResponse($locale);
        }
    }

    /**
     * Property recommendation kerakmi?
     */
    protected function needsPropertyRecommendation(string $query, array $messages): bool
    {
        $keywords = [
            'uy', 'kvartira', 'property', 'house', 'apartment',
            'sotuv', 'ijara', 'sale', 'rent',
            'top', 'qidir', 'find', 'search',
            'tavsiya', 'recommend', 'suggest',
            'ko\'rsat', 'show', 'mos', 'suitable'
        ];

        $queryLower = mb_strtolower($query);
        foreach ($keywords as $keyword) {
            if (str_contains($queryLower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Property recommendation yaratish
     */
    protected function generatePropertyRecommendation(array $messages, string $query, string $locale): array
    {
        // Database'dan property'lar olish
        $properties = Property::with('translations')
            ->published()
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Property'lar haqida ma'lumot
        $propertiesInfo = $properties->map(function ($property) use ($locale) {
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
                'slug' => $property->slug,
            ];
        })->toArray();

        // System prompt
        $systemPrompt = $this->getSystemPrompt($locale, $propertiesInfo);

        // Conversation context
        $conversationMessages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $messages
        );

        // OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => $conversationMessages,
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ]);

        if (!$response->successful()) {
            Log::error('OpenAI API xatosi: ' . $response->body());
            return $this->getFallbackResponse($locale);
        }

        $responseData = $response->json();
        $aiMessage = $responseData['choices'][0]['message']['content'] ?? '';

        // Foydalanuvchi so'rovidan filter'larni extract qilish
        $userQuery = end($messages)['content'] ?? '';
        $extractedFilters = $this->extractFiltersFromQuery($userQuery, $locale);

        // Recommended property IDs'ni extract qilish
        $recommendedPropertyIds = $this->extractPropertyIds($aiMessage, $propertiesInfo);

        // Message'dan property ID'larni linklarga aylantirish
        $cleanMessage = $this->replacePropertyIdsWithLinks($aiMessage, $locale);

        // Database'dan property'larni topish
        $recommendedProperties = collect();
        if (count($recommendedPropertyIds) > 0) {
            $recommendedProperties = Property::with('translations')
                ->whereIn('id', $recommendedPropertyIds)
                ->published()
                ->get()
                ->sortBy(function($property) use ($recommendedPropertyIds) {
                    return array_search($property->id, $recommendedPropertyIds);
                })
                ->take(10);
        }

        return [
            'message' => $cleanMessage,
            'properties' => $recommendedProperties,
            'has_recommendations' => $recommendedProperties->count() > 0,
            'filters' => $extractedFilters, // Filter'lar link uchun
        ];
    }

    /**
     * Oddiy conversation response
     */
    protected function generateConversationResponse(array $messages, string $locale): array
    {
        $systemPrompt = $this->getConversationSystemPrompt($locale);

        $conversationMessages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $messages
        );

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => $conversationMessages,
            'temperature' => 0.7,
            'max_tokens' => 500,
        ]);

        if (!$response->successful()) {
            return $this->getFallbackResponse($locale);
        }

        $responseData = $response->json();
        $aiMessage = $responseData['choices'][0]['message']['content'] ?? $this->getFallbackMessage($locale);

        return [
            'message' => $aiMessage,
            'properties' => collect(),
            'has_recommendations' => false,
        ];
    }

    /**
     * System prompt (property recommendation uchun)
     */
    protected function getSystemPrompt(string $locale, array $propertiesInfo): string
    {
        $localeTexts = [
            'uz' => [
                'intro' => 'Siz UYTV.uz uy-joy qidiruv yordamchisisiz. Foydalanuvchiga mos uy-joylarni tavsiya qilasiz.',
                'instructions' => 'Foydalanuvchi so\'rovini tahlil qilib, quyidagi property\'lardan eng moslarini tavsiya qiling (maximum 5 ta).',
                'format' => 'Javobingizda property ID\'larini [PROPERTY_ID:123] formatda ko\'rsating. Masalan: "Sizga 3 ta uy tavsiya qilaman: [PROPERTY_ID:1], [PROPERTY_ID:5], [PROPERTY_ID:12]".',
                'end' => 'Foydalanuvchiga yaxlit, do\'stona va professional javob bering.',
            ],
            'ru' => [
                'intro' => 'Вы помощник по поиску недвижимости UYTV.uz. Вы рекомендуете подходящую недвижимость пользователям.',
                'instructions' => 'Проанализируйте запрос пользователя и порекомендуйте наиболее подходящие объекты из списка ниже (максимум 5).',
                'format' => 'В ответе укажите ID объектов в формате [PROPERTY_ID:123]. Например: "Рекомендую 3 варианта: [PROPERTY_ID:1], [PROPERTY_ID:5], [PROPERTY_ID:12]".',
                'end' => 'Дайтe дружелюбный, профессиональный ответ.',
            ],
            'en' => [
                'intro' => 'You are UYTV.uz property search assistant. You recommend suitable properties to users.',
                'instructions' => 'Analyze the user query and recommend the most suitable properties from the list below (maximum 5).',
                'format' => 'In your response, include property IDs in format [PROPERTY_ID:123]. For example: "I recommend 3 properties: [PROPERTY_ID:1], [PROPERTY_ID:5], [PROPERTY_ID:12]".',
                'end' => 'Give a friendly, professional response.',
            ],
        ];

        $texts = $localeTexts[$locale] ?? $localeTexts['uz'];

        $prompt = $texts['intro'] . "\n\n";
        $prompt .= $texts['instructions'] . "\n\n";
        $prompt .= "Available properties:\n";
        $prompt .= json_encode($propertiesInfo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
        $prompt .= $texts['format'] . "\n\n";
        $prompt .= $texts['end'];

        return $prompt;
    }

    /**
     * Conversation system prompt (oddiy gaplashish uchun)
     */
    protected function getConversationSystemPrompt(string $locale): string
    {
        $localeTexts = [
            'uz' => 'Siz UYTV.uz uy-joy platformasining yordamchisisiz. Foydalanuvchilarga uy-joy qidirishda yordam berasiz. Do\'stona va professional bo\'ling. Agar uy-joy kerak bo\'lsa, "uy", "kvartira", "topish", "qidirish" kabi so\'zlar yozing.',
            'ru' => 'Вы помощник платформы недвижимости UYTV.uz. Помогаете пользователям найти недвижимость. Будьте дружелюбны и профессиональны. Если нужна недвижимость, напишите слова "квартира", "дом", "найти", "поиск".',
            'en' => 'You are an assistant for UYTV.uz property platform. You help users find properties. Be friendly and professional. If property is needed, write words like "house", "apartment", "find", "search".',
        ];

        return $localeTexts[$locale] ?? $localeTexts['uz'];
    }

    /**
     * Property ID'larni extract qilish
     */
    protected function extractPropertyIds(string $message, array $propertiesInfo): array
    {
        $propertyIds = [];
        
        // [PROPERTY_ID:123] formatdan extract qilish
        if (preg_match_all('/\[PROPERTY_ID:(\d+)\]/', $message, $matches)) {
            $propertyIds = array_map('intval', $matches[1]);
        }

        // Faqat mavjud property ID'lar
        $availableIds = array_column($propertiesInfo, 'id');
        $validIds = array_intersect($propertyIds, $availableIds);
        
        // Agar availableIds'da yo'q bo'lsa, to'g'ridan-to'g'ri database'dan tekshirish
        if (empty($validIds) && !empty($propertyIds)) {
            $validIds = \App\Models\Property::whereIn('id', $propertyIds)
                ->published()
                ->pluck('id')
                ->toArray();
        }
        
        return array_unique($validIds);
    }

    /**
     * Message'dan property ID'larni linklarga aylantirish yoki o'chirish
     */
    protected function replacePropertyIdsWithLinks(string $message, string $locale): string
    {
        // [PROPERTY_ID:123] formatni topish va o'chirish
        // Property'larni keyinroq alohida ko'rsatamiz, shuning uchun faqat o'chiramiz
        $cleanMessage = preg_replace('/\s*-\s*\[PROPERTY_ID:\d+\]/', '', $message);
        $cleanMessage = preg_replace('/\[PROPERTY_ID:\d+\]/', '', $cleanMessage);
        
        // Qo'shimcha bo'shliqlarni tozalash
        $cleanMessage = preg_replace('/\n\s*\n/', "\n\n", $cleanMessage);
        $cleanMessage = trim($cleanMessage);
        
        return $cleanMessage;
    }

    /**
     * Foydalanuvchi so'rovidan filter'larni extract qilish
     */
    protected function extractFiltersFromQuery(string $query, string $locale): array
    {
        $filters = [];
        
        // AI Property Search Service'dan foydalanish
        $aiSearchService = new \App\Services\AiPropertySearchService();
        $searchResults = $aiSearchService->search($query, $locale);
        
        // Filter'larni extract qilish
        if (isset($searchResults['filters_applied']) && !empty($searchResults['filters_applied'])) {
            $filters = $searchResults['filters_applied'];
        }
        
        return $filters;
    }

    /**
     * Fallback response
     */
    protected function getFallbackResponse(string $locale): array
    {
        return [
            'message' => $this->getFallbackMessage($locale),
            'properties' => collect(),
            'has_recommendations' => false,
        ];
    }

    /**
     * Fallback message
     */
    protected function getFallbackMessage(string $locale): string
    {
        $messages = [
            'uz' => 'Kechirasiz, hozirda javob berishda muammo bor. Iltimos, qayta urinib ko\'ring yoki filtrlardan foydalaning.',
            'ru' => 'Извините, сейчас возникла проблема с ответом. Пожалуйста, попробуйте еще раз или используйте фильтры.',
            'en' => 'Sorry, there is a problem responding right now. Please try again or use filters.',
        ];

        return $messages[$locale] ?? $messages['uz'];
    }
}


namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiChatbotService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->model = env('OPENAI_MODEL', 'gpt-4o-mini');
    }

    /**
     * Chatbot conversation
     */
    public function chat(array $messages, string $locale = 'uz'): array
    {
        if (empty($this->apiKey)) {
            return $this->getFallbackResponse($locale);
        }

        try {
            // Son xabar'ni analiz qilish
            $lastMessage = end($messages);
            $userQuery = $lastMessage['content'] ?? '';

            // Property recommendation kerakmi?
            $needsRecommendation = $this->needsPropertyRecommendation($userQuery, $messages);

            if ($needsRecommendation) {
                return $this->generatePropertyRecommendation($messages, $userQuery, $locale);
            }

            // Oddiy conversation
            return $this->generateConversationResponse($messages, $locale);
        } catch (\Exception $e) {
            Log::error('AI Chatbot xatosi: ' . $e->getMessage());
            return $this->getFallbackResponse($locale);
        }
    }

    /**
     * Property recommendation kerakmi?
     */
    protected function needsPropertyRecommendation(string $query, array $messages): bool
    {
        $keywords = [
            'uy', 'kvartira', 'property', 'house', 'apartment',
            'sotuv', 'ijara', 'sale', 'rent',
            'top', 'qidir', 'find', 'search',
            'tavsiya', 'recommend', 'suggest',
            'ko\'rsat', 'show', 'mos', 'suitable'
        ];

        $queryLower = mb_strtolower($query);
        foreach ($keywords as $keyword) {
            if (str_contains($queryLower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Property recommendation yaratish
     */
    protected function generatePropertyRecommendation(array $messages, string $query, string $locale): array
    {
        // Database'dan property'lar olish
        $properties = Property::with('translations')
            ->published()
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Property'lar haqida ma'lumot
        $propertiesInfo = $properties->map(function ($property) use ($locale) {
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
                'slug' => $property->slug,
            ];
        })->toArray();

        // System prompt
        $systemPrompt = $this->getSystemPrompt($locale, $propertiesInfo);

        // Conversation context
        $conversationMessages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $messages
        );

        // OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => $conversationMessages,
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ]);

        if (!$response->successful()) {
            Log::error('OpenAI API xatosi: ' . $response->body());
            return $this->getFallbackResponse($locale);
        }

        $responseData = $response->json();
        $aiMessage = $responseData['choices'][0]['message']['content'] ?? '';

        // Foydalanuvchi so'rovidan filter'larni extract qilish
        $userQuery = end($messages)['content'] ?? '';
        $extractedFilters = $this->extractFiltersFromQuery($userQuery, $locale);

        // Recommended property IDs'ni extract qilish
        $recommendedPropertyIds = $this->extractPropertyIds($aiMessage, $propertiesInfo);

        // Message'dan property ID'larni linklarga aylantirish
        $cleanMessage = $this->replacePropertyIdsWithLinks($aiMessage, $locale);

        // Database'dan property'larni topish
        $recommendedProperties = collect();
        if (count($recommendedPropertyIds) > 0) {
            $recommendedProperties = Property::with('translations')
                ->whereIn('id', $recommendedPropertyIds)
                ->published()
                ->get()
                ->sortBy(function($property) use ($recommendedPropertyIds) {
                    return array_search($property->id, $recommendedPropertyIds);
                })
                ->take(10);
        }

        return [
            'message' => $cleanMessage,
            'properties' => $recommendedProperties,
            'has_recommendations' => $recommendedProperties->count() > 0,
            'filters' => $extractedFilters, // Filter'lar link uchun
        ];
    }

    /**
     * Oddiy conversation response
     */
    protected function generateConversationResponse(array $messages, string $locale): array
    {
        $systemPrompt = $this->getConversationSystemPrompt($locale);

        $conversationMessages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $messages
        );

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => $conversationMessages,
            'temperature' => 0.7,
            'max_tokens' => 500,
        ]);

        if (!$response->successful()) {
            return $this->getFallbackResponse($locale);
        }

        $responseData = $response->json();
        $aiMessage = $responseData['choices'][0]['message']['content'] ?? $this->getFallbackMessage($locale);

        return [
            'message' => $aiMessage,
            'properties' => collect(),
            'has_recommendations' => false,
        ];
    }

    /**
     * System prompt (property recommendation uchun)
     */
    protected function getSystemPrompt(string $locale, array $propertiesInfo): string
    {
        $localeTexts = [
            'uz' => [
                'intro' => 'Siz UYTV.uz uy-joy qidiruv yordamchisisiz. Foydalanuvchiga mos uy-joylarni tavsiya qilasiz.',
                'instructions' => 'Foydalanuvchi so\'rovini tahlil qilib, quyidagi property\'lardan eng moslarini tavsiya qiling (maximum 5 ta).',
                'format' => 'Javobingizda property ID\'larini [PROPERTY_ID:123] formatda ko\'rsating. Masalan: "Sizga 3 ta uy tavsiya qilaman: [PROPERTY_ID:1], [PROPERTY_ID:5], [PROPERTY_ID:12]".',
                'end' => 'Foydalanuvchiga yaxlit, do\'stona va professional javob bering.',
            ],
            'ru' => [
                'intro' => 'Вы помощник по поиску недвижимости UYTV.uz. Вы рекомендуете подходящую недвижимость пользователям.',
                'instructions' => 'Проанализируйте запрос пользователя и порекомендуйте наиболее подходящие объекты из списка ниже (максимум 5).',
                'format' => 'В ответе укажите ID объектов в формате [PROPERTY_ID:123]. Например: "Рекомендую 3 варианта: [PROPERTY_ID:1], [PROPERTY_ID:5], [PROPERTY_ID:12]".',
                'end' => 'Дайтe дружелюбный, профессиональный ответ.',
            ],
            'en' => [
                'intro' => 'You are UYTV.uz property search assistant. You recommend suitable properties to users.',
                'instructions' => 'Analyze the user query and recommend the most suitable properties from the list below (maximum 5).',
                'format' => 'In your response, include property IDs in format [PROPERTY_ID:123]. For example: "I recommend 3 properties: [PROPERTY_ID:1], [PROPERTY_ID:5], [PROPERTY_ID:12]".',
                'end' => 'Give a friendly, professional response.',
            ],
        ];

        $texts = $localeTexts[$locale] ?? $localeTexts['uz'];

        $prompt = $texts['intro'] . "\n\n";
        $prompt .= $texts['instructions'] . "\n\n";
        $prompt .= "Available properties:\n";
        $prompt .= json_encode($propertiesInfo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
        $prompt .= $texts['format'] . "\n\n";
        $prompt .= $texts['end'];

        return $prompt;
    }

    /**
     * Conversation system prompt (oddiy gaplashish uchun)
     */
    protected function getConversationSystemPrompt(string $locale): string
    {
        $localeTexts = [
            'uz' => 'Siz UYTV.uz uy-joy platformasining yordamchisisiz. Foydalanuvchilarga uy-joy qidirishda yordam berasiz. Do\'stona va professional bo\'ling. Agar uy-joy kerak bo\'lsa, "uy", "kvartira", "topish", "qidirish" kabi so\'zlar yozing.',
            'ru' => 'Вы помощник платформы недвижимости UYTV.uz. Помогаете пользователям найти недвижимость. Будьте дружелюбны и профессиональны. Если нужна недвижимость, напишите слова "квартира", "дом", "найти", "поиск".',
            'en' => 'You are an assistant for UYTV.uz property platform. You help users find properties. Be friendly and professional. If property is needed, write words like "house", "apartment", "find", "search".',
        ];

        return $localeTexts[$locale] ?? $localeTexts['uz'];
    }

    /**
     * Property ID'larni extract qilish
     */
    protected function extractPropertyIds(string $message, array $propertiesInfo): array
    {
        $propertyIds = [];
        
        // [PROPERTY_ID:123] formatdan extract qilish
        if (preg_match_all('/\[PROPERTY_ID:(\d+)\]/', $message, $matches)) {
            $propertyIds = array_map('intval', $matches[1]);
        }

        // Faqat mavjud property ID'lar
        $availableIds = array_column($propertiesInfo, 'id');
        $validIds = array_intersect($propertyIds, $availableIds);
        
        // Agar availableIds'da yo'q bo'lsa, to'g'ridan-to'g'ri database'dan tekshirish
        if (empty($validIds) && !empty($propertyIds)) {
            $validIds = \App\Models\Property::whereIn('id', $propertyIds)
                ->published()
                ->pluck('id')
                ->toArray();
        }
        
        return array_unique($validIds);
    }

    /**
     * Message'dan property ID'larni linklarga aylantirish yoki o'chirish
     */
    protected function replacePropertyIdsWithLinks(string $message, string $locale): string
    {
        // [PROPERTY_ID:123] formatni topish va o'chirish
        // Property'larni keyinroq alohida ko'rsatamiz, shuning uchun faqat o'chiramiz
        $cleanMessage = preg_replace('/\s*-\s*\[PROPERTY_ID:\d+\]/', '', $message);
        $cleanMessage = preg_replace('/\[PROPERTY_ID:\d+\]/', '', $cleanMessage);
        
        // Qo'shimcha bo'shliqlarni tozalash
        $cleanMessage = preg_replace('/\n\s*\n/', "\n\n", $cleanMessage);
        $cleanMessage = trim($cleanMessage);
        
        return $cleanMessage;
    }

    /**
     * Foydalanuvchi so'rovidan filter'larni extract qilish
     */
    protected function extractFiltersFromQuery(string $query, string $locale): array
    {
        $filters = [];
        
        // AI Property Search Service'dan foydalanish
        $aiSearchService = new \App\Services\AiPropertySearchService();
        $searchResults = $aiSearchService->search($query, $locale);
        
        // Filter'larni extract qilish
        if (isset($searchResults['filters_applied']) && !empty($searchResults['filters_applied'])) {
            $filters = $searchResults['filters_applied'];
        }
        
        return $filters;
    }

    /**
     * Fallback response
     */
    protected function getFallbackResponse(string $locale): array
    {
        return [
            'message' => $this->getFallbackMessage($locale),
            'properties' => collect(),
            'has_recommendations' => false,
        ];
    }

    /**
     * Fallback message
     */
    protected function getFallbackMessage(string $locale): string
    {
        $messages = [
            'uz' => 'Kechirasiz, hozirda javob berishda muammo bor. Iltimos, qayta urinib ko\'ring yoki filtrlardan foydalaning.',
            'ru' => 'Извините, сейчас возникла проблема с ответом. Пожалуйста, попробуйте еще раз или используйте фильтры.',
            'en' => 'Sorry, there is a problem responding right now. Please try again or use filters.',
        ];

        return $messages[$locale] ?? $messages['uz'];
    }
}

