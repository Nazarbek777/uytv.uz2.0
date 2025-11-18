@extends('admin.layout')

@section('title', 'Qurilishni tahrirlash')
@section('page-title', 'Qurilish loyihasini tahrirlash')

@section('content')
<div class="admin-table">
    <div class="p-4">
        <form action="{{ route('admin.developments.update', $development->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Quruvchi</label>
                    <select name="user_id" class="form-select" required>
                        @foreach($builders as $builder)
                            <option value="{{ $builder->id }}" {{ $development->user_id == $builder->id ? 'selected' : '' }}>
                                {{ $builder->name }} ({{ $builder->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Holat</label>
                    <select name="status" class="form-select" required>
                        @foreach(['draft' => 'Qoralama', 'pending' => 'Kutilayotgan', 'published' => 'Nashr qilingan', 'rejected' => 'Rad etilgan'] as $value => $label)
                            <option value="{{ $value }}" {{ $development->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Loyiha nomi (UZ)</label>
                    <input type="text" name="title_uz" class="form-control" value="{{ $development->title_uz }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Loyiha nomi (RU)</label>
                    <input type="text" name="title_ru" class="form-control" value="{{ $development->title_ru }}">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Loyiha nomi (EN)</label>
                    <input type="text" name="title_en" class="form-control" value="{{ $development->title_en }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Developer nomi (UZ)</label>
                    <input type="text" name="developer_name_uz" class="form-control" value="{{ $development->developer_name_uz }}" required>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Developer nomi (RU)</label>
                    <input type="text" name="developer_name_ru" class="form-control" value="{{ $development->developer_name_ru }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Developer nomi (EN)</label>
                    <input type="text" name="developer_name_en" class="form-control" value="{{ $development->developer_name_en }}">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Shahar</label>
                    <input type="text" name="city" class="form-control" value="{{ $development->city }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Region</label>
                    <input type="text" name="region" class="form-control" value="{{ $development->region }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Narx (boshl.)</label>
                    <input type="number" name="price_from" class="form-control" value="{{ $development->price_from }}" step="0.01" min="0">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Maydon narxi (m²)</label>
                    <input type="number" name="price_per_sqm" class="form-control" value="{{ $development->price_per_sqm }}" step="0.01" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tugatish sanasi</label>
                    <input type="month" name="completion_date" class="form-control" value="{{ optional($development->completion_date)->format('Y-m') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Binolar</label>
                    <input type="number" name="total_buildings" class="form-control" value="{{ $development->total_buildings }}" min="1">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Qavatlar</label>
                    <input type="number" name="total_floors" class="form-control" value="{{ $development->total_floors }}" min="1">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <label class="form-label">Manzil (UZ)</label>
                    <textarea name="address_uz" class="form-control" rows=2>{{ $development->address_uz }}</textarea>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Manzil (RU)</label>
                    <textarea name="address_ru" class="form-control" rows=2>{{ $development->address_ru }}</textarea>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Manzil (EN)</label>
                    <textarea name="address_en" class="form-control" rows=2>{{ $development->address_en }}</textarea>
                </div>
            </div>

            @php
                $amenitiesList = $development->amenities;
                if (is_string($amenitiesList)) {
                    $amenitiesList = array_filter(array_map('trim', explode(',', $amenitiesList)));
                }
                if (!is_array($amenitiesList)) {
                    $amenitiesList = [];
                }
            @endphp
            <div class="mb-4">
                <label class="form-label">Afzalliklar (vergul bilan)</label>
                <input type="text" name="amenities" class="form-control" value="{{ implode(',', $amenitiesList) }}">
                <small class="text-muted">Masalan: qo'riqlash, parking, bolalar maydonchasi</small>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="featured" name="featured" value="1" {{ $development->featured ? 'checked' : '' }}>
                        <label class="form-check-label" for="featured">Featured</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="installment" name="installment_available" value="1" {{ $development->installment_available ? 'checked' : '' }}>
                        <label class="form-check-label" for="installment">Muddatli to'lov mavjud</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Saqlash
                </button>
                <a href="{{ route('admin.developments.show', $development->id) }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i>Bekor qilish
                </a>
            </div>
        </form>
    </div>
</div>
@endsection


