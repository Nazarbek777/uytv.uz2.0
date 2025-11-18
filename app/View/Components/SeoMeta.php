<?php

namespace App\View\Components;

use App\Models\SeoMeta as SeoMetaModel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SeoMeta extends Component
{
    public ?SeoMetaModel $seoMeta;
    public string $locale;

    /**
     * Create a new component instance.
     */
    public function __construct(?SeoMetaModel $seoMeta = null, string $locale = 'uz')
    {
        $this->seoMeta = $seoMeta;
        $this->locale = $locale;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.seo-meta');
    }
}
