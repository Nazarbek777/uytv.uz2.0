@extends('admin.layout')

@section('title', 'Foydalanuvchini Tahrirlash')
@section('page-title', 'Foydalanuvchini Tahrirlash')

@section('content')
<div class="admin-table">
    <div class="p-4">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Ism <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Yangi Parol (bo'sh qoldirilsa o'zgarmaydi)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Parolni tasdiqlash</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Rol <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Foydalanuvchi</option>
                        <option value="provider" {{ old('role', $user->role) == 'provider' ? 'selected' : '' }}>Provider</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Avatar</label>
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                    @if($user->avatar)
                        <small class="text-muted">Joriy avatar: <a href="{{ asset('storage/' . $user->avatar) }}" target="_blank">Ko'rish</a></small>
                    @endif
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Kompaniya nomi</label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $user->company_name) }}">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Bio</label>
                <textarea name="bio" class="form-control" rows="4">{{ old('bio', $user->bio) }}</textarea>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="verified" value="1" class="form-check-input" id="verified" {{ old('verified', $user->verified) ? 'checked' : '' }}>
                        <label class="form-check-label" for="verified">Tasdiqlangan</label>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="featured" value="1" class="form-check-input" id="featured" {{ old('featured', $user->featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="featured">Featured</label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Saqlash
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i>Bekor qilish
                </a>
            </div>
        </form>
    </div>
</div>
@endsection








