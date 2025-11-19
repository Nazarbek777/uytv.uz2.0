@extends('admin.layout')

@section('title', 'Faollik Jurnali')
@section('page-title', 'Faollik Jurnali')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami log'lar</p>
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
            <p class="text-muted mb-1">Bu hafta</p>
            <h4 class="mb-0 text-info">{{ number_format($stats['this_week']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Bu oy</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['this_month']) }}</h4>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Faollik jurnali</h5>
        <form action="{{ route('admin.activity-logs.clear') }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham eski log\'larni o\'chirmoqchimisiz?');">
            @csrf
            <input type="hidden" name="days" value="30">
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-trash me-1"></i>30 kundan eski log'larni o'chirish
            </button>
        </form>
    </div>

    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Qidirish..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="action" class="form-select">
                    <option value="">Barcha amallar</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="model_type" class="form-select">
                    <option value="">Barcha modellar</option>
                    @foreach($modelTypes as $modelType)
                        <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>{{ class_basename($modelType) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="days" class="form-control" placeholder="Kunlar" value="{{ request('days', 7) }}" min="1" max="365">
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
                    <th>Foydalanuvchi</th>
                    <th>Amal</th>
                    <th>Model</th>
                    <th>Tavsif</th>
                    <th>IP</th>
                    <th>Sana</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>#{{ $log->id }}</td>
                    <td>
                        @if($log->user)
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ $log->user->name }}</span>
                                <small class="text-muted">{{ $log->user->email }}</small>
                            </div>
                        @else
                            <span class="text-muted">Sistema</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $log->action == 'created' ? 'success' : ($log->action == 'updated' ? 'warning' : ($log->action == 'deleted' ? 'danger' : 'info')) }}">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td>
                        <small>{{ class_basename($log->model_type) }}</small>
                        @if($log->model_id)
                            <br><code>#{{ $log->model_id }}</code>
                        @endif
                    </td>
                    <td>
                        <small>{{ Str::limit($log->description ?? '—', 50) }}</small>
                    </td>
                    <td><small class="text-muted">{{ $log->ip_address ?? '—' }}</small></td>
                    <td>
                        <small>{{ $log->created_at->format('d.m.Y H:i') }}</small>
                    </td>
                    <td>
                        <a href="{{ route('admin.activity-logs.show', $log) }}" class="btn btn-sm btn-primary" title="Ko'rish">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
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

