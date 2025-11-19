<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        if ($modelType = $request->input('model_type')) {
            $query->where('model_type', $modelType);
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($search = trim($request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $days = $request->input('days', 7);
        if ($days) {
            $query->where('created_at', '>=', now()->subDays($days));
        }

        $logs = $query->paginate(50)->withQueryString();

        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week' => ActivityLog::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => ActivityLog::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        $actions = ActivityLog::distinct()->pluck('action')->sort()->values();
        $modelTypes = ActivityLog::distinct()->pluck('model_type')->sort()->values();

        return view('admin.activity-logs.index', compact('logs', 'stats', 'actions', 'modelTypes'));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'Log yozuvi muvaffaqiyatli o\'chirildi.');
    }

    public function clear(Request $request)
    {
        $days = $request->input('days', 30);
        
        ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', "{$days} kundan eski log yozuvlari o'chirildi.");
    }
}
