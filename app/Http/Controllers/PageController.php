<?php

namespace App\Http\Controllers;

use App\Models\Development;
use App\Models\DevelopmentProperty;
use App\Models\Property;
use Illuminate\Http\Request;

class PageController
{
    public function home()
    {
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

        return view('pages.home', compact('featuredProperties', 'latestProperties'));
    }

    public function pageListings(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        app()->setLocale($locale);

        $query = Property::with('translations')
            ->published()
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })->orWhere('city', 'like', "%{$search}%");
        }

        if ($request->has('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->has('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }

        if ($request->has('city')) {
            $query->where('city', $request->city);
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

    public function singleListing()
    {
        return view('pages.single-listing');
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
}
