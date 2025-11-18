@extends('admin.layout')

@section('title', 'Admin Dashboard')
@section('page-title', 'Boshqaruv paneli')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="admin-card">
                <p class="text-muted mb-1 small">Jami foydalanuvchilar</p>
                <h3 class="mb-0">{{ number_format($stats['total_users']) }}</h3>
                <small class="text-success"><i class="bi bi-arrow-up-right"></i> Admin: {{ $stats['admins'] }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card">
                <p class="text-muted mb-1 small">Providerlar</p>
                <h3 class="mb-0">{{ number_format($stats['providers']) }}</h3>
                <small class="text-muted">Builderlar: {{ $stats['builders'] }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card">
                <p class="text-muted mb-1 small">E'lonlar</p>
                <h3 class="mb-0">{{ number_format($stats['properties']) }}</h3>
                <small class="text-warning">Kutayotgan: {{ $stats['pending_properties'] }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card">
                <p class="text-muted mb-1 small">Qurilish loyihalari</p>
                <h3 class="mb-0">{{ number_format($stats['developments']) }}</h3>
                <small class="text-warning">Kutayotgan: {{ $stats['pending_developments'] }}</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Oxirgi foydalanuvchilar</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Barchasi</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Ism</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Sana</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge text-bg-secondary text-capitalize">{{ $user->role ?? 'user' }}</span></td>
                                    <td>{{ $user->created_at?->format('d.m.Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Ma'lumot topilmadi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="admin-card h-100">
                <h5 class="mb-3"><i class="bi bi-clock-history me-2"></i>Kutilayotgan e'lonlar</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Foydalanuvchi</th>
                                <th>Status</th>
                                <th>Sana</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingProperties as $property)
                                @php
                                    $propertyTitle = optional($property->translate('uz'))->title
                                        ?? optional($property->translate('ru'))->title
                                        ?? optional($property->translate('en'))->title
                                        ?? 'Noma\'lum';
                                @endphp
                                <tr>
                                    <td>{{ $propertyTitle }}</td>
                                    <td>{{ $property->user?->name ?? 'Noma\'lum' }}</td>
                                    <td><span class="badge text-bg-warning text-uppercase">{{ $property->status }}</span></td>
                                    <td>{{ $property->created_at?->format('d.m.Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Kutilayotgan e'lonlar mavjud emas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="admin-card">
                <h5 class="mb-3"><i class="bi bi-diagram-3 me-2"></i>Kutilayotgan qurilish loyihalari</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Quruvchi</th>
                                <th>Shahar</th>
                                <th>Status</th>
                                <th>Sana</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingDevelopments as $development)
                                <tr>
                                    <td>{{ $development->title_uz ?? $development->title_ru ?? $development->title_en ?? 'Noma\'lum' }}</td>
                                    <td>{{ $development->builder?->name ?? $development->developer_name_uz ?? 'Noma\'lum' }}</td>
                                    <td>{{ $development->city ?? '—' }}</td>
                                    <td><span class="badge text-bg-warning">{{ ucfirst($development->status) }}</span></td>
                                    <td>{{ $development->created_at?->format('d.m.Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Kutilayotgan qurilish loyihalari mavjud emas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
