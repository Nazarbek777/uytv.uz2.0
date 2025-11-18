<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'openai_api_key' => env('OPENAI_API_KEY', ''),
            'openai_model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'scraper_enabled' => env('SCRAPER_ENABLED', 'true'),
            'scraper_limit' => env('SCRAPER_LIMIT', '100'),
            'scraper_sources' => env('SCRAPER_SOURCES', 'olx,uybor,exarid'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'openai_api_key' => 'nullable|string',
            'openai_model' => 'required|string|in:gpt-4o-mini,gpt-4o,gpt-4-turbo,gpt-4',
            'scraper_enabled' => 'nullable',
            'scraper_limit' => 'nullable|integer|min:1|max:1000',
            'scraper_sources' => 'nullable|string',
        ]);

        // Scraper enabled ni boolean'ga o'zgartirish
        $validated['scraper_enabled'] = $request->has('scraper_enabled') ? 'true' : 'false';

        // .env faylini yangilash
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            return redirect()->route('admin.settings.index')
                ->with('error', '.env fayl topilmadi!');
        }
        
        $envContent = File::get($envPath);
        
        // Har bir sozlamani yangilash
        foreach ($validated as $key => $value) {
            $keyUpper = strtoupper($key);
            
            if ($value === null || $value === '') {
                // O'chirish (faqat bo'sh bo'lsa)
                $envContent = preg_replace("/^{$keyUpper}=.*$/m", '', $envContent);
            } else {
                // Value'ni escape qilish (maxsus belgilar uchun)
                $escapedValue = $this->escapeEnvValue($value);
                
                // Yangilash yoki qo'shish
                if (preg_match("/^{$keyUpper}=/m", $envContent)) {
                    $envContent = preg_replace("/^{$keyUpper}=.*$/m", "{$keyUpper}={$escapedValue}", $envContent);
                } else {
                    // Agar fayl oxirida yangi qator bo'lmasa, qo'shish
                    if (!preg_match("/\n$/", $envContent)) {
                        $envContent .= "\n";
                    }
                    $envContent .= "{$keyUpper}={$escapedValue}\n";
                }
            }
        }
        
        // Bo'sh qatorlarni tozalash (3+ ketma-ket bo'sh qatorlarni 2 ga kamaytirish)
        $envContent = preg_replace("/\n{3,}/", "\n\n", $envContent);
        
        // Fayl oxiridagi ortiqcha bo'sh qatorlarni olib tashlash
        $envContent = rtrim($envContent) . "\n";
        
        File::put($envPath, $envContent);
        
        // Config cache'ni tozalash
        \Artisan::call('config:clear');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Sozlamalar muvaffaqiyatli yangilandi. Iltimos, config cache tozalandi.');
    }
    
    /**
     * .env value'ni escape qilish
     */
    protected function escapeEnvValue($value): string
    {
        // Agar value'da maxsus belgilar bo'lsa, qo'shtirnoq ichiga olish
        if (preg_match('/[\s#=\$"\'\\\\]/', $value)) {
            return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
        }
        
        return $value;
    }

    public function testOpenAI(Request $request)
    {
        // JSON body'dan yoki form data'dan olish
        $apiKey = $request->input('api_key') ?? $request->json('api_key') ?? env('OPENAI_API_KEY');
        
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'OpenAI API key kiritilmagan'
            ], 400);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'Salom! Bu test xabari. Faqat "OK" deb javob bering.'
                    ]
                ],
                'max_tokens' => 10,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'message' => 'OpenAI API muvaffaqiyatli ishlayapti! Model: ' . ($data['model'] ?? 'gpt-4o-mini')
                ]);
            } else {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? $response->body();
                return response()->json([
                    'success' => false,
                    'message' => 'OpenAI API xatosi: ' . $errorMessage
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xatolik: ' . $e->getMessage()
            ], 500);
        }
    }
}

