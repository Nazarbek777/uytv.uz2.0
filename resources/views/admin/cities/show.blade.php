@extends('admin.layout')

@section('title', 'Shahar')
@section('page-title', 'Shahar Ma\'lumotlari')

@section('content')
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>{{ $city->name_uz }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i>Tahrirlash
            </a>
            <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>O'chirish
                </button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Nomi (O'zbekcha)</small>
                <strong>{{ $city->name_uz }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Nomi (Ruscha)</small>
                <strong>{{ $city->name_ru ?? '—' }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Nomi (Inglizcha)</small>
                <strong>{{ $city->name_en ?? '—' }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Slug</small>
                <code>{{ $city->slug }}</code>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Viloyat</small>
                <strong>{{ $city->region ?? '—' }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Koordinatalar</small>
                @if($city->latitude && $city->longitude)
                    <strong>{{ $city->latitude }}, {{ $city->longitude }}</strong>
                    <br><a href="https://www.google.com/maps?q={{ $city->latitude }},{{ $city->longitude }}" target="_blank" class="btn btn-sm btn-link p-0 mt-1">
                        <i class="bi bi-map"></i> Xaritada ko'rish
                    </a>
                @else
                    <span class="text-muted">—</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Tartib raqami</small>
                <strong>{{ $city->sort_order }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Holat</small>
                @if($city->is_active)
                    <span class="badge bg-success">Faol</span>
                @else
                    <span class="badge bg-secondary">Nofaol</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Orqaga
    </a>
</div>
@endsection


