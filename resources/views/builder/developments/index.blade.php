@extends('builder.layout')

@section('title', 'Mening Loyihalarim')
@section('page-title', 'B2B Builder Panel - Mening Loyihalarim')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-building me-2"></i>Mening Loyihalarim</h5>
            <a href="{{ route('builder.developments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Yangi Loyiha Qo'shish
            </a>
        </div>
    </div>
</div>

<div class="builder-table">
    <div class="p-3 border-bottom bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Loyihalar Ro'yxati</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Rasm</th>
                    <th>Loyiha Nomi</th>
                    <th>Developer</th>
                    <th>Shahar</th>
                    <th>Narx (dan)</th>
                    <th>Holat</th>
                    <th>Yaratilgan</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($developments as $development)
                <tr>
                    <td>#{{ $development->id }}</td>
                    <td>
                        @if($development->featured_image)
                            <img src="{{ asset('storage/' . $development->featured_image) }}" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('builder.developments.show', $development->id) }}" class="text-decoration-none fw-semibold">
                            {{ $development->title_uz }}
                        </a>
                        @if($development->featured)
                            <span class="badge bg-warning ms-1">Featured</span>
                        @endif
                    </td>
                    <td>{{ $development->developer_name_uz }}</td>
                    <td>{{ $development->city }}<br><small class="text-muted">{{ $development->region }}</small></td>
                    <td class="fw-bold text-main">
                        @if($development->price_from)
                            {{ number_format($development->price_from) }} {{ $development->price_per_sqm ? '/ m²: ' . number_format($development->price_per_sqm) : '' }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($development->status === 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @elseif($development->status === 'pending')
                            <span class="badge bg-warning">Tasdiqlashda</span>
                        @elseif($development->status === 'approved')
                            <span class="badge bg-info">Tasdiqlandi</span>
                        @elseif($development->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @elseif($development->status === 'rejected')
                            <span class="badge bg-danger">Rad etilgan</span>
                        @endif
                        @if($development->rejection_reason)
                            <br><small class="text-danger" title="{{ $development->rejection_reason }}">
                                <i class="bi bi-exclamation-triangle"></i> Sabab
                            </small>
                        @endif
                    </td>
                    <td><small>{{ $development->created_at->format('d.m.Y H:i') }}</small></td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('builder.developments.show', $development->id) }}" class="btn btn-sm btn-info" title="Ko'rish">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(in_array($development->status, ['draft', 'rejected']))
                                <a href="{{ route('builder.developments.edit', $development->id) }}" class="btn btn-sm btn-primary" title="Tahrirlash">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('builder.developments.submit', $development->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Loyihani tasdiqlashga yubormoqchimisiz?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Tasdiqlashga yuborish">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </form>
                                <form action="{{ route('builder.developments.destroy', $development->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu loyihani o\'chirmoqchimisiz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="O'chirish">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-3">Hech qanday loyiha yo'q. <a href="{{ route('builder.developments.create') }}">Yangi loyiha qo'shing</a></p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($developments->hasPages())
    <div class="p-3 border-top">
        {{ $developments->links() }}
    </div>
    @endif
</div>

<!-- Statistika -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="stat-card-icon">
                <i class="bi bi-file-earmark"></i>
            </div>
            <div class="stat-card-value">{{ $developments->where('status', 'draft')->count() }}</div>
            <p class="stat-card-label">Draft</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="stat-card-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-card-value">{{ $developments->where('status', 'pending')->count() }}</div>
            <p class="stat-card-label">Tasdiqlashda</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-card-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-card-value">{{ $developments->where('status', 'approved')->count() }}</div>
            <p class="stat-card-label">Tasdiqlandi</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-card-icon">
                <i class="bi bi-globe"></i>
            </div>
            <div class="stat-card-value">{{ $developments->where('status', 'published')->count() }}</div>
            <p class="stat-card-label">Published</p>
        </div>
    </div>
</div>
@endsection

