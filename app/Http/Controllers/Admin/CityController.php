<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::query()->orderBy('sort_order')->orderBy('name_uz');

        if ($region = $request->input('region')) {
            $query->where('region', $region);
        }

        if ($search = trim($request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name_uz', 'like', "%{$search}%")
                    ->orWhere('name_ru', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('region', 'like', "%{$search}%");
            });
        }

        $cities = $query->paginate(20)->withQueryString();

        $regions = City::distinct()->pluck('region')->filter()->sort()->values();

        $stats = [
            'total' => City::count(),
            'active' => City::where('is_active', true)->count(),
        ];

        return view('admin.cities.index', compact('cities', 'stats', 'regions'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name_uz']);
        $validated['is_active'] = $request->has('is_active');

        City::create($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', 'Shahar muvaffaqiyatli qo\'shildi.');
    }

    public function show(City $city)
    {
        return view('admin.cities.show', compact('city'));
    }

    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($city->name_uz !== $validated['name_uz']) {
            $validated['slug'] = Str::slug($validated['name_uz']);
        }

        $validated['is_active'] = $request->has('is_active');

        $city->update($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', 'Shahar muvaffaqiyatli yangilandi.');
    }

    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'Shahar muvaffaqiyatli o\'chirildi.');
    }
}
