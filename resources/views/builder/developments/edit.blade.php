@extends('builder.layout')

@section('title', 'Loyiha Tahrirlash')
@section('page-title', 'B2B Builder Panel - Loyiha Tahrirlash')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="builder-table">
            <div class="p-3 border-bottom bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Loyiha Tahrirlash: {{ $development->title_uz }}</h5>
            </div>
            
            @if($development->status === 'rejected' && $development->rejection_reason)
            <div class="p-3 bg-danger text-white">
                <h6 class="mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Rad qilingan sabab:</h6>
                <p class="mb-0">{{ $development->rejection_reason }}</p>
            </div>
            @endif
            
            <form action="{{ route('builder.developments.update', $development->id) }}" method="POST" enctype="multipart/form-data" class="p-4">
                @csrf
                @method('PUT')

                <!-- Asosiy Ma'lumotlar -->
                <div class="mb-4">
                    <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Asosiy Ma'lumotlar</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Loyiha nomi (O'zbek) <span class="text-danger">*</span></label>
                            <input type="text" name="title_uz" class="form-control @error('title_uz') is-invalid @enderror" value="{{ old('title_uz', $development->title_uz) }}" required>
                            @error('title_uz')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Loyiha nomi (Rus)</label>
                            <input type="text" name="title_ru" class="form-control" value="{{ old('title_ru', $development->title_ru) }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Loyiha nomi (Ingliz)</label>
                            <input type="text" name="title_en" class="form-control" value="{{ old('title_en', $development->title_en) }}">
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tavsif (O'zbek)</label>
                            <textarea name="description_uz" class="form-control" rows="3">{{ old('description_uz', $development->description_uz) }}</textarea>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tavsif (Rus)</label>
                            <textarea name="description_ru" class="form-control" rows="3">{{ old('description_ru', $development->description_ru) }}</textarea>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tavsif (Ingliz)</label>
                            <textarea name="description_en" class="form-control" rows="3">{{ old('description_en', $development->description_en) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Developer Ma'lumotlari -->
                <div class="mb-4 border-top pt-4">
                    <h5 class="mb-3"><i class="bi bi-building me-2"></i>Developer Ma'lumotlari</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Developer nomi (O'zbek) <span class="text-danger">*</span></label>
                            <input type="text" name="developer_name_uz" class="form-control" value="{{ old('developer_name_uz', $development->developer_name_uz) }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Developer nomi (Rus)</label>
                            <input type="text" name="developer_name_ru" class="form-control" value="{{ old('developer_name_ru', $development->developer_name_ru) }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Developer nomi (Ingliz)</label>
                            <input type="text" name="developer_name_en" class="form-control" value="{{ old('developer_name_en', $development->developer_name_en) }}">
                        </div>
                    </div>
                </div>

                <!-- Manzil -->
                <div class="mb-4 border-top pt-4">
                    <h5 class="mb-3"><i class="bi bi-geo-alt me-2"></i>Manzil</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Shahar <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $development->city) }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Viloyat/Tuman</label>
                            <input type="text" name="region" class="form-control" value="{{ old('region', $development->region) }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Koordinatalar</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" name="latitude" class="form-control" placeholder="Latitude" value="{{ old('latitude', $development->latitude) }}">
                                </div>
                                <div class="col-6">
                                    <input type="text" name="longitude" class="form-control" placeholder="Longitude" value="{{ old('longitude', $development->longitude) }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">To'liq manzil (O'zbek)</label>
                            <textarea name="address_uz" class="form-control" rows="2">{{ old('address_uz', $development->address_uz) }}</textarea>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">To'liq manzil (Rus)</label>
                            <textarea name="address_ru" class="form-control" rows="2">{{ old('address_ru', $development->address_ru) }}</textarea>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">To'liq manzil (Ingliz)</label>
                            <textarea name="address_en" class="form-control" rows="2">{{ old('address_en', $development->address_en) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Narx -->
                <div class="mb-4 border-top pt-4">
                    <h5 class="mb-3"><i class="bi bi-cash-stack me-2"></i>Narx</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minimal narx (dan)</label>
                            <input type="number" name="price_from" class="form-control" step="0.01" value="{{ old('price_from', $development->price_from) }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Narx m² (UZS)</label>
                            <input type="number" name="price_per_sqm" class="form-control" step="0.01" value="{{ old('price_per_sqm', $development->price_per_sqm) }}">
                        </div>
                    </div>
                </div>

                <!-- Qo'shimcha Ma'lumotlar -->
                <div class="mb-4 border-top pt-4">
                    <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Qo'shimcha Ma'lumotlar</h5>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tugallash sanasi</label>
                            <input type="date" name="completion_date" class="form-control" value="{{ old('completion_date', $development->completion_date?->format('Y-m-d')) }}">
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Binolar soni</label>
                            <input type="number" name="total_buildings" class="form-control" min="1" value="{{ old('total_buildings', $development->total_buildings) }}">
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Qavatlar soni</label>
                            <input type="number" name="total_floors" class="form-control" min="1" value="{{ old('total_floors', $development->total_floors) }}">
                        </div>
                    </div>
                </div>

                <!-- Rasmlar -->
                <div class="mb-4 border-top pt-4">
                    <h5 class="mb-3"><i class="bi bi-images me-2"></i>Rasmlar</h5>
                    
                    @if($development->featured_image)
                    <div class="mb-3">
                        <label class="form-label">Joriy asosiy rasm</label>
                        <div>
                            <img src="{{ asset('storage/' . $development->featured_image) }}" alt="" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Yangi asosiy rasm (agar o'zgartirmoqchi bo'lsangiz)</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                            <small class="text-muted">Ideal razmer: 1200x800 piksel, maksimal: 5MB</small>
                        </div>
                        
                        @if($development->images && count($development->images) > 0)
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Joriy qo'shimcha rasmlar</label>
                            <div class="row g-2">
                                @foreach($development->images as $image)
                                <div class="col-md-3">
                                    <img src="{{ asset('storage/' . $image) }}" alt="" class="img-fluid rounded">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Yangi qo'shimcha rasmlar qo'shish</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted">Bir nechta rasm tanlash mumkin (Ctrl+Click)</small>
                        </div>
                    </div>
                </div>

                <!-- Kvartira Tiplari -->
                <div class="mb-4 border-top pt-4">
                    <h5 class="mb-3"><i class="bi bi-house-door me-2"></i>Kvartira Tiplari</h5>
                    
                    <div id="properties-container">
                        @foreach($development->properties as $index => $property)
                        <div class="property-item border p-3 mb-3 rounded">
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Xonalar <span class="text-danger">*</span></label>
                                    <select name="properties[{{ $index }}][bedrooms]" class="form-control" required>
                                        <option value="1" {{ $property->bedrooms == 1 ? 'selected' : '' }}>1 xonali</option>
                                        <option value="2" {{ $property->bedrooms == 2 ? 'selected' : '' }}>2 xonali</option>
                                        <option value="3" {{ $property->bedrooms == 3 ? 'selected' : '' }}>3 xonali</option>
                                        <option value="4" {{ $property->bedrooms == 4 ? 'selected' : '' }}>4 xonali</option>
                                        <option value="5" {{ $property->bedrooms == 5 ? 'selected' : '' }}>5+ xonali</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Maydon dan (m²) <span class="text-danger">*</span></label>
                                    <input type="number" name="properties[{{ $index }}][area_from]" class="form-control" step="0.01" value="{{ $property->area_from }}" required>
                                </div>
                                
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Maydon gacha (m²)</label>
                                    <input type="number" name="properties[{{ $index }}][area_to]" class="form-control" step="0.01" value="{{ $property->area_to }}">
                                </div>
                                
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Narx dan <span class="text-danger">*</span></label>
                                    <input type="number" name="properties[{{ $index }}][price_from]" class="form-control" step="0.01" value="{{ $property->price_from }}" required>
                                </div>
                                
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Valyuta</label>
                                    <select name="properties[{{ $index }}][currency]" class="form-control">
                                        <option value="UZS" {{ $property->currency == 'UZS' ? 'selected' : '' }}>UZS</option>
                                        <option value="USD" {{ $property->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="EUR" {{ $property->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2 mb-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger w-100 remove-property" onclick="removeProperty(this)">
                                        <i class="bi bi-trash"></i> O'chirish
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <button type="button" class="btn btn-success" onclick="addProperty()">
                        <i class="bi bi-plus-circle me-2"></i>Yana kvartira tipi qo'shish
                    </button>
                </div>

                <!-- Submit -->
                <div class="border-top pt-4">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('builder.developments.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Orqaga
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Yangilash
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let propertyIndex = {{ $development->properties->count() }};

function addProperty() {
    const container = document.getElementById('properties-container');
    const newProperty = document.createElement('div');
    newProperty.className = 'property-item border p-3 mb-3 rounded';
    newProperty.innerHTML = `
        <div class="row">
            <div class="col-md-2 mb-3">
                <label class="form-label">Xonalar <span class="text-danger">*</span></label>
                <select name="properties[${propertyIndex}][bedrooms]" class="form-control" required>
                    <option value="1">1 xonali</option>
                    <option value="2">2 xonali</option>
                    <option value="3">3 xonali</option>
                    <option value="4">4 xonali</option>
                    <option value="5">5+ xonali</option>
                </select>
            </div>
            
            <div class="col-md-2 mb-3">
                <label class="form-label">Maydon dan (m²) <span class="text-danger">*</span></label>
                <input type="number" name="properties[${propertyIndex}][area_from]" class="form-control" step="0.01" required>
            </div>
            
            <div class="col-md-2 mb-3">
                <label class="form-label">Maydon gacha (m²)</label>
                <input type="number" name="properties[${propertyIndex}][area_to]" class="form-control" step="0.01">
            </div>
            
            <div class="col-md-2 mb-3">
                <label class="form-label">Narx dan <span class="text-danger">*</span></label>
                <input type="number" name="properties[${propertyIndex}][price_from]" class="form-control" step="0.01" required>
            </div>
            
            <div class="col-md-2 mb-3">
                <label class="form-label">Valyuta</label>
                <select name="properties[${propertyIndex}][currency]" class="form-control">
                    <option value="UZS" selected>UZS</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
            
            <div class="col-md-2 mb-3 d-flex align-items-end">
                <button type="button" class="btn btn-danger w-100" onclick="removeProperty(this)">
                    <i class="bi bi-trash"></i> O'chirish
                </button>
            </div>
        </div>
    `;
    container.appendChild(newProperty);
    propertyIndex++;
}

function removeProperty(btn) {
    const items = document.querySelectorAll('.property-item');
    if (items.length > 1) {
        btn.closest('.property-item').remove();
    } else {
        alert('Kamida bitta kvartira tipi bo\'lishi kerak!');
    }
}
</script>
@endsection

