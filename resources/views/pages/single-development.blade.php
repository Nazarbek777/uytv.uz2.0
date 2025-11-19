@extends('layouts.page')
@section('content')

@php
    $locale = $locale ?? app()->getLocale();
    $title = $development->{'title_' . $locale} ?? $development->title_uz;
    $description = $development->{'description_' . $locale} ?? $development->description_uz;
    $developerName = $development->{'developer_name_' . $locale} ?? $development->developer_name_uz ?? 'N/A';
    $address = $development->{'address_' . $locale} ?? ($development->region ? $development->region . ', ' . $development->city : $development->city);

    // Get images
    $images = [];
    if ($development->featured_image) {
        $images[] = asset('storage/' . $development->featured_image);
    }
    if ($development->images && is_array($development->images)) {
        foreach ($development->images as $img) {
            $imgPath = is_array($img) ? ($img['path'] ?? $img['url'] ?? null) : $img;
            if ($imgPath && !in_array(asset('storage/' . $imgPath), $images)) {
                $images[] = asset('storage/' . $imgPath);
            }
        }
    }
    if (empty($images)) {
        $images[] = 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&h=600&fit=crop';
    }

    // Calculate prices
    $minPrice = 0;
    if ($development->properties && $development->properties->count() > 0) {
        $minPriceProp = $development->properties->whereNotNull('price_from')->min('price_from');
        $minPrice = $minPriceProp ?? $development->price_from ?? 0;
    } else {
        $minPrice = $development->price_from ?? 0;
    }

    $pricePerSqm = $development->price_per_sqm ?? 0;
    if (!$pricePerSqm && $development->properties && $development->properties->count() > 0) {
        $minPriceProp = $development->properties->whereNotNull('price_from')->min('price_from') ?? 0;
        $maxAreaProp = $development->properties->whereNotNull('area_from')->max('area_from') ?? 1;
        if ($maxAreaProp > 0) {
            $pricePerSqm = $minPriceProp / $maxAreaProp;
        }
    }

    // Properties grouped by bedrooms
    $propertiesByRoom = collect();
    if ($development->properties && $development->properties->count() > 0) {
        $propertiesByRoom = $development->properties->whereNotNull('bedrooms')
            ->groupBy('bedrooms')
            ->map(function($props) {
                return [
                    'count' => $props->count(),
                    'min_area' => $props->whereNotNull('area_from')->min('area_from') ?? 0,
                    'max_area' => $props->whereNotNull('area_to')->max('area_to') ?? ($props->whereNotNull('area_from')->max('area_from') ?? 0),
                    'min_price' => $props->whereNotNull('price_from')->min('price_from') ?? 0,
                    'max_price' => $props->whereNotNull('price_to')->max('price_to') ?? ($props->whereNotNull('price_from')->max('price_from') ?? 0),
                ];
            })->sortKeys();
    }

    $totalApartments = $development->properties ? $development->properties->count() : 0;

    // Amenities
    $amenities = [];
    if (!empty($development->amenities)) {
        if (is_array($development->amenities)) {
            $amenities = array_filter($development->amenities);
        } elseif (is_string($development->amenities)) {
            $decodedAmenities = json_decode($development->amenities, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedAmenities)) {
                $amenities = array_filter($decodedAmenities);
            } else {
                $amenities = array_filter(array_map('trim', preg_split('/[,;\n]+/', $development->amenities)));
            }
        }
    }

    // Builder avatar
    $builderAvatar = null;
    if ($development->builder && $development->builder->avatar) {
        $builderAvatar = asset('storage/' . $development->builder->avatar);
    } else {
        $builderName = $development->builder ? $development->builder->name : $developerName;
        $builderAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($builderName) . '&background=0987f5&color=fff&size=200&bold=true';
    }
@endphp

<style>
:root {
    --primary-color: #0987f5 !important;
    --primary-hover: #0774d4 !important;
}
.hero-slider {
    position: relative;
    height: 500px;
    overflow: hidden;
}
.hero-slider img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
    padding: 40px;
    color: white;
}
.sticky-sidebar {
    position: sticky;
    top: 20px;
}
.info-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.info-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(9,135,245,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    font-size: 24px;
}
.promo-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 2px solid var(--primary-color);
    position: relative;
    overflow: hidden;
}
.promo-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--primary-color);
    color: white;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.layout-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}
.layout-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.layout-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(9,135,245,0.2);
}
.document-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: white;
    border-radius: 8px;
    margin-bottom: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.2s;
}
.document-item:hover {
    border-color: var(--primary-color);
    box-shadow: 0 2px 8px rgba(9,135,245,0.1);
}
.feature-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
}
.feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: white;
    border-radius: 8px;
}
.map-container {
    height: 500px;
    border-radius: 12px;
    overflow: hidden;
}
</style>

