@if($seoMeta)
<!-- Primary Meta Tags -->
<title>{{ $seoMeta->meta_title }}</title>
<meta name="title" content="{{ $seoMeta->meta_title }}">
<meta name="description" content="{{ $seoMeta->meta_description }}">
@if($seoMeta->meta_keywords)
<meta name="keywords" content="{{ $seoMeta->meta_keywords }}">
@endif
<meta name="robots" content="{{ $seoMeta->meta_robots }}">
<meta name="language" content="{{ $locale }}">
<meta name="revisit-after" content="7 days">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $seoMeta->og_type }}">
<meta property="og:url" content="{{ $seoMeta->og_url ?? url()->current() }}">
<meta property="og:title" content="{{ $seoMeta->og_title }}">
<meta property="og:description" content="{{ $seoMeta->og_description }}">
@if($seoMeta->og_image)
<meta property="og:image" content="{{ $seoMeta->og_image }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
@endif
<meta property="og:locale" content="{{ $locale === 'uz' ? 'uz_UZ' : ($locale === 'ru' ? 'ru_RU' : 'en_US') }}">

<!-- Twitter -->
<meta name="twitter:card" content="{{ $seoMeta->twitter_card }}">
<meta name="twitter:url" content="{{ $seoMeta->og_url ?? url()->current() }}">
<meta name="twitter:title" content="{{ $seoMeta->twitter_title }}">
<meta name="twitter:description" content="{{ $seoMeta->twitter_description }}">
@if($seoMeta->twitter_image)
<meta name="twitter:image" content="{{ $seoMeta->twitter_image }}">
@endif

<!-- Canonical URL -->
@if($seoMeta->canonical_url)
<link rel="canonical" href="{{ $seoMeta->canonical_url }}">
@endif

<!-- Hreflang Tags (Alternate Languages) -->
@if($seoMeta->hreflang)
@foreach($seoMeta->hreflang as $lang => $url)
<link rel="alternate" hreflang="{{ $lang }}" href="{{ $url }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ $seoMeta->canonical_url ?? url()->current() }}">
@endif

<!-- Structured Data (JSON-LD) -->
@if($seoMeta->structured_data)
<script type="application/ld+json">
{!! json_encode($seoMeta->structured_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif
@endif