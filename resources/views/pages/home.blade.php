@extends('layouts.page')
@section('content')

@php
    $locale = $locale ?? app()->getLocale();
    $featuredProperties = $featuredProperties ?? collect();
    $latestProperties = $latestProperties ?? collect();
    $featuredDevelopments = $featuredDevelopments ?? collect();
    $latestDevelopments = $latestDevelopments ?? collect();
    $cities = $cities ?? collect();
    
    // Separate properties by listing type
    $saleProperties = $latestProperties->where('listing_type', 'sale')->take(6);
    $rentProperties = $latestProperties->where('listing_type', 'rent')->take(6);
    
    // Get developments for dropdown
    $allDevelopments = \App\Models\Development::where('status', 'published')
        ->orderBy('title_' . $locale, 'asc')
        ->get();
@endphp

<style>
    .hero-banner-ideal {
        background: linear-gradient(135deg, rgba(9, 135, 245, 0.85) 0%, rgba(7, 116, 212, 0.85) 100%), url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1920&h=1080&fit=crop') center/cover no-repeat;
        padding: 100px 0 80px;
        min-height: 500px;
        display: flex;
        align-items: center;
    }
    .hero-search-ideal {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    .search-tabs {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 20px;
    }
    .search-tabs button {
        padding: 12px 24px;
        border: none;
        background: transparent;
        font-weight: 600;
        color: #666;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    .search-tabs button.active {
        color: #0987f5 !important;
        border-bottom-color: #0987f5 !important;
    }
    .search-fields-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }
    .search-field {
        position: relative;
    }
    .search-field select,
    .search-field input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
    }
    .search-field select:focus,
    .search-field input:focus {
        outline: none;
        border-color: #0987f5 !important;
        box-shadow: 0 0 0 3px rgba(9, 135, 245, 0.1);
    }
    .search-actions {
        display: flex;
        gap: 10px;
    }
    .btn-search-main {
        background: #0987f5 !important;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        flex: 1;
    }
    .btn-search-map {
        background: white;
        color: #0987f5;
        border: 2px solid #0987f5;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .development-card {
        cursor: pointer;
    }
    .development-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(9, 135, 245, 0.2) !important;
    }
    .development-card img {
        transition: transform 0.3s;
    }
    .development-card:hover img {
        transform: scale(1.05);
    }
</style>

