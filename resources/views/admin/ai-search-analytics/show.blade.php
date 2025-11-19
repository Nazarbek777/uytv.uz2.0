@extends('admin.layout')

@section('title', 'Search Log Ma\'lumotlari')
@section('page-title', 'Search Log Ma\'lumotlari')

@section('content')
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="bi bi-search me-2"></i>Log #{{ $aiSearchLog->id }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.ai.search-analytics.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Orqaga
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Qidiruv so'rovi</small>
                <p class="mb-0 fw-semibold">{{ $aiSearchLog->query }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Til</small>
                <span class="badge bg-info">{{ strtoupper($aiSearchLog->locale) }}</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Natijalar soni</small>
                <h4 class="mb-0 text-{{ $aiSearchLog->results_count > 0 ? 'success' : 'secondary' }}">{{ number_format($aiSearchLog->results_count) }}</h4>
            </div>
        </div>
        @if($aiSearchLog->ai_parsed_filters)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">AI tomonidan tahlil qilingan filtrlash</small>
                <pre class="mb-0 bg-light p-2 rounded" style="max-height: 200px; overflow-y: auto;"><code>{{ json_encode($aiSearchLog->ai_parsed_filters, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
        </div>
        @endif
        @if($aiSearchLog->properties_found && count($aiSearchLog->properties_found) > 0)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Topilgan uy-joylar ({{ count($aiSearchLog->properties_found) }} ta)</small>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(array_slice($aiSearchLog->properties_found, 0, 20) as $propertyId)
                        <a href="{{ route('admin.properties.show', $propertyId) }}" class="badge bg-primary text-decoration-none">
                            #{{ $propertyId }}
                        </a>
                    @endforeach
                    @if(count($aiSearchLog->properties_found) > 20)
                        <span class="badge bg-secondary">+{{ count($aiSearchLog->properties_found) - 20 }} ta</span>
                    @endif
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Response Time</small>
                <strong>{{ number_format($aiSearchLog->response_time_ms ?? 0) }}ms</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Holat</small>
                @if($aiSearchLog->success)
                    <span class="badge bg-success">Muvaffaqiyatli</span>
                @else
                    <span class="badge bg-danger">Xato</span>
                @endif
            </div>
        </div>
        @if($aiSearchLog->error_message)
        <div class="col-12">
            <div class="border rounded p-3 bg-danger bg-opacity-10">
                <small class="text-muted d-block mb-2">Xato xabari</small>
                <p class="mb-0 text-danger">{{ $aiSearchLog->error_message }}</p>
            </div>
        </div>
        @endif
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">IP Manzil</small>
                <strong>{{ $aiSearchLog->ip_address ?? '—' }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Sana va vaqt</small>
                <strong>{{ $aiSearchLog->created_at->format('d.m.Y H:i:s') }}</strong>
            </div>
        </div>
        @if($aiSearchLog->user_agent)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">User Agent</small>
                <small>{{ $aiSearchLog->user_agent }}</small>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('admin.ai.search-analytics.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Orqaga
    </a>
</div>
@endsection


