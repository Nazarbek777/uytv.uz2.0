@extends('admin.layout')

@section('title', 'Yangi Kategoriya')
@section('page-title', 'Yangi Kategoriya Qo\'shish')

@section('content')
<div class="admin-card">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomi (O'zbekcha) <span class="text-danger">*</span></label>
                <input type="text" name="name_uz" class="form-control @error('name_uz') is-invalid @enderror" value="{{ old('name_uz') }}" required>
                @error('name_uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomi (Ruscha)</label>
                <input type="text" name="name_ru" class="form-control @error('name_ru') is-invalid @enderror" value="{{ old('name_ru') }}">
                @error('name_ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomi (Inglizcha)</label>
                <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en') }}">
                @error('name_en')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Turi <span class="text-danger">*</span></label>
                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="property" {{ old('type') == 'property' ? 'selected' : '' }}>Property</option>
                    <option value="development" {{ old('type') == 'development' ? 'selected' : '' }}>Development</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Icon (Bootstrap Icons)</label>
                <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon') }}" placeholder="bi-house-door">
                <small class="text-muted">Masalan: bi-house-door, bi-building</small>
                @error('icon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Tartib raqami</label>
                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" min="0">
                @error('sort_order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Tavsif (O'zbekcha)</label>
                <textarea name="description_uz" class="form-control @error('description_uz') is-invalid @enderror" rows="3">{{ old('description_uz') }}</textarea>
                @error('description_uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Tavsif (Ruscha)</label>
                <textarea name="description_ru" class="form-control @error('description_ru') is-invalid @enderror" rows="3">{{ old('description_ru') }}</textarea>
                @error('description_ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Tavsif (Inglizcha)</label>
                <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="3">{{ old('description_en') }}</textarea>
                @error('description_en')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Faol
                    </label>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Saqlash
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Bekor qilish
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


