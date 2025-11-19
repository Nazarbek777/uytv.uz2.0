<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected array $supportedLocales = ['uz', 'ru', 'en'];
    
    /**
     * Translate text from source locale to target locale.
     * 
     * @param string $text Text to translate
     * @param string $sourceLocale Source locale (uz, ru, en)
     * @param string $targetLocale Target locale (uz, ru, en)
     * @return string Translated text
     */
    public function translate(string $text, string $sourceLocale, string $targetLocale): string
    {
        if ($sourceLocale === $targetLocale) {
            return $text;
        }
        
        // Google Translate API yoki boshqa tarjima servisi
        // Bu yerda siz o'z API keyingizni qo'yishingiz kerak
        $apiKey = config('services.google_translate.api_key');
        
        if (!$apiKey) {
            // Agar API key bo'lmasa, oddiy tarjima qilish (keyinchalik to'ldiriladi)
            Log::warning('Translation API key not configured');
            return $text;
        }
        
        try {
            $response = Http::post('https://translation.googleapis.com/language/translate/v2', [
                'key' => $apiKey,
                'q' => $text,
                'source' => $this->mapLocaleToGoogleCode($sourceLocale),
                'target' => $this->mapLocaleToGoogleCode($targetLocale),
                'format' => 'text',
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['data']['translations'][0]['translatedText'] ?? $text;
            }
        } catch (\Exception $e) {
            Log::error('Translation failed: ' . $e->getMessage());
        }
        
        return $text;
    }
    
    /**
     * Translate multiple texts at once.
     */
    public function translateBatch(array $texts, string $sourceLocale, string $targetLocale): array
    {
        $translated = [];
        
        foreach ($texts as $key => $text) {
            $translated[$key] = $this->translate($text, $sourceLocale, $targetLocale);
        }
        
        return $translated;
    }
    
    /**
     * Auto-translate property data to all supported locales.
     */
    public function translateProperty(array $data, string $sourceLocale): array
    {
        $translated = [];
        
        // Tarjima qilinadigan maydonlar
        $translatableFields = [
            'title',
            'description',
            'short_description',
            'address',
            'features',
            'nearby_places',
            'meta_title',
            'meta_description',
            'meta_keywords',
        ];
        
        foreach ($this->supportedLocales as $targetLocale) {
            if ($targetLocale === $sourceLocale) {
                // Asosiy tilda kiritilgan ma'lumotlarni o'zgartirmasdan qoldiramiz
                $translated[$targetLocale] = $data;
                continue;
            }
            
            $translated[$targetLocale] = [];
            
            foreach ($translatableFields as $field) {
                if (isset($data[$field]) && !empty($data[$field])) {
                    $translated[$targetLocale][$field] = $this->translate(
                        $data[$field],
                        $sourceLocale,
                        $targetLocale
                    );
                }
            }
        }
        
        return $translated;
    }
    
    /**
     * Map locale code to Google Translate code.
     */
    protected function mapLocaleToGoogleCode(string $locale): string
    {
        return match($locale) {
            'uz' => 'uz',
            'ru' => 'ru',
            'en' => 'en',
            default => 'en',
        };
    }
    
    /**
     * Alternative: Use DeepL API (more accurate for some languages).
     */
    public function translateWithDeepL(string $text, string $sourceLocale, string $targetLocale): string
    {
        $apiKey = config('services.deepl.api_key');
        
        if (!$apiKey) {
            return $text;
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => "DeepL-Auth-Key {$apiKey}",
            ])->post('https://api-free.deepl.com/v2/translate', [
                'text' => $text,
                'source_lang' => strtoupper($this->mapLocaleToDeepLCode($sourceLocale)),
                'target_lang' => strtoupper($this->mapLocaleToDeepLCode($targetLocale)),
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['translations'][0]['text'] ?? $text;
            }
        } catch (\Exception $e) {
            Log::error('DeepL Translation failed: ' . $e->getMessage());
        }
        
        return $text;
    }
    
    /**
     * Map locale code to DeepL code.
     */
    protected function mapLocaleToDeepLCode(string $locale): string
    {
        return match($locale) {
            'uz' => 'EN', // DeepL doesn't support Uzbek, use English as fallback
            'ru' => 'RU',
            'en' => 'EN',
            default => 'EN',
        };
    }
}









