@extends('admin.layout')

@section('title', 'Shaharlar')
@section('page-title', 'Shaharlar Boshqaruvi')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami shaharlar</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Faol shaharlar</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['active']) }}</h4>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Barcha shaharlar</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Yangi shahar
            </a>
        </div>
    </div>

    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.cities.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Qidirish..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="region" class="form-select">
                    <option value="">Barcha viloyatlar</option>
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Qidirish</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary w-100"><i class="bi bi-x-circle me-1"></i>Tozalash</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nomi (UZ)</th>
                    <th>Viloyat</th>
                    <th>Koordinatalar</th>
                    <th>Tartib</th>
                    <th>Holat</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cities as $city)
                <tr>
                    <td>#{{ $city->id }}</td>
                    <td>
                        <strong>{{ $city->name_uz }}</strong>
                        @if($city->name_ru || $city->name_en)
                            <br><small class="text-muted">
                                @if($city->name_ru) RU: {{ $city->name_ru }} @endif
                                @if($city->name_en) EN: {{ $city->name_en }} @endif
                            </small>
                        @endif
                    </td>
                    <td>{{ $city->region ?? '—' }}</td>
                    <td>
                        @if($city->latitude && $city->longitude)
                            <small>{{ $city->latitude }}, {{ $city->longitude }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $city->sort_order }}</td>
                    <td>
                        @if($city->is_active)
                            <span class="badge bg-success">Faol</span>
                        @else
                            <span class="badge bg-secondary">Nofaol</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-sm btn-warning" title="Tahrirlash">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
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
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                        Shaharlar topilmadi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($cities->hasPages())
    <div class="p-3 border-top">
        {{ $cities->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection

