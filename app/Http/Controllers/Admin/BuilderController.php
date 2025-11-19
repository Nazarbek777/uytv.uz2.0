<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Development;
use App\Models\User;
use Illuminate\Http\Request;

class BuilderController extends Controller
{
    /**
     * Display a listing of the builders (B2B).
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'builder');

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

        $builders = $query
            ->withCount([
                'developments',
                'developments as published_developments_count' => function ($q) {
                    $q->where('status', 'published');
                },
                'developments as pending_developments_count' => function ($q) {
                    $q->where('status', 'pending');
                },
            ])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => User::where('role', 'builder')->count(),
            'verified' => User::where('role', 'builder')->where('verified', true)->count(),
            'pending_projects' => Development::where('status', 'pending')->count(),
            'today' => User::where('role', 'builder')->whereDate('created_at', today())->count(),
        ];

        return view('admin.builders.index', compact('builders', 'stats'));
    }

    /**
     * Display builder details.
     */
    public function show(User $builder)
    {
        abort_unless($builder->role === 'builder', 404);

        $builder->load([
            'developments' => function ($query) {
                $query->latest()->limit(10);
            },
        ]);

        $stats = [
            'total' => $builder->developments()->count(),
            'pending' => $builder->developments()->where('status', 'pending')->count(),
            'published' => $builder->developments()->where('status', 'published')->count(),
            'rejected' => $builder->developments()->where('status', 'rejected')->count(),
        ];

        return view('admin.builders.show', compact('builder', 'stats'));
    }

    public function toggleVerified(User $builder)
    {
        abort_unless($builder->role === 'builder', 404);
        $builder->verified = ! $builder->verified;
        $builder->save();

        return back()->with('success', 'Quruvchi verifikatsiya holati yangilandi.');
    }

    public function toggleFeatured(User $builder)
    {
        abort_unless($builder->role === 'builder', 404);
        $builder->featured = ! $builder->featured;
        $builder->save();

        return back()->with('success', 'Quruvchi featured holati yangilandi.');
    }
}



