@extends('layouts.page')
@section('content')

@php
    $locale = $locale ?? app()->getLocale();
    $cities = $cities ?? collect();
    $stats = $stats ?? [
        'lowest_price_sqm' => 0,
        'cheapest_apartment' => 0,
        'lowest_down_payment' => 0,
        'lowest_monthly_payment' => 0,
        'longest_payment_period' => 0,
        'highest_discount' => 0,
    ];
    $minPrice = $minPrice ?? 0;
    $maxPrice = $maxPrice ?? 100000000;
    $bedrooms = $bedrooms ?? collect();
    $developments = $developments ?? collect();
    
    // Get first development for header background
    $firstDevelopment = null;
    if ($developments instanceof \Illuminate\Pagination\LengthAwarePaginator || $developments instanceof \Illuminate\Pagination\Paginator) {
        $firstDevelopment = $developments->items() ? ($developments->items()[0] ?? null) : null;
    } else {
        $firstDevelopment = $developments->first() ?? null;
    }
    
    // Determine header background image
    $headerBackground = 'https://images.unsplash.com/photo-1485846234645-a62644f84728?auto=format&fit=crop&w=1400&q=80';
    if ($firstDevelopment) {
        if ($firstDevelopment->featured_image) {
            $headerBackground = asset('storage/' . $firstDevelopment->featured_image);
        } elseif ($firstDevelopment->images && is_array($firstDevelopment->images) && count($firstDevelopment->images) > 0) {
            $firstImage = is_array($firstDevelopment->images[0]) ? ($firstDevelopment->images[0]['path'] ?? $firstDevelopment->images[0]) : $firstDevelopment->images[0];
            $headerBackground = asset('storage/' . $firstImage);
        }
    }
    
    $defaultBuilderAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($firstDevelopment && $firstDevelopment->builder ? $firstDevelopment->builder->name : 'Builder') . '&background=0987f5&color=fff&size=200&bold=true';
@endphp