<!-- ============================ Hero Banner Ideal Start ================================== -->
<div class="hero-banner-ideal">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-md-12">
                <div class="text-center mb-4">
                    <h1 class="text-white mb-3" style="font-size: 48px; font-weight: 700; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                        {{ $locale === 'uz' ? 'Orzuingizdagi uy toping' : ($locale === 'ru' ? 'Найди дом своей мечты прямо сейчас' : 'Find Your Dream Home Right Now') }}
                    </h1>
                </div>
                
                <form action="{{ route('listings') }}" method="GET" class="hero-search-ideal">
                    <div class="search-tabs">
                        <button type="button" class="search-tab-btn active" data-tab="sale">
                            {{ $locale === 'uz' ? 'Sotuv' : ($locale === 'ru' ? 'Купить' : 'Buy') }}
                        </button>
                        <button type="button" class="search-tab-btn" data-tab="rent">
                            {{ $locale === 'uz' ? 'Ijara' : ($locale === 'ru' ? 'Снять' : 'Rent') }}
                        </button>
                    </div>
                    
                    <input type="hidden" name="listing_type" id="listing_type_input" value="sale">
                    
                    <div class="search-fields-grid">
                        <div class="search-field">
                            <select name="development_id" class="form-control">
                                <option value="">{{ $locale === 'uz' ? 'ЖК (Yashil kompleks)' : ($locale === 'ru' ? 'ЖК (жилые комплексы)' : 'Residential Complex') }}</option>
                                @foreach($allDevelopments as $dev)
                                    <option value="{{ $dev->id }}">{{ $dev->{'title_' . $locale} ?? $dev->title_uz }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="search-field">
                            <select name="bedrooms" class="form-control">
                                <option value="">{{ $locale === 'uz' ? 'Xonalar soni' : ($locale === 'ru' ? 'Количество комнат' : 'Number of Rooms') }}</option>
                                <option value="1">1 {{ $locale === 'uz' ? 'xona' : ($locale === 'ru' ? 'комната' : 'room') }}</option>
                                <option value="2">2 {{ $locale === 'uz' ? 'xona' : ($locale === 'ru' ? 'комнаты' : 'rooms') }}</option>
                                <option value="3">3 {{ $locale === 'uz' ? 'xona' : ($locale === 'ru' ? 'комнаты' : 'rooms') }}</option>
                                <option value="4">4+ {{ $locale === 'uz' ? 'xona' : ($locale === 'ru' ? 'комнаты' : 'rooms') }}</option>
                            </select>
                        </div>
                        
                        <div class="search-field">
                            <select name="price_range" class="form-control">
                                <option value="">{{ $locale === 'uz' ? 'Narx' : ($locale === 'ru' ? 'Цена' : 'Price') }}</option>
                                <option value="0-50000000">{{ $locale === 'uz' ? '50 mln gacha' : ($locale === 'ru' ? 'До 50 млн' : 'Up to 50M') }}</option>
                                <option value="50000000-100000000">{{ $locale === 'uz' ? '50-100 mln' : ($locale === 'ru' ? '50-100 млн' : '50-100M') }}</option>
                                <option value="100000000-200000000">{{ $locale === 'uz' ? '100-200 mln' : ($locale === 'ru' ? '100-200 млн' : '100-200M') }}</option>
                                <option value="200000000-">{{ $locale === 'uz' ? '200 mln+' : ($locale === 'ru' ? 'От 200 млн' : '200M+') }}</option>
                            </select>
                        </div>
                        
                        <div class="search-field">
                            <select name="city" class="form-control">
                                <option value="">{{ $locale === 'uz' ? 'Barcha shaharlar' : ($locale === 'ru' ? 'По всему Узбекистану' : 'All Cities') }}</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}">{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="search-field" style="position: relative;">
                            <input type="text" name="search" id="ai-search-input" placeholder="{{ $locale === 'uz' ? 'Masalan: 3 xonali uy Toshkentda, 100 mln gacha' : ($locale === 'ru' ? 'Например: 3-комнатная квартира в Ташкенте до 100 млн' : 'E.g.: 3 bedroom apartment in Tashkent up to 100M') }}" class="form-control">
                            <button type="button" class="btn btn-sm position-absolute" id="ai-search-toggle" style="right: 5px; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 5px 10px;" title="{{ $locale === 'uz' ? 'AI qidiruv' : ($locale === 'ru' ? 'AI поиск' : 'AI Search') }}">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="#0987f5"/>
                                </svg>
                            </button>
                            <input type="hidden" name="ai_search" id="ai_search_hidden" value="false">
                        </div>
                    </div>
                    
                    <div class="search-actions">
                        <a href="{{ route('map') }}" class="btn-search-map">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5Z" fill="currentColor"/>
                            </svg>
                            {{ $locale === 'uz' ? 'Xaritada' : ($locale === 'ru' ? 'На карте' : 'On Map') }}
                        </a>
                        <button type="submit" class="btn-search-main" id="search-submit-btn">
                            {{ $locale === 'uz' ? 'Qidirish' : ($locale === 'ru' ? 'Поиск' : 'Search') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ============================ Hero Banner Ideal End ================================== -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.search-tab-btn');
    const listingTypeInput = document.getElementById('listing_type_input');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            listingTypeInput.value = this.dataset.tab;
        });
    });
    
    // AI Search Toggle
    const aiToggle = document.getElementById('ai-search-toggle');
    const aiInput = document.getElementById('ai-search-input');
    const aiHidden = document.getElementById('ai_search_hidden');
    let aiSearchActive = false;
    
    if (aiToggle) {
        aiToggle.addEventListener('click', function() {
            aiSearchActive = !aiSearchActive;
            aiHidden.value = aiSearchActive ? 'true' : 'false';
            
            if (aiSearchActive) {
                this.style.background = '#0987f5';
                this.querySelector('svg path').setAttribute('fill', 'white');
                aiInput.placeholder = '{{ $locale === "uz" ? "AI qidiruv: Masalan, 3 xonali uy Toshkentda, 100 mln gacha" : ($locale === "ru" ? "AI поиск: Например, 3-комнатная квартира в Ташкенте до 100 млн" : "AI Search: E.g., 3 bedroom apartment in Tashkent up to 100M") }}';
            } else {
                this.style.background = 'transparent';
                this.querySelector('svg path').setAttribute('fill', '#0987f5');
                aiInput.placeholder = '{{ $locale === "uz" ? "Qidirish..." : ($locale === "ru" ? "Самые дешевые квартиры" : "Search...") }}';
            }
        });
    }
    
    // AI Search on Enter
    if (aiInput) {
        aiInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && aiSearchActive) {
                e.preventDefault();
                performAiSearch();
            }
        });
    }
    
    // AI Search function
    function performAiSearch() {
        const query = aiInput.value.trim();
        if (!query) return;
        
        const submitBtn = document.getElementById('search-submit-btn');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = '{{ $locale === "uz" ? "Qidirilmoqda..." : ($locale === "ru" ? "Поиск..." : "Searching...") }}';
        
        fetch('{{ route("ai.search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                query: query,
                locale: '{{ $locale }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.properties.length > 0) {
                // Redirect to listings page with AI search results
                window.location.href = '{{ route("listings") }}?ai_search=true&search=' + encodeURIComponent(query);
            } else {
                alert('{{ $locale === "uz" ? "Natija topilmadi" : ($locale === "ru" ? "Результаты не найдены" : "No results found") }}');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('AI Search error:', error);
            alert('{{ $locale === "uz" ? "Xatolik yuz berdi" : ($locale === "ru" ? "Произошла ошибка" : "An error occurred") }}');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    }
});
</script>


