<?php

namespace App\Http\Controllers;

use App\Services\AiChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChatbotController
{
    /**
     * Chatbot chat endpoint
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'locale' => 'nullable|string|in:uz,ru,en',
        ]);

        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        // Conversation history'ni session'da saqlash
        $sessionKey = 'chatbot_history_' . $locale;
        $history = Session::get($sessionKey, []);

        // Foydalanuvchi xabarini qo'shish
        $history[] = [
            'role' => 'user',
            'content' => $request->message,
        ];

        // AI Chatbot Service
        $chatbotService = new AiChatbotService();
        $response = $chatbotService->chat($history, $locale);

        // AI javobini history'ga qo'shish
        $history[] = [
            'role' => 'assistant',
            'content' => $response['message'],
        ];

        // History'ni saqlash (maximum 20 ta xabar)
        if (count($history) > 20) {
            $history = array_slice($history, -20);
        }
        Session::put($sessionKey, $history);

        // Property'lar formatlash
        $properties = $response['properties']->map(function ($property) use ($locale) {
            return [
                'id' => $property->id,
                'title' => $property->translate($locale)->title ?? $property->title_uz ?? 'N/A',
                'slug' => $property->slug,
                'price' => number_format($property->price, 0),
                'currency' => $property->currency ?? 'UZS',
                'city' => $property->city,
                'property_type' => $property->property_type,
                'listing_type' => $property->listing_type,
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'area' => $property->area,
                'featured_image' => $property->featured_image ? asset('storage/' . $property->featured_image) : null,
                'url' => route('listing.show', $property->slug),
            ];
        });

        // Listing page link yaratish (filter'lar bilan)
        $listingsUrl = $this->buildListingsUrl($response['filters'] ?? [], $locale);

        return response()->json([
            'success' => true,
            'message' => $response['message'],
            'properties' => $properties,
            'has_recommendations' => $response['has_recommendations'],
            'listings_url' => $listingsUrl,
            'has_filters' => !empty($response['filters'] ?? []),
        ]);
    }

    /**
     * Listing page URL yaratish (filter'lar bilan)
     */
    protected function buildListingsUrl(array $filters, string $locale): ?string
    {
        if (empty($filters)) {
            return null;
        }

        $params = [];

        // Locale
        if ($locale !== app()->getLocale()) {
            $params['locale'] = $locale;
        }

        // Property type
        if (!empty($filters['property_type'])) {
            $propertyType = trim($filters['property_type']);
            // AI'dan keladigan property type'larni tozalash
            $propertyTypeMap = [
                'apartment' => 'apartment',
                'kvartira' => 'apartment',
                'квартира' => 'apartment',
                'house' => 'house',
                'uy' => 'house',
                'дом' => 'house',
                'villa' => 'villa',
                'вилла' => 'villa',
                'land' => 'land',
                'yer' => 'land',
                'участок' => 'land',
                'commercial' => 'commercial',
                'kommercheskaya' => 'commercial',
                'коммерческая' => 'commercial',
                'office' => 'office',
                'ofis' => 'office',
                'офис' => 'office',
            ];
            
            $propertyTypeLower = strtolower($propertyType);
            if (isset($propertyTypeMap[$propertyTypeLower])) {
                $params['property_type'] = $propertyTypeMap[$propertyTypeLower];
            } elseif (in_array($propertyType, ['apartment', 'house', 'villa', 'land', 'commercial', 'office'])) {
                $params['property_type'] = $propertyType;
            }
        }

        // Listing type
        if (!empty($filters['listing_type'])) {
            $listingType = strtolower(trim($filters['listing_type']));
            // AI'dan keladigan listing type'larni tozalash
            if (in_array($listingType, ['sale', 'sotuv', 'продажа', 'sell', 'buy'])) {
                $params['listing_type'] = 'sale';
            } elseif (in_array($listingType, ['rent', 'ijara', 'аренда', 'rental'])) {
                $params['listing_type'] = 'rent';
            }
        }

        // City - nomini tozalash
        if (!empty($filters['city'])) {
            $city = trim($filters['city']);
            // City nomini tozalash (masalan, "Toshkentda" -> "Toshkent", "Toshkent shahri" -> "Toshkent")
            $city = preg_replace('/\s+shahri$|\s+shahar$|\s+город$|\s+gorod$|да$|da$|даги$|dagi$|dan$|dan$|da\s|да\s|в\s|в$|v\s|v$/i', '', $city);
            $city = trim($city);
            // Bo'sh bo'lmasligini tekshirish
            if (!empty($city) && strlen($city) > 1) {
                $params['city'] = $city;
            }
        }

        // Bedrooms
        if (!empty($filters['bedrooms']) && is_numeric($filters['bedrooms'])) {
            $bedrooms = (int)$filters['bedrooms'];
            if ($bedrooms > 0) {
                $params['bedrooms'] = $bedrooms;
            }
        }

        // Price range
        if (!empty($filters['min_price']) && is_numeric($filters['min_price'])) {
            $params['min_price'] = (float)$filters['min_price'];
        }
        if (!empty($filters['max_price']) && is_numeric($filters['max_price'])) {
            $params['max_price'] = (float)$filters['max_price'];
        }

        // Area range
        if (!empty($filters['min_area']) && is_numeric($filters['min_area'])) {
            $params['min_area'] = (float)$filters['min_area'];
        }
        if (!empty($filters['max_area']) && is_numeric($filters['max_area'])) {
            $params['max_area'] = (float)$filters['max_area'];
        }

        // Keywords - search parameter sifatida
        if (!empty($filters['keywords']) && is_array($filters['keywords']) && count($filters['keywords']) > 0) {
            $keywords = array_filter($filters['keywords']);
            if (!empty($keywords)) {
                $params['search'] = implode(' ', $keywords);
            }
        }

        // Agar hech qanday filter bo'lmasa, null qaytar
        if (empty($params)) {
            return null;
        }

        // URL yaratish (query parametrlar sifatida)
        $baseUrl = route('listings');
        $queryString = http_build_query($params);
        
        return $baseUrl . '?' . $queryString;
    }

    /**
     * Chat history'ni tozalash
     */
    public function clearHistory(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $sessionKey = 'chatbot_history_' . $locale;
        Session::forget($sessionKey);

        return response()->json([
            'success' => true,
            'message' => 'History cleared',
        ]);
    }

    /**
     * Chatbot welcome message
     */
    public function welcome(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $messages = [
            'uz' => 'Salom! 👋 Men UYTV.uz yordamchisiman. Sizga mos uy-joylarni topishda yordam beraman. Qanday uy-joy kerak? Masalan: "3 xonali uy Toshkentda, 100 mln gacha"',
            'ru' => 'Привет! 👋 Я помощник UYTV.uz. Помогу найти подходящую недвижимость. Какая недвижимость нужна? Например: "3-комнатная квартира в Ташкенте до 100 млн"',
            'en' => 'Hello! 👋 I\'m UYTV.uz assistant. I\'ll help you find suitable properties. What property do you need? For example: "3 bedroom apartment in Tashkent up to 100M"',
        ];

        return response()->json([
            'success' => true,
            'message' => $messages[$locale] ?? $messages['uz'],
        ]);
    }
}


