<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AiPricePredictorController extends Controller
{
    public function index()
    {
        return view('admin.ai-price-predictor.index');
    }

    public function predict(Request $request)
    {
        $request->validate([
            'area' => 'required|numeric|min:1',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'city' => 'required|string',
            'property_type' => 'required|string',
            'listing_type' => 'required|string|in:sale,rent',
        ]);

        // Avval bazadan o'xshash uy-joylarni topish
        $similarProperties = Property::where('city', $request->city)
            ->where('property_type', $request->property_type)
            ->where('listing_type', $request->listing_type)
            ->where('status', 'published')
            ->whereBetween('area', [$request->area * 0.8, $request->area * 1.2])
            ->whereBetween('bedrooms', [$request->bedrooms - 1, $request->bedrooms + 1])
            ->limit(50)
            ->get();

        $avgPrice = $similarProperties->avg('price');
        $minPrice = $similarProperties->min('price');
        $maxPrice = $similarProperties->max('price');

        // AI orqali aniqroq bashorat
        $apiKey = env('OPENAI_API_KEY');
        if ($apiKey) {
            try {
                $prompt = "Quyidagi parametrlarga asoslanib, uy-joy narxini bashorat qiling:\n\n";
                $prompt .= "Maydon: {$request->area} m²\n";
                $prompt .= "Xonalar: {$request->bedrooms}\n";
                $prompt .= "Hammomlar: {$request->bathrooms}\n";
                $prompt .= "Shahar: {$request->city}\n";
                $prompt .= "Turi: {$request->property_type}\n";
                $prompt .= "E'lon turi: {$request->listing_type}\n\n";
                $prompt .= "O'xshash uy-joylar o'rtacha narxi: {$avgPrice} UZS\n";
                $prompt .= "Min: {$minPrice} UZS, Max: {$maxPrice} UZS\n\n";
                $prompt .= "JSON formatda javob bering: {\"predicted_price\": 123456789, \"confidence\": 85, \"reasoning\": \"tahlil\"}";

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                    'messages' => [
                        ['role' => 'system', 'content' => 'Siz uy-joy narxlarini bashorat qiluvchi ekspertsiz.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.3,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $analysis = json_decode($data['choices'][0]['message']['content'] ?? '{}', true);
                    
                    return response()->json([
                        'success' => true,
                        'predicted_price' => $analysis['predicted_price'] ?? $avgPrice,
                        'confidence' => $analysis['confidence'] ?? 70,
                        'reasoning' => $analysis['reasoning'] ?? '',
                        'market_avg' => $avgPrice,
                        'market_min' => $minPrice,
                        'market_max' => $maxPrice,
                        'similar_count' => $similarProperties->count(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('AI Price Prediction xatosi: ' . $e->getMessage());
            }
        }

        // AI ishlamasa, oddiy statistika
        return response()->json([
            'success' => true,
            'predicted_price' => $avgPrice,
            'confidence' => $similarProperties->count() > 10 ? 75 : 50,
            'reasoning' => 'Bazadagi o\'xshash uy-joylar statistikasiga asoslangan',
            'market_avg' => $avgPrice,
            'market_min' => $minPrice,
            'market_max' => $maxPrice,
            'similar_count' => $similarProperties->count(),
        ]);
    }
}
