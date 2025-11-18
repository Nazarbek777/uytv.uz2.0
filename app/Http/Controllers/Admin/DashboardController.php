<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Development;
use App\Models\Property;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'providers' => User::where('role', 'provider')->count(),
            'builders' => User::where('role', 'builder')->count(),
            'properties' => Property::count(),
            'pending_properties' => Property::where('status', 'pending')->count(),
            'developments' => Development::count(),
            'pending_developments' => Development::where('status', 'pending')->count(),
        ];

        $latestUsers = User::latest()->limit(5)->get();
        $pendingProperties = Property::with('user')
            ->latest()
            ->where('status', 'pending')
            ->limit(5)
            ->get();
        $pendingDevelopments = Development::with('builder')
            ->latest()
            ->where('status', 'pending')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'latestUsers',
            'pendingProperties',
            'pendingDevelopments'
        ));
    }
}
