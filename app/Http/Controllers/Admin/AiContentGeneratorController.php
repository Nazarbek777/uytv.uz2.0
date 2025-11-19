<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiContentGeneration;
use App\Models\Property;
use App\Models\Development;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiContentGeneratorController extends Controller
{
    public function index(Request $request)
    {
        $query = AiContentGeneration::with('user')->latest();

        if ($type = $request->input('content_type')) {
            $query->where('content_type', $type);
        }

        if ($modelType = $request->input('model_type')) {
            $query->where('model_type', $modelType);
        }

        $generations = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => AiContentGeneration::count(),
            'used' => AiContentGeneration::where('is_used', true)->count(),
            'unused' => AiContentGeneration::where('is_used', false)->count(),
            'total_tokens' => AiContentGeneration::sum('tokens_used'),
            'total_cost' => AiContentGeneration::sum('cost'),
        ];

        return view('admin.ai-content-generator.index', compact('generations', 'stats'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'content_type' => 'required|string|in:description,title,meta_description,meta_keywords',
            'locale' => 'required|string|in:uz,ru,en',
        ]);

        $model = match($request->model_type) {
            'Property' => Property::findOrFail($request->model_id),
            'Development' => Development::findOrFail($request->model_id),
            default => abort(404),
        };

        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'OpenAI API key topilmadi'], 400);
        }

        try {
            $prompt = $this->buildPrompt($model, $request->content_type, $request->locale);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => 'Siz uy-joy e\'lonlari uchun kontent yaratuvchi yordamchisiz.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $generatedContent = $data['choices'][0]['message']['content'] ?? '';
                $tokensUsed = $data['usage']['total_tokens'] ?? 0;
                $cost = $this->calculateCost($tokensUsed, env('OPENAI_MODEL', 'gpt-4o-mini'));

                $generation = AiContentGeneration::create([
                    'model_type' => get_class($model),
                    'model_id' => $model->id,
                    'content_type' => $request->content_type,
                    'locale' => $request->locale,
                    'prompt' => $prompt,
                    'generated_content' => $generatedContent,
                    'user_id' => auth()->id(),
                    'tokens_used' => $tokensUsed,
                    'cost' => $cost,
                ]);

                return response()->json([
                    'success' => true,
                    'content' => $generatedContent,
                    'generation_id' => $generation->id,
                ]);
            }

            return response()->json(['error' => 'OpenAI API xatosi'], 500);
        } catch (\Exception $e) {
            Log::error('AI Content Generation xatosi: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function buildPrompt($model, $contentType, $locale): string
    {
        $localeNames = ['uz' => 'o\'zbek', 'ru' => 'rus', 'en' => 'ingliz'];
        $localeName = $localeNames[$locale] ?? 'o\'zbek';

        $baseInfo = "Uy-joy ma'lumotlari:\n";
        if ($model instanceof Property) {
            $baseInfo .= "Narx: {$model->price} {$model->currency}\n";
            $baseInfo .= "Maydon: {$model->area} m²\n";
            $baseInfo .= "Xonalar: {$model->bedrooms}\n";
            $baseInfo .= "Shahar: {$model->city}\n";
        }

        return match($contentType) {
            'description' => "Quyidagi uy-joy uchun {$localeName} tilida jozibali va batafsil tavsif yozing:\n\n{$baseInfo}",
            'title' => "Quyidagi uy-joy uchun {$localeName} tilida qisqa va jozibali sarlavha yozing:\n\n{$baseInfo}",
            'meta_description' => "Quyidagi uy-joy uchun {$localeName} tilida SEO uchun meta tavsif yozing (150-160 belgi):\n\n{$baseInfo}",
            'meta_keywords' => "Quyidagi uy-joy uchun {$localeName} tilida SEO kalit so'zlarni yozing:\n\n{$baseInfo}",
            default => $baseInfo,
        };
    }

    private function calculateCost($tokens, $model): float
    {
        $prices = [
            'gpt-4o-mini' => ['input' => 0.15, 'output' => 0.6], // per 1M tokens
            'gpt-4o' => ['input' => 2.5, 'output' => 10],
            'gpt-4-turbo' => ['input' => 10, 'output' => 30],
            'gpt-4' => ['input' => 30, 'output' => 60],
        ];

        $price = $prices[$model] ?? $prices['gpt-4o-mini'];
        return ($tokens / 1000000) * (($price['input'] + $price['output']) / 2);
    }
}
