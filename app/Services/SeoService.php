<?php

namespace App\Services;

use App\Models\Property;
use App\Models\SeoMeta;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Generate SEO metadata for a property.
     */
    public function generateForProperty(Property $property, string $locale = 'uz'): SeoMeta
    {
        $translation = $property->translate($locale);
        
        // Generate meta title (yaxshiroq SEO uchun)
        $metaTitle = $this->generateMetaTitle($property, $translation, $locale);
        
        // Generate meta description (yaxshiroq SEO uchun)
        $metaDescription = $this->generateMetaDescription($property, $translation, $locale);
        
        // Generate meta keywords (avtomatik)
        $metaKeywords = $this->generateMetaKeywords($property, $translation, $locale);
        
        // Generate canonical URL
        $canonicalUrl = $this->generateCanonicalUrl($property, $locale);
        
        // Generate Open Graph data
        $ogImage = $property->featured_image 
            ?? ($property->images ? $property->images[0] : null);
        
        // Generate hreflang
        $hreflang = $this->generateHreflang($property);
        
        // Generate structured data (JSON-LD)
        $structuredData = $this->generateStructuredData($property, $locale);
        
        return SeoMeta::updateOrCreate(
            [
                'seoable_type' => Property::class,
                'seoable_id' => $property->id,
                'locale' => $locale,
            ],
            [
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
                'meta_keywords' => $metaKeywords,
                'meta_robots' => $property->status === 'published' ? 'index,follow' : 'noindex,nofollow',
                'og_title' => $translation->title,
                'og_description' => $metaDescription,
                'og_image' => $ogImage ? url($ogImage) : null,
                'og_type' => 'product',
                'og_url' => $canonicalUrl,
                'twitter_card' => 'summary_large_image',
                'twitter_title' => $translation->title,
                'twitter_description' => $metaDescription,
                'twitter_image' => $ogImage ? url($ogImage) : null,
                'canonical_url' => $canonicalUrl,
                'structured_data' => $structuredData,
                'hreflang' => $hreflang,
            ]
        );
    }

    /**
     * Generate canonical URL for property.
     */
    protected function generateCanonicalUrl(Property $property, string $locale): string
    {
        $slug = match($locale) {
            'uz' => $property->seo_slug_uz ?? $property->slug,
            'ru' => $property->seo_slug_ru ?? $property->slug,
            'en' => $property->seo_slug_en ?? $property->slug,
            default => $property->slug,
        };
        
        return url("/{$locale}/properties/{$slug}");
    }

    /**
     * Generate hreflang tags for all locales.
     */
    protected function generateHreflang(Property $property): array
    {
        $hreflang = [];
        $locales = ['uz', 'ru', 'en'];
        
        foreach ($locales as $locale) {
            if ($property->hasTranslation($locale)) {
                $slug = match($locale) {
                    'uz' => $property->seo_slug_uz ?? $property->slug,
                    'ru' => $property->seo_slug_ru ?? $property->slug,
                    'en' => $property->seo_slug_en ?? $property->slug,
                    default => $property->slug,
                };
                
                $hreflang[$locale] = url("/{$locale}/properties/{$slug}");
            }
        }
        
        return $hreflang;
    }

    /**
     * Generate structured data (JSON-LD) for property.
     */
    protected function generateStructuredData(Property $property, string $locale): array
    {
        $translation = $property->translate($locale);
        
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $translation->title,
            'description' => $translation->description,
            'image' => $property->images ? array_map(fn($img) => url($img), $property->images) : [],
            'offers' => [
                '@type' => 'Offer',
                'price' => $property->price,
                'priceCurrency' => $property->currency,
                'availability' => $property->status === 'published' 
                    ? 'https://schema.org/InStock' 
                    : 'https://schema.org/OutOfStock',
            ],
        ];
        
        // Add RealEstateAgent if available
        if ($property->user) {
            $structuredData['seller'] = [
                '@type' => 'RealEstateAgent',
                'name' => $property->user->name,
            ];
        }
        
        // Add Place/Location
        if ($property->latitude && $property->longitude) {
            $structuredData['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $property->latitude,
                'longitude' => $property->longitude,
            ];
        }
        
        // Add Address
        if ($translation->address) {
            $structuredData['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $translation->address,
                'addressLocality' => $property->city,
                'addressRegion' => $property->region,
                'addressCountry' => $property->country,
                'postalCode' => $property->postal_code,
            ];
        }
        
        // Add PropertyValue
        $structuredData['additionalProperty'] = [];
        
        if ($property->area) {
            $structuredData['additionalProperty'][] = [
                '@type' => 'PropertyValue',
                'name' => 'Area',
                'value' => $property->area . ' ' . $property->area_unit,
            ];
        }
        
        if ($property->bedrooms) {
            $structuredData['additionalProperty'][] = [
                '@type' => 'PropertyValue',
                'name' => 'Bedrooms',
                'value' => $property->bedrooms,
            ];
        }
        
        if ($property->bathrooms) {
            $structuredData['additionalProperty'][] = [
                '@type' => 'PropertyValue',
                'name' => 'Bathrooms',
                'value' => $property->bathrooms,
            ];
        }
        
        return $structuredData;
    }

    /**
     * Get SEO meta for a property in a specific locale.
     */
    public function getForProperty(Property $property, string $locale = 'uz'): ?SeoMeta
    {
        return SeoMeta::where('seoable_type', Property::class)
            ->where('seoable_id', $property->id)
            ->where('locale', $locale)
            ->first();
    }

    /**
     * Generate optimized meta title
     */
    protected function generateMetaTitle(Property $property, $translation, string $locale): string
    {
        $title = $translation->title;
        $city = $property->city ?? '';
        $propertyType = $this->getPropertyTypeName($property->property_type, $locale);
        $listingType = $property->listing_type === 'sale' 
            ? ($locale === 'uz' ? 'sotish' : ($locale === 'ru' ? 'продажа' : 'for sale'))
            : ($locale === 'uz' ? 'ijaraga' : ($locale === 'ru' ? 'аренда' : 'for rent'));
        
        // SEO-friendly title yaratish
        $metaTitle = $title;
        
        if ($city) {
            $metaTitle .= ' - ' . $city;
        }
        
        if ($propertyType) {
            $metaTitle .= ' | ' . $propertyType;
        }
        
        $metaTitle .= ' ' . $listingType;
        $metaTitle .= ' | ' . config('app.name');
        
        // Maksimal uzunlik
        return Str::limit($metaTitle, 60);
    }

    /**
     * Generate optimized meta description
     */
    protected function generateMetaDescription(Property $property, $translation, string $locale): string
    {
        $description = $translation->short_description ?? Str::limit($translation->description, 120);
        
        // Qo'shimcha ma'lumotlar qo'shish
        $additional = [];
        
        if ($property->price) {
            $additional[] = $property->price . ' ' . $property->currency;
        }
        
        if ($property->area) {
            $additional[] = $property->area . ' ' . $property->area_unit;
        }
        
        if ($property->city) {
            $additional[] = $property->city;
        }
        
        if (!empty($additional)) {
            $description .= ' | ' . implode(', ', $additional);
        }
        
        // Maksimal uzunlik (160 belgi - Google uchun optimal)
        return Str::limit($description, 160);
    }

    /**
     * Generate meta keywords automatically
     */
    protected function generateMetaKeywords(Property $property, $translation, string $locale): string
    {
        $keywords = [];
        
        // Asosiy kalit so'zlar
        $keywords[] = $translation->title;
        
        // Uy turi
        $propertyType = $this->getPropertyTypeName($property->property_type, $locale);
        if ($propertyType) {
            $keywords[] = $propertyType;
        }
        
        // Shahar
        if ($property->city) {
            $keywords[] = $property->city;
        }
        
        // Viloyat
        if ($property->region) {
            $keywords[] = $property->region;
        }
        
        // Davlat
        if ($property->country) {
            $keywords[] = $property->country;
        }
        
        // Listing type
        if ($property->listing_type === 'sale') {
            $keywords[] = $locale === 'uz' ? 'sotish' : ($locale === 'ru' ? 'продажа' : 'sale');
        } else {
            $keywords[] = $locale === 'uz' ? 'ijaraga' : ($locale === 'ru' ? 'аренда' : 'rent');
        }
        
        // Umumiy kalit so'zlar
        $generalKeywords = $locale === 'uz' 
            ? ['uy', 'uy-joy', 'ko\'chmas mulk', 'kvartira', 'uy sotish', 'uy ijaraga']
            : ($locale === 'ru' 
                ? ['дом', 'недвижимость', 'квартира', 'продажа', 'аренда']
                : ['house', 'property', 'real estate', 'apartment', 'sale', 'rent']);
        
        $keywords = array_merge($keywords, $generalKeywords);
        
        // Unique qilish va string'ga o'tkazish
        $keywords = array_unique($keywords);
        
        return implode(', ', array_slice($keywords, 0, 10)); // Maksimal 10 ta kalit so'z
    }

    /**
     * Get property type name in specific locale
     */
    protected function getPropertyTypeName(string $type, string $locale): string
    {
        $types = [
            'uz' => [
                'house' => 'uy',
                'apartment' => 'kvartira',
                'villa' => 'villa',
                'land' => 'yer',
                'commercial' => 'savdo',
                'office' => 'ofis',
            ],
            'ru' => [
                'house' => 'дом',
                'apartment' => 'квартира',
                'villa' => 'вилла',
                'land' => 'земля',
                'commercial' => 'коммерческая',
                'office' => 'офис',
            ],
            'en' => [
                'house' => 'house',
                'apartment' => 'apartment',
                'villa' => 'villa',
                'land' => 'land',
                'commercial' => 'commercial',
                'office' => 'office',
            ],
        ];
        
        return $types[$locale][$type] ?? $type;
    }
}

