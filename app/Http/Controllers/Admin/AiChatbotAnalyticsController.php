<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiChatbotLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiChatbotAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $query = AiChatbotLog::query()->latest();

        if ($locale = $request->input('locale')) {
            $query->where('locale', $locale);
        }

        if ($search = trim($request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('user_message', 'like', "%{$search}%")
                    ->orWhere('ai_response', 'like', "%{$search}%");
            });
        }

        $days = $request->input('days', 7);
        if ($days) {
            $query->where('created_at', '>=', now()->subDays($days));
        }

        $logs = $query->paginate(50)->withQueryString();

        $stats = [
            'total' => AiChatbotLog::count(),
            'today' => AiChatbotLog::whereDate('created_at', today())->count(),
            'this_week' => AiChatbotLog::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => AiChatbotLog::where('created_at', '>=', now()->startOfMonth())->count(),
            'successful' => AiChatbotLog::where('success', true)->count(),
            'failed' => AiChatbotLog::where('success', false)->count(),
            'avg_response_time' => AiChatbotLog::whereNotNull('response_time_ms')->avg('response_time_ms'),
        ];

        $topQueries = AiChatbotLog::select('user_message', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('user_message')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $localeStats = AiChatbotLog::select('locale', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('locale')
            ->get()
            ->pluck('count', 'locale');

        return view('admin.ai-chatbot-analytics.index', compact('logs', 'stats', 'topQueries', 'localeStats', 'days'));
    }

    public function show(AiChatbotLog $aiChatbotLog)
    {
        return view('admin.ai-chatbot-analytics.show', compact('aiChatbotLog'));
    }
}