<!-- ============================ Breadcrumb Start ================================== -->
<div class="page-title bg-cover" style="background: linear-gradient(135deg, rgba(9, 135, 245, 0.85) 0%, rgba(7, 116, 212, 0.85) 100%), url('{{ $headerBackground }}') center/cover no-repeat; background-size: cover; background-position: center; background-attachment: fixed; padding: 80px 0 40px; min-height: 250px; display: flex; align-items: center;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">{{ $locale === 'uz' ? 'Bosh sahifa' : ($locale === 'ru' ? 'Главная' : 'Home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $locale === 'uz' ? 'Novostroiki (JK)' : ($locale === 'ru' ? 'Новостройки (ЖК)' : 'New Buildings') }}
                        </li>
                    </ol>
                </nav>
                <h2 class="ipt-title text-white mb-2">{{ $locale === 'uz' ? 'Kvartiralar novostroikada (JK) O\'zbekistonda' : ($locale === 'ru' ? 'Квартиры в новостройках (ЖК) Узбекистане' : 'Apartments in new buildings (RC) Uzbekistan') }}</h2>
            </div>
        </div>
    </div>
</div>
<!-- ============================ Breadcrumb End ================================== -->

<!-- ============================ Search Bar Start ================================== -->
<section class="gray-simple py-3" style="background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
    <div class="container">
        <form action="{{ route('page.developments') }}" method="GET" id="searchForm" class="modern-filter-bar">
            <input type="hidden" name="locale" value="{{ $locale }}">
            <div class="filter-chip-row d-flex flex-wrap gap-2 align-items-center">
                <!-- Rooms dropdown -->
                <div class="chip-select">
                    <button class="chip-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 8px 14px; min-width: 160px; height: 40px;">
                        <span class="chip-label" style="font-size: 11px;">{{ $locale === 'uz' ? 'Xonalar soni' : ($locale === 'ru' ? 'Количество комнат' : 'Number of rooms') }}</span>
                        <span class="chip-value" style="font-size: 13px;">{{ request('bedrooms') ? request('bedrooms').'+' : ($locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All')) }}</span>
                        <i class="bi bi-chevron-down ms-1" style="font-size: 12px; color: var(--primary-color);"></i>
                    </button>
                    <div class="dropdown-menu chip-dropdown">
                        <label class="dropdown-title">{{ $locale === 'uz' ? 'Xonalar sonini tanlang' : ($locale === 'ru' ? 'Выберите количество комнат' : 'Select rooms') }}</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="chip-option {{ !request('bedrooms') ? 'active' : '' }}" data-value="">{{ $locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All') }}</button>
                            @foreach($bedrooms as $bedroom)
                            <button type="button" class="chip-option {{ request('bedrooms') == $bedroom ? 'active' : '' }}" data-value="{{ $bedroom }}">{{ $bedroom }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="bedrooms" id="bedroomsInput" value="{{ request('bedrooms') }}">
                    </div>
                </div>

                <!-- Price dropdown with tabs -->
                <div class="chip-select position-relative" id="priceChip">
                    <button class="chip-trigger" type="button" id="priceDropdownToggle" style="padding: 8px 14px; min-width: 160px; height: 40px;">
                        <span class="chip-label" style="font-size: 11px;">{{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}</span>
                        <span class="chip-value" style="font-size: 13px;">{{ request('min_price') || request('max_price') ? number_format(request('min_price', 0)) . ' - ' . number_format(request('max_price', 0)) : ($locale === 'uz' ? 'Ko\'rsatilmagan' : ($locale === 'ru' ? 'Не указано' : 'Any')) }}</span>
                        <i class="bi bi-chevron-down ms-1" style="font-size: 12px; color: var(--primary-color);"></i>
                    </button>
                    <div class="price-dropdown shadow" id="priceDropdown">
                        <ul class="nav nav-tabs nav-justified" id="priceTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="total-tab" data-bs-toggle="tab" data-bs-target="#total" type="button">{{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sqm-tab" data-bs-toggle="tab" data-bs-target="#sqm" type="button">{{ $locale === 'uz' ? 'Narx (m²)' : ($locale === 'ru' ? 'Цена (м²)' : 'Price (m²)') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="downpayment-tab" data-bs-toggle="tab" data-bs-target="#downpayment" type="button">{{ $locale === 'uz' ? 'Dastlabki to\'lov' : ($locale === 'ru' ? 'Перв. взнос' : 'Down payment') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button">{{ $locale === 'uz' ? 'Oylik to\'lov' : ($locale === 'ru' ? 'Ежем. платеж' : 'Monthly') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content p-3" id="priceTabContent">
                            <div class="tab-pane fade show active" id="total">
                                <label class="form-label">{{ $locale === 'uz' ? 'Jami narx' : ($locale === 'ru' ? 'Общая цена' : 'Total price') }}</label>
                                <div class="input-group mb-2">
                                    <input type="number" name="min_price" class="form-control" style="height: 38px; border-color: #dee2e6; font-size: 13px;" placeholder="{{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }}" value="{{ request('min_price') }}">
                                    <input type="number" name="max_price" class="form-control" style="height: 38px; border-color: #dee2e6; font-size: 13px;" placeholder="{{ $locale === 'uz' ? 'gacha' : ($locale === 'ru' ? 'до' : 'to') }}" value="{{ request('max_price') }}">
                                </div>
                                <div class="quick-values d-flex flex-wrap gap-2">
                                    @foreach([200000000,400000000,600000000,800000000] as $value)
                                    <button type="button" class="chip-option price-quick" data-min="0" data-max="{{ $value }}">{{ number_format($value, 0, '.', ' ') }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="sqm">
                                <div class="alert alert-light border">{{ $locale === 'uz' ? 'Tez orada qo\'shiladi.' : ($locale === 'ru' ? 'Скоро добавим.' : 'Coming soon') }}</div>
                            </div>
                            <div class="tab-pane fade" id="downpayment">
                                <div class="alert alert-light border">{{ $locale === 'uz' ? 'Tez orada qo\'shiladi.' : ($locale === 'ru' ? 'Скоро добавим.' : 'Coming soon') }}</div>
                            </div>
                            <div class="tab-pane fade" id="monthly">
                                <div class="alert alert-light border">{{ $locale === 'uz' ? 'Tez orada qo\'shiladi.' : ($locale === 'ru' ? 'Скоро добавим.' : 'Coming soon') }}</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between px-3 pb-3">
                            <button type="button" class="btn btn-link" id="priceClear" style="color: #6c757d; font-size: 13px;">{{ $locale === 'uz' ? 'Tozalash' : ($locale === 'ru' ? 'Очистить' : 'Clear') }}</button>
                            <button type="button" class="btn btn-primary btn-sm" id="priceApply" style="background: var(--primary-color); border: none; font-size: 13px; padding: 6px 16px;">{{ $locale === 'uz' ? 'Qo\'llash' : ($locale === 'ru' ? 'Применить' : 'Apply') }}</button>
                        </div>
                    </div>
                </div>

                <!-- Location select -->
                <div class="chip-select">
                    <button class="chip-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 8px 14px; min-width: 180px; height: 40px;">
                        <span class="chip-label" style="font-size: 11px;">{{ $locale === 'uz' ? 'Hudud' : ($locale === 'ru' ? 'Регион' : 'Region') }}</span>
                        <span class="chip-value" style="font-size: 13px;">{{ request('city') ?: ($locale === 'uz' ? 'O\'zbekiston bo\'ylab' : ($locale === 'ru' ? 'По всему Узбекистану' : 'All Uzbekistan')) }}</span>
                        <i class="bi bi-chevron-down ms-1" style="font-size: 12px; color: var(--primary-color);"></i>
                    </button>
                    <div class="dropdown-menu chip-dropdown w-100">
                        <input type="text" class="form-control mb-3" style="height: 38px; border-color: #dee2e6; font-size: 13px;" placeholder="{{ $locale === 'uz' ? 'Shahar qidirish...' : ($locale === 'ru' ? 'Поиск города...' : 'Search city...') }}" oninput="filterCities(this.value)">
                        <div class="cities-scroll">
                            <button type="button" class="chip-option d-block w-100 text-start {{ !request('city') ? 'active' : '' }}" data-value="">{{ $locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All') }}</button>
                            @foreach($cities as $city)
                            <button type="button" class="chip-option d-block w-100 text-start {{ request('city') === $city ? 'active' : '' }}" data-value="{{ $city }}">{{ $city }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="city" id="cityInput" value="{{ request('city') }}">
                    </div>
                </div>

                <!-- Search input -->
                <div class="flex-grow-1">
                    <div class="input-group search-group" style="max-width: 400px;">
                        <span class="input-group-text bg-white border-end-0" style="padding: 8px 12px; height: 40px; border-color: #dee2e6;"><i class="bi bi-search" style="font-size: 14px; color: var(--primary-color);"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" style="padding: 8px 12px; font-size: 13px; height: 40px; border-color: #dee2e6;" placeholder="{{ $locale === 'uz' ? 'Novostroika yoki quruvchi nomi' : ($locale === 'ru' ? 'Название ЖК или застройщика' : 'Project or builder name') }}" value="{{ request('search') }}">
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-primary rounded-3 px-3" style="height: 40px; font-size: 13px; border-color: var(--primary-color); color: var(--primary-color); padding: 8px 16px;" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-sliders me-1"></i>{{ $locale === 'uz' ? 'Filter' : ($locale === 'ru' ? 'Фильтр' : 'Filter') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4" style="background: var(--primary-color); border: none; height: 40px; padding: 8px 20px; font-size: 13px;">
                        <i class="bi bi-search me-1"></i>{{ $locale === 'uz' ? 'Qidirish' : ($locale === 'ru' ? 'Найти' : 'Search') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
<!-- ============================ Search Bar End ================================== -->

<!-- ============================ Page Title with Sort Start ================================== -->
<section class="py-3" style="background: #fff; border-bottom: 1px solid #e9ecef;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
                <h4 class="mb-0 fw-bold" style="color: #212529; font-size: 18px;">
                    {{ $developments->total() }} {{ $locale === 'uz' ? 'novostroika' : ($locale === 'ru' ? 'новостройки' : 'new buildings') }}
                </h4>
            </div>
            <div class="col-lg-6 col-md-6 text-end">
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <select class="form-select form-select-sm rounded-3" style="width: auto; font-size: 13px; border: 1px solid #dee2e6;">
                        <option>{{ $locale === 'uz' ? 'Sartirovka: Standart' : ($locale === 'ru' ? 'Сортировка: По умолчанию' : 'Sort: Default') }}</option>
                        <option>{{ $locale === 'uz' ? 'Arzon narx' : ($locale === 'ru' ? 'Дешевле' : 'Price: Low to High') }}</option>
                        <option>{{ $locale === 'uz' ? 'Qimmat narx' : ($locale === 'ru' ? 'Дороже' : 'Price: High to Low') }}</option>
                    </select>
                    <a href="{{ route('map') }}" class="btn btn-primary btn-sm rounded-3" style="background: var(--primary-color); border: none; font-size: 13px; padding: 8px 16px;">
                        <i class="bi bi-geo-alt me-1"></i>{{ $locale === 'uz' ? 'Xarita' : ($locale === 'ru' ? 'Карта' : 'Map') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ============================ Page Title with Sort End ================================== -->

<!-- ============================ Developments Listing Start ================================== -->
<section class="gray-simple py-4">
    <div class="container">
        <div class="row">
            <!-- Main Content - Developments -->
            <div class="col-12">
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stat-card shadow-sm rounded-4 p-3">
                            <p class="text-muted mb-1" style="font-size: 12px;">{{ $locale === 'uz' ? 'm² uchun narx' : ($locale === 'ru' ? 'Цена за м²' : 'Price per m²') }}</p>
                            <h5 class="mb-0 fw-bold" style="color: var(--primary-color); font-size: 18px;">{{ number_format($stats['lowest_price_sqm'] / 1000000, 1) }} {{ $locale === 'uz' ? 'mln so\'m' : ($locale === 'ru' ? 'млн сум' : 'mln UZS') }}</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card shadow-sm rounded-4 p-3">
                            <p class="text-muted mb-1" style="font-size: 12px;">{{ $locale === 'uz' ? 'Eng arzon kvartira' : ($locale === 'ru' ? 'Самая дешевая квартира' : 'Cheapest apartment') }}</p>
                            <h5 class="mb-0 fw-bold" style="color: var(--primary-color); font-size: 18px;">{{ number_format($stats['cheapest_apartment'] / 1000000, 1) }} {{ $locale === 'uz' ? 'mln so\'m' : ($locale === 'ru' ? 'млн сум' : 'mln UZS') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    @forelse($developments as $development)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(9,135,245,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                            <!-- Image -->
                            <div class="position-relative" style="height: 220px; overflow: hidden;">
                                @if($development->featured_image)
                                <img src="{{ asset('storage/' . $development->featured_image) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $development->{'title_' . $locale} ?? $development->title_uz }}">
                                @else
                                <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=600&h=400&fit=crop" class="w-100 h-100" style="object-fit: cover;" alt="Placeholder">
                                @endif
                                
                                <!-- Badges on image -->
                                <div class="position-absolute top-0 start-0 m-2 d-flex flex-column gap-2">
                                    @if(isset($development->installment_available) && $development->installment_available)
                                    <span class="badge bg-primary" style="font-size: 11px; padding: 5px 10px; background: var(--primary-color) !important;">{{ $locale === 'uz' ? 'To\'lov bo\'laklarga' : ($locale === 'ru' ? 'Рассрочка' : 'Installment') }}</span>
                                    @endif
                                    @if(isset($development->discount_percentage) && $development->discount_percentage)
                                    <span class="badge" style="font-size: 11px; padding: 5px 10px; background: #28a745; color: #fff;">-{{ $development->discount_percentage }}%</span>
                                    @endif
                                    @if(isset($development->cashback_percentage) && $development->cashback_percentage)
                                    <span class="badge" style="font-size: 11px; padding: 5px 10px; background: #dc3545; color: #fff;">
                                        {{ $locale === 'uz' ? 'Keshbek' : ($locale === 'ru' ? 'Кешбек' : 'Cashback') }} {{ $development->cashback_percentage }}%
                                    </span>
                                    @endif
                                </div>
                                
                                <!-- Heart icon -->
                                <button class="btn btn-sm position-absolute top-0 end-0 m-2" style="background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-heart" style="color: #6c757d;"></i>
                                </button>
                                
                                <!-- Icons below image -->
                                <div class="position-absolute bottom-0 start-0 m-2 d-flex gap-2">
                                    <button class="btn btn-sm" style="background: rgba(255,255,255,0.9); border: none; border-radius: 8px; padding: 4px 8px;">
                                        <i class="bi bi-file-text" style="font-size: 14px; color: #6c757d;"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background: rgba(255,255,255,0.9); border: none; border-radius: 8px; padding: 4px 8px;">
                                        <i class="bi bi-diagram-3" style="font-size: 14px; color: #6c757d;"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background: rgba(255,255,255,0.9); border: none; border-radius: 8px; padding: 4px 8px;">
                                        <i class="bi bi-building" style="font-size: 14px; color: #6c757d;"></i>
                                    </button>
                                </div>
                                
                                @if($development->featured)
                                <span class="badge position-absolute bottom-0 end-0 m-2" style="background: #28a745; font-size: 11px; padding: 5px 10px;">{{ $locale === 'uz' ? 'Tavsiya etiladi' : ($locale === 'ru' ? 'Рекомендуется' : 'Featured') }}</span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="card-body p-3">
                                <!-- Location -->
                                <div class="mb-2">
                                    <span class="text-muted" style="font-size: 12px;">
                                        <i class="bi bi-geo-alt-fill me-1" style="color: #0987f5 !important;"></i>
                                        {{ $development->region ? $development->region . ', ' : '' }}{{ $development->city }}
                                    </span>
                                </div>

                                <!-- Title -->
                                <h5 class="mb-2 fw-bold" style="color: #212529; font-size: 16px; line-height: 1.3;">
                                    <a href="{{ route('single.development', $development->slug) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='#0987f5';" onmouseout="this.style.color='#212529';">
                                        JK "{{ $development->{'title_' . $locale} ?? $development->title_uz }}"
                                    </a>
                                </h5>

                                <!-- Apartment Details -->
                                <div class="mb-2">
                                    @php
                                        $roomCounts = ($development->properties && $development->properties->count() > 0) ? $development->properties->pluck('bedrooms')->unique()->sort()->filter() : collect();
                                        $propertiesByRoom = [];
                                        if ($development->properties && $development->properties->count() > 0) {
                                            foreach ($development->properties->groupBy('bedrooms') as $bedrooms => $props) {
                                                $propertiesByRoom[$bedrooms] = [
                                                    'area_from' => $props->min('area_from'),
                                                    'area_to' => $props->max('area_to') ?? $props->max('area_from'),
                                                    'price_from' => $props->min('price_from'),
                                                    'currency' => $props->first()->currency ?? 'UZS'
                                                ];
                                            }
                                        }
                                    @endphp
                                    @foreach($propertiesByRoom as $bedrooms => $details)
                                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 12px;">
                                        <span class="text-muted">{{ $bedrooms }} {{ $locale === 'uz' ? 'xona' : ($locale === 'ru' ? 'комн.' : 'room') }}. {{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }} {{ number_format($details['area_from'], 2) }} m²</span>
                                        <span class="fw-semibold" style="color: #212529;">{{ $locale === 'uz' ? 'dan' : ($locale === 'ru' ? 'от' : 'from') }} {{ number_format($details['price_from'] / 1000000, 1) }} {{ $locale === 'uz' ? 'mln' : ($locale === 'ru' ? 'млн' : 'mln') }}</span>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Developer -->
                                <div class="mb-3 d-flex align-items-center">
                                    @php
                                        $builderName = $development->{'developer_name_' . $locale} ?? $development->developer_name_uz ?? ($development->builder ? $development->builder->name : 'Builder');
                                        $builderAvatar = null;
                                        if ($development->builder && $development->builder->avatar) {
                                            $builderAvatar = asset('storage/' . $development->builder->avatar);
                                        } else {
                                            $builderAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($builderName) . '&background=0987f5&color=fff&size=48&bold=true';
                                        }
                                    @endphp
                                    <img src="{{ $builderAvatar }}" class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover; border: 2px solid rgba(9,135,245,0.2);" alt="{{ $builderName }}">
                                    <div>
                                        <div class="fw-semibold" style="font-size: 13px; color: #212529;">
                                            {{ $development->{'developer_name_' . $locale} ?? $development->developer_name_uz ?? 'N/A' }}
                                            @if($development->builder && $development->builder->verified)
                                            <i class="bi bi-patch-check-fill ms-1" style="color: #0987f5 !important; font-size: 14px;"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex gap-2">
                                    <a href="{{ route('single.development', $development->slug) }}" class="btn btn-sm flex-fill rounded-3" style="background: #f8f9fa; border: 1px solid #e9ecef; color: #212529; font-size: 13px; padding: 8px 12px;">
                                        {{ $locale === 'uz' ? 'Konsultatsiya' : ($locale === 'ru' ? 'Консультация' : 'Consultation') }}
                                    </a>
                                    @if($development->builder && $development->builder->phone)
                                    <a href="tel:{{ $development->builder->phone }}" class="btn btn-sm btn-primary flex-fill rounded-3" style="background: #0987f5 !important; border: none; color: #fff; font-size: 13px; padding: 8px 12px;">
                                        <i class="bi bi-telephone me-1"></i>{{ $locale === 'uz' ? 'Qo\'ng\'iroq' : ($locale === 'ru' ? 'Позвонить' : 'Call') }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center rounded-4 py-5">
                            <i class="bi bi-inbox" style="font-size: 48px; color: #0987f5 !important; margin-bottom: 15px;"></i>
                            <h5 class="mb-2">{{ $locale === 'uz' ? 'Novostroika topilmadi' : ($locale === 'ru' ? 'Новостройки не найдены' : 'No developments found') }}</h5>
                            <p class="text-muted mb-0">{{ $locale === 'uz' ? 'Filtrlarni o\'zgartirib qayta urinib ko\'ring.' : ($locale === 'ru' ? 'Попробуйте изменить фильтры.' : 'Try changing the filters.') }}</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($developments->hasPages())
                <div class="row mt-4">
                    <div class="col-12">
                        {{ $developments->links('vendor.pagination.custom') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
<!-- ============================ Developments Listing End ================================== -->

<!-- Filter Modal -->
<div class="modal fade modern-filter-modal" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold">{{ $locale === 'uz' ? 'Filter' : ($locale === 'ru' ? 'Фильтр' : 'Filter') }}</h5>
                    <p class="text-muted mb-0" style="font-size: 13px;">{{ $locale === 'uz' ? 'Sizga mos variantlarni topamiz' : ($locale === 'ru' ? 'Подберем лучшие варианты' : 'Find best matches') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('page.developments') }}" method="GET">
                <input type="hidden" name="locale" value="{{ $locale }}">
                <div class="modal-body px-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="filter-label">{{ $locale === 'uz' ? 'Joylashuv' : ($locale === 'ru' ? 'Расположение' : 'Location') }}</label>
                            <input type="text" class="form-control mb-2" style="height: 40px; border-color: #dee2e6; font-size: 13px;" placeholder="{{ $locale === 'uz' ? 'Hudud' : ($locale === 'ru' ? 'Регион' : 'Region') }}">
                            <input type="text" class="form-control" style="height: 40px; border-color: #dee2e6; font-size: 13px;" placeholder="{{ $locale === 'uz' ? 'Shahar, metro yoki quruvchi' : ($locale === 'ru' ? 'Город, метро, ЖК' : 'City, metro, builder') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">{{ $locale === 'uz' ? 'Maydon' : ($locale === 'ru' ? 'Площадь' : 'Area') }}</label>
                            <div class="row g-2">
                                <div class="col"><input type="number" class="form-control" style="height: 40px; border-color: #dee2e6; font-size: 13px;" placeholder="от"></div>
                                <div class="col"><input type="number" class="form-control" style="height: 40px; border-color: #dee2e6; font-size: 13px;" placeholder="до"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="filter-label">{{ $locale === 'uz' ? 'Uy turi' : ($locale === 'ru' ? 'Тип жилья' : 'Property type') }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Новостройки', 'Квартира', 'С ремонтом', 'Без ремонта'] as $type)
                                <button type="button" class="chip-option">{{ $type }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="filter-label">{{ $locale === 'uz' ? 'Poytaxtgacha vaqt' : ($locale === 'ru' ? 'Пешком до метро' : 'Walk to metro') }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['5 мин', '10 мин', '15 мин', '20 мин'] as $time)
                                <button type="button" class="chip-option">{{ $time }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="filter-label">{{ $locale === 'uz' ? 'Uy klassi' : ($locale === 'ru' ? 'Класс жилья' : 'Class') }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Бизнес+', 'Бизнес', 'Комфорт', 'Эконом', 'Премиум'] as $class)
                                <button type="button" class="chip-option">{{ $class }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="filter-label">{{ $locale === 'uz' ? 'Qurilish holati' : ($locale === 'ru' ? 'Статус строительства' : 'Construction status') }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Готовый', 'Строится'] as $status)
                                <button type="button" class="chip-option">{{ $status }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="filter-label">{{ $locale === 'uz' ? 'To\'lov turi' : ($locale === 'ru' ? 'Тип платежа' : 'Payment type') }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Ипотека', 'Рассрочка'] as $pay)
                                <button type="button" class="chip-option">{{ $pay }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="filter-label">{{ $locale === 'uz' ? 'Chegirmalar va bonuslar' : ($locale === 'ru' ? 'Скидки и бонусы' : 'Bonuses') }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Со скидкой', 'Uysot Bonus 🎁'] as $bonus)
                                <button type="button" class="chip-option">{{ $bonus }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <a href="{{ route('page.developments', ['locale' => $locale]) }}" class="btn btn-link text-muted">{{ $locale === 'uz' ? 'Tozalash' : ($locale === 'ru' ? 'Очистить' : 'Clear') }}</a>
                    <button type="submit" class="btn btn-primary rounded-3 px-4" style="background: #0987f5 !important; border: none;">
                        {{ $locale === 'uz' ? 'Natijalarni ko\'rish' : ($locale === 'ru' ? 'Показать результаты' : 'Show results') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #0987f5 !important;
    --primary-hover: #0774d4 !important;
}
.modern-filter-bar .chip-select{position:relative}
.chip-trigger{background:#fff;border:1px solid #dee2e6;border-radius:12px;display:flex;align-items:center;gap:6px;justify-content:space-between;cursor:pointer;transition:all 0.2s;height:40px}
.chip-trigger:hover{border-color:#0987f5 !important;box-shadow:0 2px 8px rgba(9,135,245,0.15) !important}
.chip-trigger .chip-label{font-size:11px;color:#6c757d;text-transform:uppercase;font-weight:600}
.chip-trigger .chip-value{font-size:13px;color:#212529;font-weight:600}
.chip-dropdown{min-width:220px;padding:16px;border-radius:16px;border:none;box-shadow:0 20px 40px rgba(9,135,245,.15) !important}
.chip-option{border:1px solid #dee2e6;background:#fff;border-radius:999px;padding:6px 16px;font-size:13px;color:#212529;font-weight:500;cursor:pointer;transition:all 0.2s}
.chip-option.active,.chip-option:hover{background:#0987f5 !important;border-color:#0987f5 !important;color:#fff !important}
.price-dropdown{position:absolute;top:105%;left:0;background:#fff;border-radius:16px;border:1px solid #dee2e6;width:400px;display:none;z-index:20;box-shadow:0 10px 30px rgba(9,135,245,0.15) !important}
.price-dropdown .nav-link{font-size:13px;font-weight:600;color:#6c757d;border-bottom:2px solid transparent}
.price-dropdown .nav-link.active{color:#0987f5 !important;border-bottom-color:#0987f5 !important}
.cities-scroll{max-height:220px;overflow-y:auto}
.search-group .form-control{border-radius:0 12px 12px 0;height:40px}
.search-group .input-group-text{border-radius:12px 0 0 12px;height:40px}
.search-group .form-control:focus{border-color:#0987f5 !important;box-shadow:0 0 0 0.2rem rgba(9,135,245,0.25) !important}
.stat-card{background:#fff;border:1px solid #e4e9f4}
.modern-filter-modal .chip-option{border-radius:12px;padding:8px 14px;border:1px solid #dee2e6;background:#fff;cursor:pointer;transition:all 0.2s}
.modern-filter-modal .chip-option:hover,.modern-filter-modal .chip-option.active{background:#0987f5 !important;border-color:#0987f5 !important;color:#fff !important}
.filter-label{font-size:12px;color:#8c93a4;text-transform:uppercase;font-weight:600;margin-bottom:8px;display:block}
@media(max-width:768px){
    .chip-trigger{width:100%}
    .chip-select,.input-group{width:100%}
    .price-dropdown{width:100%;left:0}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const priceToggle = document.getElementById('priceDropdownToggle');
    const priceDropdown = document.getElementById('priceDropdown');
    const priceApply = document.getElementById('priceApply');
    const priceClear = document.getElementById('priceClear');
    const minPriceInput = document.querySelector('input[name="min_price"]');
    const maxPriceInput = document.querySelector('input[name="max_price"]');

    if (priceToggle) {
        priceToggle.addEventListener('click', function (e) {
            e.preventDefault();
            priceDropdown.style.display = priceDropdown.style.display === 'block' ? 'none' : 'block';
        });
    }

    priceApply && priceApply.addEventListener('click', function () {
        priceDropdown.style.display = 'none';
    });

    priceClear && priceClear.addEventListener('click', function () {
        if (minPriceInput) minPriceInput.value = '';
        if (maxPriceInput) maxPriceInput.value = '';
    });

    document.addEventListener('click', function (event) {
        if (!priceDropdown.contains(event.target) && !priceToggle.contains(event.target)) {
            priceDropdown.style.display = 'none';
        }
    });

    document.querySelectorAll('.price-quick').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (minPriceInput) minPriceInput.value = btn.dataset.min;
            if (maxPriceInput) maxPriceInput.value = btn.dataset.max;
        });
    });

    // Chips for bedrooms and city
    document.querySelectorAll('.chip-dropdown .chip-option').forEach(function (chip) {
        chip.addEventListener('click', function () {
            const value = this.dataset.value ?? this.getAttribute('data-value');
            const parent = this.closest('.chip-dropdown');
            parent.querySelectorAll('.chip-option').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            if (parent.contains(document.getElementById('bedroomsInput'))) {
                document.getElementById('bedroomsInput').value = value;
            }
            if (parent.contains(document.getElementById('cityInput'))) {
                document.getElementById('cityInput').value = value;
            }
        });
    });

    window.filterCities = function (term) {
        document.querySelectorAll('.cities-scroll .chip-option').forEach(function (btn) {
            if (!btn.dataset.value) return;
            const match = btn.textContent.toLowerCase().includes(term.toLowerCase());
            btn.style.display = match ? 'block' : 'none';
        });
    };
});
</script>

@endsection
