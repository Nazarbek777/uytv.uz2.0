@extends('admin.layout')

@section('title', 'Uy-joyni Tahrirlash')
@section('page-title', 'Uy-joyni Tahrirlash')

@section('content')
<div class="admin-table">
    <div class="p-4">
        <form action="{{ route('admin.properties.update', $property->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Egasi</label>
                    <select name="user_id" class="form-select" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $property->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Holat</label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ $property->status == 'draft' ? 'selected' : '' }}>Qoralama</option>
                        <option value="pending" {{ $property->status == 'pending' ? 'selected' : '' }}>Kutilayotgan</option>
                        <option value="published" {{ $property->status == 'published' ? 'selected' : '' }}>Nashr qilingan</option>
                        <option value="rejected" {{ $property->status == 'rejected' ? 'selected' : '' }}>Rad etilgan</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Narx</label>
                    <input type="number" name="price" class="form-control" value="{{ $property->price }}" required step="0.01">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Valyuta</label>
                    <select name="currency" class="form-select" required>
                        <option value="UZS" {{ $property->currency == 'UZS' ? 'selected' : '' }}>UZS</option>
                        <option value="USD" {{ $property->currency == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ $property->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Maydon</label>
                    <input type="number" name="area" class="form-control" value="{{ $property->area }}" step="0.01">
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Xonalar</label>
                    <input type="number" name="bedrooms" class="form-control" value="{{ $property->bedrooms }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Hammom</label>
                    <input type="number" name="bathrooms" class="form-control" value="{{ $property->bathrooms }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Uy turi</label>
                    <select name="property_type" class="form-select" required>
                        <option value="apartment" {{ $property->property_type == 'apartment' ? 'selected' : '' }}>Kvartira</option>
                        <option value="house" {{ $property->property_type == 'house' ? 'selected' : '' }}>Uy</option>
                        <option value="villa" {{ $property->property_type == 'villa' ? 'selected' : '' }}>Villa</option>
                        <option value="land" {{ $property->property_type == 'land' ? 'selected' : '' }}>Yer</option>
                        <option value="commercial" {{ $property->property_type == 'commercial' ? 'selected' : '' }}>Savdo</option>
                        <option value="office" {{ $property->property_type == 'office' ? 'selected' : '' }}>Ofis</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Holat</label>
                    <select name="listing_type" class="form-select" required>
                        <option value="sale" {{ $property->listing_type == 'sale' ? 'selected' : '' }}>Sotish</option>
                        <option value="rent" {{ $property->listing_type == 'rent' ? 'selected' : '' }}>Ijaraga</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Shahar</label>
                    <input type="text" name="city" class="form-control" value="{{ $property->city }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Latitude</label>
                    <input type="number" name="latitude" class="form-control" value="{{ $property->latitude }}" step="0.00000001">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Longitude</label>
                    <input type="number" name="longitude" class="form-control" value="{{ $property->longitude }}" step="0.00000001">
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" name="featured" value="1" class="form-check-input" id="featured" {{ $property->featured ? 'checked' : '' }}>
                        <label class="form-check-label" for="featured">Featured</label>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" name="verified" value="1" class="form-check-input" id="verified" {{ $property->verified ? 'checked' : '' }}>
                        <label class="form-check-label" for="verified">Verified</label>
                    </div>
                </div>
            </div>
            
            <h5 class="mb-3">Tarjimalar</h5>
            
            @foreach(['uz', 'ru', 'en'] as $locale)
            <div class="card mb-3">
                <div class="card-header">
                    <strong>{{ strtoupper($locale) }} Tarjima</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Sarlavha</label>
                        <input type="text" name="title_{{ $locale }}" class="form-control" value="{{ $property->translate($locale)->title ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tavsif</label>
                        <textarea name="description_{{ $locale }}" class="form-control" rows="5">{{ $property->translate($locale)->description ?? '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Manzil</label>
                        <input type="text" name="address_{{ $locale }}" class="form-control" value="{{ $property->translate($locale)->address ?? '' }}">
                    </div>
                </div>
            </div>
            @endforeach
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Saqlash
                </button>
                <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i>Bekor qilish
                </a>
            </div>
        </form>
    </div>
</div>
@endsection






