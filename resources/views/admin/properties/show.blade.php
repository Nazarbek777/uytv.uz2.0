@extends('admin.layout')

@section('title', 'Uy-joy Tafsilotlari')
@section('page-title', 'Uy-joy Tafsilotlari')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-table mb-4">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h3 class="mb-2">{{ $property->title }}</h3>
                        <p class="text-muted mb-0">
                            <i class="bi bi-geo-alt-fill me-1"></i>{{ $property->address ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <span class="badge-status badge-{{ $property->status }}">
                            {{ ucfirst($property->status) }}
                        </span>
                    </div>
                </div>
                
                @if($property->featured_image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $property->featured_image) }}" alt="" class="img-fluid rounded" style="max-height: 400px; width: 100%; object-fit: cover;">
                </div>
                @endif
                
                <div class="mb-4">
                    <h5>Tavsif</h5>
                    <p>{{ $property->description ?? 'Tavsif yo\'q' }}</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Asosiy Ma'lumotlar</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Narx</th>
                                <td class="fw-bold text-main">{{ number_format($property->price) }} {{ $property->currency }}</td>
                            </tr>
                            <tr>
                                <th>Maydon</th>
                                <td>{{ $property->area ?? 'N/A' }} {{ $property->area_unit ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Xonalar</th>
                                <td>{{ $property->bedrooms ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Hammom</th>
                                <td>{{ $property->bathrooms ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Uy turi</th>
                                <td>{{ ucfirst($property->property_type) }}</td>
                            </tr>
                            <tr>
                                <th>Holat</th>
                                <td>{{ $property->listing_type === 'sale' ? 'Sotish' : 'Ijaraga' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Qo'shimcha Ma'lumotlar</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Egasi</th>
                                <td>{{ $property->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Shahar</th>
                                <td>{{ $property->city ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Featured</th>
                                <td>
                                    @if($property->featured)
                                        <span class="badge bg-success">Ha</span>
                                    @else
                                        <span class="badge bg-secondary">Yo'q</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Verified</th>
                                <td>
                                    @if($property->verified)
                                        <span class="badge bg-success">Ha</span>
                                    @else
                                        <span class="badge bg-secondary">Yo'q</span>
                                    @endif
                                </td>
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
                        <form action="{{ route('admin.properties.reject', $property->id) }}" method="POST">
                            @csrf
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
    </div>
</div>
@endsection





