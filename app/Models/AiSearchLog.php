<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSearchLog extends Model
{
    protected $fillable = [
        'query',
        'locale',
        'ai_parsed_filters',
        'results_count',
        'properties_found',
        'response_time_ms',
        'ip_address',
        'user_agent',
        'success',
        'error_message',
    ];

    protected $casts = [
        'ai_parsed_filters' => 'array',
        'properties_found' => 'array',
        'success' => 'boolean',
        'results_count' => 'integer',
        'response_time_ms' => 'integer',
    ];

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    public function scopeWithResults($query)
    {
        return $query->where('results_count', '>', 0);
    }
}
