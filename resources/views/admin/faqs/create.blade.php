@extends('admin.layout')

@section('title', 'Yangi Savol')
@section('page-title', 'Yangi Savol Qo\'shish')

@section('content')
<div class="admin-card">
    <form action="{{ route('admin.faqs.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Savol (O'zbekcha) <span class="text-danger">*</span></label>
                <textarea name="question_uz" class="form-control @error('question_uz') is-invalid @enderror" rows="3" required>{{ old('question_uz') }}</textarea>
                @error('question_uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Savol (Ruscha)</label>
                <textarea name="question_ru" class="form-control @error('question_ru') is-invalid @enderror" rows="3">{{ old('question_ru') }}</textarea>
                @error('question_ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Savol (Inglizcha)</label>
                <textarea name="question_en" class="form-control @error('question_en') is-invalid @enderror" rows="3">{{ old('question_en') }}</textarea>
                @error('question_en')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Javob (O'zbekcha) <span class="text-danger">*</span></label>
                <textarea name="answer_uz" class="form-control @error('answer_uz') is-invalid @enderror" rows="5" required>{{ old('answer_uz') }}</textarea>
                @error('answer_uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Javob (Ruscha)</label>
                <textarea name="answer_ru" class="form-control @error('answer_ru') is-invalid @enderror" rows="5">{{ old('answer_ru') }}</textarea>
                @error('answer_ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Javob (Inglizcha)</label>
                <textarea name="answer_en" class="form-control @error('answer_en') is-invalid @enderror" rows="5">{{ old('answer_en') }}</textarea>
                @error('answer_en')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Kategoriya</label>
                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" placeholder="Masalan: general, property, payment">
                @error('category')
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
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Bekor qilish
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


