@extends('layouts.page')

@section('content')
    @php
        $notificationPreferences = $user->notification_preferences ?? [];
        $additionalPhones = $user->additional_phones ?? [];
        $socialLinks = $user->social_links ?? [];
    @endphp

    <div class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h2 class="ipt-title">{{ __('Sozlamalar') }}</h2>
                    <span class="ipn-subtitle">{{ __('Aloqa ma\'lumotlari, bildirishnomalar va xavfsizlik sozlamalari') }}</span>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-light">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="filter_search_opt">
                        <a href="javascript:void(0);" onclick="openFilterSearch()" class="btn btn-dark full-width mb-4">
                            Dashboard Navigation<i class="fa-solid fa-bars ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-12 pe-xl-4">
                    <div class="simple-sidebar sm-sidebar" id="filter_search">
                        <div class="search-sidebar_header">
                            <h4 class="ssh_heading">Close Menu</h4>
                            <button onclick="closeFilterSearch()" class="w3-bar-item w3-button w3-large">
                                <i class="fa-regular fa-circle-xmark fs-5 text-muted-2"></i>
                            </button>
                        </div>

                        <div class="sidebar-widgets">
                            <div class="dashboard-navbar">
                                <div class="d-user-avater">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" class="img-fluid avater" alt="">
                                    @else
                                        <div style="width: 100px; height: 100px; border-radius: 50%; background: #2d55a4; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 36px; font-weight: 600; margin: 0 auto;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <h4>{{ $user->name }}</h4>
                                    <span>{{ $user->email }}</span>
                                    <span class="badge bg-primary mt-1">Provider</span>
                                </div>

                                <div class="d-navigation">
                                    <ul>
                                        <li><a href="{{ route('provider.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>{{ __('Dashboard') }}</a></li>
                                        <li><a href="{{ route('provider.properties.index') }}"><i class="bi bi-house-door me-2"></i>{{ __('Mening e\'lonlarim') }}</a></li>
                                        <li><a href="{{ route('provider.properties.create') }}"><i class="bi bi-patch-plus me-2"></i>{{ __('Yangi uy-joy qo\'shish') }}</a></li>
                                        <li><a href="{{ route('provider.subscriptions.index') }}"><i class="bi bi-lightning-charge me-2"></i>{{ __('Obuna & TOP boost') }}</a></li>
                                        <li class="active"><a href="{{ route('provider.settings.index') }}"><i class="bi bi-gear me-2"></i>{{ __('Sozlamalar') }}</a></li>
                                        <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-power me-2"></i>{{ __('Chiqish') }}</a></li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-md-12">
                    <div class="dashboard-wraper">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ __('Iltimos, quyidagi xatolarni tuzating:') }}
                                <ul class="mb-0 small">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-0">{{ __('Profil va aloqa ma\'lumotlari') }}</h4>
                                    <small class="text-muted">{{ __('Xaridorlar siz bilan qanday bog‘lanishini belgilang') }}</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('provider.settings.profile') }}">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('To‘liq ism familiya') }} *</label>
                                            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $user->name) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Alohida email (ixtiyoriy)') }}</label>
                                            <input type="email" name="secondary_email" class="form-control" value="{{ old('secondary_email', $user->secondary_email) }}" placeholder="info@example.com">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Asosiy telefon raqami') }} *</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('WhatsApp raqami') }}</label>
                                            <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $user->whatsapp_number ?? $user->phone) }}">
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="form-label">{{ __('Qo‘shimcha telefon raqamlari') }}</label>
                                        <div id="additionalPhones">
                                            @for($i = 0; $i < 3; $i++)
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                    <input type="text" name="additional_phones[]" class="form-control" value="{{ old('additional_phones.' . $i, $additionalPhones[$i] ?? '') }}" placeholder="+998 91 000 00 00">
                                                </div>
                                            @endfor
                                        </div>
                                        <small class="text-muted">{{ __('Maksimum 3 ta qo‘shimcha raqam kiriting.') }}</small>
                                    </div>

                                    <div class="row g-4 mt-1">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Telegram foydalanuvchi nomi') }}</label>
                                            <input type="text" name="telegram_username" class="form-control" value="{{ old('telegram_username', $user->telegram_username ?? $socialLinks['telegram'] ?? '') }}" placeholder="@username">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Instagram profili') }}</label>
                                            <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $socialLinks['instagram'] ?? '') }}" placeholder="https://instagram.com/...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Facebook profili') }}</label>
                                            <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $socialLinks['facebook'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Veb-sayt') }}</label>
                                            <input type="url" name="website" class="form-control" value="{{ old('website', $user->website) }}" placeholder="https://...">
                                        </div>
                                    </div>

                                    <div class="row g-4 mt-1">
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Shahar') }}</label>
                                            <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Tuman / Hudud') }}</label>
                                            <input type="text" name="district" class="form-control" value="{{ old('district', $user->district) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Aniq manzil') }}</label>
                                            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Latitude') }}</label>
                                            <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $user->latitude) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Longitude') }}</label>
                                            <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $user->longitude) }}">
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <label class="form-label">{{ __('Qisqacha o‘zingiz haqida') }}</label>
                                        <textarea name="bio" rows="4" class="form-control" placeholder="{{ __('Masalan: 5 yildan beri Toshkent shahri bo‘yicha uy sotishga yordam beraman.') }}">{{ old('bio', $user->bio) }}</textarea>
                                    </div>

                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input" type="checkbox" id="isProfilePublic" name="is_profile_public" value="1" {{ old('is_profile_public', $user->is_profile_public) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isProfilePublic">{{ __('Profilni xaridorlarga ko‘rsatish') }}</label>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-main"><i class="bi bi-save me-2"></i>{{ __('Saqlash') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0">
                                <h4 class="mb-0">{{ __('Bildirishnomalar') }}</h4>
                                <small class="text-muted">{{ __('Qaysi xabarlarni qabul qilishni tanlang') }}</small>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('provider.settings.notifications') }}">
                                    @csrf
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="notifyApproval" name="notify_on_approval" value="1" {{ ($notificationPreferences['notify_on_approval'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notifyApproval">{{ __('E’lon tasdiqlanganda yoki rad etilganda ogohlantirish') }}</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="notifyMessages" name="notify_on_messages" value="1" {{ ($notificationPreferences['notify_on_messages'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notifyMessages">{{ __('Xaridorlardan yangi xabarlar/talablar') }}</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="notifyExpiry" name="notify_on_expiry" value="1" {{ ($notificationPreferences['notify_on_expiry'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notifyExpiry">{{ __('E’lon muddati tugashidan oldin eslatma') }}</label>
                                    </div>

                                    <button type="submit" class="btn btn-outline-main mt-2">
                                        <i class="bi bi-save me-2"></i>{{ __('Bildirishnomalarni saqlash') }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h4 class="mb-0">{{ __('Parolni yangilash') }}</h4>
                                <small class="text-muted">{{ __('Xavfsizlik uchun vaqti-vaqti bilan parolni yangilab turing') }}</small>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('provider.settings.password') }}">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Joriy parol') }}</label>
                                            <input type="password" name="current_password" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Yangi parol') }}</label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Yangi parolni takrorlang') }}</label>
                                            <input type="password" name="password_confirmation" class="form-control" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-outline-danger mt-3">
                                        <i class="bi bi-shield-lock me-2"></i>{{ __('Parolni yangilash') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