<!-- ============================ Popular Promotions Start ================================== -->
<section class="py-5 pb-0">
    <div class="container">

        <div class="row justify-content-center g-3">

            <!-- Single Promotion -->
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="card rounded-2 p-3 border">
                    <div class="mortgage-caption mb-3">
                        <h5 class="fs-5 mb-0">{{ $locale === 'uz' ? 'Ipoteka' : ($locale === 'ru' ? 'Ипотека' : 'Mortgage') }}</h5>
                        <p>{{ $locale === 'uz' ? 'Maxsus taklif' : ($locale === 'ru' ? 'Специальное предложение' : 'Special offer') }}</p>
                    </div>
                    <div class="mortgage-footer d-flex align-items-center justify-content-between">
                        <div class="promotion-rates">
                            <span class="text-md text-muted">{{ $locale === 'uz' ? 'Foiz' : ($locale === 'ru' ? 'Ставка' : 'Rates') }}</span>
                            <h6 class="fs-5 fw-medium m-0">4.42%</h6>
                        </div>
                        <div class="promotion-bank">
                            <img src="https://shreethemes.net/resido-2.3/resido/assets/img/bank-1.png" class="img-fluid w-20" alt="Bank 1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Promotion -->
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="card rounded-2 p-3 border">
                    <div class="mortgage-caption mb-3">
                        <h5 class="fs-5 mb-0">{{ $locale === 'uz' ? 'Ipoteka' : ($locale === 'ru' ? 'Ипотека' : 'Mortgage') }}</h5>
                        <p>{{ $locale === 'uz' ? 'Maxsus taklif' : ($locale === 'ru' ? 'Специальное предложение' : 'Special offer') }}</p>
                    </div>
                    <div class="mortgage-footer d-flex align-items-center justify-content-between">
                        <div class="promotion-rates">
                            <span class="text-md text-muted">{{ $locale === 'uz' ? 'Foiz' : ($locale === 'ru' ? 'Ставка' : 'Rates') }}</span>
                            <h6 class="fs-5 fw-medium m-0">4.50%</h6>
                        </div>
                        <div class="promotion-bank">
                            <img src="https://shreethemes.net/resido-2.3/resido/assets/img/bank-2.png" class="img-fluid w-20" alt="Bank 2">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Promotion -->
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="card rounded-2 p-3 border">
                    <div class="mortgage-caption mb-3">
                        <h5 class="fs-5 mb-0">{{ $locale === 'uz' ? 'Ipoteka' : ($locale === 'ru' ? 'Ипотека' : 'Mortgage') }}</h5>
                        <p>{{ $locale === 'uz' ? 'Maxsus taklif' : ($locale === 'ru' ? 'Специальное предложение' : 'Special offer') }}</p>
                    </div>
                    <div class="mortgage-footer d-flex align-items-center justify-content-between">
                        <div class="promotion-rates">
                            <span class="text-md text-muted">{{ $locale === 'uz' ? 'Foiz' : ($locale === 'ru' ? 'Ставка' : 'Rates') }}</span>
                            <h6 class="fs-5 fw-medium m-0">7.12%</h6>
                        </div>
                        <div class="promotion-bank">
                            <img src="https://shreethemes.net/resido-2.3/resido/assets/img/bank-3.png" class="img-fluid w-20" alt="Bank 3">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Promotion -->
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="card rounded-2 p-3 border">
                    <div class="mortgage-caption mb-3">
                        <h5 class="fs-5 mb-0">{{ $locale === 'uz' ? 'Ipoteka' : ($locale === 'ru' ? 'Ипотека' : 'Mortgage') }}</h5>
                        <p>{{ $locale === 'uz' ? 'Maxsus taklif' : ($locale === 'ru' ? 'Специальное предложение' : 'Special offer') }}</p>
                    </div>
                    <div class="mortgage-footer d-flex align-items-center justify-content-between">
                        <div class="promotion-rates">
                            <span class="text-md text-muted">{{ $locale === 'uz' ? 'Chegirma' : ($locale === 'ru' ? 'Скидка' : 'Special Discount') }}</span>
                            <h6 class="fs-5 fw-medium m-0">{{ $locale === 'uz' ? '7% gacha' : ($locale === 'ru' ? 'До 7%' : 'Up to 7%') }}</h6>
                        </div>
                        <div class="promotion-bank">
                            <img src="https://shreethemes.net/resido-2.3/resido/assets/img/bank-4.png" class="img-fluid w-20" alt="Bank 4">
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
<!-- ============================ Popular Promotions End ================================== -->

