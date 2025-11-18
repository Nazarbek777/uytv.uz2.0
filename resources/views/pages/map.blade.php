@extends('layouts.page')
@section('content')

@php
    $locale = $locale ?? app()->getLocale();
    $cities = $cities ?? collect();
    $propertyTypes = $propertyTypes ?? ['apartment', 'house', 'villa', 'land', 'commercial', 'office'];
    $listingTypes = $listingTypes ?? ['sale', 'rent'];
    $bedrooms = $bedrooms ?? collect();
    $propertiesForMap = $propertiesForMap ?? [];
    $totalResults = $totalResults ?? 0;
    $minPrice = $minPrice ?? 0;
    $maxPrice = $maxPrice ?? 10000000;
@endphp

<!-- ============================ Map Page Start ================================== -->
<section class="gray-simple" style="padding: 0;">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Filter Sidebar -->
            <div class="col-lg-4 col-md-12">
                <div class="simple-sidebar" id="mapFilterSidebar" style="height: 100vh; overflow-y: auto; background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%); padding: 20px; box-shadow: 2px 0 20px rgba(0,0,0,0.1);">
                    <div class="search-sidebar_header mb-4" style="padding-bottom: 15px; border-bottom: 2px solid #e9ecef;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="map-icon-wrapper me-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, #2d55a4 0%, #1e3d6f 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(45, 85, 164, 0.3);">
                                <i class="bi bi-geo-alt-fill text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold" style="font-size: 22px; color: #2d55a4;">{{ $locale === 'uz' ? 'Xarita' : ($locale === 'ru' ? 'Карта' : 'Map') }}</h3>
                                <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">{{ $locale === 'uz' ? 'Uy-joylarni xaritada ko\'ring va qidiring' : ($locale === 'ru' ? 'Просмотрите и найдите недвижимость на карте' : 'View and search properties on the map') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('map') }}" method="GET" id="mapFilterForm">
                        <input type="hidden" name="locale" value="{{ $locale }}">
                        <!-- Search -->
                        <div class="filter-search-box mb-4">
                            <div class="form-group">
                                <div class="position-relative">
                                    <input type="text" name="search" class="form-control rounded-4 ps-5 py-3 border-0 shadow-sm" style="font-size: 14px; background: #fff; transition: all 0.3s ease;" placeholder="{{ $locale === 'uz' ? 'Qidirish...' : ($locale === 'ru' ? 'Поиск...' : 'Search...') }}" value="{{ request('search') }}" onfocus="this.style.boxShadow='0 4px 15px rgba(45, 85, 164, 0.2)'; this.style.borderColor='#2d55a4';" onblur="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'; this.style.borderColor='#e9ecef';">
                                    <div class="position-absolute top-50 start-0 translate-middle-y ms-3">
                                        <span class="svg-icon text-main" style="width: 20px; height: 20px;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Verified Filter -->
                        <div class="position-relative d-flex flex-xl-row flex-column align-items-center mb-4">
                            <div class="verifyd-prt-block flex-fill full-width my-1 me-1">
                                <div class="d-flex align-items-center justify-content-between border-0 rounded-4 px-3 py-3 shadow-sm" style="background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%); transition: all 0.3s ease;">
                                    <div class="eliok-cliops d-flex align-items-center">
                                        <div class="verified-icon-wrapper me-2" style="width: 36px; height: 36px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);">
                                            <span class="svg-icon text-white" style="width: 18px; height: 18px;">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
                                                    <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <span class="text-dark fw-semibold" style="font-size: 14px;">{{ $locale === 'uz' ? 'Tasdiqlangan' : ($locale === 'ru' ? 'Проверено' : 'Verified') }}</span>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="verified" id="filter_verified" value="1" {{ request('verified') ? 'checked' : '' }} style="width: 48px; height: 26px; cursor: pointer;">
                                        <label class="form-check-label" for="filter_verified"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="filter_wraps">
                            <!-- City Filter -->
                            @if($cities->count() > 0)
                            <div class="single_search_boxed mb-2">
                                <div class="widget-boxed-header">
                                    <h4 style="font-size: 14px; margin: 0;">
                                        <a href="#filter_city" data-bs-toggle="collapse" aria-expanded="false" role="button" class="collapsed" style="padding: 6px 0;">
                                            {{ $locale === 'uz' ? 'Shahar' : ($locale === 'ru' ? 'Город' : 'City') }}
                                            <span class="selected" id="city_selected" style="font-size: 12px; padding: 2px 8px;">{{ request('city') ?: ($locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All')) }}</span>
                                        </a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="filter_city" data-parent="#filter_city">
                                    <div class="side-list no-border">
                                        <div class="single_filter_card">
                                            <div class="card-body pt-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list">
                                                        <li class="form-check">
                                                            <input id="city_all" class="form-check-input" name="city" type="radio" value="" {{ !request('city') ? 'checked' : '' }}>
                                                            <label for="city_all" class="form-check-label">{{ $locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All') }}</label>
                                                        </li>
                                                        @foreach($cities as $city)
                                                        <li class="form-check">
                                                            <input id="city_{{ $loop->index }}" class="form-check-input" name="city" type="radio" value="{{ $city }}" {{ request('city') === $city ? 'checked' : '' }}>
                                                            <label for="city_{{ $loop->index }}" class="form-check-label">{{ $city }}</label>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Property Type Filter -->
                            <div class="single_search_boxed mb-2">
                                <div class="widget-boxed-header">
                                    <h4 style="font-size: 14px; margin: 0;">
                                        <a href="#filter_type" data-bs-toggle="collapse" aria-expanded="false" role="button" class="collapsed" style="padding: 6px 0;">
                                            {{ $locale === 'uz' ? 'Uy turi' : ($locale === 'ru' ? 'Тип недвижимости' : 'Property Type') }}
                                            <span class="selected" id="type_selected" style="font-size: 12px; padding: 2px 8px;">
                                                @php
                                                    $types = [
                                                        'uz' => ['apartment' => 'Kvartira', 'house' => 'Uy', 'villa' => 'Villa', 'land' => 'Yer', 'commercial' => 'Savdo', 'office' => 'Ofis'],
                                                        'ru' => ['apartment' => 'Квартира', 'house' => 'Дом', 'villa' => 'Вилла', 'land' => 'Земля', 'commercial' => 'Коммерческая', 'office' => 'Офис'],
                                                        'en' => ['apartment' => 'Apartment', 'house' => 'House', 'villa' => 'Villa', 'land' => 'Land', 'commercial' => 'Commercial', 'office' => 'Office'],
                                                    ];
                                                    $selectedType = request('property_type');
                                                @endphp
                                                {{ $selectedType ? ($types[$locale][$selectedType] ?? $selectedType) : ($locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All')) }}
                                            </span>
                                        </a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="filter_type" data-parent="#filter_type">
                                    <div class="side-list no-border">
                                        <div class="single_filter_card">
                                            <div class="card-body pt-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list">
                                                        <li class="form-check">
                                                            <input id="type_all" class="form-check-input" name="property_type" type="radio" value="" {{ !request('property_type') ? 'checked' : '' }}>
                                                            <label for="type_all" class="form-check-label">{{ $locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All') }}</label>
                                                        </li>
                                                        @foreach($propertyTypes as $type)
                                                        <li class="form-check">
                                                            <input id="type_{{ $type }}" class="form-check-input" name="property_type" type="radio" value="{{ $type }}" {{ request('property_type') === $type ? 'checked' : '' }}>
                                                            <label for="type_{{ $type }}" class="form-check-label">{{ $types[$locale][$type] ?? $type }}</label>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Listing Type Filter -->
                            <div class="single_search_boxed mb-2">
                                <div class="widget-boxed-header">
                                    <h4 style="font-size: 14px; margin: 0;">
                                        <a href="#filter_listing" data-bs-toggle="collapse" aria-expanded="false" role="button" class="collapsed" style="padding: 6px 0;">
                                            {{ $locale === 'uz' ? 'Holat' : ($locale === 'ru' ? 'Статус' : 'Status') }}
                                            <span class="selected" id="listing_selected" style="font-size: 12px; padding: 2px 8px;">
                                                @php
                                                    $listingLabels = [
                                                        'uz' => ['sale' => 'Sotish', 'rent' => 'Ijaraga'],
                                                        'ru' => ['sale' => 'Продажа', 'rent' => 'Аренда'],
                                                        'en' => ['sale' => 'For Sale', 'rent' => 'For Rent'],
                                                    ];
                                                    $selectedListing = request('listing_type');
                                                @endphp
                                                {{ $selectedListing ? ($listingLabels[$locale][$selectedListing] ?? $selectedListing) : ($locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All')) }}
                                            </span>
                                        </a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="filter_listing" data-parent="#filter_listing">
                                    <div class="side-list no-border">
                                        <div class="single_filter_card">
                                            <div class="card-body pt-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list">
                                                        <li class="form-check">
                                                            <input id="listing_all" class="form-check-input" name="listing_type" type="radio" value="" {{ !request('listing_type') ? 'checked' : '' }}>
                                                            <label for="listing_all" class="form-check-label">{{ $locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All') }}</label>
                                                        </li>
                                                        @foreach($listingTypes as $listingType)
                                                        <li class="form-check">
                                                            <input id="listing_{{ $listingType }}" class="form-check-input" name="listing_type" type="radio" value="{{ $listingType }}" {{ request('listing_type') === $listingType ? 'checked' : '' }}>
                                                            <label for="listing_{{ $listingType }}" class="form-check-label">{{ $listingLabels[$locale][$listingType] ?? $listingType }}</label>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bedrooms Filter -->
                            @if($bedrooms->count() > 0)
                            <div class="single_search_boxed mb-2">
                                <div class="widget-boxed-header">
                                    <h4 style="font-size: 14px; margin: 0;">
                                        <a href="#filter_bedrooms" data-bs-toggle="collapse" aria-expanded="false" role="button" class="collapsed" style="padding: 6px 0;">
                                            {{ $locale === 'uz' ? 'Xonalar' : ($locale === 'ru' ? 'Комнаты' : 'Bedrooms') }}
                                            <span class="selected" id="bedrooms_selected" style="font-size: 12px; padding: 2px 8px;">
                                                {{ request('bedrooms') ? request('bedrooms') . ' ' . ($locale === 'uz' ? 'Xona' : ($locale === 'ru' ? 'Комнат' : 'Beds')) : ($locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All')) }}
                                            </span>
                                        </a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="filter_bedrooms" data-parent="#filter_bedrooms">
                                    <div class="side-list no-border">
                                        <div class="single_filter_card">
                                            <div class="card-body pt-0">
                                                <div class="inner_widget_link">
                                                    <ul class="no-ul-list filter-list">
                                                        <li class="form-check">
                                                            <input id="bedrooms_all" class="form-check-input" name="bedrooms" type="radio" value="" {{ !request('bedrooms') ? 'checked' : '' }}>
                                                            <label for="bedrooms_all" class="form-check-label">{{ $locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All') }}</label>
                                                        </li>
                                                        @foreach($bedrooms as $bedroom)
                                                        <li class="form-check">
                                                            <input id="bedrooms_{{ $bedroom }}" class="form-check-input" name="bedrooms" type="radio" value="{{ $bedroom }}" {{ request('bedrooms') == $bedroom ? 'checked' : '' }}>
                                                            <label for="bedrooms_{{ $bedroom }}" class="form-check-label">{{ $bedroom }} {{ $locale === 'uz' ? 'Xona' : ($locale === 'ru' ? 'Комнат' : 'Beds') }}</label>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Price Range Filter -->
                            @if($maxPrice && $minPrice)
                            <div class="single_search_boxed mb-2">
                                <div class="widget-boxed-header">
                                    <h4 style="font-size: 14px; margin: 0;">
                                        <a href="#filter_price" data-bs-toggle="collapse" aria-expanded="false" role="button" class="collapsed" style="padding: 6px 0;">
                                            {{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}
                                            <span class="selected" id="price_selected" style="font-size: 12px; padding: 2px 8px;">
                                                @if(request('min_price') || request('max_price'))
                                                    {{ number_format(request('min_price', $minPrice), 0) }} - {{ number_format(request('max_price', $maxPrice), 0) }}
                                                @else
                                                    {{ $locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All') }}
                                                @endif
                                            </span>
                                        </a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="filter_price" data-parent="#filter_price">
                                    <div class="side-list no-border">
                                        <div class="single_filter_card">
                                            <div class="card-body pt-0">
                                                <div class="form-group mb-2">
                                                    <label class="fw-medium mb-1" style="font-size: 12px;">{{ $locale === 'uz' ? 'Min narx' : ($locale === 'ru' ? 'Мин. цена' : 'Min Price') }}</label>
                                                    <input type="number" name="min_price" class="form-control form-control-sm" style="font-size: 12px; padding: 4px 8px;" value="{{ request('min_price', $minPrice) }}" min="{{ $minPrice }}" max="{{ $maxPrice }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="fw-medium mb-1" style="font-size: 12px;">{{ $locale === 'uz' ? 'Max narx' : ($locale === 'ru' ? 'Макс. цена' : 'Max Price') }}</label>
                                                    <input type="number" name="max_price" class="form-control form-control-sm" style="font-size: 12px; padding: 4px 8px;" value="{{ request('max_price', $maxPrice) }}" min="{{ $minPrice }}" max="{{ $maxPrice }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="form-group filter_button">
                            <button type="submit" class="btn btn-main rounded-4 full-width mb-3 py-3 fw-semibold shadow-sm" style="font-size: 15px; background: linear-gradient(135deg, #2d55a4 0%, #1e3d6f 100%); border: none; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(45, 85, 164, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                                <i class="bi bi-search me-2"></i>{{ $locale === 'uz' ? 'Qidirish' : ($locale === 'ru' ? 'Поиск' : 'Search') }}
                            </button>
                            <a href="{{ route('map') }}" class="btn btn-outline-secondary rounded-4 full-width py-3 fw-semibold" style="font-size: 15px; border-width: 2px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#2d55a4'; this.style.color='#2d55a4';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#dee2e6'; this.style.color='#6c757d';">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>{{ $locale === 'uz' ? 'Tozalash' : ($locale === 'ru' ? 'Очистить' : 'Clear') }}
                            </a>
                        </div>

                        <!-- Results Count -->
                        <div class="mt-4 p-4 text-center rounded-4 shadow-sm" style="background: linear-gradient(135deg, #2d55a4 0%, #1e3d6f 100%); position: relative; overflow: hidden;">
                            <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                            <div style="position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                            <div style="position: relative; z-index: 1;">
                                <p class="mb-1 fw-bold text-white" style="font-size: 32px; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">{{ $totalResults }}</p>
                                <p class="mb-0 text-white" style="font-size: 13px; opacity: 0.95;">{{ $locale === 'uz' ? 'ta uy-joy topildi' : ($locale === 'ru' ? 'объектов найдено' : 'properties found') }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Map Container -->
            <div class="col-lg-8 col-md-12 position-relative">
                <!-- Mobile Overlay -->
                <div class="map-overlay" id="mapOverlay"></div>
                
                <div id="map" style="height: 100vh; width: 100%;"></div>
                
                <!-- Filter Toggle Button (Mobile Only) -->
                <button id="filterToggleBtn" class="btn btn-main position-absolute d-lg-none" style="top: 20px; left: 20px; z-index: 1000; border-radius: 12px; padding: 10px 18px; background: linear-gradient(135deg, #2d55a4 0%, #1e3d6f 100%); border: 2px solid white; box-shadow: 0 4px 15px rgba(45, 85, 164, 0.4); font-size: 13px; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(45, 85, 164, 0.5)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(45, 85, 164, 0.4)';">
                    <i class="bi bi-funnel-fill me-2" style="font-size: 16px;"></i>
                    <span>{{ $locale === 'uz' ? 'Filter' : ($locale === 'ru' ? 'Фильтр' : 'Filter') }}</span>
                </button>
                
                <!-- My Location Button -->
                <button id="myLocationBtn" class="btn btn-main position-absolute" style="top: 20px; right: 20px; z-index: 1000; border-radius: 50%; width: 50px; height: 50px; padding: 0; background: linear-gradient(135deg, #ff4444 0%, #cc0000 100%); border: 3px solid white; box-shadow: 0 4px 15px rgba(255, 68, 68, 0.4); transition: all 0.3s ease;" title="{{ $locale === 'uz' ? 'Manzilimni ko\'rsatish' : ($locale === 'ru' ? 'Показать мое местоположение' : 'Show my location') }}" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 6px 20px rgba(255, 68, 68, 0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 15px rgba(255, 68, 68, 0.4)';">
                    <i class="bi bi-geo-alt-fill text-white" style="font-size: 22px;"></i>
                </button>
            </div>
        </div>
    </div>
</section>
<!-- ============================ Map Page End ================================== -->

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centered on Uzbekistan
    var map = L.map('map', {
        zoomControl: true,
        scrollWheelZoom: true
    }).setView([41.2995, 69.2401], 6);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // Properties data
    var properties = @json($propertiesForMap);

    // Create custom icons
    function createPropertyIcon(listingType, propertyType) {
        var color = listingType === 'sale' ? '#28a745' : '#007bff';
        var gradientColor = listingType === 'sale' ? 'linear-gradient(135deg, #28a745 0%, #20c997 100%)' : 'linear-gradient(135deg, #007bff 0%, #0056b3 100%)';
        var iconHtml = `
            <div style="
                background: ${gradientColor};
                width: 48px;
                height: 48px;
                border-radius: 50%;
                border: 4px solid white;
                box-shadow: 0 4px 15px rgba(0,0,0,0.4), 0 0 0 2px rgba(255,255,255,0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                transition: all 0.3s ease;
                cursor: pointer;
            " onmouseover="this.style.transform='scale(1.15)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.5), 0 0 0 3px rgba(255,255,255,0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.4), 0 0 0 2px rgba(255,255,255,0.3)';">
                <i class="bi bi-house-fill text-white" style="font-size: 22px; text-shadow: 0 2px 5px rgba(0,0,0,0.3);"></i>
                <div style="position: absolute; bottom: -2px; right: -2px; width: 16px; height: 16px; background: white; border-radius: 50%; border: 2px solid ${color}; display: flex; align-items: center; justify-content: center;">
                    <div style="width: 8px; height: 8px; background: ${color}; border-radius: 50%;"></div>
                </div>
            </div>
        `;
        
        return L.divIcon({
            className: 'custom-property-marker',
            html: iconHtml,
            iconSize: [48, 48],
            iconAnchor: [24, 24],
            popupAnchor: [0, -24]
        });
    }

    // Create user location icon
    var userLocationIcon = L.divIcon({
        className: 'user-location-marker',
        html: `
            <div style="
                background-color: #ff4444;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                border: 4px solid white;
                box-shadow: 0 3px 10px rgba(0,0,0,0.3);
                animation: pulse 2s infinite;
            "></div>
            <style>
                @keyframes pulse {
                    0% { transform: scale(1); opacity: 1; }
                    50% { transform: scale(1.5); opacity: 0.7; }
                    100% { transform: scale(1); opacity: 1; }
                }
            </style>
        `,
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    var userLocationMarker = null;

    // Create markers for each property
    var markers = [];
    var bounds = L.latLngBounds([]);

    properties.forEach(function(property) {
        if (property.latitude && property.longitude) {
            var icon = createPropertyIcon(property.listing_type, property.property_type);

            // Create marker
            var marker = L.marker([property.latitude, property.longitude], { icon: icon })
                .addTo(map)
                .bindPopup(`
                    <div style="min-width: 300px; max-width: 350px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        <div style="position: relative; margin-bottom: 15px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <img src="${property.featured_image}" style="width: 100%; height: 200px; object-fit: cover; display: block;" alt="${property.title}">
                            <div style="position: absolute; top: 12px; right: 12px; padding: 6px 12px; background: ${property.listing_type === 'sale' ? 'linear-gradient(135deg, #28a745 0%, #20c997 100%)' : 'linear-gradient(135deg, #007bff 0%, #0056b3 100%)'}; color: white; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 3px 10px rgba(0,0,0,0.3);">
                                ${property.listing_type === 'sale' ? ('{{ $locale === "uz" ? "Sotish" : ($locale === "ru" ? "Продажа" : "For Sale") }}') : ('{{ $locale === "uz" ? "Ijaraga" : ($locale === "ru" ? "Аренда" : "For Rent") }}')}
                            </div>
                        </div>
                        <h5 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: #1e3d6f; line-height: 1.3; letter-spacing: -0.3px;">${property.title}</h5>
                        <p style="margin: 0 0 15px 0; color: #6c757d; font-size: 13px; line-height: 1.5; display: flex; align-items: center; gap: 6px;">
                            <i class="bi bi-geo-alt-fill" style="color: #2d55a4; font-size: 14px;"></i> 
                            <span>${property.address}</span>
                        </p>
                        <div style="display: flex; gap: 20px; margin-bottom: 15px; padding: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-radius: 10px; border: 1px solid #e9ecef;">
                            ${property.bedrooms ? '<div style="display: flex; align-items: center; gap: 6px;"><i class="bi bi-door-open" style="color: #2d55a4; font-size: 16px;"></i><span style="font-size: 14px; color: #495057; font-weight: 600;">' + property.bedrooms + '</span></div>' : ''}
                            ${property.bathrooms ? '<div style="display: flex; align-items: center; gap: 6px;"><i class="bi bi-droplet" style="color: #2d55a4; font-size: 16px;"></i><span style="font-size: 14px; color: #495057; font-weight: 600;">' + property.bathrooms + '</span></div>' : ''}
                            ${property.area ? '<div style="display: flex; align-items: center; gap: 6px;"><i class="bi bi-arrows-angle-expand" style="color: #2d55a4; font-size: 16px;"></i><span style="font-size: 14px; color: #495057; font-weight: 600;">' + property.area + ' ' + property.area_unit + '</span></div>' : ''}
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; padding: 15px; background: linear-gradient(135deg, #2d55a4 0%, #1e3d6f 100%); border-radius: 10px; box-shadow: 0 4px 12px rgba(45, 85, 164, 0.3);">
                            <span style="font-size: 24px; font-weight: 700; color: white; text-shadow: 0 2px 5px rgba(0,0,0,0.2);">${property.price} ${property.currency}</span>
                        </div>
                        <a href="${property.url}" style="text-decoration: none; display: block; text-align: center; padding: 12px; background: linear-gradient(135deg, #2d55a4 0%, #1e3d6f 100%); color: white; border-radius: 10px; font-weight: 600; font-size: 14px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(45, 85, 164, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(45, 85, 164, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(45, 85, 164, 0.3)';">
                            <i class="bi bi-arrow-right-circle me-2"></i>{{ $locale === 'uz' ? 'Batafsil' : ($locale === 'ru' ? 'Подробнее' : 'Details') }}
                        </a>
                    </div>
                `, {
                    maxWidth: 350,
                    className: 'custom-popup',
                    closeButton: true
                });

            markers.push(marker);
            bounds.extend([property.latitude, property.longitude]);
        }
    });

    // Fit map to show all markers
    if (markers.length > 0) {
        map.fitBounds(bounds, { padding: [100, 100] });
    }

    // My Location Button Click Handler
    document.getElementById('myLocationBtn').addEventListener('click', function() {
        if (navigator.geolocation) {
            var btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split fs-4"></i>';
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    
                    // Remove existing user location marker
                    if (userLocationMarker) {
                        map.removeLayer(userLocationMarker);
                    }
                    
                    // Add user location marker
                    userLocationMarker = L.marker([lat, lng], { icon: userLocationIcon })
                        .addTo(map)
                        .bindPopup('{{ $locale === "uz" ? "Sizning joylashuvingiz" : ($locale === "ru" ? "Ваше местоположение" : "Your Location") }}')
                        .openPopup();
                    
                    // Center map on user location
                    map.setView([lat, lng], 13);
                    
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-geo-alt-fill fs-4"></i>';
                },
                function(error) {
                    alert('{{ $locale === "uz" ? "Joylashuvni olishda xatolik yuz berdi. Iltimos, brauzer sozlamalarida joylashuv ruxsatini bering." : ($locale === "ru" ? "Ошибка при получении местоположения. Пожалуйста, разрешите доступ к местоположению в настройках браузера." : "Error getting location. Please allow location access in browser settings.") }}');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-geo-alt-fill fs-4"></i>';
                }
            );
        } else {
            alert('{{ $locale === "uz" ? "Brauzeringiz joylashuvni qo\'llab-quvvatlamaydi." : ($locale === "ru" ? "Ваш браузер не поддерживает геолокацию." : "Your browser does not support geolocation.") }}');
        }
    });

    // Filter form auto-submit on change
    var filterInputs = document.querySelectorAll('#mapFilterForm input[type="radio"], #mapFilterForm input[type="number"]');
    filterInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            document.getElementById('mapFilterForm').submit();
        });
    });

    // Update selected text for filters
    document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var name = this.name;
            var selected = document.querySelector('input[name="' + name + '"]:checked');
            if (selected) {
                var selectedText = selected.nextElementSibling ? selected.nextElementSibling.textContent : '';
                var selectedSpan = document.getElementById(name + '_selected');
                if (selectedSpan) {
                    selectedSpan.textContent = selectedText || (selected.value ? selected.value : '{{ $locale === "uz" ? "Barcha" : ($locale === "ru" ? "Все" : "All") }}');
                }
            }
        });
    });

    // Mobile Filter Toggle
    var filterToggleBtn = document.getElementById('filterToggleBtn');
    var filterSidebar = document.getElementById('mapFilterSidebar');
    var mapOverlay = document.getElementById('mapOverlay');
    
    function toggleFilter() {
        if (window.innerWidth < 992) {
            filterSidebar.classList.toggle('show');
            if (mapOverlay) {
                mapOverlay.classList.toggle('show');
            }
        }
    }
    
    function closeFilter() {
        if (window.innerWidth < 992) {
            filterSidebar.classList.remove('show');
            if (mapOverlay) {
                mapOverlay.classList.remove('show');
            }
        }
    }
    
    if (filterToggleBtn && filterSidebar) {
        filterToggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleFilter();
        });
        
        // Close filter when clicking on overlay
        if (mapOverlay) {
            mapOverlay.addEventListener('click', function() {
                closeFilter();
            });
        }
        
        // Close filter when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && filterSidebar.classList.contains('show')) {
                if (!filterSidebar.contains(e.target) && !filterToggleBtn.contains(e.target) && !mapOverlay.contains(e.target)) {
                    closeFilter();
                }
            }
        });
    }
    
    // Close filter on form submit (mobile)
    var mapFilterForm = document.getElementById('mapFilterForm');
    if (mapFilterForm) {
        mapFilterForm.addEventListener('submit', function() {
            if (window.innerWidth < 992) {
                setTimeout(function() {
                    closeFilter();
                }, 100);
            }
        });
    }
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            closeFilter();
        }
    });
});
</script>

<style>
.custom-property-marker {
    background: transparent !important;
    border: none !important;
}

.user-location-marker {
    background: transparent !important;
    border: none !important;
}

#map {
    z-index: 1;
}

.leaflet-popup-content-wrapper {
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    padding: 0;
    border: 2px solid rgba(255,255,255,0.8);
    overflow: hidden;
}

.leaflet-popup-content {
    margin: 0;
    padding: 0;
}

.custom-popup .leaflet-popup-content-wrapper {
    padding: 20px;
    background: #ffffff;
}

.leaflet-popup-tip {
    background: white;
    border: 2px solid rgba(45, 85, 164, 0.1);
}

.leaflet-popup-close-button {
    width: 30px;
    height: 30px;
    background: rgba(45, 85, 164, 0.1);
    border-radius: 50%;
    color: #2d55a4;
    font-size: 18px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.leaflet-popup-close-button:hover {
    background: #2d55a4;
    color: white;
    transform: rotate(90deg);
}

.simple-sidebar {
    box-shadow: 2px 0 15px rgba(0,0,0,0.08);
}

.single_search_boxed {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 15px;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.widget-boxed-header h4 a {
    color: #2d55a4;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.widget-boxed-header h4 a:hover {
    color: #1e3d6f;
}

.selected {
    color: #2d55a4;
    font-weight: 600;
    font-size: 14px;
}

.filter-list {
    padding: 0;
    margin: 0;
}

.filter-list li {
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.filter-list li:last-child {
    border-bottom: none;
}

.form-check-label {
    cursor: pointer;
    font-size: 14px;
    color: #555;
}

.form-check-input:checked ~ .form-check-label {
    color: #2d55a4;
    font-weight: 600;
}

#myLocationBtn:hover {
    transform: scale(1.1);
    transition: transform 0.2s;
}

#myLocationBtn:active {
    transform: scale(0.95);
}

/* Mobile Responsive Styles */
@media (max-width: 991px) {
    .simple-sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 320px;
        max-width: 85vw;
        height: 100vh;
        z-index: 1050;
        transition: left 0.3s ease;
        box-shadow: 2px 0 20px rgba(0,0,0,0.2);
    }
    
    .simple-sidebar.show {
        left: 0;
    }
    
    .map-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.5);
        z-index: 1049;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    
    .map-overlay.show {
        opacity: 1;
        pointer-events: all;
    }
    
    #map {
        height: 100vh;
        width: 100%;
    }
    
    #filterToggleBtn {
        display: flex !important;
        align-items: center;
    }
    
    #myLocationBtn {
        top: 20px;
        right: 20px;
    }
    
    .col-lg-8 {
        padding: 0;
    }
    
    .col-lg-4 {
        padding: 0;
    }
}

@media (min-width: 992px) {
    #filterToggleBtn {
        display: none !important;
    }
    
    .simple-sidebar {
        position: relative !important;
        left: auto !important;
    }
}

/* Filter Design Improvements */
.single_search_boxed {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 10px;
    background: #fff;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.single_search_boxed:hover {
    border-color: #2d55a4;
    box-shadow: 0 4px 15px rgba(45, 85, 164, 0.15);
    transform: translateY(-2px);
}

.widget-boxed-header {
    margin-bottom: 0;
}

.widget-boxed-header h4 {
    margin: 0;
    font-size: 14px;
}

.widget-boxed-header h4 a {
    color: #2d55a4;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    font-size: 14px;
}

.widget-boxed-header h4 a:hover {
    color: #1e3d6f;
}

.widget-boxed-header h4 a[aria-expanded="true"] {
    color: #1e3d6f;
}

.selected {
    color: #2d55a4;
    font-weight: 600;
    font-size: 12px;
    background: #f0f4ff;
    padding: 2px 8px;
    border-radius: 4px;
}

.filter-list {
    padding: 8px 0 0 0;
    margin: 0;
    list-style: none;
}

.filter-list li {
    padding: 6px 0;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
}

.filter-list li:hover {
    background: #f8f9fa;
    padding-left: 5px;
}

.filter-list li:last-child {
    border-bottom: none;
}

.form-check {
    margin: 0;
    padding: 0;
}

.form-check-label {
    cursor: pointer;
    font-size: 13px;
    color: #555;
    margin-left: 6px;
    transition: color 0.2s;
}

.form-check-input:checked ~ .form-check-label {
    color: #2d55a4;
    font-weight: 600;
}

.form-check-input {
    cursor: pointer;
    margin-top: 0.3em;
}

.verifyd-prt-block {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    background: #fff;
    transition: all 0.3s ease;
}

.verifyd-prt-block:hover {
    border-color: #2d55a4;
    box-shadow: 0 2px 8px rgba(45, 85, 164, 0.1);
}

.filter_button {
    margin-top: 15px;
}

.filter_button .btn {
    transition: all 0.3s ease;
}

.filter_button .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(45, 85, 164, 0.3);
}

