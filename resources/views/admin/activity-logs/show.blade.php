@extends('admin.layout')

@section('title', 'Log Ma\'lumotlari')
@section('page-title', 'Log Ma\'lumotlari')

@section('content')
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Log #{{ $activityLog->id }}</h5>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.activity-logs.destroy', $activityLog) }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
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
                <small class="text-muted d-block mb-1">Foydalanuvchi</small>
                @if($activityLog->user)
                    <strong>{{ $activityLog->user->name }}</strong>
                    <br><small class="text-muted">{{ $activityLog->user->email }}</small>
                @else
                    <span class="text-muted">Sistema</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Amal</small>
                <span class="badge bg-{{ $activityLog->action == 'created' ? 'success' : ($activityLog->action == 'updated' ? 'warning' : ($activityLog->action == 'deleted' ? 'danger' : 'info')) }}">
                    {{ $activityLog->action }}
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Model</small>
                <strong>{{ class_basename($activityLog->model_type) }}</strong>
                @if($activityLog->model_id)
                    <br><code>ID: {{ $activityLog->model_id }}</code>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Sana va vaqt</small>
                <strong>{{ $activityLog->created_at->format('d.m.Y H:i:s') }}</strong>
            </div>
        </div>
        @if($activityLog->description)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Tavsif</small>
                <p class="mb-0">{{ $activityLog->description }}</p>
            </div>
        </div>
        @endif
        @if($activityLog->old_values)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Eski qiymatlar</small>
                <pre class="mb-0 bg-light p-2 rounded" style="max-height: 200px; overflow-y: auto;"><code>{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
        </div>
        @endif
        @if($activityLog->new_values)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Yangi qiymatlar</small>
                <pre class="mb-0 bg-light p-2 rounded" style="max-height: 200px; overflow-y: auto;"><code>{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
        </div>
        @endif
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">IP Manzil</small>
                <strong>{{ $activityLog->ip_address ?? '—' }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">User Agent</small>
                <small>{{ $activityLog->user_agent ?? '—' }}</small>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Orqaga
    </a>
</div>
@endsection

