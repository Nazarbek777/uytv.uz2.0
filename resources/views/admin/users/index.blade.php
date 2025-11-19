@extends('admin.layout')

@section('title', 'Foydalanuvchilar')
@section('page-title', 'Foydalanuvchilar Boshqaruvi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Barcha Foydalanuvchilar</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Yangi Foydalanuvchi
    </a>
</div>

<div class="admin-table mb-4">
    <!-- Filters -->
    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Qidirish (ism, email, telefon)..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Barcha rollar</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="provider" {{ request('role') == 'provider' ? 'selected' : '' }}>Provider</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Foydalanuvchi</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="verified" class="form-select">
                    <option value="">Barcha</option>
                    <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Tasdiqlangan</option>
                    <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Tasdiqlanmagan</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Qidirish</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i>Tozalash</a>
            </div>
        </form>
    </div>
    
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Avatar</th>
                <th>Ism</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Rol</th>
                <th>Verified</th>
                <th>Featured</th>
                <th>Uy-joylar</th>
                <th>Sana</th>
                <th>Amallar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>#{{ $user->id }}</td>
                <td>
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: #2d55a4; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </td>
                <td class="fw-semibold">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? 'N/A' }}</td>
                <td>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'provider' ? 'primary' : 'secondary') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td>
                    <form action="{{ route('admin.users.toggle-verified', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $user->verified ? 'success' : 'secondary' }}">
                            <i class="bi bi-check-circle{{ $user->verified ? '-fill' : '' }}"></i>
                        </button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('admin.users.toggle-featured', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $user->featured ? 'success' : 'secondary' }}">
                            <i class="bi bi-star{{ $user->featured ? '-fill' : '' }}"></i>
                        </button>
                    </form>
                </td>
                <td>
                    <span class="badge bg-info">{{ $user->properties_count ?? $user->properties()->count() }}</span>
                </td>
                <td>{{ $user->created_at->format('d.m.Y') }}</td>
                <td>
                    <div class="btn-group" role="group">
                        @if($user->role === 'builder' || $user->role === 'provider')
                            <button type="button" class="btn btn-sm btn-info" onclick="viewAccount({{ $user->id }})" title="Account holatini ko'rish">
                                <i class="bi bi-person-check"></i>
                            </button>
                        @endif
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary" title="Ko'rish">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center text-muted py-5">
                    <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                    Foydalanuvchilar topilmadi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($users->hasPages())
    <div class="p-3 border-top">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Account Holatini Ko'rsatish Modal -->
