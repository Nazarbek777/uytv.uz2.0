<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AiContentGeneration extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'content_type',
        'locale',
        'prompt',
        'generated_content',
        'user_id',
        'is_used',
        'tokens_used',
        'cost',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'tokens_used' => 'integer',
        'cost' => 'decimal:6',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }

    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    public function scopeByContentType($query, $type)
    {
        return $query->where('content_type', $type);
    }
}