namespace App\Http\Controllers;

use App\Services\AiChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChatbotController
{
    /**
     * Chatbot chat endpoint
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'locale' => 'nullable|string|in:uz,ru,en',
        ]);

        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        // Conversation history'ni session'da saqlash
        $sessionKey = 'chatbot_history_' . $locale;
        $history = Session::get($sessionKey, []);

        // Foydalanuvchi xabarini qo'shish
        $history[] = [
            'role' => 'user',
            'content' => $request->message,
        ];

        // AI Chatbot Service
        $chatbotService = new AiChatbotService();
        $response = $chatbotService->chat($history, $locale);

        // AI javobini history'ga qo'shish
        $history[] = [
            'role' => 'assistant',
            'content' => $response['message'],
        ];

        // History'ni saqlash (maximum 20 ta xabar)
        if (count($history) > 20) {
            $history = array_slice($history, -20);
        }
        Session::put($sessionKey, $history);

        // Property'lar formatlash
        $properties = $response['properties']->map(function ($property) use ($locale) {
            return [
                'id' => $property->id,
                'title' => $property->translate($locale)->title ?? $property->title_uz ?? 'N/A',
                'slug' => $property->slug,
                'price' => number_format($property->price, 0),
                'currency' => $property->currency ?? 'UZS',
                'city' => $property->city,
                'property_type' => $property->property_type,
                'listing_type' => $property->listing_type,
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'area' => $property->area,
                'featured_image' => $property->featured_image ? asset('storage/' . $property->featured_image) : null,
                'url' => route('listing.show', $property->slug),
            ];
        });

        // Listing page link yaratish (filter'lar bilan)
        $listingsUrl = $this->buildListingsUrl($response['filters'] ?? [], $locale);

        return response()->json([
            'success' => true,
            'message' => $response['message'],
            'properties' => $properties,
            'has_recommendations' => $response['has_recommendations'],
            'listings_url' => $listingsUrl,
            'has_filters' => !empty($response['filters'] ?? []),
        ]);
    }

    /**
     * Listing page URL yaratish (filter'lar bilan)
     */
    protected function buildListingsUrl(array $filters, string $locale): ?string
    {
        if (empty($filters)) {
            return null;
        }

        $params = [];

        // Locale
        if ($locale !== app()->getLocale()) {
            $params['locale'] = $locale;
        }

        // Property type
        if (!empty($filters['property_type'])) {
            $propertyType = trim($filters['property_type']);
            // AI'dan keladigan property type'larni tozalash
            $propertyTypeMap = [
                'apartment' => 'apartment',
                'kvartira' => 'apartment',
                'квартира' => 'apartment',
                'house' => 'house',
                'uy' => 'house',
                'дом' => 'house',
                'villa' => 'villa',
                'вилла' => 'villa',
                'land' => 'land',
                'yer' => 'land',
                'участок' => 'land',
                'commercial' => 'commercial',
                'kommercheskaya' => 'commercial',
                'коммерческая' => 'commercial',
                'office' => 'office',
                'ofis' => 'office',
                'офис' => 'office',
            ];
            
            $propertyTypeLower = strtolower($propertyType);
            if (isset($propertyTypeMap[$propertyTypeLower])) {
                $params['property_type'] = $propertyTypeMap[$propertyTypeLower];
            } elseif (in_array($propertyType, ['apartment', 'house', 'villa', 'land', 'commercial', 'office'])) {
                $params['property_type'] = $propertyType;
            }
        }

        // Listing type
        if (!empty($filters['listing_type'])) {
            $listingType = strtolower(trim($filters['listing_type']));
            // AI'dan keladigan listing type'larni tozalash
            if (in_array($listingType, ['sale', 'sotuv', 'продажа', 'sell', 'buy'])) {
                $params['listing_type'] = 'sale';
            } elseif (in_array($listingType, ['rent', 'ijara', 'аренда', 'rental'])) {
                $params['listing_type'] = 'rent';
            }
        }

        // City - nomini tozalash
        if (!empty($filters['city'])) {
            $city = trim($filters['city']);
            // City nomini tozalash (masalan, "Toshkentda" -> "Toshkent", "Toshkent shahri" -> "Toshkent")
            $city = preg_replace('/\s+shahri$|\s+shahar$|\s+город$|\s+gorod$|да$|da$|даги$|dagi$|dan$|dan$|da\s|да\s|в\s|в$|v\s|v$/i', '', $city);
            $city = trim($city);
            // Bo'sh bo'lmasligini tekshirish
            if (!empty($city) && strlen($city) > 1) {
                $params['city'] = $city;
            }
        }

        // Bedrooms
        if (!empty($filters['bedrooms']) && is_numeric($filters['bedrooms'])) {
            $bedrooms = (int)$filters['bedrooms'];
            if ($bedrooms > 0) {
                $params['bedrooms'] = $bedrooms;
            }
        }

        // Price range
        if (!empty($filters['min_price']) && is_numeric($filters['min_price'])) {
            $params['min_price'] = (float)$filters['min_price'];
        }
        if (!empty($filters['max_price']) && is_numeric($filters['max_price'])) {
            $params['max_price'] = (float)$filters['max_price'];
        }

        // Area range
        if (!empty($filters['min_area']) && is_numeric($filters['min_area'])) {
            $params['min_area'] = (float)$filters['min_area'];
        }
        if (!empty($filters['max_area']) && is_numeric($filters['max_area'])) {
            $params['max_area'] = (float)$filters['max_area'];
        }

        // Keywords - search parameter sifatida
        if (!empty($filters['keywords']) && is_array($filters['keywords']) && count($filters['keywords']) > 0) {
            $keywords = array_filter($filters['keywords']);
            if (!empty($keywords)) {
                $params['search'] = implode(' ', $keywords);
            }
        }

        // Agar hech qanday filter bo'lmasa, null qaytar
        if (empty($params)) {
            return null;
        }

        // URL yaratish (query parametrlar sifatida)
        $baseUrl = route('listings');
        $queryString = http_build_query($params);
        
        return $baseUrl . '?' . $queryString;
    }

    /**
     * Chat history'ni tozalash
     */
    public function clearHistory(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $sessionKey = 'chatbot_history_' . $locale;
        Session::forget($sessionKey);

        return response()->json([
            'success' => true,
            'message' => 'History cleared',
        ]);
    }

    /**
     * Chatbot welcome message
     */
    public function welcome(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $messages = [
            'uz' => 'Salom! 👋 Men UYTV.uz yordamchisiman. Sizga mos uy-joylarni topishda yordam beraman. Qanday uy-joy kerak? Masalan: "3 xonali uy Toshkentda, 100 mln gacha"',
            'ru' => 'Привет! 👋 Я помощник UYTV.uz. Помогу найти подходящую недвижимость. Какая недвижимость нужна? Например: "3-комнатная квартира в Ташкенте до 100 млн"',
            'en' => 'Hello! 👋 I\'m UYTV.uz assistant. I\'ll help you find suitable properties. What property do you need? For example: "3 bedroom apartment in Tashkent up to 100M"',
        ];

        return response()->json([
            'success' => true,
            'message' => $messages[$locale] ?? $messages['uz'],
        ]);
    }
}

