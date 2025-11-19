<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Property extends Model
{
    use Translatable, HasSlug, SoftDeletes;

    protected ?string $customSlugSource = null;

    public $translatedAttributes = [
        'title',
        'description',
        'short_description',
        'address',
        'features',
        'nearby_places',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $fillable = [
        'user_id',
        'owner_phone', // Egasining telefon raqami
        // 'slug' - slug avtomatik yaratiladi, qo'lda kiritilmaydi
        'price',
        'currency',
        'area',
        'area_unit',
        'bedrooms',
        'bathrooms',
        'garages',
        'floors',
        'floor',
        'construction_material',
        'year_built',
        'property_type',
        'listing_type',
        'latitude',
        'longitude',
        'city',
        'region',
        'country',
        'postal_code',
        'images',
        'featured_image',
        'videos',
        'status',
        'approval_status',
        'approval_submitted_at',
        'approval_reviewed_at',
        'approval_reviewer_id',
        'approval_notes',
        'approval_history',
        'featured',
        'verified',
        'views',
        'favorites_count',
        'seo_slug_uz',
        'seo_slug_ru',
        'seo_slug_en',
        'source',
        'source_id',
        'source_url',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'price' => 'decimal:2',
        'area' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'featured' => 'boolean',
        'verified' => 'boolean',
        'year_built' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'garages' => 'integer',
        'floors' => 'integer',
        'floor' => 'integer',
        'views' => 'integer',
        'favorites_count' => 'integer',
        'approval_submitted_at' => 'datetime',
        'approval_reviewed_at' => 'datetime',
        'approval_history' => 'array',
    ];

    /**
     * Get the options for generating the slug.
     * Slug'lar avtomatik yaratiladi va doimiy bo'ladi (o'zgarmaydi).
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($model) {
                // Title'dan slug yaratish (translatable field)
                return $model->getSlugSourceString();
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate() // Slug'lar doimiy - update qilganda ham o'zgarmaydi
            ->usingLanguage('uz')
            ->allowDuplicateSlugs(false); // Unique bo'lishini ta'minlash
    }

    /**
     * Slug yaratish uchun source string olish
     * Translatable title'dan slug yaratiladi
     */
    public function getSlugSourceString(): string
    {
        if (!empty($this->customSlugSource)) {
            return $this->customSlugSource;
        }

        // Avval asosiy tildan (uz) olish
        foreach (['uz', 'ru', 'en'] as $locale) {
            if ($this->hasTranslation($locale)) {
                $title = $this->translate($locale)->title;
                if (!empty($title)) {
                    return $title;
                }
            }
        }
        
        // Agar tarjima bo'lmasa, default qiymat
        return 'property-' . ($this->id ?? 'new');
    }

    public function setCustomSlugSource(?string $value): void
    {
        $this->customSlugSource = $value;
    }

    public function getCustomSlugSource(): ?string
    {
        return $this->customSlugSource;
    }

    /**
     * Boot method - slug'lar doimiy bo'lishini ta'minlash
     */
    protected static function boot()
    {
        parent::boot();

        // Slug'lar doimiy - update qilganda ham o'zgarmaydi
        static::updating(function ($property) {
            // Slug'ni o'zgartirishga urinish bo'lsa, eski qiymatni saqlash
            if ($property->isDirty('slug') && !empty($property->getOriginal('slug'))) {
                $property->slug = $property->getOriginal('slug');
            }
            // SEO slug'larni ham o'zgartirishga urinish bo'lsa, eski qiymatlarni saqlash
            foreach (['seo_slug_uz', 'seo_slug_ru', 'seo_slug_en'] as $field) {
                if ($property->isDirty($field) && !empty($property->getOriginal($field))) {
                    $property->$field = $property->getOriginal($field);
                }
            }
        });
    }

    /**
     * Slug yaratish metod
     */
    public function generateSlug(): void
    {
        $sourceString = $this->getSlugSourceString();
        if (empty($sourceString) || $sourceString === 'property-new') {
            return; // Title bo'lmasa slug yaratilmaydi
        }

        $slug = \Illuminate\Support\Str::slug($sourceString);
        
        // Unique bo'lishini ta'minlash
        $originalSlug = $slug;
        $counter = 1;
        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Slug'ni saqlash (agar o'zgarmagan bo'lsa)
        if ($this->slug !== $slug) {
            $this->slug = $slug;
            $this->saveQuietly(); // Event'larni trigger qilmasdan saqlash
        }

        // SEO slug'larni ham yaratish
        $this->generateSeoSlugs();
    }

    /**
     * SEO slug'larni yaratish
     */
    public function generateSeoSlugs(): void
    {
        foreach (['uz', 'ru', 'en'] as $locale) {
            if ($this->hasTranslation($locale)) {
                $title = $this->translate($locale)->title;
                if (!empty($title)) {
                    $seoSlug = \Illuminate\Support\Str::slug($title);
                    $fieldName = 'seo_slug_' . $locale;
                    
                    // Unique bo'lishini ta'minlash
                    $originalSlug = $seoSlug;
                    $counter = 1;
                    while (static::where($fieldName, $seoSlug)->where('id', '!=', $this->id ?? 0)->exists()) {
                        $seoSlug = $originalSlug . '-' . $counter;
                        $counter++;
                    }
                    
                    $this->$fieldName = $seoSlug;
                }
            }
        }
        
        if ($this->isDirty(['seo_slug_uz', 'seo_slug_ru', 'seo_slug_en'])) {
            $this->saveQuietly();
        }
    }

    /**
     * Get the user that owns the property.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvalReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval_reviewer_id');
    }

    public function appendApprovalHistory(string $status, array $meta = []): void
    {
        $history = $this->approval_history ?? [];
        $history[] = [
            'status' => $status,
            'meta' => $meta,
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->approval_history = array_slice($history, -20);
    }

    /**
     * Get the SEO meta for the property.
     */
    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    /**
     * Get all SEO metas for different locales.
     */
    public function seoMetas(): MorphMany
    {
        return $this->morphMany(SeoMeta::class, 'seoable');
    }

    /**
     * Scope a query to only include published properties.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include featured properties.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Get comments for the property.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get approved comments for the property.
     */
    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->approved();
    }

    /**
     * Get top-level comments (not replies).
     */
    public function topLevelComments(): HasMany
    {
        return $this->hasMany(Comment::class)->topLevel()->approved();
    }
}
