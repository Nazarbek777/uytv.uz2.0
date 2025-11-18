<?php

namespace App\Http\Controllers.Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of the providers (B2C).
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'provider');

        if ($search = trim($request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!is_null($request->input('verified'))) {
            $query->where('verified', (bool) $request->input('verified'));
        }

        if (!is_null($request->input('featured'))) {
            $query->where('featured', (bool) $request->input('featured'));
        }

        $providers = $query
            ->withCount([
                'properties',
                'properties as published_properties_count' => function ($q) {
                    $q->where('status', 'published');
                },
                'properties as pending_properties_count' => function ($q) {
                    $q->where('status', 'pending');
                },
            ])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => User::where('role', 'provider')->count(),
            'verified' => User::where('role', 'provider')->where('verified', true)->count(),
            'pending_listings' => Property::where('status', 'pending')->count(),
            'today' => User::where('role', 'provider')->whereDate('created_at', today())->count(),
        ];

        return view('admin.providers.index', compact('providers', 'stats'));
    }

    /**
     * Display provider details.
     */
    public function show(User $provider)
    {
        abort_unless($provider->role === 'provider', 404);

        $provider->load([
            'properties' => function ($query) {
                $query->latest()->limit(10);
            },
        ]);

        $stats = [
            'total' => $provider->properties()->count(),
            'pending' => $provider->properties()->where('status', 'pending')->count(),
            'published' => $provider->properties()->where('status', 'published')->count(),
            'rejected' => $provider->properties()->where('status', 'rejected')->count(),
        ];

        return view('admin.providers.show', compact('provider', 'stats'));
    }

    public function toggleVerified(User $provider)
    {
        abort_unless($provider->role === 'provider', 404);
        $provider->verified = ! $provider->verified;
        $provider->save();

        return back()->with('success', 'Provider verifikatsiya holati yangilandi.');
    }

    public function toggleFeatured(User $provider)
    {
        abort_unless($provider->role === 'provider', 404);
        $provider->featured = ! $provider->featured;
        $provider->save();

        return back()->with('success', 'Provider featured holati yangilandi.');
    }
}

