@extends('admin.layout')

@section('title', 'Uy-joylar')
@section('page-title', 'Uy-joylar Boshqaruvi')

@section('content')
@php
    $approvalStatusMap = [
        'draft' => ['label' => 'Qoralama', 'class' => 'secondary'],
        'pending' => ['label' => 'Tasdiqlashda', 'class' => 'warning'],
        'approved' => ['label' => 'Tasdiqlangan', 'class' => 'success'],
        'needs_changes' => ['label' => 'Qayta ishlash kerak', 'class' => 'danger'],
        'rejected' => ['label' => 'Rad etilgan', 'class' => 'danger'],
    ];
@endphp
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami e'lonlar</p>
            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
            <small class="text-success"><i class="bi bi-arrow-up-right"></i> Bugun: {{ $stats['today'] }}</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Kutilayotgan</p>
            <h4 class="mb-0 text-warning">{{ number_format($stats['pending']) }}</h4>
            <small class="text-muted">Tasdiqlash talab etiladi</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Nashr qilingan</p>
            <h4 class="mb-0 text-success">{{ number_format($stats['published']) }}</h4>
            <small class="text-muted">Faol e'lonlar</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Rad etilgan</p>
            <h4 class="mb-0 text-danger">{{ number_format($stats['rejected']) }}</h4>
            <small class="text-muted">Ko'rib chiqish kerak</small>
        </div>
    </div>
</div>

<div class="admin-table mb-4">
    <div class="p-4 border-bottom">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0"><i class="bi bi-house-door me-2"></i>Barcha uy-joylar</h5>
            <span class="badge bg-primary bg-opacity-10 text-primary">{{ $properties->total() }} ta natija</span>
        </div>
    </div>
    
    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.properties.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Qidirish..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Barcha holatlar</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Nashr qilingan</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Kutilayotgan</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Qoralama</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rad etilgan</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="property_type" class="form-select">
                    <option value="">Barcha turlar</option>
                    <option value="apartment" {{ request('property_type') == 'apartment' ? 'selected' : '' }}>Kvartira</option>
                    <option value="house" {{ request('property_type') == 'house' ? 'selected' : '' }}>Uy</option>
                    <option value="villa" {{ request('property_type') == 'villa' ? 'selected' : '' }}>Villa</option>
                    <option value="land" {{ request('property_type') == 'land' ? 'selected' : '' }}>Yer</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="listing_type" class="form-select">
                    <option value="">Barcha</option>
                    <option value="sale" {{ request('listing_type') == 'sale' ? 'selected' : '' }}>Sotish</option>
                    <option value="rent" {{ request('listing_type') == 'rent' ? 'selected' : '' }}>Ijaraga</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Qidirish</button>
                <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i>Tozalash</a>
            </div>
        </form>
    </div>
    
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Rasm</th>
                <th>Sarlavha</th>
                <th>Egasi</th>
                <th>Narx</th>
                <th>Holat</th>
                <th>Tasdiqlash</th>
                <th>Featured</th>
                <th>Verified</th>
                <th>Sana</th>
                <th>Amallar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($properties as $property)
            @php
                $title = $property->translate('uz')->title
                    ?? $property->translate('ru')->title
                    ?? $property->translate('en')->title
                    ?? 'Sarlavha belgilanmagan';
            @endphp
            <tr>
                <td>#{{ $property->id }}</td>
                <td>
                    @if($property->featured_image)
                        <img src="{{ asset('storage/' . $property->featured_image) }}" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                    @else
                        <div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.properties.show', $property->id) }}" class="text-decoration-none fw-semibold">
                        {{ $title }}
                    </a>
                    <div class="text-muted small">
                        <i class="bi bi-geo-alt me-1"></i>{{ $property->city ?? 'Noma\'lum joy' }}
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-semibold">{{ $property->user->name ?? 'N/A' }}</span>
                        <small class="text-muted">{{ $property->user->email ?? '' }}</small>
                    </div>
                </td>
                <td class="fw-bold text-main">{{ number_format($property->price, 0, '.', ' ') }} {{ $property->currency }}</td>
                <td>
                    <span class="badge-status badge-{{ $property->status }}">
                        {{ ucfirst($property->status) }}
                    </span>
                </td>
                <td>
                    @php
                        $approvalStatus = $property->approval_status ?? ($property->status === 'published' ? 'approved' : 'draft');
                        if ($property->status === 'rejected') {
                            $approvalStatus = 'needs_changes';
                        }
                        $approvalBadge = $approvalStatusMap[$approvalStatus] ?? ['label' => ucfirst($approvalStatus), 'class' => 'secondary'];
                    @endphp
                    <span class="badge bg-{{ $approvalBadge['class'] }}">
                        {{ $approvalBadge['label'] }}
                    </span>
                    @if($property->approval_status === 'pending' && $property->approval_submitted_at)
                        <div class="small text-muted">
                            {{ $property->approval_submitted_at->format('d.m') }}
                        </div>
                    @endif
                    @if($property->approval_status === 'approved' && $property->approval_reviewed_at)
                        <div class="small text-muted text-success">
                            {{ $property->approval_reviewed_at->format('d.m') }}
                        </div>
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.properties.toggle-featured', $property->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $property->featured ? 'success' : 'secondary' }}">
                            <i class="bi bi-star{{ $property->featured ? '-fill' : '' }}"></i>
                        </button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('admin.properties.toggle-verified', $property->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $property->verified ? 'success' : 'secondary' }}">
                            <i class="bi bi-check-circle{{ $property->verified ? '-fill' : '' }}"></i>
                        </button>
                    </form>
                </td>
                <td>{{ $property->created_at->format('d.m.Y') }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-sm btn-primary" title="Ko'rish">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-sm btn-warning" title="Tahrirlash">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($property->status == 'pending')
                            <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" title="Tasdiqlash">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.properties.reject', $property->id) }}" method="POST" class="d-inline js-reject-form">
                                @csrf
                                <input type="hidden" name="reason">
                                <button type="button" class="btn btn-sm btn-danger js-reject-btn" title="Rad etish" data-title="{{ $title }}">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
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
                <td colspan="10" class="text-center text-muted py-5">
                    <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                    Uy-joylar topilmadi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    @if($properties->hasPages())
    <div class="p-3 border-top">
        {{ $properties->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-reject-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const form = this.closest('form');
            if (!form) {
                return;
            }
            const title = this.dataset.title ? ` (${this.dataset.title})` : '';
            const reason = prompt(`Rad etish sababi${title}:`);
            if (reason && reason.trim().length) {
                const input = form.querySelector('input[name="reason"]');
                input.value = reason.trim();
                form.submit();
            }
        });
    });
});
</script>
@endpush




