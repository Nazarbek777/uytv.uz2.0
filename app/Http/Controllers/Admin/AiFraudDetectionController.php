<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiFraudDetection;
use App\Models\Property;
use App\Models\Development;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiFraudDetectionController extends Controller
{
    public function index(Request $request)
    {
        $query = AiFraudDetection::with(['reviewedBy'])->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($minScore = $request->input('min_score')) {
            $query->where('fraud_score', '>=', $minScore);
        }

        $detections = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => AiFraudDetection::count(),
            'pending' => AiFraudDetection::where('status', 'pending')->count(),
            'high_risk' => AiFraudDetection::where('fraud_score', '>=', 70)->count(),
            'medium_risk' => AiFraudDetection::whereBetween('fraud_score', [40, 69])->count(),
            'low_risk' => AiFraudDetection::where('fraud_score', '<', 40)->count(),
        ];

        return view('admin.ai-fraud-detection.index', compact('detections', 'stats'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string|in:Property,Development',
            'model_id' => 'required|integer',
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
            $prompt = $this->buildFraudDetectionPrompt($model);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => 'Siz uy-joy e\'lonlarini yolg\'onlik uchun tekshiruvchi ekspertsiz.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.3,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $analysis = json_decode($data['choices'][0]['message']['content'] ?? '{}', true);
                
                $fraudScore = $analysis['fraud_score'] ?? 0;
                $issues = $analysis['issues'] ?? [];

                $detection = AiFraudDetection::updateOrCreate(
                    [
                        'model_type' => get_class($model),
                        'model_id' => $model->id,
                    ],
                    [
                        'fraud_score' => $fraudScore,
                        'detected_issues' => $issues,
                        'ai_analysis' => $analysis,
                        'status' => 'pending',
                    ]
                );

                return response()->json([
                    'success' => true,
                    'fraud_score' => $fraudScore,
                    'issues' => $issues,
                    'detection_id' => $detection->id,
                ]);
            }

            return response()->json(['error' => 'OpenAI API xatosi'], 500);
        } catch (\Exception $e) {
            Log::error('AI Fraud Detection xatosi: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function review(Request $request, AiFraudDetection $aiFraudDetection)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'review_notes' => 'nullable|string',
        ]);

        $aiFraudDetection->update([
            'status' => $request->status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->review_notes,
        ]);

        return redirect()->route('admin.ai-fraud-detection.index')
            ->with('success', 'Tekshiruv muvaffaqiyatli yakunlandi.');
    }

    private function buildFraudDetectionPrompt($model): string
    {
        $info = "Quyidagi uy-joy e'lonini yolg'onlik uchun tekshiring:\n\n";
        
        if ($model instanceof Property) {
            $info .= "Narx: {$model->price} {$model->currency}\n";
            $info .= "Maydon: {$model->area} m²\n";
            $info .= "Xonalar: {$model->bedrooms}\n";
            $info .= "Shahar: {$model->city}\n";
            $info .= "Tavsif: " . ($model->translate('uz')->description ?? '') . "\n";
        }

        $info .= "\nQuyidagi formatda JSON javob bering:\n";
        $info .= '{"fraud_score": 0-100, "issues": ["muammo1", "muammo2"], "reasoning": "tahlil"}';

        return $info;
    }
}
