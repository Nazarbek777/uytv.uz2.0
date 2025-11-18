<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FloorPlan extends Model
{
    protected $fillable = [
        'development_id',
        'development_property_id',
        'image_path',
        'title_uz',
        'title_ru',
        'title_en',
        'description_uz',
        'description_ru',
        'description_en',
        'bedrooms',
        'area',
        'price',
        'floor',
        'availability',
    ];

    protected $casts = [
        'bedrooms' => 'integer',
        'area' => 'decimal:2',
        'price' => 'decimal:2',
        'floor' => 'integer',
        'availability' => 'boolean',
    ];

    /**
     * Get the development that owns the floor plan
     */
    public function development(): BelongsTo
    {
        return $this->belongsTo(Development::class);
    }

    /**
     * Get the property for the floor plan
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(DevelopmentProperty::class, 'development_property_id');
    }
}
