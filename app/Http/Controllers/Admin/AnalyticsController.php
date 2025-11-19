<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Development;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', '30'); // days

        // Properties statistics
        $propertiesStats = [
            'total' => Property::count(),
            'published' => Property::where('status', 'published')->count(),
            'pending' => Property::where('status', 'pending')->count(),
            'draft' => Property::where('status', 'draft')->count(),
            'featured' => Property::where('featured', true)->count(),
            'recent' => Property::where('created_at', '>=', now()->subDays($period))->count(),
        ];

        // Developments statistics
        $developmentsStats = [
            'total' => Development::count(),
            'published' => Development::where('status', 'published')->count(),
            'pending' => Development::where('status', 'pending')->count(),
            'featured' => Development::where('featured', true)->count(),
            'recent' => Development::where('created_at', '>=', now()->subDays($period))->count(),
        ];

        // Users statistics
        $usersStats = [
            'total' => User::count(),
            'providers' => User::where('role', 'provider')->count(),
            'builders' => User::where('role', 'builder')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'verified' => User::where('verified', true)->count(),
            'recent' => User::where('created_at', '>=', now()->subDays($period))->count(),
        ];

        // Activity statistics
        $activityStats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week' => ActivityLog::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => ActivityLog::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        // Properties by type
        $propertiesByType = Property::select('property_type', DB::raw('count(*) as count'))
            ->groupBy('property_type')
            ->get()
            ->pluck('count', 'property_type');

        // Properties by city
        $propertiesByCity = Property::select('city', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->pluck('count', 'city');

        // Recent activity
        $recentActivity = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Properties created over time (last 30 days)
        $propertiesOverTime = Property::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics.index', compact(
            'propertiesStats',
            'developmentsStats',
            'usersStats',
            'activityStats',
            'propertiesByType',
            'propertiesByCity',
            'recentActivity',
            'propertiesOverTime',
            'period'
        ));
    }
}
