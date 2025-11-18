<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Development;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DevelopmentController extends Controller
{
    /**
     * Display a listing of the developments.
     */
    public function index(Request $request)
    {
        $query = Development::query()
            ->with('builder')
            ->latest();

        if ($search = trim($request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title_uz', 'like', "%{$search}%")
                    ->orWhere('title_ru', 'like', "%{$search}%")
                    ->orWhere('developer_name_uz', 'like', "%{$search}%")
                    ->orWhereHas('builder', function ($builderQuery) use ($search) {
                        $builderQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($city = $request->input('city')) {
            $query->where('city', 'like', "%{$city}%");
        }

        $developments = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Development::count(),
            'pending' => Development::where('status', 'pending')->count(),
            'published' => Development::where('status', 'published')->count(),
            'draft' => Development::where('status', 'draft')->count(),
            'today' => Development::whereDate('created_at', today())->count(),
        ];

        return view('admin.developments.index', compact('developments', 'stats'));
    }

    /**
     * Display the specified development.
     */
    public function show(Development $development)
    {
        $development->loadMissing([
            'builder',
            'properties',
            'floorPlans',
            'documents',
        ]);

        return view('admin.developments.show', compact('development'));
    }

    /**
     * Show the form for editing the specified development.
     */
    public function edit(Development $development)
    {
        $development->loadMissing(['builder']);
        $builders = User::where('role', 'builder')->orderBy('name')->get();

        return view('admin.developments.edit', compact('development', 'builders'));
    }

    /**
     * Update the specified development in storage.
     */
    public function update(Request $request, Development $development)
    {
        $validated = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')],
            'status' => ['required', Rule::in(['draft', 'pending', 'published', 'rejected'])],
            'title_uz' => ['required', 'string', 'max:255'],
            'title_ru' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'developer_name_uz' => ['required', 'string', 'max:255'],
            'developer_name_ru' => ['nullable', 'string', 'max:255'],
            'developer_name_en' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'address_uz' => ['nullable', 'string'],
            'address_ru' => ['nullable', 'string'],
            'address_en' => ['nullable', 'string'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_per_sqm' => ['nullable', 'numeric', 'min:0'],
            'completion_date' => ['nullable', 'date_format:Y-m'],
            'total_buildings' => ['nullable', 'integer', 'min:1'],
            'total_floors' => ['nullable', 'integer', 'min:1'],
            'featured' => ['nullable', 'boolean'],
            'installment_available' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($development, $validated, $request) {
            $amenities = collect(explode(',', (string) $request->input('amenities', '')))
                ->map(fn ($item) => trim($item))
                ->filter()
                ->values()
                ->all();

            $completionDate = $validated['completion_date'] ?? null;
            if ($completionDate) {
                $completionDate = $completionDate . '-01';
            }

            $development->fill([
                'user_id' => $validated['user_id'],
                'status' => $validated['status'],
                'title_uz' => $validated['title_uz'],
                'title_ru' => $validated['title_ru'] ?? null,
                'title_en' => $validated['title_en'] ?? null,
                'developer_name_uz' => $validated['developer_name_uz'],
                'developer_name_ru' => $validated['developer_name_ru'] ?? null,
                'developer_name_en' => $validated['developer_name_en'] ?? null,
                'city' => $validated['city'],
                'region' => $validated['region'] ?? null,
                'address_uz' => $validated['address_uz'] ?? null,
                'address_ru' => $validated['address_ru'] ?? null,
                'address_en' => $validated['address_en'] ?? null,
                'price_from' => $validated['price_from'] ?? null,
                'price_per_sqm' => $validated['price_per_sqm'] ?? null,
                'completion_date' => $completionDate,
                'total_buildings' => $validated['total_buildings'] ?? null,
                'total_floors' => $validated['total_floors'] ?? null,
                'featured' => $request->boolean('featured'),
                'installment_available' => $request->boolean('installment_available'),
                'amenities' => $amenities,
            ]);

            $development->save();
        });

        return redirect()
            ->route('admin.developments.show', $development->id)
            ->with('success', 'Qurilish ma\'lumotlari yangilandi.');
    }

    /**
     * Remove the specified development from storage.
     */
    public function destroy(Development $development)
    {
        $development->delete();

        return redirect()
            ->route('admin.developments.index')
            ->with('success', 'Qurilish loyihasi o\'chirildi.');
    }

    /**
     * Approve development submission.
     */
    public function approve(Development $development)
    {
        $development->status = 'published';
        $development->save();

        return back()->with('success', 'Qurilish loyihasi tasdiqlandi.');
    }

    /**
     * Reject development submission.
     */
    public function reject(Development $development)
    {
        $development->status = 'rejected';
        $development->save();

        return back()->with('success', 'Qurilish loyihasi rad etildi.');
    }

    /**
     * Toggle featured flag.
     */
    public function toggleFeatured(Development $development)
    {
        $development->featured = ! $development->featured;
        $development->save();

        return back()->with('success', 'Featured holati yangilandi.');
    }
}
