@extends('builder.layout')

@section('title', 'Loyiha Ko\'rish')
@section('page-title', 'Loyiha Ma\'lumotlari')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-eye me-2"></i>{{ $development->title_uz }}</h5>
            <div>
                @if(in_array($development->status, ['draft', 'rejected']))
                    <a href="{{ route('builder.developments.edit', $development->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Tahrirlash
                    </a>
                @endif
                <a href="{{ route('builder.developments.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Orqaga
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Status Badge -->
<div class="row mb-4">
    <div class="col-12">
        <div class="builder-table">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">Holat</h5>
            </div>
            <div class="p-4">
                @if($development->status === 'draft')
                    <span class="badge bg-secondary fs-6 px-3 py-2">Draft - Tahrirlash mumkin</span>
                    <form action="{{ route('builder.developments.submit', $development->id) }}" method="POST" class="d-inline ms-3" onsubmit="return confirm('Loyihani tasdiqlashga yubormoqchimisiz?');">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send me-2"></i>Tasdiqlashga yuborish
                        </button>
                    </form>
                @elseif($development->status === 'pending')
                    <span class="badge bg-warning fs-6 px-3 py-2">Tasdiqlashda - Admin tekshiryapti</span>
                @elseif($development->status === 'approved')
                    <span class="badge bg-info fs-6 px-3 py-2">Tasdiqlandi - Admin publish qilishi kerak</span>
                @elseif($development->status === 'published')
                    <span class="badge bg-success fs-6 px-3 py-2">Published - Saytda chiqgan</span>
                @elseif($development->status === 'rejected')
                    <span class="badge bg-danger fs-6 px-3 py-2">Rad etilgan</span>
                    @if($development->rejection_reason)
                        <div class="alert alert-danger mt-3 mb-0">
                            <strong>Sabab:</strong> {{ $development->rejection_reason }}
                        </div>
                        <a href="{{ route('builder.developments.edit', $development->id) }}" class="btn btn-primary mt-3">
                            <i class="bi bi-pencil me-2"></i>Tahrirlash va qayta yuborish
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Asosiy Ma'lumotlar -->
<div class="row">
    <div class="col-md-8">
        <div class="builder-table mb-4">
            <div class="p-3 border-bottom bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Asosiy Ma'lumotlar</h5>
            </div>
            <div class="p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="200">Loyiha nomi:</th>
                        <td><strong>{{ $development->title_uz }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tavsif (O'zbek):</th>
                        <td>{{ $development->description_uz ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Developer:</th>
                        <td>{{ $development->developer_name_uz }}</td>
                    </tr>
                    <tr>
                        <th>Manzil:</th>
                        <td>{{ $development->city }}{{ $development->region ? ', ' . $development->region : '' }}<br>
                            <small class="text-muted">{{ $development->address_uz }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Narx:</th>
                        <td>
                            @if($development->price_from)
                                <strong>{{ number_format($development->price_from) }} UZS</strong> dan
                            @endif
                            @if($development->price_per_sqm)
                                <br><small class="text-muted">{{ number_format($development->price_per_sqm) }} UZS / m²</small>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tugallash sanasi:</th>
                        <td>{{ $development->completion_date ? $development->completion_date->format('d.m.Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Binolar soni:</th>
                        <td>{{ $development->total_buildings ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Qavatlar soni:</th>
                        <td>{{ $development->total_floors ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Kvartira Tiplari -->
        @if($development->properties->count() > 0)
        <div class="builder-table mb-4">
            <div class="p-3 border-bottom bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-house-door me-2"></i>Kvartira Tiplari</h5>
            </div>
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Xonalar</th>
                                <th>Maydon</th>
                                <th>Narx (dan)</th>
                                <th>Valyuta</th>
                                <th>Mavjud</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($development->properties as $property)
                            <tr>
                                <td>{{ $property->bedrooms }} xonali</td>
                                <td>
                                    {{ number_format($property->area_from, 2) }} m²
                                    @if($property->area_to)
                                        - {{ number_format($property->area_to, 2) }} m²
                                    @endif
                                </td>
                                <td class="fw-bold text-main">{{ number_format($property->price_from) }}</td>
                                <td>{{ $property->currency }}</td>
                                <td>
                                    @if($property->quantity_available)
                                        {{ $property->quantity_available }} / {{ $property->total_quantity ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Rasm -->
        @if($development->featured_image)
        <div class="builder-table mb-4">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">Asosiy rasm</h5>
            </div>
            <div class="p-3">
                <img src="{{ asset('storage/' . $development->featured_image) }}" alt="" class="img-fluid rounded">
            </div>
        </div>
        @endif

        <!-- Qo'shimcha Rasmlar -->
        @if($development->images && count($development->images) > 0)
        <div class="builder-table mb-4">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">Qo'shimcha rasmlar ({{ count($development->images) }})</h5>
            </div>
            <div class="p-3">
                <div class="row g-2">
                    @foreach($development->images as $image)
                    <div class="col-6">
                        <img src="{{ asset('storage/' . $image) }}" alt="" class="img-fluid rounded">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Statistika -->
        <div class="builder-table">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">Statistika</h5>
            </div>
            <div class="p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th>Yaratilgan:</th>
                        <td>{{ $development->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    @if($development->published_at)
                    <tr>
                        <th>Published:</th>
                        <td>{{ $development->published_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Ko'rishlar:</th>
                        <td>{{ $development->views }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

