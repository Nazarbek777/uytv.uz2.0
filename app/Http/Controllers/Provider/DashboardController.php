<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $statusCounts = Property::select('status', DB::raw('COUNT(*) as total'))
            ->where('user_id', $user->id)
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total' => $statusCounts->sum(),
            'published' => $statusCounts['published'] ?? 0,
            'pending' => $statusCounts['pending'] ?? 0,
            'draft' => $statusCounts['draft'] ?? 0,
            'rejected' => $statusCounts['rejected'] ?? 0,
            'views' => Property::where('user_id', $user->id)->sum('views'),
            'favorites' => Property::where('user_id', $user->id)->sum('favorites_count'),
        ];

        $recentProperties = Property::where('user_id', $user->id)
            ->with('translations')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        $topViewedProperties = Property::where('user_id', $user->id)
            ->with('translations')
            ->orderByDesc('views')
            ->limit(5)
            ->get();

        $approvalQueue = Property::where('user_id', $user->id)
            ->with('translations')
            ->whereIn('status', ['pending', 'rejected'])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        $activityTimeline = $this->buildTimeline($user->id);

        [$chartLabels, $chartValues] = $this->buildMonthlyChart($user->id);

        return view('provider.dashboard', compact(
            'user',
            'stats',
            'recentProperties',
            'topViewedProperties',
            'approvalQueue',
            'activityTimeline',
            'chartLabels',
            'chartValues'
        ));
    }

    protected function buildTimeline(int $userId): Collection
    {
        $properties = Property::where('user_id', $userId)
            ->with('translations')
            ->whereNotNull('approval_history')
            ->latest('updated_at')
            ->get();

        return $properties->flatMap(function (Property $property) {
            if (!is_array($property->approval_history)) {
                return [];
            }

            return collect($property->approval_history)->map(function (array $event) use ($property) {
                $timestamp = Carbon::parse($event['timestamp'] ?? $property->updated_at);

                return [
                    'property_id' => $property->id,
                    'title' => $property->title,
                    'status' => $event['status'] ?? 'draft',
                    'meta' => $event['meta'] ?? [],
                    'timestamp' => $timestamp,
                ];
            });
        })
            ->sortByDesc('timestamp')
            ->take(8)
            ->values();
    }

    protected function buildMonthlyChart(int $userId): array
    {
        $start = Carbon::now()->subMonths(5)->startOfMonth();
        $monthlyData = Property::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
            DB::raw('COUNT(*) as total')
        )
            ->where('user_id', $userId)
            ->where('created_at', '>=', $start)
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $labels = [];
        $values = [];

        for ($i = 0; $i < 6; $i++) {
            $month = (clone $start)->addMonths($i);
            $key = $month->format('Y-m');

            $labels[] = $month->translatedFormat('M Y');
            $values[] = (int) ($monthlyData[$key] ?? 0);
        }

        return [$labels, $values];
    }
}