<div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="accountModalLabel">
                    <i class="bi bi-person-check me-2"></i>Account Holati
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="accountModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Yopish</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewAccount(userId) {
    const modal = new bootstrap.Modal(document.getElementById('accountModal'));
    const modalBody = document.getElementById('accountModalBody');
    
    // Loading ko'rsatish
    modalBody.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Yuklanmoqda...</p>
        </div>
    `;
    
    modal.show();
    
    // Account ma'lumotlarini olish
    fetch(`/admin/users/${userId}/view-account`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const account = data.account;
            let html = `
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        ${account.avatar ? 
                            `<img src="/storage/${account.avatar}" alt="${account.name}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #0d6efd;">` :
                            `<div style="width: 120px; height: 120px; border-radius: 50%; background: #0d6efd; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 48px; font-weight: 700; margin: 0 auto; border: 4px solid #0d6efd;">
                                ${account.name.charAt(0).toUpperCase()}
                            </div>`
                        }
                        <h4 class="mt-3 mb-1">${account.name}</h4>
                        <p class="text-muted mb-0">${account.email}</p>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 40%;">ID</th>
                                <td>#${account.id}</td>
                            </tr>
                            <tr>
                                <th>Telefon</th>
                                <td>${account.phone}</td>
                            </tr>
                            <tr>
                                <th>Rol</th>
                                <td>
                                    <span class="badge bg-${account.role === 'admin' ? 'danger' : (account.role === 'provider' ? 'primary' : 'success')}">
                                        ${account.role.charAt(0).toUpperCase() + account.role.slice(1)}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Kompaniya</th>
                                <td>${account.company_name}</td>
                            </tr>
                            <tr>
                                <th>Verified</th>
                                <td>
                                    ${account.verified ? 
                                        '<span class="badge bg-success">Ha</span>' : 
                                        '<span class="badge bg-warning">Yo\'q</span>'
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th>Featured</th>
                                <td>
                                    ${account.featured ? 
                                        '<span class="badge bg-success">Ha</span>' : 
                                        '<span class="badge bg-secondary">Yo\'q</span>'
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th>Yaratilgan</th>
                                <td>${account.created_at}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
            
            // Provider uchun uy-joylar statistika
            if (account.role === 'provider' && account.properties_count !== undefined) {
                html += `
                    <hr>
                    <h5 class="mb-3"><i class="bi bi-house-door me-2"></i>Uy-joylar Statistika</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-primary">${account.properties_count}</h3>
                                    <p class="mb-0 text-muted">Jami Uy-joylar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">${account.published_properties || 0}</h3>
                                    <p class="mb-0 text-muted">Nashr qilingan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-warning">${account.pending_properties || 0}</h3>
                                    <p class="mb-0 text-muted">Kutilayotgan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // So'nggi uy-joylar
                if (account.recent_properties && account.recent_properties.length > 0) {
                    html += `
                        <h5 class="mb-3">So'nggi Uy-joylar</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sarlavha</th>
                                        <th>Shahar</th>
                                        <th>Holat</th>
                                        <th>Sana</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    account.recent_properties.forEach(property => {
                        html += `
                            <tr>
                                <td>#${property.id}</td>
                                <td>${property.title ? property.title.substring(0, 30) : 'N/A'}${property.title && property.title.length > 30 ? '...' : ''}</td>
                                <td>${property.city || 'N/A'}</td>
                                <td>
                                    <span class="badge bg-${property.status === 'published' ? 'success' : (property.status === 'pending' ? 'warning' : 'secondary')}">
                                        ${property.status.charAt(0).toUpperCase() + property.status.slice(1)}
                                    </span>
                                </td>
                                <td>${new Date(property.created_at).toLocaleDateString('uz-UZ')}</td>
                            </tr>
                        `;
                    });
                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                }
            }
            
            // Builder uchun novostroiki statistika
            if (account.role === 'builder' && account.developments_count !== undefined) {
                html += `
                    <hr>
                    <h5 class="mb-3"><i class="bi bi-building me-2"></i>Novostroiki Statistika</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">${account.developments_count}</h3>
                                    <p class="mb-0 text-muted">Jami Novostroiki</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">${account.published_developments || 0}</h3>
                                    <p class="mb-0 text-muted">Nashr qilingan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-warning">${account.pending_developments || 0}</h3>
                                    <p class="mb-0 text-muted">Tasdiqlashda</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // So'nggi novostroikilar
                if (account.recent_developments && account.recent_developments.length > 0) {
                    html += `
                        <h5 class="mb-3">So'nggi Novostroikilar</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sarlavha</th>
                                        <th>Shahar</th>
                                        <th>Holat</th>
                                        <th>Sana</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    account.recent_developments.forEach(development => {
                        html += `
                            <tr>
                                <td>#${development.id}</td>
                                <td>${development.title_uz ? development.title_uz.substring(0, 30) : 'N/A'}${development.title_uz && development.title_uz.length > 30 ? '...' : ''}</td>
                                <td>${development.city || 'N/A'}</td>
                                <td>
                                    <span class="badge bg-${development.status === 'published' ? 'success' : (development.status === 'pending' ? 'warning' : 'secondary')}">
                                        ${development.status.charAt(0).toUpperCase() + development.status.slice(1)}
                                    </span>
                                </td>
                                <td>${new Date(development.created_at).toLocaleDateString('uz-UZ')}</td>
                            </tr>
                        `;
                    });
                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                }
            }
            
            modalBody.innerHTML = html;
        } else {
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Xatolik yuz berdi. Iltimos, qayta urinib ko'ring.
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Xatolik yuz berdi. Iltimos, qayta urinib ko'ring.
            </div>
        `;
    });
}
</script>
@endsection






@section('title', 'Foydalanuvchilar')
@section('page-title', 'Foydalanuvchilar Boshqaruvi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Barcha Foydalanuvchilar</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Yangi Foydalanuvchi
    </a>
</div>

<div class="admin-table mb-4">
    <!-- Filters -->
    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Qidirish (ism, email, telefon)..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Barcha rollar</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="provider" {{ request('role') == 'provider' ? 'selected' : '' }}>Provider</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Foydalanuvchi</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="verified" class="form-select">
                    <option value="">Barcha</option>
                    <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Tasdiqlangan</option>
                    <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Tasdiqlanmagan</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Qidirish</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i>Tozalash</a>
            </div>
        </form>
    </div>
    
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Avatar</th>
                <th>Ism</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Rol</th>
                <th>Verified</th>
                <th>Featured</th>
                <th>Uy-joylar</th>
                <th>Sana</th>
                <th>Amallar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>#{{ $user->id }}</td>
                <td>
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: #2d55a4; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </td>
                <td class="fw-semibold">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? 'N/A' }}</td>
                <td>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'provider' ? 'primary' : 'secondary') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td>
                    <form action="{{ route('admin.users.toggle-verified', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $user->verified ? 'success' : 'secondary' }}">
                            <i class="bi bi-check-circle{{ $user->verified ? '-fill' : '' }}"></i>
                        </button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('admin.users.toggle-featured', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $user->featured ? 'success' : 'secondary' }}">
                            <i class="bi bi-star{{ $user->featured ? '-fill' : '' }}"></i>
                        </button>
                    </form>
                </td>
                <td>
                    <span class="badge bg-info">{{ $user->properties_count ?? $user->properties()->count() }}</span>
                </td>
                <td>{{ $user->created_at->format('d.m.Y') }}</td>
                <td>
                    <div class="btn-group" role="group">
                        @if($user->role === 'builder' || $user->role === 'provider')
                            <button type="button" class="btn btn-sm btn-info" onclick="viewAccount({{ $user->id }})" title="Account holatini ko'rish">
                                <i class="bi bi-person-check"></i>
                            </button>
                        @endif
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary" title="Ko'rish">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center text-muted py-5">
                    <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                    Foydalanuvchilar topilmadi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($users->hasPages())
    <div class="p-3 border-top">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Account Holatini Ko'rsatish Modal -->
<div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="accountModalLabel">
                    <i class="bi bi-person-check me-2"></i>Account Holati
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="accountModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Yopish</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewAccount(userId) {
    const modal = new bootstrap.Modal(document.getElementById('accountModal'));
    const modalBody = document.getElementById('accountModalBody');
    
    // Loading ko'rsatish
    modalBody.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Yuklanmoqda...</p>
        </div>
    `;
    
    modal.show();
    
    // Account ma'lumotlarini olish
    fetch(`/admin/users/${userId}/view-account`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const account = data.account;
            let html = `
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        ${account.avatar ? 
                            `<img src="/storage/${account.avatar}" alt="${account.name}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #0d6efd;">` :
                            `<div style="width: 120px; height: 120px; border-radius: 50%; background: #0d6efd; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 48px; font-weight: 700; margin: 0 auto; border: 4px solid #0d6efd;">
                                ${account.name.charAt(0).toUpperCase()}
                            </div>`
                        }
                        <h4 class="mt-3 mb-1">${account.name}</h4>
                        <p class="text-muted mb-0">${account.email}</p>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 40%;">ID</th>
                                <td>#${account.id}</td>
                            </tr>
                            <tr>
                                <th>Telefon</th>
                                <td>${account.phone}</td>
                            </tr>
                            <tr>
                                <th>Rol</th>
                                <td>
                                    <span class="badge bg-${account.role === 'admin' ? 'danger' : (account.role === 'provider' ? 'primary' : 'success')}">
                                        ${account.role.charAt(0).toUpperCase() + account.role.slice(1)}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Kompaniya</th>
                                <td>${account.company_name}</td>
                            </tr>
                            <tr>
                                <th>Verified</th>
                                <td>
                                    ${account.verified ? 
                                        '<span class="badge bg-success">Ha</span>' : 
                                        '<span class="badge bg-warning">Yo\'q</span>'
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th>Featured</th>
                                <td>
                                    ${account.featured ? 
                                        '<span class="badge bg-success">Ha</span>' : 
                                        '<span class="badge bg-secondary">Yo\'q</span>'
                                    }
                                </td>
                            </tr>
                            <tr>
                                <th>Yaratilgan</th>
                                <td>${account.created_at}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
            
            // Provider uchun uy-joylar statistika
            if (account.role === 'provider' && account.properties_count !== undefined) {
                html += `
                    <hr>
                    <h5 class="mb-3"><i class="bi bi-house-door me-2"></i>Uy-joylar Statistika</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-primary">${account.properties_count}</h3>
                                    <p class="mb-0 text-muted">Jami Uy-joylar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">${account.published_properties || 0}</h3>
                                    <p class="mb-0 text-muted">Nashr qilingan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-warning">${account.pending_properties || 0}</h3>
                                    <p class="mb-0 text-muted">Kutilayotgan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // So'nggi uy-joylar
                if (account.recent_properties && account.recent_properties.length > 0) {
                    html += `
                        <h5 class="mb-3">So'nggi Uy-joylar</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sarlavha</th>
                                        <th>Shahar</th>
                                        <th>Holat</th>
                                        <th>Sana</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    account.recent_properties.forEach(property => {
                        html += `
                            <tr>
                                <td>#${property.id}</td>
                                <td>${property.title ? property.title.substring(0, 30) : 'N/A'}${property.title && property.title.length > 30 ? '...' : ''}</td>
                                <td>${property.city || 'N/A'}</td>
                                <td>
                                    <span class="badge bg-${property.status === 'published' ? 'success' : (property.status === 'pending' ? 'warning' : 'secondary')}">
                                        ${property.status.charAt(0).toUpperCase() + property.status.slice(1)}
                                    </span>
                                </td>
                                <td>${new Date(property.created_at).toLocaleDateString('uz-UZ')}</td>
                            </tr>
                        `;
                    });
                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                }
            }
            
            // Builder uchun novostroiki statistika
            if (account.role === 'builder' && account.developments_count !== undefined) {
                html += `
                    <hr>
                    <h5 class="mb-3"><i class="bi bi-building me-2"></i>Novostroiki Statistika</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">${account.developments_count}</h3>
                                    <p class="mb-0 text-muted">Jami Novostroiki</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">${account.published_developments || 0}</h3>
                                    <p class="mb-0 text-muted">Nashr qilingan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-warning">${account.pending_developments || 0}</h3>
                                    <p class="mb-0 text-muted">Tasdiqlashda</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // So'nggi novostroikilar
                if (account.recent_developments && account.recent_developments.length > 0) {
                    html += `
                        <h5 class="mb-3">So'nggi Novostroikilar</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sarlavha</th>
                                        <th>Shahar</th>
                                        <th>Holat</th>
                                        <th>Sana</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    account.recent_developments.forEach(development => {
                        html += `
                            <tr>
                                <td>#${development.id}</td>
                                <td>${development.title_uz ? development.title_uz.substring(0, 30) : 'N/A'}${development.title_uz && development.title_uz.length > 30 ? '...' : ''}</td>
                                <td>${development.city || 'N/A'}</td>
                                <td>
                                    <span class="badge bg-${development.status === 'published' ? 'success' : (development.status === 'pending' ? 'warning' : 'secondary')}">
                                        ${development.status.charAt(0).toUpperCase() + development.status.slice(1)}
                                    </span>
                                </td>
                                <td>${new Date(development.created_at).toLocaleDateString('uz-UZ')}</td>
                            </tr>
                        `;
                    });
                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                }
            }
            
            modalBody.innerHTML = html;
        } else {
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Xatolik yuz berdi. Iltimos, qayta urinib ko'ring.
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Xatolik yuz berdi. Iltimos, qayta urinib ko'ring.
            </div>
        `;
    });
}
</script>
@endsection





