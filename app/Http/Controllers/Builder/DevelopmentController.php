<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Development;
use App\Models\DevelopmentDocument;
use App\Models\DevelopmentProperty;
use App\Models\FloorPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DevelopmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $developments = Development::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('developer.developments.index', compact('developments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('developer.developments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_uz' => 'required|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_uz' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'description_en' => 'nullable|string',
            'developer_name_uz' => 'required|string|max:255',
            'developer_name_ru' => 'nullable|string|max:255',
            'developer_name_en' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'address_uz' => 'nullable|string',
            'address_ru' => 'nullable|string',
            'address_en' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'price_from' => 'nullable|numeric|min:0',
            'price_per_sqm' => 'nullable|numeric|min:0',
            'completion_date' => 'nullable|date',
            'total_buildings' => 'nullable|integer|min:1',
            'total_floors' => 'nullable|integer|min:1',
            'featured_image' => 'nullable|image|max:5120',
            'images.*' => 'nullable|image|max:5120',
            'amenities' => 'nullable|array',
            'properties' => 'required|array|min:1',
            'properties.*.bedrooms' => 'required|integer|min:1',
            'properties.*.area_from' => 'required|numeric|min:1',
            'properties.*.area_to' => 'nullable|numeric|min:1',
            'properties.*.price_from' => 'required|numeric|min:0',
            'properties.*.currency' => 'nullable|string|in:UZS,USD,EUR',
        ]);

        try {
            $development = new Development();
            $development->user_id = auth()->id();
            $development->fill($validated);
            $development->status = 'draft';
            
            // Slug yaratish
            $development->slug = Str::slug($validated['title_uz']) . '-' . time();

            // Featured image yuklash
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('developments', 'public');
                $development->featured_image = $path;
            }

            // Images yuklash
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('developments', 'public');
                    $images[] = $path;
                }
                $development->images = $images;
            }

            $development->save();

            // Properties saqlash
            foreach ($validated['properties'] as $propData) {
                DevelopmentProperty::create([
                    'development_id' => $development->id,
                    'bedrooms' => $propData['bedrooms'],
                    'property_type' => $propData['property_type'] ?? 'apartment',
                    'area_from' => $propData['area_from'],
                    'area_to' => $propData['area_to'] ?? null,
                    'price_from' => $propData['price_from'],
                    'currency' => $propData['currency'] ?? 'UZS',
                    'quantity_available' => $propData['quantity_available'] ?? null,
                    'total_quantity' => $propData['total_quantity'] ?? null,
                    'notes_uz' => $propData['notes_uz'] ?? null,
                    'notes_ru' => $propData['notes_ru'] ?? null,
                    'notes_en' => $propData['notes_en'] ?? null,
                ]);
            }

            return redirect()->route('developer.developments.index')
                ->with('success', 'Yangi qurilish yaratildi! Endi uni tahrirlashingiz va tasdiqlashga yuborishingiz mumkin.');

        } catch (\Exception $e) {
            Log::error('Development yaratish xatosi: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $development = Development::where('user_id', auth()->id())
            ->with(['properties', 'floorPlans', 'documents'])
            ->findOrFail($id);

        return view('developer.developments.show', compact('development'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $development = Development::where('user_id', auth()->id())
            ->with(['properties', 'floorPlans', 'documents'])
            ->findOrFail($id);

        // Faqat draft yoki rejected status'da tahrirlash mumkin
        if (!in_array($development->status, ['draft', 'rejected'])) {
            return redirect()->route('developer.developments.index')
                ->with('error', 'Faqat tasdiqlanmagan loyihalarni tahrirlash mumkin.');
        }

        return view('developer.developments.edit', compact('development'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $development = Development::where('user_id', auth()->id())->findOrFail($id);

        // Faqat draft yoki rejected status'da tahrirlash mumkin
        if (!in_array($development->status, ['draft', 'rejected'])) {
            return redirect()->route('developer.developments.index')
                ->with('error', 'Faqat tasdiqlanmagan loyihalarni tahrirlash mumkin.');
        }

        $validated = $request->validate([
            'title_uz' => 'required|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_uz' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'description_en' => 'nullable|string',
            'developer_name_uz' => 'required|string|max:255',
            'developer_name_ru' => 'nullable|string|max:255',
            'developer_name_en' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'address_uz' => 'nullable|string',
            'address_ru' => 'nullable|string',
            'address_en' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'price_from' => 'nullable|numeric|min:0',
            'price_per_sqm' => 'nullable|numeric|min:0',
            'completion_date' => 'nullable|date',
            'total_buildings' => 'nullable|integer|min:1',
            'total_floors' => 'nullable|integer|min:1',
            'featured_image' => 'nullable|image|max:5120',
            'images.*' => 'nullable|image|max:5120',
            'amenities' => 'nullable|array',
            'properties' => 'nullable|array',
        ]);

        try {
            $development->fill($validated);

            // Featured image yuklash
            if ($request->hasFile('featured_image')) {
                // Eski rasmini o'chirish
                if ($development->featured_image) {
                    Storage::disk('public')->delete($development->featured_image);
                }
                $path = $request->file('featured_image')->store('developments', 'public');
                $development->featured_image = $path;
            }

            // Images yuklash
            if ($request->hasFile('images')) {
                $currentImages = $development->images ?? [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('developments', 'public');
                    $currentImages[] = $path;
                }
                $development->images = $currentImages;
            }

            $development->save();

            // Properties yangilash
            if (isset($validated['properties'])) {
                // Eski properties'ni o'chirish
                $development->properties()->delete();

                foreach ($validated['properties'] as $propData) {
                    DevelopmentProperty::create([
                        'development_id' => $development->id,
                        'bedrooms' => $propData['bedrooms'],
                        'property_type' => $propData['property_type'] ?? 'apartment',
                        'area_from' => $propData['area_from'],
                        'area_to' => $propData['area_to'] ?? null,
                        'price_from' => $propData['price_from'],
                        'currency' => $propData['currency'] ?? 'UZS',
                        'quantity_available' => $propData['quantity_available'] ?? null,
                        'total_quantity' => $propData['total_quantity'] ?? null,
                        'notes_uz' => $propData['notes_uz'] ?? null,
                        'notes_ru' => $propData['notes_ru'] ?? null,
                        'notes_en' => $propData['notes_en'] ?? null,
                    ]);
                }
            }

            return redirect()->route('developer.developments.index')
                ->with('success', 'Loyiha yangilandi!');

        } catch (\Exception $e) {
            Log::error('Development yangilash xatosi: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $development = Development::where('user_id', auth()->id())->findOrFail($id);

        // Faqat draft status'da o'chirish mumkin
        if ($development->status !== 'draft') {
            return redirect()->route('developer.developments.index')
                ->with('error', 'Faqat tasdiqlanmagan loyihalarni o\'chirish mumkin.');
        }

        try {
            // Rasmlarni o'chirish
            if ($development->featured_image) {
                Storage::disk('public')->delete($development->featured_image);
            }
            if ($development->images) {
                foreach ($development->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $development->delete();

            return redirect()->route('developer.developments.index')
                ->with('success', 'Loyiha o\'chirildi.');

        } catch (\Exception $e) {
            Log::error('Development o\'chirish xatosi: ' . $e->getMessage());
            return back()->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Submit development for approval
     */
    public function submit(string $id)
    {
        $development = Development::where('user_id', auth()->id())->findOrFail($id);

        if ($development->status !== 'draft') {
            return back()->with('error', 'Faqat yangi loyihalarni tasdiqlashga yuborish mumkin.');
        }

        $development->status = 'pending';
        $development->save();

        return back()->with('success', 'Loyiha admin tasdiqlashiga yuborildi!');
    }
}