@extends('admin.layout')

@section('title', 'Yangi Foydalanuvchi')
@section('page-title', 'Yangi Foydalanuvchi Yaratish')

@section('content')
<div class="admin-table">
    <div class="p-4">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Ism <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Parol <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Parolni tasdiqlash <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Rol <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Foydalanuvchi</option>
                        <option value="provider" {{ old('role') == 'provider' ? 'selected' : '' }}>Provider</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Avatar</label>
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Kompaniya nomi</label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Bio</label>
                <textarea name="bio" class="form-control" rows="4">{{ old('bio') }}</textarea>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="verified" value="1" class="form-check-input" id="verified" {{ old('verified') ? 'checked' : '' }}>
                        <label class="form-check-label" for="verified">Tasdiqlangan</label>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="featured" value="1" class="form-check-input" id="featured" {{ old('featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="featured">Featured</label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Yaratish
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i>Bekor qilish
                </a>
            </div>
        </form>
    </div>
</div>
@endsection








