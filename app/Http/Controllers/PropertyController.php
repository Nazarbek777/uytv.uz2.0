<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\SeoService;
use App\Services\TranslationService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    protected SeoService $seoService;
    protected TranslationService $translationService;
    protected ImageService $imageService;

    public function __construct(SeoService $seoService, TranslationService $translationService, ImageService $imageService)
    {
        $this->seoService = $seoService;
        $this->translationService = $translationService;
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $query = Property::with('translations')
            ->published()
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->has('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->has('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $properties = $query->paginate(12);

        // Filter options
        $cities = Property::published()->distinct()->pluck('city')->filter()->sort()->values();
        $propertyTypes = ['apartment', 'house', 'villa', 'land', 'commercial', 'office'];
        $listingTypes = ['sale', 'rent'];
        $bedrooms = Property::published()->distinct()->pluck('bedrooms')->filter()->sort()->values();

        $minPrice = Property::published()->min('price') ?? 0;
        $maxPrice = Property::published()->max('price') ?? 10000000;

        return view('pages.page-listings', compact(
            'properties',
            'locale',
            'cities',
            'propertyTypes',
            'listingTypes',
            'bedrooms',
            'minPrice',
            'maxPrice'
        ));
    }

    /**
     * Provider dashboard - My properties list
     */
    public function myProperties()
    {
        $properties = Property::with('translations')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('provider.property.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('provider.property.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'area' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|max:10',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'garages' => 'nullable|integer|min:0',
            'floors' => 'nullable|integer|min:0',
            'floor' => 'nullable|integer|min:1',
            'construction_material' => 'nullable|in:gisht,pishgan_gisht,beton,yogoch,paneli,monolit,boshqa',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'property_type' => 'required|in:house,apartment,villa,land,commercial,office',
            'listing_type' => 'required|in:sale,rent',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'videos' => 'nullable|array',
            'videos.*' => 'url',
            'features' => 'nullable|string|max:2000',
            'nearby_places' => 'nullable|string|max:2000',
            'locale' => 'required|in:uz,ru,en',
            // SEO avtomatik yaratiladi, qo'lda kiritilmaydi
        ]);

        // Asosiy tilda kiritilgan ma'lumotlar
        $sourceLocale = $validated['locale'];
        
        // Features va Nearby Places ni array'ga o'tkazish
        // Agar features_array bo'lsa, undan foydalanish, aks holda features string'dan
        if ($request->has('features_array') && is_array($request->features_array)) {
            $features = array_filter(array_map('trim', $request->features_array));
        } else {
            $features = !empty($request->features) ? array_filter(array_map('trim', explode(',', $request->features))) : [];
        }
        $nearbyPlaces = !empty($request->nearby_places) ? array_filter(array_map('trim', explode(',', $request->nearby_places))) : [];
        
        $translatableData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'] ?? null,
            'address' => $validated['address'] ?? null,
            'features' => !empty($features) ? json_encode(array_values($features), JSON_UNESCAPED_UNICODE) : null,
            'nearby_places' => !empty($nearbyPlaces) ? json_encode(array_values($nearbyPlaces), JSON_UNESCAPED_UNICODE) : null,
            // SEO avtomatik yaratiladi
        ];

        // Avtomatik tarjima qilish
        $allTranslations = $this->translationService->translateProperty($translatableData, $sourceLocale);

        // Slug yaratish (Property yaratishdan oldin - NOT NULL constraint uchun)
        $slug = $this->generateUniqueSlug($allTranslations[$sourceLocale]['title']);
        $seoSlugUz = $this->generateUniqueSeoSlug('uz', $allTranslations['uz']['title'] ?? $allTranslations[$sourceLocale]['title']);
        $seoSlugRu = $this->generateUniqueSeoSlug('ru', $allTranslations['ru']['title'] ?? $allTranslations[$sourceLocale]['title']);
        $seoSlugEn = $this->generateUniqueSeoSlug('en', $allTranslations['en']['title'] ?? $allTranslations[$sourceLocale]['title']);

        // Property yaratish (slug avtomatik yaratilgan)
        $property = Property::create([
            'user_id' => Auth::id(),
            'slug' => $slug, // Avtomatik yaratilgan slug
            'price' => $validated['price'],
            'currency' => $validated['currency'],
            'area' => $validated['area'] ?? null,
            'area_unit' => $validated['area_unit'] ?? 'm²',
            'bedrooms' => $validated['bedrooms'] ?? null,
            'bathrooms' => $validated['bathrooms'] ?? null,
            'garages' => $validated['garages'] ?? null,
            'floors' => $validated['floors'] ?? null,
            'floor' => $validated['floor'] ?? null,
            'construction_material' => $validated['construction_material'] ?? null,
            'year_built' => $validated['year_built'] ?? null,
            'property_type' => $validated['property_type'],
            'listing_type' => $validated['listing_type'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'city' => $validated['city'] ?? null,
            'region' => $validated['region'] ?? null,
            'country' => $validated['country'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'status' => 'draft', // Default draft, keyin publish qilish mumkin
            'seo_slug_uz' => $seoSlugUz,
            'seo_slug_ru' => $seoSlugRu,
            'seo_slug_en' => $seoSlugEn,
        ]);

        // Tarjimalarni saqlash
        foreach ($allTranslations as $locale => $data) {
            $property->translateOrNew($locale)->fill($data)->save();
        }

        // SEO metadata yaratish
        foreach (['uz', 'ru', 'en'] as $locale) {
            if ($property->hasTranslation($locale)) {
                $this->seoService->generateForProperty($property, $locale);
            }
        }

        // Rasm yuklash va optimallashtirish (agar bo'lsa)
        if ($request->hasFile('images')) {
            $images = $this->imageService->processMultiple(
                $request->file('images'),
                'properties',
                true // Watermark qo'shish
            );
            $property->images = $images;
            $property->save();
        }

        if ($request->hasFile('featured_image')) {
            $path = $this->imageService->processAndStore(
                $request->file('featured_image'),
                'properties',
                true // Watermark qo'shish
            );
            $property->featured_image = $path;
            $property->save();
        }

        return redirect()->route('provider.properties.index')
            ->with('success', 'Property created successfully! It will be reviewed before publishing.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $slug)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $property = Property::where('slug', $slug)
            ->orWhere('seo_slug_uz', $slug)
            ->orWhere('seo_slug_ru', $slug)
            ->orWhere('seo_slug_en', $slug)
            ->with(['translations', 'user', 'seoMetas'])
            ->firstOrFail();

        // Views ni oshirish
        $property->incrementViews();

        // SEO metadata olish yoki yaratish
        $seoMeta = $this->seoService->getForProperty($property, $locale);
        if (!$seoMeta) {
            $seoMeta = $this->seoService->generateForProperty($property, $locale);
        }

        return view('pages.single-listing', compact('property', 'seoMeta', 'locale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $property = Property::with('translations')->findOrFail($id);
        
        // Check authorization
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        return view('provider.property.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $property = Property::with('translations')->findOrFail($id);
        
        // Check authorization
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'area' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|max:10',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'garages' => 'nullable|integer|min:0',
            'floors' => 'nullable|integer|min:0',
            'floor' => 'nullable|integer|min:1',
            'construction_material' => 'nullable|in:gisht,pishgan_gisht,beton,yogoch,paneli,monolit,boshqa',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'property_type' => 'required|in:house,apartment,villa,land,commercial,office',
            'listing_type' => 'required|in:sale,rent',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'videos' => 'nullable|array',
            'videos.*' => 'url',
            'features' => 'nullable|string|max:2000',
            'nearby_places' => 'nullable|string|max:2000',
            'locale' => 'required|in:uz,ru,en',
            // SEO avtomatik yaratiladi, qo'lda kiritilmaydi
        ]);

        // Asosiy tilda kiritilgan ma'lumotlar
        $sourceLocale = $validated['locale'];
        
        // Features va Nearby Places ni array'ga o'tkazish
        // Agar features_array bo'lsa, undan foydalanish, aks holda features string'dan
        if ($request->has('features_array') && is_array($request->features_array)) {
            $features = array_filter(array_map('trim', $request->features_array));
        } else {
            $features = !empty($request->features) ? array_filter(array_map('trim', explode(',', $request->features))) : [];
        }
        $nearbyPlaces = !empty($request->nearby_places) ? array_filter(array_map('trim', explode(',', $request->nearby_places))) : [];
        
        $translatableData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'] ?? null,
            'address' => $validated['address'] ?? null,
            'features' => !empty($features) ? json_encode(array_values($features), JSON_UNESCAPED_UNICODE) : null,
            'nearby_places' => !empty($nearbyPlaces) ? json_encode(array_values($nearbyPlaces), JSON_UNESCAPED_UNICODE) : null,
            // SEO avtomatik yaratiladi
        ];

        // Avtomatik tarjima qilish
        $allTranslations = $this->translationService->translateProperty($translatableData, $sourceLocale);

        // Property yangilash
        $property->update([
            'price' => $validated['price'],
            'currency' => $validated['currency'],
            'area' => $validated['area'] ?? null,
            'area_unit' => $validated['area_unit'] ?? 'm²',
            'bedrooms' => $validated['bedrooms'] ?? null,
            'bathrooms' => $validated['bathrooms'] ?? null,
            'garages' => $validated['garages'] ?? null,
            'floors' => $validated['floors'] ?? null,
            'floor' => $validated['floor'] ?? null,
            'construction_material' => $validated['construction_material'] ?? null,
            'year_built' => $validated['year_built'] ?? null,
            'property_type' => $validated['property_type'],
            'listing_type' => $validated['listing_type'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'city' => $validated['city'] ?? null,
            'region' => $validated['region'] ?? null,
            'country' => $validated['country'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
        ]);

        // Tarjimalarni yangilash
        foreach ($allTranslations as $locale => $data) {
            $property->translateOrNew($locale)->fill($data)->save();
        }

        // SEO metadata yangilash
        foreach (['uz', 'ru', 'en'] as $locale) {
            if ($property->hasTranslation($locale)) {
                $this->seoService->generateForProperty($property, $locale);
            }
        }

        // Rasm yuklash va optimallashtirish (agar bo'lsa)
        if ($request->hasFile('images')) {
            // Eski rasmlarni o'chirish (ixtiyoriy)
            if ($property->images) {
                foreach ($property->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            
            $images = $this->imageService->processMultiple(
                $request->file('images'),
                'properties',
                true // Watermark qo'shish
            );
            $property->images = $images;
            $property->save();
        }

        if ($request->hasFile('featured_image')) {
            // Eski featured image'ni o'chirish
            if ($property->featured_image && Storage::disk('public')->exists($property->featured_image)) {
                Storage::disk('public')->delete($property->featured_image);
            }
            
            $path = $this->imageService->processAndStore(
                $request->file('featured_image'),
                'properties',
                true // Watermark qo'shish
            );
            $property->featured_image = $path;
            $property->save();
        }

        return redirect()->route('provider.properties.index')
            ->with('success', 'Uy muvaffaqiyatli yangilandi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $property = Property::findOrFail($id);
        
        // Check authorization
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $property->delete();

        return redirect()->route('provider.properties.index')
            ->with('success', 'Property deleted successfully!');
    }

    /**
     * Unique slug yaratish
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Property::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Unique SEO slug yaratish
     */
    private function generateUniqueSeoSlug(string $locale, string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        $fieldName = 'seo_slug_' . $locale;
        
        while (Property::where($fieldName, $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}


        return view('pages.page-listings', compact(
            'properties',
            'locale',
            'cities',
            'propertyTypes',
            'listingTypes',
            'bedrooms',
            'minPrice',
            'maxPrice'
        ));
    }

    /**
     * Provider dashboard - My properties list
     */
    public function myProperties()
    {
        $properties = Property::with('translations')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('provider.property.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('provider.property.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'area' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|max:10',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'garages' => 'nullable|integer|min:0',
            'floors' => 'nullable|integer|min:0',
            'floor' => 'nullable|integer|min:1',
            'construction_material' => 'nullable|in:gisht,pishgan_gisht,beton,yogoch,paneli,monolit,boshqa',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'property_type' => 'required|in:house,apartment,villa,land,commercial,office',
            'listing_type' => 'required|in:sale,rent',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'videos' => 'nullable|array',
            'videos.*' => 'url',
            'features' => 'nullable|string|max:2000',
            'nearby_places' => 'nullable|string|max:2000',
            'locale' => 'required|in:uz,ru,en',
            // SEO avtomatik yaratiladi, qo'lda kiritilmaydi
        ]);

        // Asosiy tilda kiritilgan ma'lumotlar
        $sourceLocale = $validated['locale'];
        
        // Features va Nearby Places ni array'ga o'tkazish
        // Agar features_array bo'lsa, undan foydalanish, aks holda features string'dan
        if ($request->has('features_array') && is_array($request->features_array)) {
            $features = array_filter(array_map('trim', $request->features_array));
        } else {
            $features = !empty($request->features) ? array_filter(array_map('trim', explode(',', $request->features))) : [];
        }
        $nearbyPlaces = !empty($request->nearby_places) ? array_filter(array_map('trim', explode(',', $request->nearby_places))) : [];
        
        $translatableData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'] ?? null,
            'address' => $validated['address'] ?? null,
            'features' => !empty($features) ? json_encode(array_values($features), JSON_UNESCAPED_UNICODE) : null,
            'nearby_places' => !empty($nearbyPlaces) ? json_encode(array_values($nearbyPlaces), JSON_UNESCAPED_UNICODE) : null,
            // SEO avtomatik yaratiladi
        ];

        // Avtomatik tarjima qilish
        $allTranslations = $this->translationService->translateProperty($translatableData, $sourceLocale);

        // Slug yaratish (Property yaratishdan oldin - NOT NULL constraint uchun)
        $slug = $this->generateUniqueSlug($allTranslations[$sourceLocale]['title']);
        $seoSlugUz = $this->generateUniqueSeoSlug('uz', $allTranslations['uz']['title'] ?? $allTranslations[$sourceLocale]['title']);
        $seoSlugRu = $this->generateUniqueSeoSlug('ru', $allTranslations['ru']['title'] ?? $allTranslations[$sourceLocale]['title']);
        $seoSlugEn = $this->generateUniqueSeoSlug('en', $allTranslations['en']['title'] ?? $allTranslations[$sourceLocale]['title']);

        // Property yaratish (slug avtomatik yaratilgan)
        $property = Property::create([
            'user_id' => Auth::id(),
            'slug' => $slug, // Avtomatik yaratilgan slug
            'price' => $validated['price'],
            'currency' => $validated['currency'],
            'area' => $validated['area'] ?? null,
            'area_unit' => $validated['area_unit'] ?? 'm²',
            'bedrooms' => $validated['bedrooms'] ?? null,
            'bathrooms' => $validated['bathrooms'] ?? null,
            'garages' => $validated['garages'] ?? null,
            'floors' => $validated['floors'] ?? null,
            'floor' => $validated['floor'] ?? null,
            'construction_material' => $validated['construction_material'] ?? null,
            'year_built' => $validated['year_built'] ?? null,
            'property_type' => $validated['property_type'],
            'listing_type' => $validated['listing_type'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'city' => $validated['city'] ?? null,
            'region' => $validated['region'] ?? null,
            'country' => $validated['country'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'status' => 'draft', // Default draft, keyin publish qilish mumkin
            'seo_slug_uz' => $seoSlugUz,
            'seo_slug_ru' => $seoSlugRu,
            'seo_slug_en' => $seoSlugEn,
        ]);

        // Tarjimalarni saqlash
        foreach ($allTranslations as $locale => $data) {
            $property->translateOrNew($locale)->fill($data)->save();
        }

        // SEO metadata yaratish
        foreach (['uz', 'ru', 'en'] as $locale) {
            if ($property->hasTranslation($locale)) {
                $this->seoService->generateForProperty($property, $locale);
            }
        }

        // Rasm yuklash va optimallashtirish (agar bo'lsa)
        if ($request->hasFile('images')) {
            $images = $this->imageService->processMultiple(
                $request->file('images'),
                'properties',
                true // Watermark qo'shish
            );
            $property->images = $images;
            $property->save();
        }

        if ($request->hasFile('featured_image')) {
            $path = $this->imageService->processAndStore(
                $request->file('featured_image'),
                'properties',
                true // Watermark qo'shish
            );
            $property->featured_image = $path;
            $property->save();
        }

        return redirect()->route('provider.properties.index')
            ->with('success', 'Property created successfully! It will be reviewed before publishing.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $slug)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $property = Property::where('slug', $slug)
            ->orWhere('seo_slug_uz', $slug)
            ->orWhere('seo_slug_ru', $slug)
            ->orWhere('seo_slug_en', $slug)
            ->with(['translations', 'user', 'seoMetas'])
            ->firstOrFail();

        // Views ni oshirish
        $property->incrementViews();

        // SEO metadata olish yoki yaratish
        $seoMeta = $this->seoService->getForProperty($property, $locale);
        if (!$seoMeta) {
            $seoMeta = $this->seoService->generateForProperty($property, $locale);
        }

        return view('pages.single-listing', compact('property', 'seoMeta', 'locale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $property = Property::with('translations')->findOrFail($id);
        
        // Check authorization
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        return view('provider.property.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $property = Property::with('translations')->findOrFail($id);
        
        // Check authorization
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'area' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|max:10',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'garages' => 'nullable|integer|min:0',
            'floors' => 'nullable|integer|min:0',
            'floor' => 'nullable|integer|min:1',
            'construction_material' => 'nullable|in:gisht,pishgan_gisht,beton,yogoch,paneli,monolit,boshqa',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'property_type' => 'required|in:house,apartment,villa,land,commercial,office',
            'listing_type' => 'required|in:sale,rent',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'videos' => 'nullable|array',
            'videos.*' => 'url',
            'features' => 'nullable|string|max:2000',
            'nearby_places' => 'nullable|string|max:2000',
            'locale' => 'required|in:uz,ru,en',
            // SEO avtomatik yaratiladi, qo'lda kiritilmaydi
        ]);

        // Asosiy tilda kiritilgan ma'lumotlar
        $sourceLocale = $validated['locale'];
        
        // Features va Nearby Places ni array'ga o'tkazish
        // Agar features_array bo'lsa, undan foydalanish, aks holda features string'dan
        if ($request->has('features_array') && is_array($request->features_array)) {
            $features = array_filter(array_map('trim', $request->features_array));
        } else {
            $features = !empty($request->features) ? array_filter(array_map('trim', explode(',', $request->features))) : [];
        }
        $nearbyPlaces = !empty($request->nearby_places) ? array_filter(array_map('trim', explode(',', $request->nearby_places))) : [];
        
        $translatableData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'] ?? null,
            'address' => $validated['address'] ?? null,
            'features' => !empty($features) ? json_encode(array_values($features), JSON_UNESCAPED_UNICODE) : null,
            'nearby_places' => !empty($nearbyPlaces) ? json_encode(array_values($nearbyPlaces), JSON_UNESCAPED_UNICODE) : null,
            // SEO avtomatik yaratiladi
        ];

        // Avtomatik tarjima qilish
        $allTranslations = $this->translationService->translateProperty($translatableData, $sourceLocale);

        // Property yangilash
        $property->update([
            'price' => $validated['price'],
            'currency' => $validated['currency'],
            'area' => $validated['area'] ?? null,
            'area_unit' => $validated['area_unit'] ?? 'm²',
            'bedrooms' => $validated['bedrooms'] ?? null,
            'bathrooms' => $validated['bathrooms'] ?? null,
            'garages' => $validated['garages'] ?? null,
            'floors' => $validated['floors'] ?? null,
            'floor' => $validated['floor'] ?? null,
            'construction_material' => $validated['construction_material'] ?? null,
            'year_built' => $validated['year_built'] ?? null,
            'property_type' => $validated['property_type'],
            'listing_type' => $validated['listing_type'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'city' => $validated['city'] ?? null,
            'region' => $validated['region'] ?? null,
            'country' => $validated['country'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
        ]);

        // Tarjimalarni yangilash
        foreach ($allTranslations as $locale => $data) {
            $property->translateOrNew($locale)->fill($data)->save();
        }

        // SEO metadata yangilash
        foreach (['uz', 'ru', 'en'] as $locale) {
            if ($property->hasTranslation($locale)) {
                $this->seoService->generateForProperty($property, $locale);
            }
        }

        // Rasm yuklash va optimallashtirish (agar bo'lsa)
        if ($request->hasFile('images')) {
            // Eski rasmlarni o'chirish (ixtiyoriy)
            if ($property->images) {
                foreach ($property->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            
            $images = $this->imageService->processMultiple(
                $request->file('images'),
                'properties',
                true // Watermark qo'shish
            );
            $property->images = $images;
            $property->save();
        }

        if ($request->hasFile('featured_image')) {
            // Eski featured image'ni o'chirish
            if ($property->featured_image && Storage::disk('public')->exists($property->featured_image)) {
                Storage::disk('public')->delete($property->featured_image);
            }
            
            $path = $this->imageService->processAndStore(
                $request->file('featured_image'),
                'properties',
                true // Watermark qo'shish
            );
            $property->featured_image = $path;
            $property->save();
        }

        return redirect()->route('provider.properties.index')
            ->with('success', 'Uy muvaffaqiyatli yangilandi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $property = Property::findOrFail($id);
        
        // Check authorization
        if ($property->user_id !== Auth::id()) {
            abort(403);
        }

        $property->delete();

        return redirect()->route('provider.properties.index')
            ->with('success', 'Property deleted successfully!');
    }

    /**
     * Unique slug yaratish
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Property::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Unique SEO slug yaratish
     */
    private function generateUniqueSeoSlug(string $locale, string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        $fieldName = 'seo_slug_' . $locale;
        
        while (Property::where($fieldName, $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
