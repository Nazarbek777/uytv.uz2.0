@extends('admin.layout')

@section('title', 'Savol-Javob')
@section('page-title', 'Savol-Javob Ma\'lumotlari')

@section('content')
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Savol #{{ $faq->id }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i>Tahrirlash
            </a>
            <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>O'chirish
                </button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Savol (O'zbekcha)</small>
                <p class="mb-0 fw-semibold">{{ $faq->question_uz }}</p>
            </div>
        </div>
        @if($faq->question_ru)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Savol (Ruscha)</small>
                <p class="mb-0">{{ $faq->question_ru }}</p>
            </div>
        </div>
        @endif
        @if($faq->question_en)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Savol (Inglizcha)</small>
                <p class="mb-0">{{ $faq->question_en }}</p>
            </div>
        </div>
        @endif
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Javob (O'zbekcha)</small>
                <p class="mb-0">{{ $faq->answer_uz }}</p>
            </div>
        </div>
        @if($faq->answer_ru)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Javob (Ruscha)</small>
                <p class="mb-0">{{ $faq->answer_ru }}</p>
            </div>
        </div>
        @endif
        @if($faq->answer_en)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Javob (Inglizcha)</small>
                <p class="mb-0">{{ $faq->answer_en }}</p>
            </div>
        </div>
        @endif
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Kategoriya</small>
                @if($faq->category)
                    <span class="badge bg-info">{{ $faq->category }}</span>
                @else
                    <span class="text-muted">—</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Tartib raqami</small>
                <strong>{{ $faq->sort_order }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Ko'rishlar</small>
                <strong>{{ number_format($faq->views) }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Holat</small>
                @if($faq->is_active)
                    <span class="badge bg-success">Faol</span>
                @else
                    <span class="badge bg-secondary">Nofaol</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Orqaga
    </a>
</div>
@endsection


