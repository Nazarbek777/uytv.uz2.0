<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name_uz',
        'name_ru',
        'name_en',
        'slug',
        'type',
        'icon',
        'description_uz',
        'description_ru',
        'description_en',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name_uz);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePropertyType($query)
    {
        return $query->where('type', 'property');
    }

    public function scopeDevelopmentType($query)
    {
        return $query->where('type', 'development');
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $nameField = 'name_' . $locale;
        return $this->$nameField ?? $this->name_uz;
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $descField = 'description_' . $locale;
        return $this->$descField ?? $this->description_uz;
    }
}
