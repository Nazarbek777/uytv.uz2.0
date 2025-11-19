<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiSearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiSearchAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $query = AiSearchLog::query()->latest();

        if ($locale = $request->input('locale')) {
            $query->where('locale', $locale);
        }

        if ($search = trim($request->input('search'))) {
            $query->where('query', 'like', "%{$search}%");
        }

        $days = $request->input('days', 7);
        if ($days) {
            $query->where('created_at', '>=', now()->subDays($days));
        }

        $logs = $query->paginate(50)->withQueryString();

        $stats = [
            'total' => AiSearchLog::count(),
            'today' => AiSearchLog::whereDate('created_at', today())->count(),
            'this_week' => AiSearchLog::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => AiSearchLog::where('created_at', '>=', now()->startOfMonth())->count(),
            'successful' => AiSearchLog::where('success', true)->count(),
            'with_results' => AiSearchLog::where('results_count', '>', 0)->count(),
            'avg_results' => AiSearchLog::where('results_count', '>', 0)->avg('results_count'),
            'avg_response_time' => AiSearchLog::whereNotNull('response_time_ms')->avg('response_time_ms'),
        ];

        $topQueries = AiSearchLog::select('query', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $localeStats = AiSearchLog::select('locale', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('locale')
            ->get()
            ->pluck('count', 'locale');

        return view('admin.ai-search-analytics.index', compact('logs', 'stats', 'topQueries', 'localeStats', 'days'));
    }

    public function show(AiSearchLog $aiSearchLog)
    {
        return view('admin.ai-search-analytics.show', compact('aiSearchLog'));
    }
}
