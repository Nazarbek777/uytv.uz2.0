@extends('admin.layout')

@section('title', 'Chatbot Log Ma\'lumotlari')
@section('page-title', 'Chatbot Log Ma\'lumotlari')

@section('content')
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="bi bi-robot me-2"></i>Log #{{ $aiChatbotLog->id }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.ai.chatbot-analytics.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Orqaga
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Session ID</small>
                <code>{{ $aiChatbotLog->session_id ?? '—' }}</code>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Til</small>
                <span class="badge bg-info">{{ strtoupper($aiChatbotLog->locale) }}</span>
            </div>
        </div>
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Foydalanuvchi xabari</small>
                <p class="mb-0 fw-semibold">{{ $aiChatbotLog->user_message }}</p>
            </div>
        </div>
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">AI javobi</small>
                <p class="mb-0">{{ $aiChatbotLog->ai_response }}</p>
            </div>
        </div>
        @if($aiChatbotLog->properties_suggested && count($aiChatbotLog->properties_suggested) > 0)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-2">Taklif qilingan uy-joylar ({{ count($aiChatbotLog->properties_suggested) }} ta)</small>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($aiChatbotLog->properties_suggested as $propertyId)
                        <a href="{{ route('admin.properties.show', $propertyId) }}" class="badge bg-primary text-decoration-none">
                            #{{ $propertyId }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Response Time</small>
                <strong>{{ number_format($aiChatbotLog->response_time_ms ?? 0) }}ms</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Holat</small>
                @if($aiChatbotLog->success)
                    <span class="badge bg-success">Muvaffaqiyatli</span>
                @else
                    <span class="badge bg-danger">Xato</span>
                @endif
            </div>
        </div>
        @if($aiChatbotLog->error_message)
        <div class="col-12">
            <div class="border rounded p-3 bg-danger bg-opacity-10">
                <small class="text-muted d-block mb-2">Xato xabari</small>
                <p class="mb-0 text-danger">{{ $aiChatbotLog->error_message }}</p>
            </div>
        </div>
        @endif
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">IP Manzil</small>
                <strong>{{ $aiChatbotLog->ip_address ?? '—' }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">Sana va vaqt</small>
                <strong>{{ $aiChatbotLog->created_at->format('d.m.Y H:i:s') }}</strong>
            </div>
        </div>
        @if($aiChatbotLog->user_agent)
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block mb-1">User Agent</small>
                <small>{{ $aiChatbotLog->user_agent }}</small>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('admin.ai.chatbot-analytics.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Orqaga
    </a>
</div>
@endsection


