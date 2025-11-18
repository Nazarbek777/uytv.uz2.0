<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentDocument extends Model
{
    protected $fillable = [
        'development_id',
        'file_path',
        'title_uz',
        'title_ru',
        'title_en',
        'type',
    ];

    /**
     * Get the development that owns the document
     */
    public function development(): BelongsTo
    {
        return $this->belongsTo(Development::class);
    }
}
