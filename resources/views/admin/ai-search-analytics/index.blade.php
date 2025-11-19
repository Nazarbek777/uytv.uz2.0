@extends('admin.layout')

@section('title', 'AI Search Analytics')
@section('page-title', 'AI Search Analytics')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami qidiruvlar</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Natijali</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['with_results']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">O'rtacha natijalar</p>
            <h4 class="mb-0 text-info">{{ number_format($stats['avg_results'] ?? 0) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">O'rtacha vaqt</p>
            <h4 class="mb-0 text-primary">{{ number_format($stats['avg_response_time'] ?? 0) }}ms</h4>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-search me-2"></i>Search Log'lari</h5>
    </div>

    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.ai.search-analytics.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Qidirish..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="locale" class="form-select">
                    <option value="">Barcha tillar</option>
                    <option value="uz" {{ request('locale') == 'uz' ? 'selected' : '' }}>O'zbek</option>
                    <option value="ru" {{ request('locale') == 'ru' ? 'selected' : '' }}>Rus</option>
                    <option value="en" {{ request('locale') == 'en' ? 'selected' : '' }}>Ingliz</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="days" class="form-control" placeholder="Kunlar" value="{{ request('days', $days) }}" min="1" max="365">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Qidirish</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Qidiruv so'rovi</th>
                    <th>Natijalar</th>
                    <th>Til</th>
                    <th>Vaqt</th>
                    <th>Holat</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>#{{ $log->id }}</td>
                    <td><small>{{ Str::limit($log->query, 60) }}</small></td>
                    <td><span class="badge bg-{{ $log->results_count > 0 ? 'success' : 'secondary' }}">{{ $log->results_count }}</span></td>
                    <td><span class="badge bg-info">{{ strtoupper($log->locale) }}</span></td>
                    <td><small>{{ $log->response_time_ms ?? '—' }}ms</small></td>
                    <td>
                        @if($log->success)
                            <span class="badge bg-success">Muvaffaqiyatli</span>
                        @else
                            <span class="badge bg-danger">Xato</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.ai.search-analytics.show', $log) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                        Log'lar topilmadi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="p-3 border-top">
        {{ $logs->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection


