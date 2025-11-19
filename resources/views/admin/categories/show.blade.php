@extends('admin.layout')

@section('title', 'Kategoriya')
@section('page-title', 'Kategoriya Ma\'lumotlari')

@section('content')
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="bi bi-tag me-2"></i>{{ $category->name_uz }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i>Tahrirlash
            </a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
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
                <strong>{{ $category->name_uz }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Nomi (Ruscha)</small>
                <strong>{{ $category->name_ru ?? '—' }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Nomi (Inglizcha)</small>
                <strong>{{ $category->name_en ?? '—' }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Slug</small>
                <code>{{ $category->slug }}</code>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Turi</small>
                <span class="badge bg-{{ $category->type == 'property' ? 'primary' : 'info' }}">
                    {{ $category->type }}
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Icon</small>
                @if($category->icon)
                    <i class="bi {{ $category->icon }}"></i> {{ $category->icon }}
                @else
                    <span class="text-muted">—</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Tartib raqami</small>
                <strong>{{ $category->sort_order }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Holat</small>
                @if($category->is_active)
                    <span class="badge bg-success">Faol</span>
                @else
                    <span class="badge bg-secondary">Nofaol</span>
                @endif
            </div>
        </div>
        @if($category->description_uz)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Tavsif (O'zbekcha)</small>
                <p class="mb-0">{{ $category->description_uz }}</p>
            </div>
        </div>
        @endif
        @if($category->description_ru)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Tavsif (Ruscha)</small>
                <p class="mb-0">{{ $category->description_ru }}</p>
            </div>
        </div>
        @endif
        @if($category->description_en)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Tavsif (Inglizcha)</small>
                <p class="mb-0">{{ $category->description_en }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Orqaga
    </a>
</div>
@endsection

