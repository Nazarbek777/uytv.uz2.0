@extends('admin.layout')

@section('title', 'AI Content Generator')
@section('page-title', 'AI Kontent Yaratuvchi')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami yaratilgan</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Ishlatilgan</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['used']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami tokenlar</p>
            <h4 class="mb-0 text-info">{{ number_format($stats['total_tokens']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami xarajat</p>
            <h4 class="mb-0 text-warning">${{ number_format($stats['total_cost'], 4) }}</h4>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4 border-bottom">
        <h5 class="mb-0"><i class="bi bi-magic me-2"></i>Yaratilgan kontentlar</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Model</th>
                    <th>Kontent turi</th>
                    <th>Til</th>
                    <th>Tokenlar</th>
                    <th>Xarajat</th>
                    <th>Holat</th>
                    <th>Sana</th>
                </tr>
            </thead>
            <tbody>
                @forelse($generations as $gen)
                <tr>
                    <td>#{{ $gen->id }}</td>
                    <td><small>{{ class_basename($gen->model_type) }} #{{ $gen->model_id }}</small></td>
                    <td><span class="badge bg-primary">{{ $gen->content_type }}</span></td>
                    <td><span class="badge bg-info">{{ strtoupper($gen->locale) }}</span></td>
                    <td>{{ number_format($gen->tokens_used ?? 0) }}</td>
                    <td>${{ number_format($gen->cost ?? 0, 4) }}</td>
                    <td>
                        @if($gen->is_used)
                            <span class="badge bg-success">Ishlatilgan</span>
                        @else
                            <span class="badge bg-secondary">Ishlatilmagan</span>
                        @endif
                    </td>
                    <td><small>{{ $gen->created_at->format('d.m.Y H:i') }}</small></td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                        Kontentlar topilmadi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($generations->hasPages())
    <div class="p-3 border-top">
        {{ $generations->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection


