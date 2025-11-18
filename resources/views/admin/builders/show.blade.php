@extends('admin.layout')

@section('title', 'Quruvchi tafsilotlari')
@section('page-title', 'Quruvchi profili')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <h4 class="mb-0">{{ $builder->name }}</h4>
                    <small class="text-muted">{{ $builder->company_name ?? 'Kompaniya ko\'rsatilmagan' }}</small>
                </div>
            </div>
            <div class="d-flex gap-2 mb-3 flex-wrap">
                <span class="badge {{ $builder->verified ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                    <i class="bi bi-shield-check me-1"></i>{{ $builder->verified ? 'Verified' : 'Tekshirilmagan' }}
                </span>
                <span class="badge {{ $builder->featured ? 'bg-warning-subtle text-warning' : 'bg-light text-muted' }}">
                    <i class="bi bi-star{{ $builder->featured ? '-fill' : '' }} me-1"></i>Featured
                </span>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Email</div>
                <div class="fw-semibold">{{ $builder->email }}</div>
            </div>
            <div class="mb-3">
                <div class="text-muted small">Telefon</div>
                <div>{{ $builder->phone ?? 'Kiritilmagan' }}</div>
            </div>
            <div class="mb-3">
                <div class="text-muted small">Ro'yxatdan o'tgan</div>
                <div>{{ $builder->created_at?->format('d.m.Y H:i') }}</div>
            </div>

            <div class="d-grid gap-2">
                <form action="{{ route('admin.builders.toggle-verified', $builder) }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-success w-100">
                        <i class="bi bi-shield-check me-1"></i>Verified holatini almashtirish
                    </button>
                </form>
                <form action="{{ route('admin.builders.toggle-featured', $builder) }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-warning w-100">
                        <i class="bi bi-star{{ $builder->featured ? '-fill' : '' }} me-1"></i>Featured holatini almashtirish
                    </button>
                </form>
                <a href="{{ route('admin.builders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Orqaga
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <p class="text-muted mb-2">Loyiha statistikasi</p>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="admin-card p-3 h-100">
                        <div class="text-muted small">Jami loyihalar</div>
                        <div class="fs-4 fw-semibold">{{ $stats['total'] }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="admin-card p-3 h-100">
                        <div class="text-muted small">Kutilayotgan</div>
                        <div class="fs-4 text-warning fw-semibold">{{ $stats['pending'] }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="admin-card p-3 h-100">
                        <div class="text-muted small">Nashr qilingan</div>
                        <div class="fs-4 text-success fw-semibold">{{ $stats['published'] }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="admin-card p-3 h-100">
                        <div class="text-muted small">Rad etilgan</div>
                        <div class="fs-4 text-danger fw-semibold">{{ $stats['rejected'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-table">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">So'nggi loyihalari</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $builder->developments->count() }} ta</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Shahar</th>
                            <th>Holat</th>
                            <th>Sana</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($builder->developments as $development)
                            <tr>
                                <td>{{ $development->title_uz ?? $development->title_ru ?? 'Noma\'lum' }}</td>
                                <td>{{ $development->city ?? '—' }}</td>
                                <td>
                                    <span class="badge-status badge-{{ $development->status }}">
                                        {{ ucfirst($development->status) }}
                                    </span>
                                </td>
                                <td>{{ $development->created_at?->format('d.m.Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.developments.show', $development->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Loyihalar topilmadi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

