<?php

namespace App\Http\Controllers;

use App\Models\Development;
use App\Models\DevelopmentProperty;
use App\Models\Property;
use App\Services\AiPropertySearchService;
use Illuminate\Http\Request;

class PageController
{
    public function home()
    {
        $locale = app()->getLocale();
        
        // Featured properties for home page
        $featuredProperties = Property::with('translations')
            ->published()
            ->featured()
            ->latest()
            ->limit(6)
            ->get();

        // Latest properties
        $latestProperties = Property::with('translations')
            ->published()
            ->latest()
            ->limit(6)
            ->get();

        // Featured developments
        $featuredDevelopments = Development::with(['builder', 'properties'])
            ->where('status', 'published')
            ->where('featured', true)
            ->latest()
            ->limit(6)
            ->get();

        // Latest developments
        $latestDevelopments = Development::with(['builder', 'properties'])
            ->where('status', 'published')
            ->latest()
            ->limit(6)
            ->get();

        // Cities for search (from both properties and developments)
        $propertyCities = Property::published()
            ->distinct()
            ->pluck('city')
            ->filter();
        
        $developmentCities = Development::where('status', 'published')
            ->distinct()
            ->pluck('city')
            ->filter();
        
        $cities = $propertyCities->merge($developmentCities)
            ->unique()
            ->sort()
            ->values();

        return view('pages.home', compact(
            'featuredProperties',
            'latestProperties',
            'featuredDevelopments',
            'latestDevelopments',
            'cities',
            'locale'
        ));
    }

