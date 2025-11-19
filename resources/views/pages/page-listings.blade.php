@extends('layouts.page')
@section('content')

@php
    $locale = $locale ?? app()->getLocale();
    $cities = $cities ?? collect();
    $propertyTypes = $propertyTypes ?? ['apartment', 'house', 'villa', 'land', 'commercial', 'office'];
    $listingTypes = $listingTypes ?? ['sale', 'rent'];
    $bedrooms = $bedrooms ?? collect();
    $minPrice = $minPrice ?? 0;
    $maxPrice = $maxPrice ?? 10000000;
    $properties = $properties ?? collect();
@endphp

<!-- ============================ Page Title Start================================== -->
<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">

                <h2 class="ipt-title">{{ $locale === 'uz' ? 'Barcha uy-joylar' : ($locale === 'ru' ? 'Вся недвижимость' : 'All Properties') }}</h2>
                <span class="ipn-subtitle">{{ $locale === 'uz' ? 'Uy-joylar ro\'yxati' : ($locale === 'ru' ? 'Список недвижимости' : 'Property Listings') }}</span>

            </div>
        </div>
    </div>
</div>
<!-- ============================ Page Title End ================================== -->

<!-- ============================ All Property ================================== -->
<section class="gray-simple">

    <div class="container">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="filter_search_opt">
                    <a href="javascript:void(0);" class="btn btn-dark full-width mb-4" onclick="openFilterSearch()">
									<span class="svg-icon text-light svg-icon-2hx me-2">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor"/>
										</svg>
									</span>{{ $locale === 'uz' ? 'Filterni ochish' : ($locale === 'ru' ? 'Открыть фильтр' : 'Open Filter') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- property Sidebar -->
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="simple-sidebar sm-sidebar" id="filter_search"  style="left:0;">

                    <div class="search-sidebar_header">
                        <h4 class="ssh_heading">{{ $locale === 'uz' ? 'Filterni yopish' : ($locale === 'ru' ? 'Закрыть фильтр' : 'Close Filter') }}</h4>
                        <button onclick="closeFilterSearch()" class="w3-bar-item w3-button w3-large"><i class="fa-regular fa-circle-xmark fs-5 text-muted-2"></i></button>
                    </div>

                    <!-- Find New Property -->
                    <div class="sidebar-widgets">

                        <div class="search-inner p-0">
                            <form action="{{ route('listings') }}" method="GET" id="filterForm">
                                <input type="hidden" name="locale" value="{{ $locale }}">
                            <div class="filter-search-box">
                                <div class="form-group">
                                    <div class="position-relative">
                                        <input type="text" name="search" class="form-control rounded-3 ps-5" placeholder="{{ $locale === 'uz' ? 'Qidirish...' : ($locale === 'ru' ? 'Поиск...' : 'Search...') }}" value="{{ request('search') }}">
                                        <div class="position-absolute top-50 start-0 translate-middle-y ms-2">
														<span class="svg-icon text-main svg-icon-2hx">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
																<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"/>
															</svg>
														</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="position-relative d-flex flex-xl-row flex-column align-items-center">
                                <div class="verifyd-prt-block flex-fill full-width my-1 me-1">
                                    <div class="d-flex align-items-center justify-content-center justify-content-between border rounded-3 px-2 py-3">
                                        <div class="eliok-cliops d-flex align-items-center">
														<span class="svg-icon text-success svg-icon-2hx">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
																<path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="currentColor"/>
															</svg>
														</span><span class="text-muted-2 fw-medium ms-1">{{ $locale === 'uz' ? 'Tasdiqlangan' : ($locale === 'ru' ? 'Проверено' : 'Verified') }}</span>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" name="verified" id="filter_verified" value="1" {{ request('verified') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="filter_verified"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="filter_wraps">

                                <!-- City Filter -->
                                @if($cities->count() > 0)
                                <div class="single_search_boxed">
                                    <div class="widget-boxed-header">
                                        <h4>
                                            <a href="#filter_city" data-bs-toggle="collapse" aria-expanded="false" role="button" class="collapsed">
                                                {{ $locale === 'uz' ? 'Shahar' : ($locale === 'ru' ? 'Город' : 'City') }}
                                                <span class="selected" id="city_selected">{{ request('city') ?: ($locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All')) }}</span>
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
                                <div class="single_search_boxed">
                                    <div class="widget-boxed-header">
                                        <h4>
                                            <a href="#fptype" data-bs-toggle="collapse" aria-expanded="false" role="button" class="collapsed">
                                                {{ $locale === 'uz' ? 'Uy turi' : ($locale === 'ru' ? 'Тип недвижимости' : 'Property Type') }}
                                                <span class="selected" id="type_selected">
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
                                    <div class="widget-boxed-body collapse" id="fptype" data-parent="#fptype">
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

                                <!-- Bedrooms Filter -->
                                @if($bedrooms->count() > 0)
                                <div class="single_search_boxed">
                                    <div class="widget-boxed-header">
                                        <h4>
                                            <a href="#fbedrooms" data-bs-toggle="collapse" aria-expanded="false" role="button" class="collapsed">
                                                {{ $locale === 'uz' ? 'Xonalar' : ($locale === 'ru' ? 'Комнаты' : 'Bedrooms') }}
                                                <span class="selected" id="bedrooms_selected">
                                                    {{ request('bedrooms') ? request('bedrooms') . ' ' . ($locale === 'uz' ? 'Xona' : ($locale === 'ru' ? 'Комнат' : 'Beds')) : ($locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All')) }}
                                                </span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse" id="fbedrooms" data-parent="#fbedrooms">
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
                                <div class="single_search_boxed">
                                    <div class="widget-boxed-header">
                                        <h4>
                                            <a href="#fprice" data-bs-toggle="collapse" aria-expanded="false" role="button" class="collapsed">
                                                {{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}
                                                <span class="selected" id="price_selected">
                                                    @if(request('min_price') || request('max_price'))
                                                        {{ number_format(request('min_price', $minPrice), 0) }} - {{ number_format(request('max_price', $maxPrice), 0) }}
                                                    @else
                                                        {{ $locale === 'uz' ? 'Barcha' : ($locale === 'ru' ? 'Все' : 'All') }}
                                                    @endif
                                                </span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse" id="fprice" data-parent="#fprice">
                                        <div class="side-list no-border">
                                            <div class="single_filter_card">
                                                <div class="card-body pt-0">
                                                    <div class="form-group">
                                                        <label>{{ $locale === 'uz' ? 'Min narx' : ($locale === 'ru' ? 'Мин. цена' : 'Min Price') }}</label>
                                                        <input type="number" name="min_price" class="form-control" value="{{ request('min_price', $minPrice) }}" min="{{ $minPrice }}" max="{{ $maxPrice }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ $locale === 'uz' ? 'Max narx' : ($locale === 'ru' ? 'Макс. цена' : 'Max Price') }}</label>
                                                        <input type="number" name="max_price" class="form-control" value="{{ request('max_price', $maxPrice) }}" min="{{ $minPrice }}" max="{{ $maxPrice }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Listing Type Filter -->
                                <div class="single_search_boxed">
                                    <div class="widget-boxed-header">
                                        <h4>
                                            <a href="#filter_listing" data-bs-toggle="collapse" aria-expanded="false" role="button" class="collapsed">
                                                {{ $locale === 'uz' ? 'Holat' : ($locale === 'ru' ? 'Статус' : 'Status') }}
                                                <span class="selected" id="listing_selected">
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

                            </div>

                            <div class="form-group filter_button">
                                <button type="submit" class="btn btn btn-main rounded full-width">
                                    {{ $totalResults ?? $properties->total() }} {{ $locale === 'uz' ? 'Natija' : ($locale === 'ru' ? 'Результатов' : 'Results') }}
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Sidebar End -->

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // City filter selected text update
                    const cityInputs = document.querySelectorAll('input[name="city"]');
                    cityInputs.forEach(input => {
                        input.addEventListener('change', function() {
                            const citySelected = document.getElementById('city_selected');
                            if (this.value) {
                                citySelected.textContent = this.value;
                            } else {
                                citySelected.textContent = '{{ $locale === "uz" ? "Barcha" : ($locale === "ru" ? "Все" : "All") }}';
                            }
                        });
                    });

                    // Property type filter selected text update
                    const typeInputs = document.querySelectorAll('input[name="property_type"]');
                    typeInputs.forEach(input => {
                        input.addEventListener('change', function() {
                            const typeSelected = document.getElementById('type_selected');
                            const label = this.nextElementSibling.textContent;
                            typeSelected.textContent = label;
                        });
                    });

                    // Listing type filter selected text update
                    const listingInputs = document.querySelectorAll('input[name="listing_type"]');
                    listingInputs.forEach(input => {
                        input.addEventListener('change', function() {
                            const listingSelected = document.getElementById('listing_selected');
                            const label = this.nextElementSibling.textContent;
                            listingSelected.textContent = label;
                        });
                    });

                    // Bedrooms filter selected text update
                    const bedroomsInputs = document.querySelectorAll('input[name="bedrooms"]');
                    bedroomsInputs.forEach(input => {
                        input.addEventListener('change', function() {
                            const bedroomsSelected = document.getElementById('bedrooms_selected');
                            if (this.value) {
                                bedroomsSelected.textContent = this.value + ' {{ $locale === "uz" ? "Xona" : ($locale === "ru" ? "Комнат" : "Beds") }}';
                            } else {
                                bedroomsSelected.textContent = '{{ $locale === "uz" ? "Barcha" : ($locale === "ru" ? "Все" : "All") }}';
                            }
                        });
                    });

                    // Price filter selected text update
                    const minPriceInput = document.querySelector('input[name="min_price"]');
                    const maxPriceInput = document.querySelector('input[name="max_price"]');
                    const priceSelected = document.getElementById('price_selected');

                    function updatePriceSelected() {
                        const minPrice = minPriceInput.value;
                        const maxPrice = maxPriceInput.value;
                        if (minPrice || maxPrice) {
                            const min = minPrice ? parseInt(minPrice).toLocaleString() : '{{ number_format($minPrice ?? 0, 0) }}';
                            const max = maxPrice ? parseInt(maxPrice).toLocaleString() : '{{ number_format($maxPrice ?? 0, 0) }}';
                            priceSelected.textContent = min + ' - ' + max;
                        } else {
                            priceSelected.textContent = '{{ $locale === "uz" ? "Barcha" : ($locale === "ru" ? "Все" : "All") }}';
                        }
                    }

                    if (minPriceInput) minPriceInput.addEventListener('input', updatePriceSelected);
                    if (maxPriceInput) maxPriceInput.addEventListener('input', updatePriceSelected);
                });
                </script>

            </div>

            <div class="col-lg-8 col-md-12 col-sm-12">


                <div class="row justify-content-center g-4">

                    @forelse($properties as $property)
                    <!-- Single Property -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="property-listing card border-0 rounded-3">

                            <div class="listing-img-wrapper p-3">
                                <div class="list-img-slide position-relative">
                                    @if($property->user && $property->user->verified)
                                    <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                        <div class="label verified-listing d-inline-flex align-items-center justify-content-center">
														<span class="svg-icon text-light svg-icon-2hx me-1">
															<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
																<path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="currentColor"></path>
															</svg>
														</span>{{ $locale === 'uz' ? 'Tasdiqlangan' : ($locale === 'ru' ? 'Проверено' : 'Verified') }}
                                        </div>
                                    </div>
                                    @endif
                                    <div class="click rounded-3 overflow-hidden mb-0">
                                        @if($property->featured_image)
                                        <div><a href="{{ route('listing.show', $property->slug) }}"><img src="{{ asset('storage/' . $property->featured_image) }}" class="img-fluid" alt="{{ $property->translate($locale)->title }}" /></a></div>
                                        @endif
                                        @if($property->images && is_array($property->images))
                                            @foreach(array_slice($property->images, 0, 2) as $image)
                                            <div><a href="{{ route('listing.show', $property->slug) }}"><img src="{{ asset('storage/' . $image) }}" class="img-fluid" alt="{{ $property->translate($locale)->title }}" /></a></div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="listing-caption-wrapper px-3">
                                <div class="listing-detail-wrapper">
                                    <div class="listing-short-detail-wrap">
                                        <div class="listing-short-detail">
                                            <div class="d-flex align-items-center">
                                                <span class="label {{ $property->listing_type === 'sale' ? 'for-sale' : 'for-rent' }} prt-type me-2">
                                                    {{ $locale === 'uz' ? ($property->listing_type === 'sale' ? 'Sotish' : 'Ijaraga') : ($locale === 'ru' ? ($property->listing_type === 'sale' ? 'Продажа' : 'Аренда') : ($property->listing_type === 'sale' ? 'For Sale' : 'For Rent')) }}
                                                </span>
                                                <span class="label property-type property-cats">
                                                    @php
                                                        $types = [
                                                            'uz' => ['apartment' => 'Kvartira', 'house' => 'Uy', 'villa' => 'Villa', 'land' => 'Yer', 'commercial' => 'Savdo', 'office' => 'Ofis'],
                                                            'ru' => ['apartment' => 'Квартира', 'house' => 'Дом', 'villa' => 'Вилла', 'land' => 'Земля', 'commercial' => 'Коммерческая', 'office' => 'Офис'],
                                                            'en' => ['apartment' => 'Apartment', 'house' => 'House', 'villa' => 'Villa', 'land' => 'Land', 'commercial' => 'Commercial', 'office' => 'Office'],
                                                        ];
                                                    @endphp
                                                    {{ $types[$locale][$property->property_type] ?? $property->property_type }}
                                                </span>
                                            </div>
                                            <h4 class="listing-name fw-medium fs-5 mb-1">
                                                <a href="{{ route('listing.show', $property->slug) }}">
                                                    {{ $property->translate($locale)->title }}
                                                </a>
                                            </h4>
                                            <div class="prt-location text-muted-2">
															<span class="svg-icon svg-icon-2hx">
																<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
																	<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
																</svg>
															</span>
                                                {{ $property->translate($locale)->address ?? ($property->city . ($property->region ? ', ' . $property->region : '')) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="price-features-wrapper">
                                    <div class="list-fx-features d-flex align-items-center justify-content-between">
                                        @if($property->bedrooms)
                                        <div class="listing-card d-flex align-items-center">
                                            <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-bed fs-xs"></i></div><span class="text-muted-2 fs-sm">{{ $property->bedrooms }} {{ $locale === 'uz' ? 'Xona' : ($locale === 'ru' ? 'Комнат' : 'Beds') }}</span>
                                        </div>
                                        @endif
                                        @if($property->bathrooms)
                                        <div class="listing-card d-flex align-items-center">
                                            <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-bath fs-xs"></i></div><span class="text-muted-2 fs-sm">{{ $property->bathrooms }} {{ $locale === 'uz' ? 'Hammom' : ($locale === 'ru' ? 'Ванных' : 'Baths') }}</span>
                                        </div>
                                        @endif
                                        @if($property->area)
                                        <div class="listing-card d-flex align-items-center">
                                            <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-clone fs-xs"></i></div><span class="text-muted-2 fs-sm">{{ number_format($property->area, 0) }} {{ $property->area_unit ?? 'm²' }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                    <div class="listing-short-detail-flex">
                                        <h6 class="listing-card-info-price m-0">{{ number_format($property->price, 0) }} {{ $property->currency }}</h6>
                                    </div>
                                    <div class="footer-flex">
                                        <a href="{{ route('listing.show', $property->slug) }}" class="prt-view">
														<span class="svg-icon text-main svg-icon-2hx">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z" fill="currentColor"/>
																<path opacity="0.3" d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z" fill="currentColor"/>
															</svg>
														</span>
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <!-- End Single Property -->
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h5>{{ $locale === 'uz' ? 'Uy-joy topilmadi' : ($locale === 'ru' ? 'Недвижимость не найдена' : 'No properties found') }}</h5>
                        </div>
                    </div>
                    @endforelse

                </div>

                <!-- Pagination -->
                @if($properties->hasPages())
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        {{ $properties->links('vendor.pagination.custom') }}
                    </div>
                </div>
                @endif

            </div>

        </div>
    </div>
</section>
<!-- ============================ All Property End ================================== -->

@endsection
