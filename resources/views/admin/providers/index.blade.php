@extends('admin.layout')

@section('title', 'Providerlar')
@section('page-title', 'B2C Provider akkountlari')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami providerlar</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
            <small class="text-success"><i class="bi bi-arrow-up-right"></i> Bugun: {{ $stats['today'] }}</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Verified</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['verified']) }}</h4>
            <small class="text-muted">Ishonchli hamkorlar</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Kutilayotgan e'lonlar</p>
            <h4 class="mb-0 text-warning">{{ number_format($stats['pending_listings']) }}</h4>
            <small class="text-muted">Tasdiqlash talab etiladi</small>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Provider akkountlari</h5>
        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $providers->total() }} ta natija</span>
    </div>

    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.providers.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Qidirish (ism, email, telefon)" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="verified" class="form-select">
                    <option value="">Verified holati</option>
                    <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="featured" class="form-select">
                    <option value="">Featured holati</option>
                    <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured</option>
                    <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Oddiy</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Qidirish</button>
                <a href="{{ route('admin.providers.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Provider</th>
                    <th>Kontakt</th>
                    <th>Agentlik</th>
                    <th>E'lonlar</th>
                    <th>Holat</th>
                    <th>Joined</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($providers as $provider)
                    <tr>
                        <td>
                            <a href="{{ route('admin.providers.show', $provider) }}" class="fw-semibold text-decoration-none">
                                {{ $provider->name }}
                            </a>
                        </td>
                        <td>
                            <div class="small">
                                <div><i class="bi bi-envelope me-1 text-muted"></i>{{ $provider->email }}</div>
                                <div><i class="bi bi-telephone me-1 text-muted"></i>{{ $provider->phone ?? '—' }}</div>
                            </div>
                        </td>
                        <td>{{ $provider->company_name ?? 'Agentlik ko\'rsatilmagan' }}</td>
                        <td>
                            <div class="fw-semibold">{{ $provider->properties_count }} ta</div>
                            <small class="text-muted">Pending: {{ $provider->pending_properties_count }} • Published: {{ $provider->published_properties_count }}</small>
                        </td>
                        <td>
                            <form action="{{ route('admin.providers.toggle-verified', $provider) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-shield-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.providers.toggle-featured', $provider) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-star{{ $provider->featured ? '-fill' : '' }}"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{ $provider->created_at?->format('d.m.Y') }}</td>
                        <td>
                            <a href="{{ route('admin.providers.show', $provider) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-emoji-neutral" style="font-size:48px; display:block;"></i>
                            Providerlar topilmadi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($providers->hasPages())
        <div class="p-3 border-top">
            {{ $providers->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