    public function pageListings(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $query = Property::with('translations')
            ->published()
            ->orderBy('created_at', 'desc');

        // AI Search yoki oddiy search
        if ($request->has('ai_search') && $request->ai_search === 'true' && $request->has('search')) {
            // AI Search ishlatish
            $aiService = new AiPropertySearchService();
            $aiResults = $aiService->search($request->search, $locale);
            $properties = $aiResults['properties'];
            
            // Manual pagination
            $perPage = 12;
            $currentPage = $request->get('page', 1);
            $items = $properties->forPage($currentPage, $perPage);
            $properties = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $properties->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
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
            ))->with('ai_search_used', true)->with('ai_filters', $aiResults['filters_applied']);
        }

        // Search query - property type'ni extract qilish
        $searchQuery = $request->has('search') && !empty($request->search) ? trim($request->search) : null;
        $extractedPropertyType = null;
        
        if ($searchQuery) {
            // Search query'dan property type'ni extract qilish
            $extractedPropertyType = $this->extractPropertyTypeFromSearch($searchQuery);
        }
        
        // Property type - URL'da yoki search'dan extract qilingan
        $propertyType = null;
        if ($request->has('property_type') && !empty($request->property_type)) {
            $propertyType = trim($request->property_type);
        } elseif ($extractedPropertyType) {
            $propertyType = $extractedPropertyType;
        }
        
        // Property type filter
        if ($propertyType) {
            $propertyTypeMapped = $this->mapPropertyType($propertyType);
            
            if ($propertyTypeMapped) {
                $query->where('property_type', $propertyTypeMapped);
            } elseif (in_array($propertyType, ['apartment', 'house', 'villa', 'land', 'commercial', 'office'])) {
                // Agar to'g'ridan-to'g'ri property_type bo'lsa
                $query->where('property_type', $propertyType);
            }
        }

        // Search filter (property type'ni olib tashlaganidan keyin)
        if ($searchQuery) {
            // Search query'dan property type so'zlarini olib tashlash
            $cleanSearchQuery = $this->cleanSearchQueryFromPropertyType($searchQuery);
            
            if (!empty($cleanSearchQuery)) {
                $query->where(function($q) use ($cleanSearchQuery) {
                    $q->whereHas('translations', function($tq) use ($cleanSearchQuery) {
                        $tq->where('title', 'like', "%{$cleanSearchQuery}%")
                           ->orWhere('description', 'like', "%{$cleanSearchQuery}%")
                           ->orWhere('address', 'like', "%{$cleanSearchQuery}%");
                    })
                    ->orWhere('city', 'like', "%{$cleanSearchQuery}%")
                    ->orWhere('region', 'like', "%{$cleanSearchQuery}%");
                });
            }
        }

        if ($request->has('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }

        if ($request->has('city') && !empty($request->city)) {
            $city = trim($request->city);
            // City nomini tozalash (masalan, "Toshkentda" -> "Toshkent")
            $city = preg_replace('/да$|da$|даги$|dagi$|dan$|dan$/i', '', $city);
            $city = trim($city);
            $query->where(function($q) use ($city) {
                $q->where('city', 'like', '%' . $city . '%')
                  ->orWhere('city', $city);
            });
        }

        if ($request->has('bedrooms') && $request->bedrooms !== null && $request->bedrooms !== '') {
            $bedrooms = (int)$request->bedrooms;
            if ($bedrooms > 0) {
                $query->where('bedrooms', '>=', $bedrooms);
            }
        }

        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', (float)$request->min_price);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', (float)$request->max_price);
        }

        // Area range
        if ($request->has('min_area') && !empty($request->min_area)) {
            $query->where('area', '>=', (float)$request->min_area);
        }
        if ($request->has('max_area') && !empty($request->max_area)) {
            $query->where('area', '<=', (float)$request->max_area);
        }

        if ($request->has('verified')) {
            $query->whereHas('user', function($q) {
                $q->where('verified', true);
            });
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

    public function singleListing(Request $request, $slug)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $property = Property::with(['translations', 'user', 'approvedComments.user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment views
        $property->increment('views');

        // Related properties
        $relatedProperties = Property::with('translations')
            ->published()
            ->where('id', '!=', $property->id)
            ->where(function($q) use ($property) {
                $q->where('property_type', $property->property_type)
                  ->orWhere('city', $property->city)
                  ->orWhere('listing_type', $property->listing_type);
            })
            ->limit(6)
            ->get();

        return view('pages.single-listing', compact('property', 'relatedProperties', 'locale'));
    }

    public function map(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $query = Property::with('translations')
            ->published()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        // Filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhere('city', 'like', "%{$search}%");
        }

        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        if ($request->has('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->has('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }

        if ($request->has('verified')) {
            $query->whereHas('user', function($q) {
                $q->where('verified', true);
            });
        }

        if ($request->has('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $properties = $query->get();

        // Format properties for map
        $propertiesForMap = $properties->map(function($property) use ($locale) {
            return [
                'id' => $property->id,
                'title' => $property->translate($locale)->title ?? $property->title ?? 'N/A',
                'address' => $property->translate($locale)->address ?? $property->address ?? ($property->city . ($property->region ? ', ' . $property->region : '')),
                'price' => number_format($property->price, 0),
                'currency' => $property->currency,
                'listing_type' => $property->listing_type,
                'property_type' => $property->property_type,
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'area' => $property->area,
                'area_unit' => $property->area_unit,
                'latitude' => $property->latitude,
                'longitude' => $property->longitude,
                'featured_image' => $property->featured_image ? asset('storage/' . $property->featured_image) : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400&h=300&fit=crop',
                'url' => route('property.show', $property->slug),
            ];
        });

        // Filter options
        $cities = Property::published()->distinct()->pluck('city')->filter()->sort()->values();
        $propertyTypes = ['apartment', 'house', 'villa', 'land', 'commercial', 'office'];
        $listingTypes = ['sale', 'rent'];
        $bedrooms = Property::published()->distinct()->pluck('bedrooms')->filter()->sort()->values();

        $minPrice = Property::published()->min('price') ?? 0;
        $maxPrice = Property::published()->max('price') ?? 10000000;

        $totalResults = $properties->count();

        return view('pages.map', compact(
            'locale',
            'propertiesForMap',
            'cities',
            'propertyTypes',
            'listingTypes',
            'bedrooms',
            'minPrice',
            'maxPrice',
            'totalResults'
        ));
    }

    /**
     * Display developments listing page
     */
    public function pageDevelopments(Request $request, ?string $locale = null)
    {
        $locale = $locale ?? $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $query = Development::where('status', 'published')
            ->with(['builder', 'properties'])
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        if ($request->has('featured')) {
            $query->where('featured', true);
        }

        if ($request->has('min_price')) {
            $query->where('price_per_sqm', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price_per_sqm', '<=', $request->max_price);
        }

        if ($request->has('bedrooms')) {
            $query->whereHas('properties', function($q) use ($request) {
                $q->where('bedrooms', $request->bedrooms);
            });
        }

        $developments = $query->paginate(12);

        // Cities for filter
        $cities = Development::where('status', 'published')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->sort()
            ->values();

        // Statistics for "Hot Offers"
        $stats = [
            'lowest_price_sqm' => Development::where('status', 'published')
                ->whereNotNull('price_per_sqm')
                ->min('price_per_sqm') ?? 0,
            'cheapest_apartment' => DevelopmentProperty::whereHas('development', function($q) {
                    $q->where('status', 'published');
                })
                ->whereNotNull('price_from')
                ->min('price_from') ?? 0,
            'lowest_down_payment' => 0, // Need to add this field
            'lowest_monthly_payment' => 0, // Need to add this field
            'longest_payment_period' => 0, // Need to add this field
            'highest_discount' => 0, // Need to add this field
        ];

        // Min/Max price for price filter
        $minPrice = Development::where('status', 'published')
            ->whereNotNull('price_per_sqm')
            ->min('price_per_sqm') ?? 0;
        $maxPrice = Development::where('status', 'published')
            ->whereNotNull('price_per_sqm')
            ->max('price_per_sqm') ?? 100000000;

        // Bedrooms options
        $bedrooms = DevelopmentProperty::whereHas('development', function($q) {
                $q->where('status', 'published');
            })
            ->whereNotNull('bedrooms')
            ->distinct()
            ->pluck('bedrooms')
            ->filter()
            ->sort()
            ->values();

        return view('pages.page-developments', compact(
            'developments',
            'locale',
            'cities',
            'stats',
            'minPrice',
            'maxPrice',
            'bedrooms'
        ));
    }

    /**
     * Display single development page
     */
    public function singleDevelopment(Request $request, $slug = null, $locale = null)
    {
        $locale = $locale ?? $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $development = Development::where('slug', $slug)
            ->where('status', 'published')
            ->with(['builder', 'properties', 'floorPlans', 'documents'])
            ->firstOrFail();

        // Increment views
        $development->increment('views');

        return view('pages.single-development', compact('development', 'locale'));
    }

    /**
     * Property type'ni map qilish (odam yozgan narsani property_type'ga)
     */
    protected function mapPropertyType(string $type): ?string
    {
        $type = strtolower(trim($type));
        
        // Property type mapping
        $propertyTypeMap = [
            // UZ
            'uy' => 'house',
            'kvartira' => 'apartment',
            'uy-joy' => 'house',
            'villa' => 'villa',
            'yer' => 'land',
            'yer uchastkasi' => 'land',
            'kommercheskaya' => 'commercial',
            'tijorat' => 'commercial',
            'ofis' => 'office',
            
            // RU
            'дом' => 'house',
            'квартира' => 'apartment',
            'квартиры' => 'apartment',
            'вилла' => 'villa',
            'участок' => 'land',
            'земля' => 'land',
            'коммерческая' => 'commercial',
            'офис' => 'office',
            
            // EN
            'apartment' => 'apartment',
            'house' => 'house',
            'villa' => 'villa',
            'land' => 'land',
            'commercial' => 'commercial',
            'office' => 'office',
            'condo' => 'apartment',
            'studio' => 'apartment',
            
            // Common patterns
            'apt' => 'apartment',
            'flat' => 'apartment',
        ];
        
        // Direct mapping
        if (isset($propertyTypeMap[$type])) {
            return $propertyTypeMap[$type];
        }
        
        // Pattern matching
        if (str_contains($type, 'uy') || str_contains($type, 'дом') || str_contains($type, 'house')) {
            return 'house';
        }
        if (str_contains($type, 'kvartira') || str_contains($type, 'квартира') || str_contains($type, 'apartment')) {
            return 'apartment';
        }
        if (str_contains($type, 'villa') || str_contains($type, 'вилла')) {
            return 'villa';
        }
        if (str_contains($type, 'yer') || str_contains($type, 'участок') || str_contains($type, 'land')) {
            return 'land';
        }
        if (str_contains($type, 'commercial') || str_contains($type, 'коммерческая') || str_contains($type, 'tijorat')) {
            return 'commercial';
        }
        if (str_contains($type, 'office') || str_contains($type, 'офис') || str_contains($type, 'ofis')) {
            return 'office';
        }
        
        return null;
    }

    /**
     * Search query'dan property type'ni extract qilish
     */
    protected function extractPropertyTypeFromSearch(string $searchQuery): ?string
    {
        $searchLower = strtolower(trim($searchQuery));
        
        // Property type keywords
        $propertyTypeKeywords = [
            'uy' => 'house',
            'uy-joy' => 'house',
            'kvartira' => 'apartment',
            'villa' => 'villa',
            'yer' => 'land',
            'yer uchastkasi' => 'land',
            'tijorat' => 'commercial',
            'kommercheskaya' => 'commercial',
            'ofis' => 'office',
            'дом' => 'house',
            'квартира' => 'apartment',
            'вилла' => 'villa',
            'участок' => 'land',
            'коммерческая' => 'commercial',
            'офис' => 'office',
            'house' => 'house',
            'apartment' => 'apartment',
            'villa' => 'villa',
            'land' => 'land',
            'commercial' => 'commercial',
            'office' => 'office',
        ];
        
        // Direct match
        foreach ($propertyTypeKeywords as $keyword => $type) {
            if ($searchLower === $keyword || str_contains($searchLower, ' ' . $keyword . ' ') || 
                preg_match('/\b' . preg_quote($keyword, '/') . '\b/i', $searchLower)) {
                return $type;
            }
        }
        
        // Pattern matching
        if (preg_match('/\buy\b|\bдом\b|\bhouse\b/i', $searchLower)) {
            return 'house';
        }
        if (preg_match('/\bkvartira\b|\bквартира\b|\bapartment\b/i', $searchLower)) {
            return 'apartment';
        }
        if (preg_match('/\bvilla\b|\bвилла\b/i', $searchLower)) {
            return 'villa';
        }
        if (preg_match('/\byer\b|\bучасток\b|\bland\b/i', $searchLower)) {
            return 'land';
        }
        if (preg_match('/\bcommercial\b|\bкоммерческая\b|\btijorat\b/i', $searchLower)) {
            return 'commercial';
        }
        if (preg_match('/\boffice\b|\bофис\b|\bofis\b/i', $searchLower)) {
            return 'office';
        }
        
        return null;
    }

    /**
     * Search query'dan property type so'zlarini olib tashlash
     */
    protected function cleanSearchQueryFromPropertyType(string $searchQuery): string
    {
        $cleanQuery = $searchQuery;
        
        // Property type so'zlarini olib tashlash
        $propertyTypeWords = [
            'uy', 'uy-joy', 'kvartira', 'villa', 'yer', 'yer uchastkasi', 
            'tijorat', 'kommercheskaya', 'ofis', 'office',
            'дом', 'квартира', 'вилла', 'участок', 'коммерческая', 'офис',
            'house', 'apartment', 'villa', 'land', 'commercial', 'office',
            'condo', 'studio', 'flat', 'apt'
        ];
        
        foreach ($propertyTypeWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            $cleanQuery = preg_replace($pattern, '', $cleanQuery);
        }
        
        // Qo'shimcha bo'shliqlarni tozalash
        $cleanQuery = preg_replace('/\s+/', ' ', $cleanQuery);
        $cleanQuery = trim($cleanQuery);
        
        return $cleanQuery;
    }

    /**
     * AI Search API endpoint
     */
    public function aiSearch(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:500',
            'locale' => 'nullable|string|in:uz,ru,en',
        ]);

        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $aiService = new AiPropertySearchService();
        $results = $aiService->search($request->query, $locale);

        return response()->json([
            'success' => true,
            'properties' => $results['properties']->map(function($property) use ($locale) {
                return [
                    'id' => $property->id,
                    'title' => $property->translate($locale)->title ?? $property->title ?? 'N/A',
                    'slug' => $property->slug,
                    'price' => number_format($property->price, 0),
                    'currency' => $property->currency ?? 'UZS',
                    'city' => $property->city,
                    'property_type' => $property->property_type,
                    'listing_type' => $property->listing_type,
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                    'area' => $property->area,
                    'featured_image' => $property->featured_image ? asset('storage/' . $property->featured_image) : null,
                    'url' => route('property.show', $property->slug),
                ];
            }),
            'count' => $results['count'],
            'filters_applied' => $results['filters_applied'],
        ]);
    }
}