/* Smooth Scrollbar */
.simple-sidebar::-webkit-scrollbar {
    width: 6px;
}

.simple-sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.simple-sidebar::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #2d55a4 0%, #1e3d6f 100%);
    border-radius: 10px;
}

.simple-sidebar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #1e3d6f 0%, #2d55a4 100%);
}

/* Map Container Enhancements */
#map {
    position: relative;
}

#map::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, rgba(45, 85, 164, 0.02) 0%, transparent 100%);
    pointer-events: none;
    z-index: 100;
}

/* Filter Box Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.single_search_boxed {
    animation: slideIn 0.3s ease-out;
}

/* Results Count Animation */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.mt-4.p-4.text-center.rounded-4.shadow-sm {
    animation: pulse 3s ease-in-out infinite;
}

/* Marker Hover Effect */
.custom-property-marker {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.custom-property-marker:hover {
    z-index: 1000 !important;
}

/* Enhanced Popup */
.leaflet-popup-content-wrapper {
    animation: popupSlideIn 0.3s ease-out;
}

@keyframes popupSlideIn {
    from {
        opacity: 0;
        transform: translateY(-10px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Loading State */
.map-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    background: white;
    padding: 20px 40px;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    gap: 15px;
}

.map-loading .spinner {
    width: 30px;
    height: 30px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #2d55a4;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

@endsection
