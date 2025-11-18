<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentProperty extends Model
{
    protected $fillable = [
        'development_id',
        'bedrooms',
        'property_type',
        'area_from',
        'area_to',
        'price_from',
        'price_to',
        'currency',
        'floor',
        'availability',
        'quantity_available',
        'total_quantity',
        'notes_uz',
        'notes_ru',
        'notes_en',
    ];

    protected $casts = [
        'bedrooms' => 'integer',
        'area_from' => 'decimal:2',
        'area_to' => 'decimal:2',
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
        'floor' => 'integer',
    ];

    /**
     * Get the development that owns the property
     */
    public function development(): BelongsTo
    {
        return $this->belongsTo(Development::class);
    }
}
