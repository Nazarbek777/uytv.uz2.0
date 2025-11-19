@extends('layouts.page')

@php
    $approvalStatusMeta = [
        'draft' => ['label' => __('Qoralama'), 'class' => 'secondary'],
        'pending' => ['label' => __('Tasdiqlash jarayonida'), 'class' => 'warning'],
        'approved' => ['label' => __('Tasdiqlangan'), 'class' => 'success'],
        'needs_changes' => ['label' => __('Qayta ishlash kerak'), 'class' => 'danger'],
        'rejected' => ['label' => __('Rad etilgan'), 'class' => 'danger'],
        'published' => ['label' => __('Nashr qilingan'), 'class' => 'success'],
    ];
@endphp
@section('content')
    <!-- ============================ Page Title Start================================== -->
    <div class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h2 class="ipt-title">Xush kelibsiz, {{ auth()->user()->name }}!</h2>
                    <span class="ipn-subtitle">Sizning akkauntingiz</span>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================ Page Title End ================================== -->

    <!-- ============================ User Dashboard ================================== -->
    <section class="bg-light">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="filter_search_opt">
                        <a href="javascript:void(0);" onclick="openFilterSearch()" class="btn btn-dark full-width mb-4">Dashboard Navigation<i class="fa-solid fa-bars ms-2"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-12 pe-xl-4">
                    <div class="simple-sidebar sm-sidebar" id="filter_search">
                        <div class="search-sidebar_header">
                            <h4 class="ssh_heading">Close Filter</h4>
                            <button onclick="closeFilterSearch()" class="w3-bar-item w3-button w3-large"><i class="fa-regular fa-circle-xmark fs-5 text-muted-2"></i></button>
                        </div>

                        <div class="sidebar-widgets">
                            <div class="dashboard-navbar">
                                <div class="d-user-avater">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="img-fluid avater" alt="">
                                    @else
                                        <div style="width: 100px; height: 100px; border-radius: 50%; background: #2d55a4; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 36px; font-weight: 600; margin: 0 auto;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <h4>{{ auth()->user()->name }}</h4>
                                    <span>{{ auth()->user()->email }}</span>
                                    @if(auth()->user()->role === 'provider')
                                        <span class="badge bg-primary mt-1">Provider</span>
                                    @endif
                                </div>

                                <div class="d-navigation">
                                    <ul>
                                        <li class="{{ request()->routeIs('provider.dashboard') ? 'active' : '' }}">
                                            <a href="{{ route('provider.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>{{ __('Dashboard') }}</a>
                                        </li>
                                        <li class="{{ request()->routeIs('provider.properties.index') ? 'active' : '' }}">
                                            <a href="{{ route('provider.properties.index') }}"><i class="bi bi-house-door me-2"></i>{{ __('Mening e\'lonlarim') }}</a>
                                        </li>
                                        <li class="{{ request()->routeIs('provider.properties.create') ? 'active' : '' }}">
                                            <a href="{{ route('provider.properties.create') }}"><i class="bi bi-patch-plus me-2"></i>{{ __('Yangi uy-joy qo\'shish') }}</a>
                                        </li>
                                        <li class="{{ request()->routeIs('provider.subscriptions.*') ? 'active' : '' }}">
                                            <a href="{{ route('provider.subscriptions.index') }}"><i class="bi bi-lightning-charge me-2"></i>{{ __('Obuna & TOP boost') }}</a>
                                        </li>
                                        <li class="{{ request()->routeIs('provider.settings.*') ? 'active' : '' }}">
                                            <a href="{{ route('provider.settings.index') }}"><i class="bi bi-gear me-2"></i>{{ __('Sozlamalar') }}</a>
                                        </li>
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

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="form-submit d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">Mening uy-joylarim ({{ $properties->total() }})</h4>
                            <a href="{{ route('provider.properties.create') }}" class="btn btn-main">
                                <i class="bi bi-plus-circle me-1"></i>Yangi uy-joy qo'shish
                            </a>
                        </div>

                        @if($properties->count() > 0)
                            <div class="row">
                                @foreach($properties as $property)
                                    <div class="col-md-12 col-sm-12 mb-4">
                                        <div class="singles-dashboard-list">
                                            <div class="sd-list-left">
                                                @if($property->featured_image)
                                                    <img src="{{ asset('storage/' . $property->featured_image) }}" class="img-fluid" alt="{{ $property->title }}" style="width: 200px; height: 150px; object-fit: cover;">
                                                @elseif($property->images && count($property->images) > 0)
                                                    <img src="{{ asset('storage/' . $property->images[0]) }}" class="img-fluid" alt="{{ $property->title }}" style="width: 200px; height: 150px; object-fit: cover;">
                                                @else
                                                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400&h=300&fit=crop" class="img-fluid" alt="No image" style="width: 200px; height: 150px; object-fit: cover;">
                                                @endif
                                            </div>
                                            <div class="sd-list-right">
                                    @php
                                        $approvalStatus = $property->approval_status ?? ($property->status === 'published' ? 'approved' : 'draft');
                                        if ($property->status === 'rejected') {
                                            $approvalStatus = 'needs_changes';
                                        } elseif ($property->status === 'pending') {
                                            $approvalStatus = 'pending';
                                        } elseif ($property->status === 'published') {
                                            $approvalStatus = 'approved';
                                        }
                                        $approvalMeta = $approvalStatusMeta[$approvalStatus] ?? ['label' => ucfirst($approvalStatus), 'class' => 'secondary'];
                                        $approvalHistory = is_array($property->approval_history) ? $property->approval_history : [];
                                    @endphp
                                                <h4 class="listing_dashboard_title">
                                                    <a href="{{ route('property.show', $property->slug) }}">{{ $property->title }}</a>
                                                </h4>
                                                <div class="user_dashboard_listed">
                                                    <strong>Narx:</strong> 
                                                    @if($property->price)
                                                        {{ number_format($property->price, 0, '.', ' ') }} 
                                                        {{ $property->currency === 'USD' ? '$' : ($property->currency === 'EUR' ? '€' : 'so\'m') }}
                                                        @if($property->listing_type === 'rent')
                                                            / oy
                                                        @endif
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                                <div class="user_dashboard_listed">
                                                    <strong>Tur:</strong> 
                                                    @if($property->listing_type === 'sale')
                                                        <span class="badge bg-success">Sotish</span>
                                                    @else
                                                        <span class="badge bg-primary">Ijaraga</span>
                                                    @endif
                                                    <span class="badge bg-info ms-2">{{ ucfirst($property->property_type) }}</span>
                                                </div>
                                                <div class="user_dashboard_listed">
                                                    <strong>Manzil:</strong> 
                                                    {{ $property->city ?? 'N/A' }}
                                                    @if($property->area)
                                                        , {{ $property->area }} {{ $property->area_unit ?? 'm²' }}
                                                    @endif
                                                    @if($property->bedrooms)
                                                        , {{ $property->bedrooms }} xona
                                                    @endif
                                                </div>
                                                <div class="user_dashboard_listed">
                                                    <strong>Holat:</strong> 
                                                    @if($property->status === 'published')
                                                        <span class="badge bg-success">Nashr qilingan</span>
                                                    @elseif($property->status === 'pending')
                                                        <span class="badge bg-warning">Tasdiqlashda</span>
                                                    @elseif($property->status === 'draft')
                                                        <span class="badge bg-secondary">Qoralama</span>
                                                    @else
                                                        <span class="badge bg-danger">Rad etilgan</span>
                                                    @endif
                                                </div>
                                                <div class="user_dashboard_listed">
                                                    <strong>Tasdiqlash:</strong>
                                                    <span class="badge bg-{{ $approvalMeta['class'] ?? 'secondary' }}">
                                                        {{ $approvalMeta['label'] ?? __('Holat yangilanmoqda') }}
                                                    </span>
                                                    @if($property->approval_status === 'pending' && $property->approval_submitted_at)
                                                        <small class="text-muted ms-2">
                                                            {{ __('Yuborildi: :date', ['date' => $property->approval_submitted_at->format('d.m.Y H:i')]) }}
                                                        </small>
                                                    @endif
                                                    @if($property->approval_status === 'approved' && $property->approval_reviewed_at)
                                                        <small class="text-muted ms-2">
                                                            {{ __('Tasdiqlandi: :date', ['date' => $property->approval_reviewed_at->format('d.m.Y H:i')]) }}
                                                        </small>
                                                    @endif
                                                </div>
                                                @if(!empty($property->approval_notes) && $property->status === 'rejected')
                                                    <div class="alert alert-warning mt-2 mb-1 small">
                                                        <strong>{{ __('Moderator izohi:') }}</strong>
                                                        <span>{{ $property->approval_notes }}</span>
                                                    </div>
                                                @endif
                                                <div class="user_dashboard_listed">
                                                    <small class="text-muted">
                                                        Yaratilgan: {{ $property->created_at->format('d.m.Y H:i') }}
                                                        @if($property->views > 0)
                                                            | Ko'rishlar: {{ $property->views }}
                                                        @endif
                                                    </small>
                                                </div>
                                                <div class="action mt-3">
                                                    <a href="{{ route('provider.properties.edit', $property->id) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-placement="top" 
                                                       title="Tahrirlash">
                                                        <i class="fa-solid fa-pen-to-square"></i> Tahrirlash
                                                    </a>
                                                    <a href="{{ route('property.show', $property->slug) }}" 
                                                       target="_blank"
                                                       class="btn btn-sm btn-info" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-placement="top" 
                                                       title="Ko'rish">
                                                        <i class="fa-regular fa-eye"></i> Ko'rish
                                                    </a>
                                                    <form action="{{ route('provider.properties.destroy', $property->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Bu uy-joyni o\'chirmoqchimisiz?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                data-bs-toggle="tooltip" 
                                                                data-bs-placement="top" 
                                                                title="O'chirish">
                                                            <i class="fa-regular fa-circle-xmark"></i> O'chirish
                                                        </button>
                                                    </form>
                                                    @if(in_array($property->status, ['draft', 'rejected']))
                                                        <form action="{{ route('provider.properties.submit', $property->id) }}"
                                                              method="POST"
                                                              class="d-inline ms-1">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="btn btn-sm btn-success"
                                                                    onclick="return confirm('{{ __('Tasdiqlashga yuborishni tasdiqlaysizmi?') }}');">
                                                                <i class="bi bi-send-check me-1"></i>{{ __('Tasdiqqa yuborish') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($property->status === 'pending')
                                                        <form action="{{ route('provider.properties.withdraw', $property->id) }}"
                                                              method="POST"
                                                              class="d-inline ms-1">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="btn btn-sm btn-outline-warning"
                                                                    onclick="return confirm('{{ __('Tasdiqqa yuborishni bekor qilasizmi?') }}');">
                                                                <i class="bi bi-arrow-counterclockwise me-1"></i>{{ __('Bekor qilish') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                @if(!empty($approvalHistory))
                                                    <div class="mt-3">
                                                        <div class="text-muted small mb-2">{{ __('Tasdiqlash tarixi') }}</div>
                                                        <ul class="list-unstyled mb-0 approval-timeline">
                                                            @foreach(array_slice(array_reverse($approvalHistory), 0, 3) as $event)
                                                                <li class="d-flex align-items-start gap-2 mb-2">
                                                                    <span class="badge bg-light text-dark">
                                                                        {{ \Carbon\Carbon::parse($event['timestamp'])->format('d.m.Y H:i') }}
                                                                    </span>
                                                                    <div>
                                                                        <div class="fw-semibold">
                                                                            {{ $approvalStatusMeta[$event['status']]['label'] ?? ucfirst($event['status']) }}
                                                                        </div>
                                                                        @if(!empty($event['meta']['reason']))
                                                                            <small class="text-muted">{{ $event['meta']['reason'] }}</small>
                                                                        @elseif(!empty($event['meta']['action']))
                                                                            <small class="text-muted">{{ __('Amal: :action', ['action' => $event['meta']['action']]) }}</small>
                                                                        @endif
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($properties->hasPages())
                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <div class="pagination justify-content-center">
                                            {{ $properties->links() }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info text-center">
                                <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 15px;"></i>
                                <h5>Hali uy-joy yo'q</h5>
                                <p>Birinchi uy-joyingizni qo'shing va e'lon qiling!</p>
                                <a href="{{ route('provider.properties.create') }}" class="btn btn-main mt-3">
                                    <i class="bi bi-plus-circle me-1"></i>Yangi uy-joy qo'shish
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================ User Dashboard End ================================== -->
@endsection
