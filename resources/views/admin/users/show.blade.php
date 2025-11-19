@extends('admin.layout')

@section('title', 'Foydalanuvchi Tafsilotlari')
@section('page-title', 'Foydalanuvchi Tafsilotlari')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-table mb-4">
            <div class="p-4">
                <div class="d-flex align-items-center mb-4">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-right: 20px;">
                    @else
                        <div style="width: 100px; height: 100px; border-radius: 50%; background: #2d55a4; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 36px; font-weight: 600; margin-right: 20px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="mb-1">{{ $user->name }}</h3>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'provider' ? 'primary' : 'secondary') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Asosiy Ma'lumotlar</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>#{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Ism</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Telefon</th>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Rol</th>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'provider' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Qo'shimcha Ma'lumotlar</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Kompaniya</th>
                                <td>{{ $user->company_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Verified</th>
                                <td>
                                    @if($user->verified)
                                        <span class="badge bg-success">Ha</span>
                                    @else
                                        <span class="badge bg-secondary">Yo'q</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Featured</th>
                                <td>
                                    @if($user->featured)
                                        <span class="badge bg-success">Ha</span>
                                    @else
                                        <span class="badge bg-secondary">Yo'q</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Uy-joylar</th>
                                <td>{{ $user->properties()->count() }}</td>
                            </tr>
                            <tr>
                                <th>Yaratilgan</th>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($user->bio)
                <div class="mb-4">
                    <h5>Bio</h5>
                    <p>{{ $user->bio }}</p>
                </div>
                @endif
            </div>
        </div>
        
        @if($user->properties()->count() > 0)
        <div class="admin-table">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">Uy-joylari ({{ $user->properties()->count() }})</h5>
            </div>
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sarlavha</th>
                        <th>Holat</th>
                        <th>Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->properties()->latest()->limit(10)->get() as $property)
                    <tr>
                        <td>#{{ $property->id }}</td>
                        <td>{{ $property->title }}</td>
                        <td>
                            <span class="badge-status badge-{{ $property->status }}">
                                {{ ucfirst($property->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <div class="admin-table mb-4">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">Amallar</h5>
            </div>
            <div class="p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i>Tahrirlash
                    </a>
                    
                    <form action="{{ route('admin.users.toggle-verified', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $user->verified ? 'success' : 'secondary' }} w-100">
                            <i class="bi bi-check-circle{{ $user->verified ? '-fill' : '' }} me-1"></i>
                            {{ $user->verified ? 'Verified o\'chirish' : 'Verified qilish' }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.users.toggle-featured', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $user->featured ? 'success' : 'secondary' }} w-100">
                            <i class="bi bi-star{{ $user->featured ? '-fill' : '' }} me-1"></i>
                            {{ $user->featured ? 'Featured o\'chirish' : 'Featured qilish' }}
                        </button>
                    </form>
                    
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-1"></i>O'chirish
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Orqaga
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection








