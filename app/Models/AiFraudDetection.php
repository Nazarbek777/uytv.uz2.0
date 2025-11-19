<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AiFraudDetection extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'fraud_score',
        'detected_issues',
        'ai_analysis',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'fraud_score' => 'decimal:2',
        'detected_issues' => 'array',
        'ai_analysis' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeHighRisk($query, $threshold = 70)
    {
        return $query->where('fraud_score', '>=', $threshold);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', '!=', 'pending');
    }
}
