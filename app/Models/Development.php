<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Development extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title_uz',
        'title_ru',
        'title_en',
        'description_uz',
        'description_ru',
        'description_en',
        'developer_name_uz',
        'developer_name_ru',
        'developer_name_en',
        'city',
        'region',
        'address_uz',
        'address_ru',
        'address_en',
        'latitude',
        'longitude',
        'price_from',
        'price_to',
        'price_per_sqm',
        'currency',
        'completion_date',
        'total_buildings',
        'total_floors',
        'featured_image',
        'images',
        'amenities',
        'slug',
        'status',
        'featured',
        'views',
        'installment_available',
        'cashback_percentage',
        'discount_percentage',
        'class',
    ];

    protected $casts = [
        'images' => 'array',
        'amenities' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
        'price_per_sqm' => 'decimal:2',
        'completion_date' => 'date',
        'total_buildings' => 'integer',
        'total_floors' => 'integer',
        'views' => 'integer',
        'featured' => 'boolean',
        'installment_available' => 'boolean',
        'cashback_percentage' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];

    /**
     * Get the builder (user) that owns the development
     */
    public function builder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the properties for the development
     */
    public function properties(): HasMany
    {
        return $this->hasMany(DevelopmentProperty::class);
    }

    /**
     * Get the floor plans for the development
     */
    public function floorPlans(): HasMany
    {
        return $this->hasMany(FloorPlan::class);
    }

    /**
     * Get the documents for the development
     */
    public function documents(): HasMany
    {
        return $this->hasMany(DevelopmentDocument::class);
    }

    /**
     * Scope a query to only include published developments.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include featured developments.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
