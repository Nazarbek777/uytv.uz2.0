@extends('admin.layout')

@section('title', 'Qurilish tafsilotlari')
@section('page-title', 'Qurilish tafsilotlari')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-table mb-4">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h3 class="mb-1">{{ $development->title_uz ?? $development->title_ru ?? $development->title_en ?? 'Noma\'lum loyiha' }}</h3>
                        <p class="mb-0 text-muted">
                            <i class="bi bi-geo-alt-fill me-1"></i>{{ $development->city ?? '—' }} {{ $development->address_uz ? '• '.$development->address_uz : '' }}
                        </p>
                    </div>
                    <span class="badge-status badge-{{ $development->status }}">{{ ucfirst($development->status) }}</span>
                </div>

                @if($development->featured_image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $development->featured_image) }}" class="img-fluid rounded" alt="Featured image">
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h5>Asosiy ma'lumotlar</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Quruvchi</th>
                                <td>{{ $development->builder->name ?? 'Noma\'lum' }}</td>
                            </tr>
                            <tr>
                                <th>Developer</th>
                                <td>{{ $development->developer_name_uz ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Narx (boshl.)</th>
                                <td>
                                    @if($development->price_from)
                                        {{ number_format($development->price_from) }} {{ $development->currency ?? 'USD' }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Maydon narxi</th>
                                <td>
                                    @if($development->price_per_sqm)
                                        {{ number_format($development->price_per_sqm) }} {{ $development->currency ?? 'USD' }}/m²
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tugatish</th>
                                <td>{{ optional($development->completion_date)->format('Y-m') ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h5>Qo'shimcha</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Umumiy binolar</th>
                                <td>{{ $development->total_buildings ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Qavatlar</th>
                                <td>{{ $development->total_floors ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Featured</th>
                                <td>{!! $development->featured ? '<span class="badge bg-success">Ha</span>' : '<span class="badge bg-secondary">Yo\'q</span>' !!}</td>
                            </tr>
                            <tr>
                                <th>Installment</th>
                                <td>{!! $development->installment_available ? '<span class="badge bg-success">Mavjud</span>' : '<span class="badge bg-secondary">Yo\'q</span>' !!}</td>
                            </tr>
                            <tr>
                                <th>Yaratilgan</th>
                                <td>{{ optional($development->created_at)->format('d.m.Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Atributlar</h5>
                    <p>{{ $development->description_uz ?? $development->description_ru ?? $development->description_en ?? 'Tavsif mavjud emas' }}</p>
                </div>

                @php
                    $amenities = $development->amenities;
                    if (is_string($amenities)) {
                        $amenities = array_filter(array_map('trim', explode(',', $amenities)));
                    }
                @endphp
                @if(!empty($amenities))
                    <div class="mb-4">
                        <h5>Afzalliklar</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($amenities as $amenity)
                                <span class="badge text-bg-light">{{ $amenity }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <h5>Uy turlari</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Xonalar</th>
                                    <th>Maydon (m²)</th>
                                    <th>Narx</th>
                                    <th>Valyuta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($development->properties as $unit)
                                    <tr>
                                        <td>{{ $unit->bedrooms }} xonali</td>
                                        <td>{{ $unit->area_from }} - {{ $unit->area_to ?? '—' }}</td>
                                        <td>{{ number_format($unit->price_from) }}</td>
                                        <td>{{ $unit->currency }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Uy turlari qo'shilmagan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($development->documents->isNotEmpty())
                    <div class="mb-4">
                        <h5>Hujjatlar</h5>
                        <ul class="list-group">
                            @foreach($development->documents as $document)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $document->title_uz ?? $document->title_ru ?? $document->title_en ?? 'Hujjat' }}</span>
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Ko'rish
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-table mb-4">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">Amallar</h5>
            </div>
            <div class="p-3 d-grid gap-2">
                <a href="{{ route('admin.developments.edit', $development->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i>Tahrirlash
                </a>

                @if($development->status === 'pending')
                    <form action="{{ route('admin.developments.approve', $development->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success w-100"><i class="bi bi-check-circle me-1"></i>Tasdiqlash</button>
                    </form>
                    <form action="{{ route('admin.developments.reject', $development->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-danger w-100"><i class="bi bi-x-circle me-1"></i>Rad etish</button>
                    </form>
                @endif

                <form action="{{ route('admin.developments.toggle-featured', $development->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-{{ $development->featured ? 'success' : 'secondary' }} w-100">
                        <i class="bi bi-star{{ $development->featured ? '-fill' : '' }} me-1"></i>
                        {{ $development->featured ? 'Featured o\'chirish' : 'Featured qilish' }}
                    </button>
                </form>

                <form action="{{ route('admin.developments.destroy', $development->id) }}" method="POST" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger w-100"><i class="bi bi-trash me-1"></i>O'chirish</button>
                </form>

                <a href="{{ route('admin.developments.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Orqaga
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


