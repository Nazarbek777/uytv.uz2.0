@extends('layouts.page')

@section('content')
    @php
        $statusColors = [
            'not_started' => 'secondary',
            'in_progress' => 'warning',
            'submitted' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
        ];

        $statusLabels = [
            'not_started' => __('Boshlanmagan'),
            'in_progress' => __('Davom etmoqda'),
            'submitted' => __('Kutish jarayonida'),
            'approved' => __('Faol'),
            'rejected' => __('Rad etilgan'),
        ];

        $status = $user->onboarding_status ?? 'not_started';
        $progress = min(100, $user->onboarding_progress ?? 0);
        $statusColor = $statusColors[$status] ?? 'secondary';
        $statusLabel = $statusLabels[$status] ?? __('Boshlanmagan');
        $socialLinks = $user->social_links ?? [];
    @endphp

    <div class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 text-center">
                    <h2 class="ipt-title">{{ __('Uyini sotayotgan foydalanuvchi profili') }}</h2>
                    <span class="ipn-subtitle">{{ __('Ism va aloqa ma\'lumotlarini kiriting — shundan so‘ng e’lon joylab sotishni boshlang') }}</span>
                </div>
            </div>
        </div>
    </div>

    <section class="pt-4 pb-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <p class="text-muted mb-1">{{ __('Joriy holat') }}</p>
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <div class="text-end">
                                    <p class="text-muted mb-1">{{ __('Progress') }}</p>
                                    <h4 class="mb-0">{{ $progress }}%</h4>
                                </div>
                            </div>
                            <div class="progress progress-sm mb-4" style="height: 10px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%;"
                                     aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <h5 class="mb-3">{{ __('Tez va soddalashtirilgan qadamlar') }}</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-start mb-3">
                                    <i class="bi bi-person-bounding-box text-primary me-3 fs-5"></i>
                                    <div>
                                        <strong>{{ __('Ism-sharif va telefon') }}</strong>
                                        <p class="text-muted mb-0">{{ __('Siz bilan bog‘lanish uchun asosiy ma\'lumotlar kerak bo‘ladi.') }}</p>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="bi bi-geo-alt text-primary me-3 fs-5"></i>
                                    <div>
                                        <strong>{{ __('Hudud va manzil') }}</strong>
                                        <p class="text-muted mb-0">{{ __('Qaysi shaharda ekaningizni bilsak, xaridorlar uchun ishonchli bo‘ladi.') }}</p>
                                    </div>
                                </li>
                                <li class="d-flex align-items-start">
                                    <i class="bi bi-send-check text-primary me-3 fs-5"></i>
                                    <div>
                                        <strong>{{ __('E’lonni darhol joylashtiring') }}</strong>
                                        <p class="text-muted mb-0">{{ __('Profilni saqlaganingizdan so‘ng uy-joy qo‘shishni boshlang.') }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body">
                            <h5 class="mb-3">{{ __('Nega ma’lumot kerak?') }}</h5>
                            <p class="text-muted mb-2">
                                {{ __('Bu B2C platforma: oddiy foydalanuvchilar uyini joylab sota oladi. Ism va aloqa raqami xaridorlarga ishonch bag‘ishlaydi.') }}
                            </p>
                            <p class="text-muted mb-0">
                                {{ __('Savollar bo‘lsa support@uytv.uz manziliga yozing.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="dashboard-wraper">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($status !== 'approved')
                            <div class="alert alert-info border-0 shadow-sm">
                                <i class="bi bi-info-circle me-2"></i>
                                {{ __('Ma’lumotlarni saqlaganingizdan so‘ng darhol e’lon qo‘shishingiz mumkin.') }}
                            </div>
                        @else
                            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="bi bi-check2-circle me-2"></i>
                                    {{ __('Ajoyib! Profilingiz faollashtirilgan. Endi e’lon joylashga o‘ting.') }}
                                </div>
                                <a href="{{ route('provider.properties.create') }}" class="btn btn-light btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>{{ __('Yangi e’lon qo‘shish') }}
                                </a>
                            </div>
                        @endif

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0">
                                <h4 class="mb-0">{{ __('Asosiy ma’lumotlar') }}</h4>
                                <small class="text-muted">{{ __('Ism, telefon va oddiy aloqa ma’lumotlari kifoya') }}</small>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('provider.onboarding.company') }}">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('To‘liq ism familiya') }} *</label>
                                            <input type="text" name="full_name"
                                                   class="form-control @error('full_name') is-invalid @enderror"
                                                   value="{{ old('full_name', $user->name ?? $user->company_name) }}" required>
                                            @error('full_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Telefon raqam') }} *</label>
                                            <input type="text" name="phone"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   value="{{ old('phone', $user->phone) }}" placeholder="+998 90 123 45 67" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Telegram foydalanuvchi nomi') }}</label>
                                            <input type="text" name="telegram"
                                                   class="form-control @error('telegram') is-invalid @enderror"
                                                   value="{{ old('telegram', $socialLinks['telegram'] ?? '') }}" placeholder="@username">
                                            @error('telegram')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('WhatsApp raqami') }}</label>
                                            <input type="text" name="whatsapp"
                                                   class="form-control @error('whatsapp') is-invalid @enderror"
                                                   value="{{ old('whatsapp', $socialLinks['whatsapp'] ?? $user->phone) }}">
                                            @error('whatsapp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Instagram profili (ixtiyoriy)') }}</label>
                                            <input type="url" name="instagram"
                                                   class="form-control @error('instagram') is-invalid @enderror"
                                                   value="{{ old('instagram', $socialLinks['instagram'] ?? '') }}" placeholder="https://instagram.com/...">
                                            @error('instagram')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Passport yoki ID raqam (ixtiyoriy)') }}</label>
                                            <input type="text" name="id_number"
                                                   class="form-control @error('id_number') is-invalid @enderror"
                                                   value="{{ old('id_number', $user->license_number) }}" placeholder="AB1234567">
                                            @error('id_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Shahar') }}</label>
                                            <input type="text" name="city"
                                                   class="form-control @error('city') is-invalid @enderror"
                                                   value="{{ old('city', $user->city) }}" placeholder="Toshkent, Samarqand ...">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Manzil (ixtiyoriy)') }}</label>
                                            <input type="text" name="address"
                                                   class="form-control @error('address') is-invalid @enderror"
                                                   value="{{ old('address', $user->address) }}" placeholder="Yashash hududi">
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{ __('Qisqacha o‘zingiz haqida (ixtiyoriy)') }}</label>
                                            <textarea name="bio" rows="3"
                                                      class="form-control @error('bio') is-invalid @enderror"
                                                      placeholder="{{ __('Masalan: 2 yildan beri uylarni sotishga yordam beraman') }}">{{ old('bio', $user->bio) }}</textarea>
                                            @error('bio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-4 d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-main">
                                            <i class="bi bi-save me-2"></i>{{ __('Saqlash') }}
                                        </button>
                                        <a href="{{ route('provider.properties.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-2"></i>{{ __('Boshqaruv paneliga qaytish') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-0">{{ __('Ixtiyoriy: ishonch uchun hujjat yuklash') }}</h4>
                                    <small class="text-muted">{{ __('Bu qadam majburiy emas, lekin xaridorlar uchun qo‘shimcha ishonch beradi') }}</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('provider.onboarding.documents') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Fayl tanlang') }}</label>
                                        <input type="file" name="documents[]" multiple class="form-control @error('documents') is-invalid @enderror @error('documents.*') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted d-block mt-2">{{ __('PDF yoki rasm formatida, har birining hajmi 5MB dan oshmasin.') }}</small>
                                        @error('documents')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @error('documents.*')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-outline-main">
                                        <i class="bi bi-cloud-arrow-up me-2"></i>{{ __('Yuklash') }}
                                    </button>
                                </form>

                                @if(!empty($user->provider_documents))
                                    <div class="table-responsive mt-4">
                                        <table class="table table-sm align-middle">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Fayl nomi') }}</th>
                                                    <th>{{ __('Sana') }}</th>
                                                    <th>{{ __('Ko‘rish') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($user->provider_documents as $document)
                                                    <tr>
                                                        <td>{{ $document['name'] ?? __('Noma‘lum fayl') }}</td>
                                                        <td>{{ isset($document['uploaded_at']) ? \Carbon\Carbon::parse($document['uploaded_at'])->format('d.m.Y H:i') : '—' }}</td>
                                                        <td>
                                                            @if(!empty($document['path']))
                                                                <a href="{{ asset('storage/' . $document['path']) }}" target="_blank" class="btn btn-light btn-sm">
                                                                    <i class="bi bi-box-arrow-up-right me-1"></i>{{ __('Ochish') }}
                                                                </a>
                                                            @else
                                                                —
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                    <div>
                                        <h4 class="mb-1">{{ __('Hammasi tayyor!') }}</h4>
                                        <p class="text-muted mb-0">
                                            {{ __('Ma’lumotlarni saqlaganingizdan so‘ng istalgan vaqtda uy-joy e’loningizni qo‘shishingiz mumkin.') }}
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('provider.onboarding.submit') }}" class="mt-3 mt-md-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-send-check-fill me-2"></i>{{ __('Mening profilim tayyor') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