<!-- Hero Section -->
<section class="hero-slider mb-4">
    <div id="heroCarousel" class="carousel slide h-100" data-bs-ride="carousel">
        <div class="carousel-inner h-100">
            @foreach($images as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }} h-100">
                <img src="{{ $image }}" alt="{{ $title }}">
            </div>
            @endforeach
        </div>
        @if(count($images) > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
        @endif
    </div>
    <div class="hero-overlay">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 mb-3">
                        <button class="btn btn-sm btn-light rounded-3">
                            <i class="bi bi-images me-1"></i>{{ $locale === 'uz' ? 'Rasmlarni ko\'rish' : ($locale === 'ru' ? 'Смотреть фото' : 'View photos') }}
                        </button>
                        <button class="btn btn-sm btn-light rounded-3">
                            <i class="bi bi-heart me-1"></i>{{ $locale === 'uz' ? 'Saqlash' : ($locale === 'ru' ? 'В избранное' : 'Add to favorites') }}
                        </button>
                        <button class="btn btn-sm btn-light rounded-3">
                            <i class="bi bi-share me-1"></i>{{ $locale === 'uz' ? 'Ulashish' : ($locale === 'ru' ? 'Поделиться' : 'Share') }}
                        </button>
                    </div>
                    <h1 class="text-white mb-2" style="font-size: 36px; font-weight: 700;">JK "{{ $title }}"</h1>
                    <div class="d-flex align-items-center gap-4 mb-2">
                        <span class="text-white" style="font-size: 20px; font-weight: 600;">{{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }} {{ number_format($pricePerSqm, 0) }} {{ $development->currency ?? 'UZS' }}/m²</span>
                    </div>
                    <p class="text-white-50 mb-0">
                        <i class="bi bi-geo-alt-fill me-1" style="color: var(--primary-color);"></i>
                        {{ $address }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Information Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="info-card-icon">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0" style="font-size: 12px;">{{ $locale === 'uz' ? 'Uy klassi' : ($locale === 'ru' ? 'Класс жилья' : 'Housing class') }}</p>
                                <h6 class="mb-0 fw-bold" style="color: var(--primary-color);">{{ $development->class ?? ($locale === 'uz' ? 'Komfort' : ($locale === 'ru' ? 'Комфорт' : 'Comfort')) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="info-card-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0" style="font-size: 12px;">{{ $locale === 'uz' ? 'Kvartiralar sotuvda' : ($locale === 'ru' ? 'Квартиры на продажу' : 'Apartments for sale') }}</p>
                                <h6 class="mb-0 fw-bold" style="color: var(--primary-color);">{{ $totalApartments }} {{ $locale === 'uz' ? 'kvartira' : ($locale === 'ru' ? 'кв.' : 'apts.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="info-card-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0" style="font-size: 12px;">{{ $locale === 'uz' ? 'Topshirish muddati' : ($locale === 'ru' ? 'Срок сдачи' : 'Completion date') }}</p>
                                <h6 class="mb-0 fw-bold" style="color: var(--primary-color);">
                                    @if($development->completion_date)
                                        {{ $development->completion_date->format('m/Y') }}
                                    @else
                                        {{ $locale === 'uz' ? 'Aniqlanmagan' : ($locale === 'ru' ? 'Не указано' : 'TBD') }}
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promotions -->
            @if(($development->discount_percentage || $development->cashback_percentage) && $development->installment_available)
            <div class="mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Aksiyalar va bonuslar' : ($locale === 'ru' ? 'Акции и бонусы' : 'Promotions and Bonuses') }}</h4>
                <div class="row g-3">
                    @if($development->discount_percentage)
                    <div class="col-md-6">
                        <div class="promo-card">
                            <span class="promo-badge">{{ $development->discount_percentage }}% {{ $locale === 'uz' ? 'Chegirma' : ($locale === 'ru' ? 'Скидка' : 'Discount') }}</span>
                            <p class="mb-0" style="font-size: 14px;">{{ $locale === 'uz' ? '100% dastlabki to\'lovni to\'lang...' : ($locale === 'ru' ? 'Внесите 100% первоначальный взнос...' : 'Make 100% down payment...') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($development->cashback_percentage)
                    <div class="col-md-6">
                        <div class="promo-card">
                            <span class="promo-badge">{{ $locale === 'uz' ? 'Keshbek' : ($locale === 'ru' ? 'Кешбек' : 'Cashback') }} {{ $development->cashback_percentage }}%</span>
                            <p class="mb-0" style="font-size: 14px;">{{ $locale === 'uz' ? '50% dastlabki to\'lovni to\'lang...' : ($locale === 'ru' ? 'Внесите 50% первоначальный взнос...' : 'Make 50% down payment...') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Visual View -->
            @if(!empty($images))
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Vizual ko\'rinish' : ($locale === 'ru' ? 'Визуальный вид' : 'Visual View') }}</h4>
                <img src="{{ $images[1] ?? $images[0] }}" alt="{{ $title }}" class="w-100 rounded-3" style="max-height: 400px; object-fit: cover;">
            </div>
            @endif

            <!-- Apartment/Layout Tabs -->
            <div class="info-card mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <button class="tab-btn active" data-tab="apartments" style="background: none; border: none; border-bottom: 3px solid var(--primary-color); padding: 10px 20px; color: var(--primary-color); font-weight: 600;">
                        {{ $locale === 'uz' ? 'Kvartira' : ($locale === 'ru' ? 'Квартира' : 'Apartment') }}
                        <span class="badge bg-light text-dark ms-2">{{ $totalApartments }}</span>
                    </button>
                    <button class="tab-btn" data-tab="layouts" style="background: none; border: none; border-bottom: 3px solid transparent; padding: 10px 20px; color: #6c757d; font-weight: 600;">
                        {{ $locale === 'uz' ? 'Plan' : ($locale === 'ru' ? 'Планировка' : 'Layout') }}
                        <span class="badge bg-light text-dark ms-2">{{ $development->floorPlans ? $development->floorPlans->count() : 0 }}</span>
                    </button>
                </div>

                <!-- Filters -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <select class="form-select form-select-sm" style="width: auto; font-size: 13px;">
                        <option>{{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}</option>
                    </select>
                    <select class="form-select form-select-sm" style="width: auto; font-size: 13px;">
                        <option>{{ $locale === 'uz' ? 'Bloklar' : ($locale === 'ru' ? 'Блоки' : 'Blocks') }}</option>
                    </select>
                    <button class="btn btn-sm {{ request('bedrooms') ? '' : 'btn-primary' }}" style="background: {{ request('bedrooms') ? '#f8f9fa' : 'var(--primary-color)' }}; border: none; color: {{ request('bedrooms') ? '#212529' : '#fff' }};">{{ $locale === 'uz' ? 'Studiya' : ($locale === 'ru' ? 'Студия' : 'Studio') }}</button>
                    @foreach([1,2,3,4] as $rooms)
                    <button class="btn btn-sm {{ request('bedrooms') == $rooms ? 'btn-primary' : '' }}" style="background: {{ request('bedrooms') == $rooms ? 'var(--primary-color)' : '#f8f9fa' }}; border: none; color: {{ request('bedrooms') == $rooms ? '#fff' : '#212529' }};">{{ $rooms }}</button>
                    @endforeach
                    <button class="btn btn-sm" style="background: #f8f9fa; border: none; color: #212529;">4+</button>
                </div>

                <!-- Apartments Tab -->
                <div id="apartmentsTab" class="tab-content active">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="border: none; font-size: 13px; color: #6c757d; font-weight: 600;">{{ $locale === 'uz' ? 'Kvartira turi' : ($locale === 'ru' ? 'Тип квартиры' : 'Apartment type') }}</th>
                                    <th style="border: none; font-size: 13px; color: #6c757d; font-weight: 600;">{{ $locale === 'uz' ? 'Maydon' : ($locale === 'ru' ? 'Площадь' : 'Area') }}</th>
                                    <th style="border: none; font-size: 13px; color: #6c757d; font-weight: 600;">{{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}</th>
                                    <th style="border: none; font-size: 13px; color: #6c757d; font-weight: 600;">{{ $locale === 'uz' ? 'Mavjud' : ($locale === 'ru' ? 'Доступно' : 'Available') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($propertiesByRoom as $bedrooms => $data)
                                <tr style="cursor: pointer;" onclick="window.location.href='#layout-{{ $bedrooms }}'">
                                    <td style="border: none; padding: 15px;">
                                        <strong>{{ $bedrooms }} {{ $locale === 'uz' ? 'xonali' : ($locale === 'ru' ? 'комнатная' : 'room') }}</strong>
                                    </td>
                                    <td style="border: none; padding: 15px;">
                                        {{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }} {{ number_format($data['min_area'] ?? 0, 2) }} m²
                                    </td>
                                    <td style="border: none; padding: 15px;">
                                        @if(($data['max_price'] ?? 0) > ($data['min_price'] ?? 0))
                                            {{ number_format(($data['min_price'] ?? 0) / 1000000, 3) }} {{ $locale === 'uz' ? 'mln' : ($locale === 'ru' ? 'млн' : 'mln') }} - {{ number_format(($data['max_price'] ?? 0) / 1000000, 3) }} {{ $locale === 'uz' ? 'mln' : ($locale === 'ru' ? 'млн' : 'mln') }} {{ $development->currency ?? 'UZS' }}
                                        @else
                                            {{ number_format(($data['min_price'] ?? 0) / 1000000, 3) }} {{ $locale === 'uz' ? 'mln' : ($locale === 'ru' ? 'млн' : 'mln') }} {{ $development->currency ?? 'UZS' }}
                                        @endif
                                    </td>
                                    <td style="border: none; padding: 15px;">
                                        <span class="badge" style="background: var(--primary-color); color: white;">{{ $data['count'] ?? 0 }} {{ $locale === 'uz' ? 'kvartira' : ($locale === 'ru' ? 'квартиры' : 'apartments') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        {{ $locale === 'uz' ? 'Kvartiralar topilmadi' : ($locale === 'ru' ? 'Квартиры не найдены' : 'No apartments found') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Layouts Tab -->
                <div id="layoutsTab" class="tab-content">
                    <div class="layout-grid">
                        @forelse(($development->floorPlans ?? collect()) as $plan)
                        <div class="layout-card">
                            @if($plan->image)
                            <img src="{{ asset('storage/' . $plan->image) }}" alt="{{ $plan->{'title_' . $locale} ?? $plan->title_uz }}" style="width: 100%; height: 200px; object-fit: cover;">
                            @else
                            <div style="width: 100%; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                <i class="bi bi-image" style="font-size: 48px;"></i>
                            </div>
                            @endif
                            <div class="p-3">
                                <h6 class="mb-1">{{ $plan->bedrooms }} {{ $locale === 'uz' ? 'xona' : ($locale === 'ru' ? 'комн' : 'room') }} - {{ number_format($plan->area_from ?? 0, 2) }} m²</h6>
                                @if($plan->price_from)
                                <p class="mb-2 fw-bold" style="color: var(--primary-color);">{{ number_format($plan->price_from / 1000000, 1) }} {{ $locale === 'uz' ? 'mln' : ($locale === 'ru' ? 'млн' : 'mln') }} {{ $development->currency ?? 'UZS' }}</p>
                                @endif
                                <button class="btn btn-sm w-100" style="background: var(--primary-color); color: white; border: none;">
                                    {{ $plan->quantity_available ?? 1 }} {{ $locale === 'uz' ? 'kvartira' : ($locale === 'ru' ? 'квартиры' : 'apartments') }}
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 48px; color: var(--primary-color);"></i>
                            <p class="text-muted mt-3">{{ $locale === 'uz' ? 'Plan mavjud emas' : ($locale === 'ru' ? 'Планировки не найдены' : 'No floor plans available') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Documents -->
            @if($development->documents && $development->documents->count() > 0)
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Hujjatlar' : ($locale === 'ru' ? 'Документы' : 'Documents') }}</h4>
                @foreach($development->documents->groupBy('type') as $type => $docs)
                <div class="document-item">
                    <div class="info-card-icon me-3">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $docs->first()->{'title_' . $locale} ?? $docs->first()->title_uz ?? $type }}</h6>
                        <small class="text-muted">{{ $docs->count() }} {{ $locale === 'uz' ? 'hujjat' : ($locale === 'ru' ? 'документ' : 'document') }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Features -->
            @if(!empty($amenities))
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Xususiyatlar' : ($locale === 'ru' ? 'Особенности недвижимости' : 'Property Features') }}</h4>
                <div class="feature-list">
                        @foreach($amenities as $amenity)
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill" style="color: var(--primary-color);"></i>
                        <span>{{ is_array($amenity) ? ($amenity['name'] ?? $amenity['title'] ?? '') : $amenity }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- About Complex -->
            @if($description)
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Loyiha haqida' : ($locale === 'ru' ? 'О жилом комплексе' : 'About the Residential Complex') }}</h4>
                <div class="text-muted" style="line-height: 1.8;">
                    {!! nl2br(e($description)) !!}
                </div>
            </div>
            @endif

            <!-- Construction Process -->
            @if(!empty($images) && count($images) > 1)
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Qurilish jarayoni' : ($locale === 'ru' ? 'Процесс строительства' : 'Construction Process') }}</h4>
                <div class="position-relative" style="height: 300px; border-radius: 12px; overflow: hidden;">
                    <img src="{{ $images[2] ?? $images[1] ?? $images[0] }}" alt="Construction" style="width: 100%; height: 100%; object-fit: cover; filter: blur(2px);">
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <button class="btn btn-light rounded-3 px-4">
                            <i class="bi bi-camera me-2"></i>{{ $locale === 'uz' ? 'Ko\'rsatish' : ($locale === 'ru' ? 'Показать' : 'Show') }} ({{ count($images) }})
                        </button>
                    </div>
                </div>
                @if($development->completion_date)
                <p class="text-muted mt-2 mb-0">{{ $development->completion_date->format('d F Y') }}</p>
                @endif
            </div>
            @endif

            <!-- Map -->
            @if($development->latitude && $development->longitude)
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Xaritada' : ($locale === 'ru' ? 'На карте' : 'On the Map') }}</h4>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="btn btn-sm btn-primary" style="background: var(--primary-color); border: none;">{{ $locale === 'uz' ? 'Ovqat' : ($locale === 'ru' ? 'Еда' : 'Food') }}</button>
                    <button class="btn btn-sm" style="background: #f8f9fa; border: none; color: #212529;">{{ $locale === 'uz' ? 'Parklar' : ($locale === 'ru' ? 'Парки' : 'Parks') }}</button>
                    <button class="btn btn-sm" style="background: #f8f9fa; border: none; color: #212529;">{{ $locale === 'uz' ? 'Maktablar' : ($locale === 'ru' ? 'Школы' : 'Schools') }}</button>
                    <button class="btn btn-sm" style="background: #f8f9fa; border: none; color: #212529;">{{ $locale === 'uz' ? 'Bolalar bog\'lari' : ($locale === 'ru' ? 'Детские сады' : 'Kindergartens') }}</button>
                    <button class="btn btn-sm" style="background: #f8f9fa; border: none; color: #212529;">{{ $locale === 'uz' ? 'Shifoxonalar' : ($locale === 'ru' ? 'Больницы' : 'Hospitals') }}</button>
                </div>
                <div class="map-container" id="map" style="height: 500px;"></div>
            </div>
            @endif
        </div>

        <!-- Sticky Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <div class="info-card">
                    <h5 class="mb-3 fw-bold">JK "{{ $title }}"</h5>
                    <div class="mb-3">
                        <p class="text-muted mb-1" style="font-size: 12px;">{{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}</p>
                        <h4 class="mb-0 fw-bold" style="color: var(--primary-color);">{{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }} {{ number_format($minPrice, 0) }} {{ $development->currency ?? 'UZS' }}</h4>
                        <p class="text-muted mb-0" style="font-size: 14px;">{{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }} {{ number_format($pricePerSqm, 0) }} {{ $development->currency ?? 'UZS' }}/m²</p>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="{{ $builderAvatar }}" alt="{{ $developerName }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0">{{ $developerName }}</h6>
                                @if($development->builder && $development->builder->verified)
                                <small class="text-muted">
                                    <i class="bi bi-patch-check-fill" style="color: var(--primary-color);"></i> {{ $locale === 'uz' ? 'Tasdiqlangan' : ($locale === 'ru' ? 'Бренд застройщика' : 'Verified builder') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-light rounded-3" style="border: 1px solid #e9ecef;">
                            {{ $locale === 'uz' ? 'Konsultatsiya' : ($locale === 'ru' ? 'Консультация' : 'Consultation') }}
                        </button>
                        @if($development->builder && $development->builder->phone)
                        <a href="tel:{{ $development->builder->phone }}" class="btn btn-primary rounded-3" style="background: var(--primary-color); border: none; color: white;">
                            <i class="bi bi-telephone me-2"></i>{{ $locale === 'uz' ? 'Qo\'ng\'iroq' : ($locale === 'ru' ? 'Позвонить' : 'Call') }}
                        </a>
                        @endif
                    </div>
                    <hr>
                    <small class="text-muted">{{ $locale === 'uz' ? 'Oxirgi yangilanish:' : ($locale === 'ru' ? 'Последнее обновление:' : 'Last update:') }} {{ $development->updated_at->format('d.m.Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

@if($development->latitude && $development->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([{{ $development->latitude }}, {{ $development->longitude }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker = L.marker([{{ $development->latitude }}, {{ $development->longitude }}], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map);

    marker.bindPopup('<b>JK "{{ $title }}"</b><br>{{ $address }}').openPopup();
});
</script>
@endif

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const tab = this.dataset.tab;

        // Remove active from all
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
            b.style.borderBottom = '3px solid transparent';
            b.style.color = '#6c757d';
        });
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        // Add active to clicked
        this.classList.add('active');
        this.style.borderBottom = '3px solid var(--primary-color)';
        this.style.color = 'var(--primary-color)';

        document.getElementById(tab + 'Tab').classList.add('active');
    });
});
</script>


@php
    $locale = $locale ?? app()->getLocale();
    $title = $development->{'title_' . $locale} ?? $development->title_uz;
    $description = $development->{'description_' . $locale} ?? $development->description_uz;
    $developerName = $development->{'developer_name_' . $locale} ?? $development->developer_name_uz ?? 'N/A';
    $address = $development->{'address_' . $locale} ?? ($development->region ? $development->region . ', ' . $development->city : $development->city);

    // Get images
    $images = [];
    if ($development->featured_image) {
        $images[] = asset('storage/' . $development->featured_image);
    }
    if ($development->images && is_array($development->images)) {
        foreach ($development->images as $img) {
            $imgPath = is_array($img) ? ($img['path'] ?? $img['url'] ?? null) : $img;
            if ($imgPath && !in_array(asset('storage/' . $imgPath), $images)) {
                $images[] = asset('storage/' . $imgPath);
            }
        }
    }
    if (empty($images)) {
        $images[] = 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&h=600&fit=crop';
    }

    // Calculate prices
    $minPrice = 0;
    if ($development->properties && $development->properties->count() > 0) {
        $minPriceProp = $development->properties->whereNotNull('price_from')->min('price_from');
        $minPrice = $minPriceProp ?? $development->price_from ?? 0;
    } else {
        $minPrice = $development->price_from ?? 0;
    }

    $pricePerSqm = $development->price_per_sqm ?? 0;
    if (!$pricePerSqm && $development->properties && $development->properties->count() > 0) {
        $minPriceProp = $development->properties->whereNotNull('price_from')->min('price_from') ?? 0;
        $maxAreaProp = $development->properties->whereNotNull('area_from')->max('area_from') ?? 1;
        if ($maxAreaProp > 0) {
            $pricePerSqm = $minPriceProp / $maxAreaProp;
        }
    }

    // Properties grouped by bedrooms
    $propertiesByRoom = collect();
    if ($development->properties && $development->properties->count() > 0) {
        $propertiesByRoom = $development->properties->whereNotNull('bedrooms')
            ->groupBy('bedrooms')
            ->map(function($props) {
                return [
                    'count' => $props->count(),
                    'min_area' => $props->whereNotNull('area_from')->min('area_from') ?? 0,
                    'max_area' => $props->whereNotNull('area_to')->max('area_to') ?? ($props->whereNotNull('area_from')->max('area_from') ?? 0),
                    'min_price' => $props->whereNotNull('price_from')->min('price_from') ?? 0,
                    'max_price' => $props->whereNotNull('price_to')->max('price_to') ?? ($props->whereNotNull('price_from')->max('price_from') ?? 0),
                ];
            })->sortKeys();
    }

    $totalApartments = $development->properties ? $development->properties->count() : 0;

    // Builder avatar
    $builderAvatar = null;
    if ($development->builder && $development->builder->avatar) {
        $builderAvatar = asset('storage/' . $development->builder->avatar);
    } else {
        $builderName = $development->builder ? $development->builder->name : $developerName;
        $builderAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($builderName) . '&background=0987f5&color=fff&size=200&bold=true';
    }
@endphp

<style>
:root {
    --primary-color: #0987f5 !important;
    --primary-hover: #0774d4 !important;
}
.hero-slider {
    position: relative;
    height: 500px;
    overflow: hidden;
}
.hero-slider img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
    padding: 40px;
    color: white;
}
.sticky-sidebar {
    position: sticky;
    top: 20px;
}
.info-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.info-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(9,135,245,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    font-size: 24px;
}
.promo-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 2px solid var(--primary-color);
    position: relative;
    overflow: hidden;
}
.promo-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--primary-color);
    color: white;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.layout-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}
.layout-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.layout-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(9,135,245,0.2);
}
.document-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: white;
    border-radius: 8px;
    margin-bottom: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.2s;
}
.document-item:hover {
    border-color: var(--primary-color);
    box-shadow: 0 2px 8px rgba(9,135,245,0.1);
}
.feature-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
}
.feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: white;
    border-radius: 8px;
}
.map-container {
    height: 500px;
    border-radius: 12px;
    overflow: hidden;
}
</style>

<!-- Hero Section -->
<section class="hero-slider mb-4">
    <div id="heroCarousel" class="carousel slide h-100" data-bs-ride="carousel">
        <div class="carousel-inner h-100">
            @foreach($images as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }} h-100">
                <img src="{{ $image }}" alt="{{ $title }}">
            </div>
            @endforeach
        </div>
        @if(count($images) > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
        @endif
    </div>
    <div class="hero-overlay">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 mb-3">
                        <button class="btn btn-sm btn-light rounded-3">
                            <i class="bi bi-images me-1"></i>{{ $locale === 'uz' ? 'Rasmlarni ko\'rish' : ($locale === 'ru' ? 'Смотреть фото' : 'View photos') }}
                        </button>
                        <button class="btn btn-sm btn-light rounded-3">
                            <i class="bi bi-heart me-1"></i>{{ $locale === 'uz' ? 'Saqlash' : ($locale === 'ru' ? 'В избранное' : 'Add to favorites') }}
                        </button>
                        <button class="btn btn-sm btn-light rounded-3">
                            <i class="bi bi-share me-1"></i>{{ $locale === 'uz' ? 'Ulashish' : ($locale === 'ru' ? 'Поделиться' : 'Share') }}
                        </button>
                    </div>
                    <h1 class="text-white mb-2" style="font-size: 36px; font-weight: 700;">JK "{{ $title }}"</h1>
                    <div class="d-flex align-items-center gap-4 mb-2">
                        <span class="text-white" style="font-size: 20px; font-weight: 600;">{{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }} {{ number_format($pricePerSqm, 0) }} {{ $development->currency ?? 'UZS' }}/m²</span>
                    </div>
                    <p class="text-white-50 mb-0">
                        <i class="bi bi-geo-alt-fill me-1" style="color: var(--primary-color);"></i>
                        {{ $address }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Information Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="info-card-icon">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0" style="font-size: 12px;">{{ $locale === 'uz' ? 'Uy klassi' : ($locale === 'ru' ? 'Класс жилья' : 'Housing class') }}</p>
                                <h6 class="mb-0 fw-bold" style="color: var(--primary-color);">{{ $development->class ?? ($locale === 'uz' ? 'Komfort' : ($locale === 'ru' ? 'Комфорт' : 'Comfort')) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="info-card-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0" style="font-size: 12px;">{{ $locale === 'uz' ? 'Kvartiralar sotuvda' : ($locale === 'ru' ? 'Квартиры на продажу' : 'Apartments for sale') }}</p>
                                <h6 class="mb-0 fw-bold" style="color: var(--primary-color);">{{ $totalApartments }} {{ $locale === 'uz' ? 'kvartira' : ($locale === 'ru' ? 'кв.' : 'apts.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="info-card-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0" style="font-size: 12px;">{{ $locale === 'uz' ? 'Topshirish muddati' : ($locale === 'ru' ? 'Срок сдачи' : 'Completion date') }}</p>
                                <h6 class="mb-0 fw-bold" style="color: var(--primary-color);">
                                    @if($development->completion_date)
                                        {{ $development->completion_date->format('m/Y') }}
                                    @else
                                        {{ $locale === 'uz' ? 'Aniqlanmagan' : ($locale === 'ru' ? 'Не указано' : 'TBD') }}
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promotions -->
            @if(($development->discount_percentage || $development->cashback_percentage) && $development->installment_available)
            <div class="mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Aksiyalar va bonuslar' : ($locale === 'ru' ? 'Акции и бонусы' : 'Promotions and Bonuses') }}</h4>
                <div class="row g-3">
                    @if($development->discount_percentage)
                    <div class="col-md-6">
                        <div class="promo-card">
                            <span class="promo-badge">{{ $development->discount_percentage }}% {{ $locale === 'uz' ? 'Chegirma' : ($locale === 'ru' ? 'Скидка' : 'Discount') }}</span>
                            <p class="mb-0" style="font-size: 14px;">{{ $locale === 'uz' ? '100% dastlabki to\'lovni to\'lang...' : ($locale === 'ru' ? 'Внесите 100% первоначальный взнос...' : 'Make 100% down payment...') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($development->cashback_percentage)
                    <div class="col-md-6">
                        <div class="promo-card">
                            <span class="promo-badge">{{ $locale === 'uz' ? 'Keshbek' : ($locale === 'ru' ? 'Кешбек' : 'Cashback') }} {{ $development->cashback_percentage }}%</span>
                            <p class="mb-0" style="font-size: 14px;">{{ $locale === 'uz' ? '50% dastlabki to\'lovni to\'lang...' : ($locale === 'ru' ? 'Внесите 50% первоначальный взнос...' : 'Make 50% down payment...') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Visual View -->
            @if(!empty($images))
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Vizual ko\'rinish' : ($locale === 'ru' ? 'Визуальный вид' : 'Visual View') }}</h4>
                <img src="{{ $images[1] ?? $images[0] }}" alt="{{ $title }}" class="w-100 rounded-3" style="max-height: 400px; object-fit: cover;">
            </div>
            @endif

            <!-- Apartment/Layout Tabs -->
            <div class="info-card mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <button class="tab-btn active" data-tab="apartments" style="background: none; border: none; border-bottom: 3px solid var(--primary-color); padding: 10px 20px; color: var(--primary-color); font-weight: 600;">
                        {{ $locale === 'uz' ? 'Kvartira' : ($locale === 'ru' ? 'Квартира' : 'Apartment') }}
                        <span class="badge bg-light text-dark ms-2">{{ $totalApartments }}</span>
                    </button>
                    <button class="tab-btn" data-tab="layouts" style="background: none; border: none; border-bottom: 3px solid transparent; padding: 10px 20px; color: #6c757d; font-weight: 600;">
                        {{ $locale === 'uz' ? 'Plan' : ($locale === 'ru' ? 'Планировка' : 'Layout') }}
                        <span class="badge bg-light text-dark ms-2">{{ $development->floorPlans ? $development->floorPlans->count() : 0 }}</span>
                    </button>
                </div>

                <!-- Filters -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <select class="form-select form-select-sm" style="width: auto; font-size: 13px;">
                        <option>{{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}</option>
                    </select>
                    <select class="form-select form-select-sm" style="width: auto; font-size: 13px;">
                        <option>{{ $locale === 'uz' ? 'Bloklar' : ($locale === 'ru' ? 'Блоки' : 'Blocks') }}</option>
                    </select>
                    <button class="btn btn-sm {{ request('bedrooms') ? '' : 'btn-primary' }}" style="background: {{ request('bedrooms') ? '#f8f9fa' : 'var(--primary-color)' }}; border: none; color: {{ request('bedrooms') ? '#212529' : '#fff' }};">{{ $locale === 'uz' ? 'Studiya' : ($locale === 'ru' ? 'Студия' : 'Studio') }}</button>
                    @foreach([1,2,3,4] as $rooms)
                    <button class="btn btn-sm {{ request('bedrooms') == $rooms ? 'btn-primary' : '' }}" style="background: {{ request('bedrooms') == $rooms ? 'var(--primary-color)' : '#f8f9fa' }}; border: none; color: {{ request('bedrooms') == $rooms ? '#fff' : '#212529' }};">{{ $rooms }}</button>
                    @endforeach
                    <button class="btn btn-sm" style="background: #f8f9fa; border: none; color: #212529;">4+</button>
                </div>

                <!-- Apartments Tab -->
                <div id="apartmentsTab" class="tab-content active">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="border: none; font-size: 13px; color: #6c757d; font-weight: 600;">{{ $locale === 'uz' ? 'Kvartira turi' : ($locale === 'ru' ? 'Тип квартиры' : 'Apartment type') }}</th>
                                    <th style="border: none; font-size: 13px; color: #6c757d; font-weight: 600;">{{ $locale === 'uz' ? 'Maydon' : ($locale === 'ru' ? 'Площадь' : 'Area') }}</th>
                                    <th style="border: none; font-size: 13px; color: #6c757d; font-weight: 600;">{{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}</th>
                                    <th style="border: none; font-size: 13px; color: #6c757d; font-weight: 600;">{{ $locale === 'uz' ? 'Mavjud' : ($locale === 'ru' ? 'Доступно' : 'Available') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($propertiesByRoom as $bedrooms => $data)
                                <tr style="cursor: pointer;" onclick="window.location.href='#layout-{{ $bedrooms }}'">
                                    <td style="border: none; padding: 15px;">
                                        <strong>{{ $bedrooms }} {{ $locale === 'uz' ? 'xonali' : ($locale === 'ru' ? 'комнатная' : 'room') }}</strong>
                                    </td>
                                    <td style="border: none; padding: 15px;">
                                        {{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }} {{ number_format($data['min_area'] ?? 0, 2) }} m²
                                    </td>
                                    <td style="border: none; padding: 15px;">
                                        @if(($data['max_price'] ?? 0) > ($data['min_price'] ?? 0))
                                            {{ number_format(($data['min_price'] ?? 0) / 1000000, 3) }} {{ $locale === 'uz' ? 'mln' : ($locale === 'ru' ? 'млн' : 'mln') }} - {{ number_format(($data['max_price'] ?? 0) / 1000000, 3) }} {{ $locale === 'uz' ? 'mln' : ($locale === 'ru' ? 'млн' : 'mln') }} {{ $development->currency ?? 'UZS' }}
                                        @else
                                            {{ number_format(($data['min_price'] ?? 0) / 1000000, 3) }} {{ $locale === 'uz' ? 'mln' : ($locale === 'ru' ? 'млн' : 'mln') }} {{ $development->currency ?? 'UZS' }}
                                        @endif
                                    </td>
                                    <td style="border: none; padding: 15px;">
                                        <span class="badge" style="background: var(--primary-color); color: white;">{{ $data['count'] ?? 0 }} {{ $locale === 'uz' ? 'kvartira' : ($locale === 'ru' ? 'квартиры' : 'apartments') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        {{ $locale === 'uz' ? 'Kvartiralar topilmadi' : ($locale === 'ru' ? 'Квартиры не найдены' : 'No apartments found') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Layouts Tab -->
                <div id="layoutsTab" class="tab-content">
                    <div class="layout-grid">
                        @forelse(($development->floorPlans ?? collect()) as $plan)
                        <div class="layout-card">
                            @if($plan->image)
                            <img src="{{ asset('storage/' . $plan->image) }}" alt="{{ $plan->{'title_' . $locale} ?? $plan->title_uz }}" style="width: 100%; height: 200px; object-fit: cover;">
                            @else
                            <div style="width: 100%; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                <i class="bi bi-image" style="font-size: 48px;"></i>
                            </div>
                            @endif
                            <div class="p-3">
                                <h6 class="mb-1">{{ $plan->bedrooms }} {{ $locale === 'uz' ? 'xona' : ($locale === 'ru' ? 'комн' : 'room') }} - {{ number_format($plan->area_from ?? 0, 2) }} m²</h6>
                                @if($plan->price_from)
                                <p class="mb-2 fw-bold" style="color: var(--primary-color);">{{ number_format($plan->price_from / 1000000, 1) }} {{ $locale === 'uz' ? 'mln' : ($locale === 'ru' ? 'млн' : 'mln') }} {{ $development->currency ?? 'UZS' }}</p>
                                @endif
                                <button class="btn btn-sm w-100" style="background: var(--primary-color); color: white; border: none;">
                                    {{ $plan->quantity_available ?? 1 }} {{ $locale === 'uz' ? 'kvartira' : ($locale === 'ru' ? 'квартиры' : 'apartments') }}
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 48px; color: var(--primary-color);"></i>
                            <p class="text-muted mt-3">{{ $locale === 'uz' ? 'Plan mavjud emas' : ($locale === 'ru' ? 'Планировки не найдены' : 'No floor plans available') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Documents -->
            @if($development->documents && $development->documents->count() > 0)
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Hujjatlar' : ($locale === 'ru' ? 'Документы' : 'Documents') }}</h4>
                @foreach($development->documents->groupBy('type') as $type => $docs)
                <div class="document-item">
                    <div class="info-card-icon me-3">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $docs->first()->{'title_' . $locale} ?? $docs->first()->title_uz ?? $type }}</h6>
                        <small class="text-muted">{{ $docs->count() }} {{ $locale === 'uz' ? 'hujjat' : ($locale === 'ru' ? 'документ' : 'document') }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Features -->
            @if(!empty($amenities))
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Xususiyatlar' : ($locale === 'ru' ? 'Особенности недвижимости' : 'Property Features') }}</h4>
                <div class="feature-list">
                    @foreach($amenities as $amenity)
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill" style="color: var(--primary-color);"></i>
                        <span>{{ is_array($amenity) ? ($amenity['name'] ?? $amenity['title'] ?? '') : $amenity }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- About Complex -->
            @if($description)
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Loyiha haqida' : ($locale === 'ru' ? 'О жилом комплексе' : 'About the Residential Complex') }}</h4>
                <div class="text-muted" style="line-height: 1.8;">
                    {!! nl2br(e($description)) !!}
                </div>
            </div>
            @endif

            <!-- Construction Process -->
            @if(!empty($images) && count($images) > 1)
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Qurilish jarayoni' : ($locale === 'ru' ? 'Процесс строительства' : 'Construction Process') }}</h4>
                <div class="position-relative" style="height: 300px; border-radius: 12px; overflow: hidden;">
                    <img src="{{ $images[2] ?? $images[1] ?? $images[0] }}" alt="Construction" style="width: 100%; height: 100%; object-fit: cover; filter: blur(2px);">
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <button class="btn btn-light rounded-3 px-4">
                            <i class="bi bi-camera me-2"></i>{{ $locale === 'uz' ? 'Ko\'rsatish' : ($locale === 'ru' ? 'Показать' : 'Show') }} ({{ count($images) }})
                        </button>
                    </div>
                </div>
                @if($development->completion_date)
                <p class="text-muted mt-2 mb-0">{{ $development->completion_date->format('d F Y') }}</p>
                @endif
            </div>
            @endif

            <!-- Map -->
            @if($development->latitude && $development->longitude)
            <div class="info-card mb-4">
                <h4 class="mb-3 fw-bold">{{ $locale === 'uz' ? 'Xaritada' : ($locale === 'ru' ? 'На карте' : 'On the Map') }}</h4>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="btn btn-sm btn-primary" style="background: var(--primary-color); border: none;">{{ $locale === 'uz' ? 'Ovqat' : ($locale === 'ru' ? 'Еда' : 'Food') }}</button>
                    <button class="btn btn-sm" style="background: #f8f9fa; border: none; color: #212529;">{{ $locale === 'uz' ? 'Parklar' : ($locale === 'ru' ? 'Парки' : 'Parks') }}</button>
                    <button class="btn btn-sm" style="background: #f8f9fa; border: none; color: #212529;">{{ $locale === 'uz' ? 'Maktablar' : ($locale === 'ru' ? 'Школы' : 'Schools') }}</button>
                    <button class="btn btn-sm" style="background: #f8f9fa; border: none; color: #212529;">{{ $locale === 'uz' ? 'Bolalar bog\'lari' : ($locale === 'ru' ? 'Детские сады' : 'Kindergartens') }}</button>
                    <button class="btn btn-sm" style="background: #f8f9fa; border: none; color: #212529;">{{ $locale === 'uz' ? 'Shifoxonalar' : ($locale === 'ru' ? 'Больницы' : 'Hospitals') }}</button>
                </div>
                <div class="map-container" id="map" style="height: 500px;"></div>
            </div>
            @endif
        </div>

        <!-- Sticky Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <div class="info-card">
                    <h5 class="mb-3 fw-bold">JK "{{ $title }}"</h5>
                    <div class="mb-3">
                        <p class="text-muted mb-1" style="font-size: 12px;">{{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}</p>
                        <h4 class="mb-0 fw-bold" style="color: var(--primary-color);">{{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }} {{ number_format($minPrice, 0) }} {{ $development->currency ?? 'UZS' }}</h4>
                        <p class="text-muted mb-0" style="font-size: 14px;">{{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }} {{ number_format($pricePerSqm, 0) }} {{ $development->currency ?? 'UZS' }}/m²</p>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="{{ $builderAvatar }}" alt="{{ $developerName }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0">{{ $developerName }}</h6>
                                @if($development->builder && $development->builder->verified)
                                <small class="text-muted">
                                    <i class="bi bi-patch-check-fill" style="color: var(--primary-color);"></i> {{ $locale === 'uz' ? 'Tasdiqlangan' : ($locale === 'ru' ? 'Бренд застройщика' : 'Verified builder') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-light rounded-3" style="border: 1px solid #e9ecef;">
                            {{ $locale === 'uz' ? 'Konsultatsiya' : ($locale === 'ru' ? 'Консультация' : 'Consultation') }}
                        </button>
                        @if($development->builder && $development->builder->phone)
                        <a href="tel:{{ $development->builder->phone }}" class="btn btn-primary rounded-3" style="background: var(--primary-color); border: none; color: white;">
                            <i class="bi bi-telephone me-2"></i>{{ $locale === 'uz' ? 'Qo\'ng\'iroq' : ($locale === 'ru' ? 'Позвонить' : 'Call') }}
                        </a>
                        @endif
                    </div>
                    <hr>
                    <small class="text-muted">{{ $locale === 'uz' ? 'Oxirgi yangilanish:' : ($locale === 'ru' ? 'Последнее обновление:' : 'Last update:') }} {{ $development->updated_at->format('d.m.Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

@if($development->latitude && $development->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([{{ $development->latitude }}, {{ $development->longitude }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker = L.marker([{{ $development->latitude }}, {{ $development->longitude }}], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map);

    marker.bindPopup('<b>JK "{{ $title }}"</b><br>{{ $address }}').openPopup();
});
</script>
@endif

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const tab = this.dataset.tab;

        // Remove active from all
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
            b.style.borderBottom = '3px solid transparent';
            b.style.color = '#6c757d';
        });
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        // Add active to clicked
        this.classList.add('active');
        this.style.borderBottom = '3px solid var(--primary-color)';
        this.style.color = 'var(--primary-color)';

        document.getElementById(tab + 'Tab').classList.add('active');
    });
});
</script>

@endsection
