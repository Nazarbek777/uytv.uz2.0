@extends('admin.layout')

@section('title', 'Qurilish loyihalari')
@section('page-title', 'B2B (Quruvchi) loyihalar')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami loyihalar</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
            <small class="text-success"><i class="bi bi-arrow-up-right"></i> Bugun: {{ $stats['today'] }}</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Kutilayotgan</p>
            <h4 class="mb-0 text-warning">{{ number_format($stats['pending']) }}</h4>
            <small class="text-muted">Tasdiqlash talab etiladi</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Nashr qilingan</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['published']) }}</h4>
            <small class="text-muted">Faol loyihalar</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Qoralama</p>
            <h4 class="mb-0 text-secondary">{{ number_format($stats['draft']) }}</h4>
            <small class="text-muted">Tahrirlashda</small>
        </div>
    </div>
</div>

<div class="admin-table mb-4">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-bricks me-2"></i>Barcha qurilishlar</h5>
        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $developments->total() }} ta natija</span>
    </div>

    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.developments.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Nom, quruvchi yoki developer" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="city" class="form-control" placeholder="Shahar" value="{{ request('city') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Barcha holatlar</option>
                    @foreach (['draft' => 'Qoralama', 'pending' => 'Kutilayotgan', 'published' => 'Nashr qilingan', 'rejected' => 'Rad etilgan'] as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Qidirish</button>
                    <a href="{{ route('admin.developments.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Quruvchi</th>
                    <th>Shahar</th>
                    <th>Narx (boshl.)</th>
                    <th>Holat</th>
                    <th>Featured</th>
                    <th>Sana</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($developments as $development)
                    @php
                        $title = $development->title_uz ?? $development->title_ru ?? $development->title_en ?? 'Nom berilmagan';
                    @endphp
                    <tr>
                        <td>#{{ $development->id }}</td>
                        <td>
                            <a href="{{ route('admin.developments.show', $development->id) }}" class="fw-semibold text-decoration-none">
                                {{ $title }}
                            </a>
                            <br>
                            <small class="text-muted">{{ $development->developer_name_uz ?? $development->builder?->name }}</small>
                        </td>
                        <td>{{ $development->builder->name ?? 'Noma\'lum' }}</td>
                        <td>{{ $development->city ?? '—' }}</td>
                        <td class="fw-semibold">
                            @if($development->price_from)
                                {{ number_format($development->price_from) }} {{ $development->currency ?? 'USD' }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $development->status }}">
                                {{ ucfirst($development->status) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.developments.toggle-featured', $development->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-{{ $development->featured ? 'success' : 'secondary' }}">
                                    <i class="bi bi-star{{ $development->featured ? '-fill' : '' }}"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{ optional($development->created_at)->format('d.m.Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.developments.show', $development->id) }}" class="btn btn-sm btn-primary" title="Ko'rish">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.developments.edit', $development->id) }}" class="btn btn-sm btn-warning" title="Tahrirlash">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($development->status === 'pending')
                                    <form action="{{ route('admin.developments.approve', $development->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Tasdiqlash">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.developments.reject', $development->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Rad etish">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.developments.destroy', $development->id) }}" method="POST" onsubmit="return confirm('O\'chirishni tasdiqlang');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="O'chirish">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-inbox" style="font-size:48px; display:block; margin-bottom:10px;"></i>
                            Qurilishlar topilmadi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($developments->hasPages())
        <div class="p-3 border-top">
            {{ $developments->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

