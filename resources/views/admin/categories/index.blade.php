@extends('admin.layout')

@section('title', 'Kategoriyalar')
@section('page-title', 'Kategoriyalar Boshqaruvi')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami kategoriyalar</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Property turlari</p>
            <h4 class="mb-0 text-primary">{{ number_format($stats['property']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Development turlari</p>
            <h4 class="mb-0 text-info">{{ number_format($stats['development']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Faol kategoriyalar</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['active']) }}</h4>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Barcha kategoriyalar</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Yangi kategoriya
            </a>
        </div>
    </div>

    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Qidirish..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">Barcha turlar</option>
                    <option value="property" {{ request('type') == 'property' ? 'selected' : '' }}>Property</option>
                    <option value="development" {{ request('type') == 'development' ? 'selected' : '' }}>Development</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Qidirish</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary w-100"><i class="bi bi-x-circle me-1"></i>Tozalash</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nomi (UZ)</th>
                    <th>Slug</th>
                    <th>Turi</th>
                    <th>Icon</th>
                    <th>Tartib</th>
                    <th>Holat</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>#{{ $category->id }}</td>
                    <td>
                        <strong>{{ $category->name_uz }}</strong>
                        @if($category->name_ru || $category->name_en)
                            <br><small class="text-muted">
                                @if($category->name_ru) RU: {{ $category->name_ru }} @endif
                                @if($category->name_en) EN: {{ $category->name_en }} @endif
                            </small>
                        @endif
                    </td>
                    <td><code>{{ $category->slug }}</code></td>
                    <td>
                        <span class="badge bg-{{ $category->type == 'property' ? 'primary' : 'info' }}">
                            {{ $category->type }}
                        </span>
                    </td>
                    <td>
                        @if($category->icon)
                            <i class="bi {{ $category->icon }}"></i>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $category->sort_order }}</td>
                    <td>
                        @if($category->is_active)
                            <span class="badge bg-success">Faol</span>
                        @else
                            <span class="badge bg-secondary">Nofaol</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning" title="Tahrirlash">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
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
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                        Kategoriyalar topilmadi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
    <div class="p-3 border-top">
        {{ $categories->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection

