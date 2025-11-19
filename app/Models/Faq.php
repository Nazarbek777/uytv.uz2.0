<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question_uz',
        'question_ru',
        'question_en',
        'answer_uz',
        'answer_ru',
        'answer_en',
        'category',
        'sort_order',
        'is_active',
        'views',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'views' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function getQuestionAttribute()
    {
        $locale = app()->getLocale();
        $questionField = 'question_' . $locale;
        return $this->$questionField ?? $this->question_uz;
    }

    public function getAnswerAttribute()
    {
        $locale = app()->getLocale();
        $answerField = 'answer_' . $locale;
        return $this->$answerField ?? $this->answer_uz;
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
