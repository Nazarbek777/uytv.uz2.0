<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatbotLog extends Model
{
    protected $fillable = [
        'session_id',
        'locale',
        'user_message',
        'ai_response',
        'properties_suggested',
        'response_time_ms',
        'ip_address',
        'user_agent',
        'success',
        'error_message',
    ];

    protected $casts = [
        'properties_suggested' => 'array',
        'success' => 'boolean',
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
}
