@extends('admin.layout')

@section('title', 'FAQ')
@section('page-title', 'Savol-Javoblar Boshqaruvi')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami savollar</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Faol savollar</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['active']) }}</h4>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Barcha savol-javoblar</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Yangi savol
            </a>
        </div>
    </div>

    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.faqs.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Qidirish..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Barcha kategoriyalar</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Qidirish</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary w-100"><i class="bi bi-x-circle me-1"></i>Tozalash</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Savol (UZ)</th>
                    <th>Kategoriya</th>
                    <th>Ko'rishlar</th>
                    <th>Tartib</th>
                    <th>Holat</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                <tr>
                    <td>#{{ $faq->id }}</td>
                    <td>
                        <strong>{{ Str::limit($faq->question_uz, 60) }}</strong>
                    </td>
                    <td>
                        @if($faq->category)
                            <span class="badge bg-info">{{ $faq->category }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ number_format($faq->views) }}</td>
                    <td>{{ $faq->sort_order }}</td>
                    <td>
                        @if($faq->is_active)
                            <span class="badge bg-success">Faol</span>
                        @else
                            <span class="badge bg-secondary">Nofaol</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm btn-warning" title="Tahrirlash">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
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
                        Savol-javoblar topilmadi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($faqs->hasPages())
    <div class="p-3 border-top">
        {{ $faqs->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection

