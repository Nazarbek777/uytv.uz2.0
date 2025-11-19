@extends('admin.layout')

@section('title', 'Statistika')
@section('page-title', 'Statistika va Hisobotlar')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami uy-joylar</p>
            <h3 class="mb-0">{{ number_format($propertiesStats['total']) }}</h3>
            <small class="text-success"><i class="bi bi-arrow-up-right"></i> {{ $propertiesStats['recent'] }} ta ({{ $period }} kun)</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Nashr qilingan</p>
            <h4 class="mb-0 text-success">{{ number_format($propertiesStats['published']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Kutilayotgan</p>
            <h4 class="mb-0 text-warning">{{ number_format($propertiesStats['pending']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Featured</p>
            <h4 class="mb-0 text-primary">{{ number_format($propertiesStats['featured']) }}</h4>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami qurilishlar</p>
            <h3 class="mb-0">{{ number_format($developmentsStats['total']) }}</h3>
            <small class="text-info"><i class="bi bi-arrow-up-right"></i> {{ $developmentsStats['recent'] }} ta ({{ $period }} kun)</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Nashr qilingan</p>
            <h4 class="mb-0 text-success">{{ number_format($developmentsStats['published']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Kutilayotgan</p>
            <h4 class="mb-0 text-warning">{{ number_format($developmentsStats['pending']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Featured</p>
            <h4 class="mb-0 text-primary">{{ number_format($developmentsStats['featured']) }}</h4>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami foydalanuvchilar</p>
            <h3 class="mb-0">{{ number_format($usersStats['total']) }}</h3>
            <small class="text-success"><i class="bi bi-arrow-up-right"></i> {{ $usersStats['recent'] }} ta ({{ $period }} kun)</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Providerlar</p>
            <h4 class="mb-0 text-info">{{ number_format($usersStats['providers']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Quruvchilar</p>
            <h4 class="mb-0 text-warning">{{ number_format($usersStats['builders']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Verified</p>
            <h4 class="mb-0 text-success">{{ number_format($usersStats['verified']) }}</h4>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Jami faolliklar</p>
            <h3 class="mb-0">{{ number_format($activityStats['total']) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Bugungi</p>
            <h4 class="mb-0 text-primary">{{ number_format($activityStats['today']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Bu hafta</p>
            <h4 class="mb-0 text-info">{{ number_format($activityStats['this_week']) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card">
            <p class="text-muted mb-1">Bu oy</p>
            <h4 class="mb-0 text-success">{{ number_format($activityStats['this_month']) }}</h4>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="admin-card">
            <h6 class="mb-3"><i class="bi bi-pie-chart me-2"></i>Uy-joylar turi bo'yicha</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Turi</th>
                            <th>Soni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propertiesByType as $type => $count)
                        <tr>
                            <td>{{ ucfirst($type) }}</td>
                            <td><strong>{{ number_format($count) }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Ma'lumot yo'q</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-card">
            <h6 class="mb-3"><i class="bi bi-geo-alt me-2"></i>Shaharlar bo'yicha (Top 10)</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Shahar</th>
                            <th>Soni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propertiesByCity as $city => $count)
                        <tr>
                            <td>{{ $city }}</td>
                            <td><strong>{{ number_format($count) }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Ma'lumot yo'q</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="admin-card mt-4">
    <h6 class="mb-3"><i class="bi bi-clock-history me-2"></i>Oxirgi faolliklar</h6>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Foydalanuvchi</th>
                    <th>Amal</th>
                    <th>Model</th>
                    <th>Sana</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentActivity as $activity)
                <tr>
                    <td>{{ $activity->user->name ?? 'Sistema' }}</td>
                    <td><span class="badge bg-{{ $activity->action == 'created' ? 'success' : ($activity->action == 'updated' ? 'warning' : 'danger') }}">{{ $activity->action }}</span></td>
                    <td><small>{{ class_basename($activity->model_type) }}</small></td>
                    <td><small>{{ $activity->created_at->format('d.m.Y H:i') }}</small></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Faolliklar topilmadi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

