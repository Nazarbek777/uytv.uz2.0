@extends('layouts.page')

@section('content')
    <div class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h2 class="ipt-title">{{ __('Obuna va TOP boost') }}</h2>
                    <span class="ipn-subtitle">{{ __('Tarif tanlang yoki e’loningizni TOPga ko‘tarib chiqing') }}</span>
                </div>
            </div>
        </div>
    </div>

    <section class="gray-simple">
        <div class="container">
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

            <div class="row g-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-1">{{ __('Joriy obuna') }}</h5>
                                @if($currentSubscription)
                                    <p class="mb-0 text-muted">
                                        {{ $currentSubscription->plan->name }} &middot;
                                        {{ __('Muddati: :date gacha', ['date' => $currentSubscription->ends_at->format('d.m.Y')]) }}
                                    </p>
                                    <small class="text-muted">
                                        {{ __('Qolgan e’lon slotlari: :slots | TOP krediti: :boosts', [
                                            'slots' => $currentSubscription->remaining_listing_slots ?? '∞',
                                            'boosts' => $currentSubscription->remaining_boosts ?? 0,
                                        ]) }}
                                    </small>
                                @else
                                    <p class="mb-0 text-muted">{{ __('Hozircha faol obunangiz yo‘q. Shablon tariflardan birini tanlang.') }}</p>
                                @endif
                            </div>
                            <a href="#planList" class="btn btn-main">{{ __('Tariflarni ko‘rish') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4" id="planList">
                @foreach($plans as $plan)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="pricing-wrap {{ $plan->is_popular ? 'popular' : '' }}">
                            @if($plan->is_popular)
                                <div class="pricing-badge">{{ __('Eng mashhur') }}</div>
                            @endif
                            <div class="pricing-header">
                                <h4 class="pricing-title">{{ $plan->name }}</h4>
                                <div class="pricing-cost">
                                    <sup>{{ $plan->currency === 'USD' ? '$' : ($plan->currency === 'UZS' ? 'so\'m' : $plan->currency) }}</sup>{{ number_format($plan->price, 0) }}
                                </div>
                                <span>{{ __(':days kunlik obuna', ['days' => $plan->duration_days]) }}</span>
                            </div>
                            <div class="pricing-body">
                                <ul>
                                    <li><i class="fa-solid fa-check"></i>{{ __(':count ta e’lon joylash', ['count' => $plan->listing_limit]) }}</li>
                                    <li><i class="fa-solid fa-check"></i>{{ __(':count ta TOP boost krediti', ['count' => $plan->boost_credits]) }}</li>
                                    <li><i class="fa-solid fa-check"></i>{{ __(':count ta featured slot', ['count' => $plan->featured_limit]) }}</li>
                                </ul>
                                @if($plan->features)
                                    <ul class="mt-2">
                                        @foreach($plan->features as $feature)
                                            <li><i class="fa-solid fa-check"></i>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="pricing-bottom">
                                <form method="POST" action="{{ route('provider.subscriptions.subscribe', $plan) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-main w-100" {{ $currentSubscription && $currentSubscription->subscription_plan_id === $plan->id ? 'disabled' : '' }}>
                                        {{ $currentSubscription && $currentSubscription->subscription_plan_id === $plan->id ? __('Faol') : __('Obuna bo‘lish') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row g-4 mt-2">
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">{{ __('Pulli TOP boost') }}</h5>
                            <small class="text-muted">{{ __('Agar obunangiz bo‘lmasa ham, e’lonni vaqtincha TOPga ko‘tarishingiz mumkin') }}</small>
                        </div>
                        <div class="card-body">
                            @if($properties->isEmpty())
                                <p class="text-muted mb-0">{{ __('Avval hech bo‘lmaganda bitta e’lon qo‘shing.') }}</p>
                            @else
                                <form method="POST" action="{{ route('provider.subscriptions.boost') }}">
                                    @csrf
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('E’lonni tanlang') }}</label>
                                            <select name="property_id" class="form-select">
                                                @foreach($properties as $property)
                                                    <option value="{{ $property->id }}">{{ $property->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('Muddat (soat)') }}</label>
                                            <input type="number" name="hours" class="form-control" value="24" min="6" max="168">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('To‘lov (so‘m)') }}</label>
                                            <input type="number" name="amount" class="form-control" value="50000" min="10000" step="1000">
                                            <input type="hidden" name="currency" value="UZS">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-outline-main w-100">
                                                <i class="bi bi-lightning"></i> {{ __('TOPga ko‘tarish') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">{{ __('So‘nggi boostlar') }}</h5>
                        </div>
                        <div class="card-body">
                            @forelse($recentBoosts as $boost)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $boost->property->title ?? __('O‘chirilgan e’lon') }}</strong>
                                        <span class="badge bg-{{ $boost->isActive() ? 'success' : 'secondary' }}">
                                            {{ $boost->isActive() ? __('Faol') : __('Yakunlangan') }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block">{{ $boost->starts_at->format('d.m.Y H:i') }} - {{ $boost->ends_at->format('d.m.Y H:i') }}</small>
                                    <small class="text-muted">{{ __('Manba: :source', ['source' => $boost->meta['source'] ?? 'one-time']) }}</small>
                                </div>
                            @empty
                                <p class="text-muted mb-0">{{ __('Hali TOP boost tarixi mavjud emas.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

