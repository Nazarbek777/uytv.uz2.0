<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramChannel extends Model
{
    protected $fillable = [
        'name',
        'username',
        'chat_id',
        'is_active',
        'description',
        'scrape_limit',
        'scrape_days',
        'last_scraped_at',
        'total_scraped',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_scraped_at' => 'datetime',
        'scrape_limit' => 'integer',
        'scrape_days' => 'integer',
        'total_scraped' => 'integer',
    ];

    /**
     * Username'ni tozalash (@ belgisini olib tashlash)
     */
    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = ltrim(strtolower(trim($value)), '@');
    }

    /**
     * Faol kanallarni olish
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
