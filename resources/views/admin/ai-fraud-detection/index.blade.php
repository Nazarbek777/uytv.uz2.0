@extends('admin.layout')

@section('title', 'AI Fraud Detection')
@section('page-title', 'AI Yolg\'onlik Aniqlash')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami tekshiruvlar</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Yuqori xavf</p>
            <h4 class="mb-0 text-danger">{{ number_format($stats['high_risk']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">O'rtacha xavf</p>
            <h4 class="mb-0 text-warning">{{ number_format($stats['medium_risk']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Past xavf</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['low_risk']) }}</h4>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-shield-exclamation me-2"></i>Yolg'onlik tekshiruvlari</h5>
    </div>

    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.ai.fraud-detection.index') }}" class="row g-3">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Barcha holatlar</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Kutilayotgan</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Ko'rib chiqilgan</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Tasdiqlangan</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rad etilgan</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="min_score" class="form-control" placeholder="Min xavf skori" value="{{ request('min_score') }}" min="0" max="100">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Qidirish</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.ai.fraud-detection.index') }}" class="btn btn-secondary w-100"><i class="bi bi-x-circle me-1"></i>Tozalash</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Model</th>
                    <th>Xavf skori</th>
                    <th>Muammolar</th>
                    <th>Holat</th>
                    <th>Sana</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detections as $detection)
                <tr>
                    <td>#{{ $detection->id }}</td>
                    <td><small>{{ class_basename($detection->model_type) }} #{{ $detection->model_id }}</small></td>
                    <td>
                        <span class="badge bg-{{ $detection->fraud_score >= 70 ? 'danger' : ($detection->fraud_score >= 40 ? 'warning' : 'success') }}">
                            {{ number_format($detection->fraud_score, 1) }}%
                        </span>
                    </td>
                    <td>
                        @if($detection->detected_issues)
                            <small>{{ count($detection->detected_issues) }} ta muammo</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $detection->status == 'pending' ? 'warning' : ($detection->status == 'approved' ? 'success' : 'danger') }}">
                            {{ ucfirst($detection->status) }}
                        </span>
                    </td>
                    <td><small>{{ $detection->created_at->format('d.m.Y H:i') }}</small></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="reviewDetection({{ $detection->id }})">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                        Tekshiruvlar topilmadi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($detections->hasPages())
    <div class="p-3 border-top">
        {{ $detections->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection


