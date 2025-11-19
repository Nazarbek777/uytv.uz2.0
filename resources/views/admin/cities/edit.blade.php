@extends('admin.layout')

@section('title', 'Shaharni Tahrirlash')
@section('page-title', 'Shaharni Tahrirlash')

@section('content')
<div class="admin-card">
    <form action="{{ route('admin.cities.update', $city) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomi (O'zbekcha) <span class="text-danger">*</span></label>
                <input type="text" name="name_uz" class="form-control @error('name_uz') is-invalid @enderror" value="{{ old('name_uz', $city->name_uz) }}" required>
                @error('name_uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomi (Ruscha)</label>
                <input type="text" name="name_ru" class="form-control @error('name_ru') is-invalid @enderror" value="{{ old('name_ru', $city->name_ru) }}">
                @error('name_ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomi (Inglizcha)</label>
                <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $city->name_en) }}">
                @error('name_en')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Viloyat</label>
                <input type="text" name="region" class="form-control @error('region') is-invalid @enderror" value="{{ old('region', $city->region) }}" placeholder="Masalan: Toshkent viloyati">
                @error('region')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Latitude</label>
                <input type="number" step="0.00000001" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $city->latitude) }}" placeholder="41.311081">
                @error('latitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Longitude</label>
                <input type="number" step="0.00000001" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $city->longitude) }}" placeholder="69.240562">
                @error('longitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Tartib raqami</label>
                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $city->sort_order) }}" min="0">
                @error('sort_order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $city->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Faol
                    </label>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Yangilash
                    </button>
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Bekor qilish
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

