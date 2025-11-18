<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'locale',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_robots',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'og_url',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'canonical_url',
        'structured_data',
        'hreflang',
    ];

    protected $casts = [
        'structured_data' => 'array',
        'hreflang' => 'array',
    ];

    /**
     * Get the parent seoable model (property, page, etc.).
     */
    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