<!-- ============================ Popular New Buildings Start ================================== -->
@if($featuredDevelopments->count() > 0 || $latestDevelopments->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-7 col-md-10 text-center">
                <div class="sec-heading center">
                    <h2 class="mb-2" style="font-size: 36px; font-weight: 700; color: #333;">{{ $locale === 'uz' ? 'Mashhur yangi binolar' : ($locale === 'ru' ? 'Популярные новостройки' : 'Popular New Buildings') }}</h2>
                    <p class="text-muted">{{ $locale === 'uz' ? 'Eng yaxshi yangi loyihalar' : ($locale === 'ru' ? 'Лучшие новые проекты' : 'Best new developments') }}</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
                $displayDevelopments = $featuredDevelopments->count() > 0 ? $featuredDevelopments->take(3) : $latestDevelopments->take(3);
            @endphp
            
            @foreach($displayDevelopments as $development)
            <div class="col-xl-4 col-lg-4 col-md-6">
                <a href="{{ route('single.development', ['locale' => $locale, 'slug' => $development->slug]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 development-card" style="transition: transform 0.3s, box-shadow 0.3s;">
                    <div class="position-relative" style="height: 250px; overflow: hidden;">
                        @php
                            $devImage = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop';
                            if ($development->featured_image) {
                                $devImage = asset('storage/' . $development->featured_image);
                            } elseif ($development->images && is_array($development->images) && count($development->images) > 0) {
                                $firstImage = is_array($development->images[0]) ? ($development->images[0]['path'] ?? $development->images[0]) : $development->images[0];
                                $devImage = asset('storage/' . $firstImage);
                            }
                            
                            $devTitle = $development->{'title_' . $locale} ?? $development->title_uz ?? 'Development';
                            $devLocation = ($development->city ?? '') . ($development->region ? ', ' . $development->region : '');
                            
                            $builderName = $development->{'developer_name_' . $locale} ?? $development->developer_name_uz ?? ($development->builder ? $development->builder->name : 'Builder');
                            $builderAvatar = null;
                            if ($development->builder && $development->builder->avatar) {
                                $builderAvatar = asset('storage/' . $development->builder->avatar);
                            } else {
                                $builderAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($builderName) . '&background=0987f5&color=fff&size=48&bold=true';
                            }
                        @endphp
                        
                        <img src="{{ $devImage }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $devTitle }}">
                        
                        <div class="position-absolute top-0 start-0 m-3">
                            @if($development->installment_available)
                            <span class="badge rounded-pill px-3 py-2" style="background: rgba(0,0,0,0.7); color: white; font-weight: 600;">
                                {{ $locale === 'uz' ? 'Рассрочка' : ($locale === 'ru' ? 'Рассрочка' : 'Installment') }}
                            </span>
                            @endif
                        </div>
                        
                        <div class="position-absolute top-0 end-0 m-3">
                            <button class="btn btn-sm rounded-circle" style="background: rgba(255,255,255,0.9); width: 36px; height: 36px; padding: 0; border: none;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="#0987f5"/>
                                </svg>
                            </button>
                        </div>
                        
                        @if($development->cashback_percentage || $development->discount_percentage)
                        <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                            <div class="d-flex gap-2 flex-wrap">
                                @if($development->cashback_percentage)
                                <span class="badge rounded-pill px-3 py-1" style="background: #0987f5; color: white; font-weight: 600;">
                                    {{ $locale === 'uz' ? 'Кешбек' : ($locale === 'ru' ? 'Кешбек' : 'Cashback') }} {{ $development->cashback_percentage }}%
                                </span>
                                @endif
                                @if($development->discount_percentage)
                                <span class="badge rounded-pill px-3 py-1" style="background: #dc3545; color: white; font-weight: 600;">
                                    -{{ $development->discount_percentage }}%
                                </span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ $builderAvatar }}" class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover; border: 2px solid rgba(9,135,245,0.2);" alt="{{ $builderName }}">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-semibold" style="font-size: 14px; color: #333;">{{ $builderName }}</span>
                                    @if($development->builder && $development->builder->verified)
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="#0987f5"/>
                                    </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="fw-bold mb-2" style="color: #333; font-size: 18px;">
                            {{ $devTitle }}
                        </h5>
                        
                        <div class="d-flex align-items-center text-muted mb-3" style="font-size: 14px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-1">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>
                            </svg>
                            {{ $devLocation }}
                        </div>
                        
                        @php
                            $minPrice = $development->price_from ?? 0;
                            $pricePerSqm = $development->price_per_sqm ?? 0;
                            if ($development->properties && $development->properties->count() > 0) {
                                $minPriceProp = $development->properties->whereNotNull('price_from')->min('price_from');
                                if ($minPriceProp) $minPrice = $minPriceProp;
                            }
                        @endphp
                        
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                @if($minPrice > 0)
                                <div class="fw-bold" style="color: #0987f5; font-size: 20px;">
                                    {{ number_format($minPrice, 0, '.', ' ') }} {{ $development->currency ?? 'UZS' }}
                                </div>
                                @endif
                                @if($pricePerSqm > 0)
                                <div class="text-muted" style="font-size: 12px;">
                                    {{ number_format($pricePerSqm, 0, '.', ' ') }} {{ $development->currency ?? 'UZS' }}/м²
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2" onclick="event.stopPropagation();">
                            <a href="{{ route('single.development', ['locale' => $locale, 'slug' => $development->slug]) }}" class="btn btn-sm flex-grow-1" style="background: #0987f5; color: white; border: none; font-weight: 600;">
                                {{ $locale === 'uz' ? 'Konsultatsiya' : ($locale === 'ru' ? 'Консультация' : 'Consultation') }}
                            </a>
                            <a href="tel:+998781136350" class="btn btn-sm" style="background: white; color: #0987f5; border: 2px solid #0987f5; font-weight: 600; min-width: 50px;" onclick="event.stopPropagation();">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" fill="currentColor"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                </a>
            </div>
            @endforeach
        </div>
        
        @if($featuredDevelopments->count() > 3 || $latestDevelopments->count() > 3)
        <div class="text-center mt-4">
            <a href="{{ route('page.developments', ['locale' => $locale]) }}" class="btn btn-lg px-5" style="background: #0987f5; color: white; border: none; font-weight: 600; border-radius: 8px;">
                {{ $locale === 'uz' ? 'Barcha loyihalarni ko\'rish' : ($locale === 'ru' ? 'Смотреть все проекты' : 'View All Developments') }}
            </a>
        </div>
        @endif
    </div>
