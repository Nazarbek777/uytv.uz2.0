@extends('admin.layout')

@section('title', 'Quruvchilar')
@section('page-title', 'B2B Quruvchi akkountlari')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami quruvchilar</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
            <small class="text-success"><i class="bi bi-arrow-up-right"></i> Bugun: {{ $stats['today'] }}</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Verified</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['verified']) }}</h4>
            <small class="text-muted">Faol hamkorlar</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Kutilayotgan loyihalar</p>
            <h4 class="mb-0 text-warning">{{ number_format($stats['pending_projects']) }}</h4>
            <small class="text-muted">Tasdiqlash talab etiladi</small>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Quruvchi akkountlari</h5>
        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $builders->total() }} ta natija</span>
    </div>

    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.builders.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Qidirish (ism, email, telefon)" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="verified" class="form-select">
                    <option value="">{{ __('Verified holati') }}</option>
                    <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="featured" class="form-select">
                    <option value="">{{ __('Featured holati') }}</option>
                    <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured</option>
                    <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Oddiy</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Qidirish</button>
                <a href="{{ route('admin.builders.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Quruvchi</th>
                    <th>Kontakt</th>
                    <th>Loyihalar</th>
                    <th>Statistika</th>
                    <th>Holat</th>
                    <th>Joined</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($builders as $builder)
                    <tr>
                        <td>
                            <a href="{{ route('admin.builders.show', $builder) }}" class="fw-semibold text-decoration-none">
                                {{ $builder->name }}
                            </a>
                            <div class="text-muted small">{{ $builder->company_name ?? 'Kompaniya belgilanmagan' }}</div>
                        </td>
                        <td>
                            <div class="small">
                                <div><i class="bi bi-envelope me-1 text-muted"></i>{{ $builder->email }}</div>
                                <div><i class="bi bi-telephone me-1 text-muted"></i>{{ $builder->phone ?? '—' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $builder->developments_count }} ta</div>
                            <small class="text-muted">Pending: {{ $builder->pending_developments_count }} • Published: {{ $builder->published_developments_count }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $builder->verified ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                <i class="bi bi-shield-check me-1"></i>{{ $builder->verified ? 'Verified' : 'Tekshirilmagan' }}
                            </span>
                            <span class="badge {{ $builder->featured ? 'bg-warning-subtle text-warning' : 'bg-light text-muted' }}">
                                <i class="bi bi-star{{ $builder->featured ? '-fill' : '' }} me-1"></i>Featured
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.builders.toggle-verified', $builder) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-shield-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.builders.toggle-featured', $builder) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-star{{ $builder->featured ? '-fill' : '' }}"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{ $builder->created_at?->format('d.m.Y') }}</td>
                        <td>
                            <a href="{{ route('admin.builders.show', $builder) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-emoji-neutral" style="font-size: 48px; display:block;"></i>
                            Quruvchilar topilmadi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($builders->hasPages())
        <div class="p-3 border-top">
            {{ $builders->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection


