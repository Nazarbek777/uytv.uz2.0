@extends('admin.layout')

@section('title', 'Uy-joy Tafsilotlari')
@section('page-title', 'Uy-joy Tafsilotlari')

@section('content')
<div class="row">
    <div class="col-lg-8">
        @php
            $title = $property->translate('uz')->title
                ?? $property->translate('ru')->title
                ?? $property->translate('en')->title
                ?? 'Sarlavha belgilanmagan';
            $description = $property->translate('uz')->description
                ?? $property->translate('ru')->description
                ?? $property->translate('en')->description
                ?? null;
            $approvalStatusMap = [
                'draft' => ['label' => 'Qoralama', 'class' => 'secondary'],
                'pending' => ['label' => 'Tasdiqlashda', 'class' => 'warning'],
                'approved' => ['label' => 'Tasdiqlangan', 'class' => 'success'],
                'needs_changes' => ['label' => 'Qayta ishlash kerak', 'class' => 'danger'],
                'rejected' => ['label' => 'Rad etilgan', 'class' => 'danger'],
            ];
            $computedApproval = $property->approval_status ?? ($property->status === 'published' ? 'approved' : 'draft');
            if ($property->status === 'rejected') {
                $computedApproval = 'needs_changes';
            }
            $approvalBadge = $approvalStatusMap[$computedApproval] ?? ['label' => ucfirst($computedApproval), 'class' => 'secondary'];
            $approvalHistory = is_array($property->approval_history) ? $property->approval_history : [];
        @endphp

        <div class="admin-table mb-4">
            <div class="p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge-status badge-{{ $property->status }}">{{ ucfirst($property->status) }}</span>
                            @if($property->featured)
                                <span class="badge bg-warning-subtle text-warning"><i class="bi bi-star-fill me-1"></i>Featured</span>
                            @endif
                            @if($property->verified)
                                <span class="badge bg-success-subtle text-success"><i class="bi bi-check2-circle me-1"></i>Verified</span>
                            @endif
                        </div>
                        <h3 class="mb-1">{{ $title }}</h3>
                        <p class="text-muted mb-0">
                            <i class="bi bi-geo-alt-fill me-1"></i>{{ $property->address ?? 'Manzil kiritilmagan' }}
                        </p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">Narx</small>
                        <div class="fs-3 fw-semibold text-primary">{{ number_format($property->price, 0, '.', ' ') }} {{ $property->currency }}</div>
                        <small class="text-muted">{{ $property->listing_type === 'sale' ? 'Sotish' : 'Ijaraga' }}</small>
                    </div>
                </div>
                
                @if($property->featured_image)
                <div class="mb-4 rounded-4 overflow-hidden shadow-sm">
                    <img src="{{ asset('storage/' . $property->featured_image) }}" alt="" class="img-fluid w-100" style="max-height: 420px; object-fit: cover;">
                </div>
                @endif
                
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="admin-card">
                            <p class="text-muted mb-1">Maydon</p>
                            <h4 class="mb-0">{{ $property->area ?? '—' }} {{ $property->area_unit ?? 'm²' }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="admin-card">
                            <p class="text-muted mb-1">Xonalar</p>
                            <h4 class="mb-0">{{ $property->bedrooms ?? '—' }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="admin-card">
                            <p class="text-muted mb-1">Hammom</p>
                            <h4 class="mb-0">{{ $property->bathrooms ?? '—' }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="admin-card">
                            <p class="text-muted mb-1">Shahar</p>
                            <h4 class="mb-0">{{ $property->city ?? '—' }}</h4>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="mb-2">Tavsif</h5>
                    <p class="text-muted mb-0">{{ $description ?? 'Tavsif qo\'shilmagan.' }}</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">Asosiy ma'lumotlar</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Uy turi</th>
                                <td>{{ ucfirst($property->property_type) }}</td>
                            </tr>
                            <tr>
                                <th>Listing turi</th>
                                <td>{{ $property->listing_type === 'sale' ? 'Sotish' : 'Ijaraga' }}</td>
                            </tr>
                            <tr>
                                <th>Lat / Long</th>
                                <td>{{ $property->latitude ?? '—' }} / {{ $property->longitude ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Ko'rishlar</th>
                                <td>{{ $property->views ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th>Yaratilgan</th>
                                <td>{{ $property->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Provider haqida</h5>
                        <div class="p-3 rounded-4 border bg-light">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $property->user->name ?? 'Noma\'lum' }}</div>
                                    <small class="text-muted">{{ $property->user->email ?? 'email mavjud emas' }}</small>
                                </div>
                            </div>
                            <div class="text-muted small">
                                <div><i class="bi bi-telephone me-1"></i>{{ $property->user->phone ?? 'Telefon kiritilmagan' }}</div>
                                <div><i class="bi bi-building me-1"></i>{{ $property->user->company_name ?? 'Kompaniya ko\'rsatilmagan' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="admin-table mb-4">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">Amallar</h5>
            </div>
            <div class="p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i>Tahrirlash
                    </a>
                    
                    @if($property->status == 'pending')
                        <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-1"></i>Tasdiqlash
                            </button>
                        </form>
                        <form action="{{ route('admin.properties.reject', $property->id) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small text-muted">{{ __('Rad etish sababi') }}</label>
                                <textarea name="reason" class="form-control" rows="3" required placeholder="{{ __('Izoh qoldiring') }}"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle me-1"></i>Rad etish
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.properties.toggle-featured', $property->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $property->featured ? 'success' : 'secondary' }} w-100">
                            <i class="bi bi-star{{ $property->featured ? '-fill' : '' }} me-1"></i>
                            {{ $property->featured ? 'Featured o\'chirish' : 'Featured qilish' }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.properties.toggle-verified', $property->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $property->verified ? 'success' : 'secondary' }} w-100">
                            <i class="bi bi-check-circle{{ $property->verified ? '-fill' : '' }} me-1"></i>
                            {{ $property->verified ? 'Verified o\'chirish' : 'Verified qilish' }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-1"></i>O'chirish
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Orqaga
                    </a>
                </div>
            </div>
        </div>

        <div class="admin-table">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">{{ __('Tasdiqlash jarayoni') }}</h5>
            </div>
            <div class="p-3">
                <div class="mb-3">
                    <span class="badge bg-{{ $approvalBadge['class'] }}">{{ $approvalBadge['label'] }}</span>
                    @if($property->approval_status === 'pending' && $property->approval_submitted_at)
                        <small class="text-muted ms-2">
                            {{ __('Yuborilgan: :date', ['date' => $property->approval_submitted_at->format('d.m.Y H:i')]) }}
                        </small>
                    @endif
                    @if($property->approval_reviewed_at)
                        <small class="text-muted ms-2 d-block">
                            {{ __('Ko\'rib chiqildi: :date', ['date' => $property->approval_reviewed_at->format('d.m.Y H:i')]) }}
                        </small>
                    @endif
                </div>

                @if($property->approval_notes)
                    <div class="alert alert-warning">
                        <strong>{{ __('Moderator izohi:') }}</strong>
                        <div>{{ $property->approval_notes }}</div>
                    </div>
                @endif

                @if(!empty($approvalHistory))
                    <ul class="list-unstyled approval-timeline mb-0">
                        @foreach(array_slice(array_reverse($approvalHistory), 0, 5) as $event)
                            <li class="d-flex align-items-start gap-2 mb-3">
                                <span class="badge bg-light text-dark">
                                    {{ \Carbon\Carbon::parse($event['timestamp'])->format('d.m.Y H:i') }}
                                </span>
                                <div>
                                    <div class="fw-semibold">
                                        {{ $approvalStatusMap[$event['status']]['label'] ?? ucfirst($event['status']) }}
                                    </div>
                                    @if(!empty($event['meta']['reason']))
                                        <small class="text-muted d-block">{{ $event['meta']['reason'] }}</small>
                                    @endif
                                    @if(!empty($event['meta']['reviewer']))
                                        <small class="text-muted">{{ __('Moderator: :name', ['name' => $event['meta']['reviewer']]) }}</small>
                                    @elseif(!empty($event['meta']['actor']))
                                        <small class="text-muted">{{ __('Foydalanuvchi: :name', ['name' => $event['meta']['actor']]) }}</small>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted small mb-0">{{ __('Tasdiqlash tarixi mavjud emas.') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection





