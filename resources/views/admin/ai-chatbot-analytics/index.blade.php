@extends('admin.layout')

@section('title', 'AI Chatbot Analytics')
@section('page-title', 'AI Chatbot Analytics')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami so'rovlar</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Bugungi</p>
            <h4 class="mb-0 text-primary">{{ number_format($stats['today']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Muvaffaqiyatli</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['successful']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">O'rtacha vaqt</p>
            <h4 class="mb-0 text-info">{{ number_format($stats['avg_response_time'] ?? 0) }}ms</h4>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="admin-card">
            <h6 class="mb-3">Eng ko'p so'ralgan savollar (30 kun)</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Savol</th>
                            <th>Soni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topQueries as $query)
                        <tr>
                            <td><small>{{ Str::limit($query->user_message, 50) }}</small></td>
                            <td><strong>{{ $query->count }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Ma'lumot yo'q</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-card">
            <h6 class="mb-3">Tillar bo'yicha (30 kun)</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Til</th>
                            <th>Soni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($localeStats as $locale => $count)
                        <tr>
                            <td><strong>{{ strtoupper($locale) }}</strong></td>
                            <td><strong>{{ number_format($count) }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Ma'lumot yo'q</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-robot me-2"></i>Chatbot Log'lari</h5>
    </div>

    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.ai.chatbot-analytics.index') }}" class="row g-3">
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
                    <th>Foydalanuvchi xabari</th>
                    <th>AI javobi</th>
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
                    <td><small>{{ Str::limit($log->user_message, 50) }}</small></td>
                    <td><small>{{ Str::limit($log->ai_response, 50) }}</small></td>
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
                        <a href="{{ route('admin.ai.chatbot-analytics.show', $log) }}" class="btn btn-sm btn-primary">
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