</section>
@endif
<!-- ============================ Popular New Buildings End ================================== -->

<!-- ============================ Latest Property For Sale Start ================================== -->
<section>
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10 text-center">
                <div class="tabOptions">
                    <ul class="nav nav-pills simple-tabs gray-simple mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-sell-tab" data-bs-toggle="pill" data-bs-target="#pills-sell" type="button" role="tab" aria-controls="pills-sell" aria-selected="true">{{ $locale === 'uz' ? 'Sotuvga' : ($locale === 'ru' ? 'На продажу' : 'Listing for Sell') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-rent-tab" data-bs-toggle="pill" data-bs-target="#pills-rent" type="button" role="tab" aria-controls="pills-rent" aria-selected="false">{{ $locale === 'uz' ? 'Ijara uchun' : ($locale === 'ru' ? 'На аренду' : 'Listing for Rent') }}</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12">

                <!-- Property for Sale/Rent -->
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-sell" role="tabpanel" aria-labelledby="pills-sell-tab" tabindex="0">
                        <div class="row align-items-center justify-content-center g-4">

                            @forelse($saleProperties as $property)
                            <!-- Single Property -->
                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <div class="property-listing card border rounded-3">
                                    <div class="listing-img-wrapper p-3">
                                        <div class="list-img-slide position-relative">
                                            @if($property->featured)
                                            <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                <div class="label verified-listing d-inline-flex align-items-center justify-content-center">
                                                    <span class="svg-icon text-light svg-icon-2hx me-1">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
                                                            <path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="currentColor"></path>
                                                        </svg>
                                                    </span>{{ $locale === 'uz' ? 'Tavsiya' : ($locale === 'ru' ? 'Проверено' : 'Verified') }}
                                                </div>
                                            </div>
                                            @endif
                                            <div class="clicks rounded-3 overflow-hidden mb-0">
                                                <a href="{{ route('property.show', $property->slug) }}">
                                                    @if($property->featured_image)
                                                    <img src="{{ asset('storage/' . $property->featured_image) }}" class="img-fluid" alt="{{ $property->translate($locale)->title ?? 'N/A' }}" />
                                                    @else
                                                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop" class="img-fluid" alt="Placeholder" />
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="listing-caption-wrapper px-3">
                                        <div class="listing-detail-wrapper">
                                            <div class="listing-short-detail-wrap">
                                                <div class="listing-short-detail">
                                                    <div class="d-flex align-items-center">
                                                        <span class="label for-sale prt-type me-2">{{ $locale === 'uz' ? 'Sotuv' : ($locale === 'ru' ? 'Продажа' : 'For Sale') }}</span>
                                                        <span class="label property-type property-cats">{{ ucfirst($property->property_type ?? 'Property') }}</span>
                                                    </div>
                                                    <h4 class="listing-name fw-medium fs-5 mb-1">
                                                        <a href="{{ route('property.show', $property->slug) }}">{{ $property->translate($locale)->title ?? $property->title ?? 'N/A' }}</a>
                                                    </h4>
                                                    <div class="prt-location text-muted-2">
                                                        <span class="svg-icon svg-icon-2hx">
                                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
                                                                <path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                        {{ $property->city }}{{ $property->region ? ', ' . $property->region : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="price-features-wrapper">
                                            <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                @if($property->bedrooms)
                                                <div class="listing-card d-flex align-items-center">
                                                    <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">{{ $property->bedrooms }}BHK</span>
                                                </div>
                                                @endif
                                                @if($property->bedrooms)
                                                <div class="listing-card d-flex align-items-center">
                                                    <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">{{ $property->bedrooms }} {{ $locale === 'uz' ? 'Yotoq' : ($locale === 'ru' ? 'Спальни' : 'Beds') }}</span>
                                                </div>
                                                @endif
                                                @if($property->area)
                                                <div class="listing-card d-flex align-items-center">
                                                    <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">{{ number_format($property->area, 0) }} SQFT</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                            <div class="listing-short-detail-flex">
                                                <h6 class="listing-card-info-price m-0 text-main">{{ number_format($property->price, 0) }} {{ $property->currency ?? 'UZS' }}</h6>
                                            </div>
                                            <div class="footer-flex">
                                                <a href="{{ route('property.show', $property->slug) }}" class="prt-view">
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
                            @empty
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">{{ $locale === 'uz' ? 'Sotuvga e\'lonlar topilmadi' : ($locale === 'ru' ? 'Объявления на продажу не найдены' : 'No sale listings found') }}</p>
                            </div>
                            @endforelse

                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-rent" role="tabpanel" aria-labelledby="pills-rent-tab" tabindex="0">
                        <div class="row align-items-center justify-content-center g-4">

                            @forelse($rentProperties as $property)
                            <!-- Single Property -->
                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <div class="property-listing card border rounded-3">
                                    <div class="listing-img-wrapper p-3">
                                        <div class="list-img-slide position-relative">
                                            @if($property->featured)
                                            <div class="position-absolute top-0 left-0 ms-3 mt-3 z-1">
                                                <div class="label verified-listing d-inline-flex align-items-center justify-content-center">
                                                    <span class="svg-icon text-light svg-icon-2hx me-1">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
                                                            <path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="currentColor"></path>
                                                        </svg>
                                                    </span>{{ $locale === 'uz' ? 'Tavsiya' : ($locale === 'ru' ? 'Проверено' : 'Verified') }}
                                                </div>
                                            </div>
                                            @endif
                                            <div class="clicks rounded-3 overflow-hidden mb-0">
                                                <a href="{{ route('property.show', $property->slug) }}">
                                                    @if($property->featured_image)
                                                    <img src="{{ asset('storage/' . $property->featured_image) }}" class="img-fluid" alt="{{ $property->translate($locale)->title ?? 'N/A' }}" />
                                                    @else
                                                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop" class="img-fluid" alt="Placeholder" />
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="listing-caption-wrapper px-3">
                                        <div class="listing-detail-wrapper">
                                            <div class="listing-short-detail-wrap">
                                                <div class="listing-short-detail">
                                                    <div class="d-flex align-items-center">
                                                        <span class="label for-rent prt-type me-2">{{ $locale === 'uz' ? 'Ijaraga' : ($locale === 'ru' ? 'Аренда' : 'For Rent') }}</span>
                                                        <span class="label property-type property-cats">{{ ucfirst($property->property_type ?? 'Property') }}</span>
                                                    </div>
                                                    <h4 class="listing-name fw-medium fs-5 mb-1">
                                                        <a href="{{ route('property.show', $property->slug) }}">{{ $property->translate($locale)->title ?? $property->title ?? 'N/A' }}</a>
                                                    </h4>
                                                    <div class="prt-location text-muted-2">
                                                        <span class="svg-icon svg-icon-2hx">
                                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor"/>
                                                                <path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                        {{ $property->city }}{{ $property->region ? ', ' . $property->region : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="price-features-wrapper">
                                            <div class="list-fx-features d-flex align-items-center justify-content-between">
                                                @if($property->bedrooms)
                                                <div class="listing-card d-flex align-items-center">
                                                    <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-building-shield fs-sm"></i></div><span class="text-muted-2">{{ $property->bedrooms }}BHK</span>
                                                </div>
                                                @endif
                                                @if($property->bedrooms)
                                                <div class="listing-card d-flex align-items-center">
                                                    <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-bed fs-sm"></i></div><span class="text-muted-2">{{ $property->bedrooms }} {{ $locale === 'uz' ? 'Yotoq' : ($locale === 'ru' ? 'Спальни' : 'Beds') }}</span>
                                                </div>
                                                @endif
                                                @if($property->area)
                                                <div class="listing-card d-flex align-items-center">
                                                    <div class="square--30 text-muted-2 fs-sm circle gray-simple me-2"><i class="fa-solid fa-clone fs-sm"></i></div><span class="text-muted-2">{{ number_format($property->area, 0) }} SQFT</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="listing-detail-footer d-flex align-items-center justify-content-between py-4">
                                            <div class="listing-short-detail-flex">
                                                <h6 class="listing-card-info-price m-0 text-main">{{ number_format($property->price, 0) }} {{ $property->currency ?? 'UZS' }}/{{ $locale === 'uz' ? 'oy' : ($locale === 'ru' ? 'мес' : 'month') }}</h6>
                                            </div>
                                            <div class="footer-flex">
                                                <a href="{{ route('property.show', $property->slug) }}" class="prt-view">
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
                            @empty
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">{{ $locale === 'uz' ? 'Ijara e\'lonlari topilmadi' : ($locale === 'ru' ? 'Объявления на аренду не найдены' : 'No rent listings found') }}</p>
                            </div>
                            @endforelse

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
<!-- ============================ Latest Property For Sale End ================================== -->

<!-- ============================ Featured Property For Sale Start ================================== -->
@if($featuredProperties->count() > 0)
<section class="bg-light">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10 text-center">
                <div class="sec-heading center">
                    <h2>{{ $locale === 'uz' ? 'Tavsiya etilgan uy-joylar' : ($locale === 'ru' ? 'Рекомендуемые объявления' : 'Featured Property For Sale') }}</h2>
                    <p>{{ $locale === 'uz' ? 'Eng yaxshi takliflar' : ($locale === 'ru' ? 'Лучшие предложения' : 'Best offers') }}</p>
                </div>
            </div>
        </div>

        <div class="row list-layout">

            @foreach($featuredProperties->take(3) as $property)
            <!-- Single Property Start -->
            <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="property-listing property-1 bg-white p-2 rounded">

                    <div class="listing-img-wrapper">
                        <a href="{{ route('property.show', $property->slug) }}">
                            @if($property->featured_image)
                            <img src="{{ asset('storage/' . $property->featured_image) }}" class="img-fluid mx-auto rounded" alt="{{ $property->translate($locale)->title ?? 'N/A' }}" />
                            @else
                            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop" class="img-fluid mx-auto rounded" alt="Placeholder" />
                            @endif
                        </a>
                    </div>

                    <div class="listing-content">
                        <div class="listing-detail-wrapper-box">
                            <div class="listing-detail-wrapper d-flex align-items-center justify-content-between">
                                <div class="listing-short-detail">
                                    <span class="label for-sale d-inline-flex mb-1">{{ $property->listing_type === 'sale' ? ($locale === 'uz' ? 'Sotuv' : ($locale === 'ru' ? 'Продажа' : 'For Sale')) : ($locale === 'uz' ? 'Ijaraga' : ($locale === 'ru' ? 'Аренда' : 'For Rent')) }}</span>
                                    <h4 class="listing-name mb-0"><a href="{{ route('property.show', $property->slug) }}">{{ $property->translate($locale)->title ?? $property->title ?? 'N/A' }}</a></h4>
                                </div>
                                <div class="list-price">
                                    <h6 class="listing-card-info-price text-main">{{ number_format($property->price, 0) }} {{ $property->currency ?? 'UZS' }}</h6>
                                </div>
                            </div>
                        </div>

                        <div class="price-features-wrapper">
                            <div class="list-fx-features d-flex align-items-center justify-content-between mt-3 mb-1">
                                @if($property->bedrooms)
                                <div class="listing-card d-flex align-items-center">
                                    <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-building-shield fs-xs"></i></div><span class="text-muted-2 fs-sm">{{ $property->bedrooms }}BHK</span>
                                </div>
                                @endif
                                @if($property->bedrooms)
                                <div class="listing-card d-flex align-items-center">
                                    <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-bed fs-xs"></i></div><span class="text-muted-2 fs-sm">{{ $property->bedrooms }} {{ $locale === 'uz' ? 'Yotoq' : ($locale === 'ru' ? 'Спальни' : 'Beds') }}</span>
                                </div>
                                @endif
                                @if($property->area)
                                <div class="listing-card d-flex align-items-center">
                                    <div class="square--25 text-muted-2 fs-sm circle gray-simple me-1"><i class="fa-solid fa-clone fs-xs"></i></div><span class="text-muted-2 fs-sm">{{ number_format($property->area, 0) }} SQFT</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="listing-footer-wrapper">
                            <div class="listing-locate">
                                <span class="listing-location text-muted-2"><i class="fa-solid fa-location-pin me-1"></i>{{ $property->city }}{{ $property->region ? ', ' . $property->region : '' }}</span>
                            </div>
                            <div class="listing-detail-btn">
                                <a href="{{ route('property.show', $property->slug) }}" class="btn btn-sm px-4 fw-medium btn-main">{{ $locale === 'uz' ? 'Ko\'rish' : ($locale === 'ru' ? 'Смотреть' : 'View') }}</a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <!-- Single Property End -->
            @endforeach

        </div>

    </div>
</section>
@endif
<!-- ============================ Featured Property For Sale End ================================== -->

@endsection
