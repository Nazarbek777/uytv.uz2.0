@extends('layouts.page')
@section('content')

<!-- ============================ Page Title Start================================== -->
<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h2 class="ipt-title">Uy qo'shish</h2>
                <span class="ipn-subtitle">Uyingizni qo'shing</span>
            </div>
        </div>
    </div>
</div>
<!-- ============================ Page Title End ================================== -->

<!-- ============================ Submit Property Start ================================== -->
<section class="gray-simple">
    <div class="container">
        <div class="row">
            <!-- Submit Form -->
            <div class="col-lg-12 col-md-12">
                <div class="submit-page">
                    <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Information -->
                        <div class="form-submit">
                            <h3>Asosiy ma'lumotlar</h3>
                            <div class="submit-section">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Uy nomi <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>E'lon turi <span class="text-danger">*</span></label>
                                        <select name="listing_type" class="form-control @error('listing_type') is-invalid @enderror" required>
                                            <option value="">Tanlang...</option>
                                            <option value="sale" {{ old('listing_type') == 'sale' ? 'selected' : '' }}>Sotish</option>
                                            <option value="rent" {{ old('listing_type') == 'rent' ? 'selected' : '' }}>Ijaraga</option>
                                        </select>
                                        @error('listing_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Uy turi <span class="text-danger">*</span></label>
                                        <select name="property_type" class="form-control @error('property_type') is-invalid @enderror" required>
                                            <option value="">Tanlang...</option>
                                            <option value="house" {{ old('property_type') == 'house' ? 'selected' : '' }}>Uy</option>
                                            <option value="apartment" {{ old('property_type') == 'apartment' ? 'selected' : '' }}>Kvartira</option>
                                            <option value="villa" {{ old('property_type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                            <option value="land" {{ old('property_type') == 'land' ? 'selected' : '' }}>Yer</option>
                                            <option value="commercial" {{ old('property_type') == 'commercial' ? 'selected' : '' }}>Savdo</option>
                                            <option value="office" {{ old('property_type') == 'office' ? 'selected' : '' }}>Ofis</option>
                                        </select>
                                        @error('property_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Narx <span class="text-danger">*</span></label>
                                        <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Valyuta <span class="text-danger">*</span></label>
                                        <select name="currency" class="form-control @error('currency') is-invalid @enderror" required>
                                            <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="UZS" {{ old('currency') == 'UZS' ? 'selected' : '' }}>UZS</option>
                                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        </select>
                                        @error('currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Maydon</label>
                                        <input type="number" name="area" step="0.01" class="form-control @error('area') is-invalid @enderror" value="{{ old('area') }}">
                                        @error('area')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Maydon birligi</label>
                                        <select name="area_unit" class="form-control">
                                            <option value="m²" {{ old('area_unit', 'm²') == 'm²' ? 'selected' : '' }}>m²</option>
                                            <option value="sqft" {{ old('area_unit') == 'sqft' ? 'selected' : '' }}>sqft</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Yotoqxonalar</label>
                                        <select name="bedrooms" class="form-control">
                                            <option value="">Tanlang...</option>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ old('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Hammomlar</label>
                                        <select name="bathrooms" class="form-control">
                                            <option value="">Tanlang...</option>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ old('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Garajlar</label>
                                        <select name="garages" class="form-control">
                                            <option value="">Tanlang...</option>
                                            @for($i = 0; $i <= 5; $i++)
                                                <option value="{{ $i }}" {{ old('garages') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Qavatlar (umumiy)</label>
                                        <input type="number" name="floors" class="form-control" value="{{ old('floors') }}" min="1" placeholder="Masalan: 5">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Qavat (apartment uchun)</label>
                                        <input type="number" name="floor" class="form-control" value="{{ old('floor') }}" min="1" placeholder="Masalan: 3">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Qurilgan yili</label>
                                        <input type="number" name="year_built" class="form-control" value="{{ old('year_built') }}" min="1800" max="{{ date('Y') }}">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Qurilish materiali</label>
                                        <select name="construction_material" class="form-control">
                                            <option value="">Tanlang...</option>
                                            <option value="gisht" {{ old('construction_material') == 'gisht' ? 'selected' : '' }}>G'isht</option>
                                            <option value="pishgan_gisht" {{ old('construction_material') == 'pishgan_gisht' ? 'selected' : '' }}>Pishgan g'isht</option>
                                            <option value="beton" {{ old('construction_material') == 'beton' ? 'selected' : '' }}>Beton</option>
                                            <option value="yogoch" {{ old('construction_material') == 'yogoch' ? 'selected' : '' }}>Yog'och</option>
                                            <option value="paneli" {{ old('construction_material') == 'paneli' ? 'selected' : '' }}>Paneli</option>
                                            <option value="monolit" {{ old('construction_material') == 'monolit' ? 'selected' : '' }}>Monolit</option>
                                            <option value="boshqa" {{ old('construction_material') == 'boshqa' ? 'selected' : '' }}>Boshqa</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Kontent tili <span class="text-danger">*</span></label>
                                        <select name="locale" class="form-control @error('locale') is-invalid @enderror" required>
                                            <option value="uz" {{ old('locale', 'uz') == 'uz' ? 'selected' : '' }}>O'zbek</option>
                                            <option value="ru" {{ old('locale') == 'ru' ? 'selected' : '' }}>Русский</option>
                                            <option value="en" {{ old('locale') == 'en' ? 'selected' : '' }}>English</option>
                                        </select>
                                        <small class="text-muted">Qolgan tillar avtomatik tarjima qilinadi</small>
                                        @error('locale')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery -->
                        <div class="form-submit">
                            <h3>Rasmlar</h3>
                            <div class="submit-section">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="alert alert-info">
                                            <strong><i class="bi bi-info-circle me-2"></i>Rasm talablari:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li><strong>Ideal razmer:</strong> 1200x800 piksel (3:2 nisbati)</li>
                                                <li><strong>Maksimal fayl hajmi:</strong> 5 MB</li>
                                                <li><strong>Qo'llab-quvvatlanadigan formatlar:</strong> JPG, PNG, GIF</li>
                                                <li><strong>Eslatma:</strong> Agar rasm boshqa razmerda bo'lsa, tizim uni avtomatik ravishda ideal razmerga (1200x800) moslashtiradi. Kichik rasmlar ham kattalashtiriladi va to'liq 1200x800 ga moslashtiriladi.</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Asosiy rasm <span class="text-danger">*</span></label>
                                        <input type="file" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif">
                                        <small class="text-muted">Ideal razmer: 1200x800 piksel</small>
                                        @error('featured_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Qo'shimcha rasmlar (Bir nechta)</label>
                                        <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif" multiple>
                                        <small class="text-muted">Bir nechta rasm tanlashingiz mumkin. Ideal razmer: 1200x800 piksel</small>
                                        @error('images.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="form-submit">
                            <h3>Manzil</h3>
                            <div class="submit-section">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Manzil</label>
                                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Shahar</label>
                                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Viloyat</label>
                                        <input type="text" name="region" class="form-control @error('region') is-invalid @enderror" value="{{ old('region') }}">
                                        @error('region')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Davlat</label>
                                        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', 'Uzbekistan') }}">
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Pochta indeksi</label>
                                        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code') }}">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Koordinatalar</label>
                                        <div class="input-group">
                                            <input type="number" name="latitude" step="0.00000001" id="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude') }}" placeholder="Kenglik" readonly>
                                            <input type="number" name="longitude" step="0.00000001" id="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude') }}" placeholder="Uzunlik" readonly>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mapModal">
                                                <i class="bi bi-geo-alt"></i> Xaritadan belgilash
                                            </button>
                                        </div>
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Information -->
                        <div class="form-submit">
                            <h3>Batafsil ma'lumot</h3>
                            <div class="submit-section">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Qisqa tavsif</label>
                                        <textarea name="short_description" class="form-control" rows="3" maxlength="500">{{ old('short_description') }}</textarea>
                                        <small class="text-muted">Maksimal 500 belgi</small>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Tavsif <span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control h-120 @error('description') is-invalid @enderror" rows="10" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Qulayliklar (Amenities)</label>
                                        <div class="alert alert-info mb-3">
                                            <small><i class="bi bi-info-circle me-1"></i>Kerakli qulayliklarni belgilang</small>
                                        </div>
                                        <div class="row">
                                            @php
                                                $amenities = [
                                                    'Wi-Fi' => 'Wi-Fi',
                                                    'Konditsioner' => 'Konditsioner',
                                                    'Basseyn' => 'Basseyn',
                                                    'Garaj' => 'Garaj',
                                                    'Lift' => 'Lift',
                                                    'Xavfsizlik' => 'Xavfsizlik',
                                                    'Parkovka' => 'Parkovka',
                                                    'Bog\'cha' => 'Bog\'cha',
                                                    'Fitnes zal' => 'Fitnes zal',
                                                    'Sauna' => 'Sauna',
                                                    'Hamam' => 'Hamam',
                                                    'Balkon' => 'Balkon',
                                                    'Terrasa' => 'Terrasa',
                                                    'Kamin' => 'Kamin',
                                                    'Mebel' => 'Mebel',
                                                    'Plyonka oynalar' => 'Plyonka oynalar',
                                                    'Video kuzatuv' => 'Video kuzatuv',
                                                    'Qo\'riqchi' => 'Qo\'riqchi',
                                                    'Qo\'shimcha hammom' => 'Qo\'shimcha hammom',
                                                    'Oshxona anjomlari' => 'Oshxona anjomlari',
                                                ];
                                                $selectedFeatures = old('features_array', []);
                                                if (is_string($selectedFeatures)) {
                                                    $selectedFeatures = json_decode($selectedFeatures, true) ?? [];
                                                }
                                            @endphp
                                            @foreach($amenities as $key => $label)
                                            <div class="col-md-3 col-sm-4 col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="features_array[]" value="{{ $key }}" id="feature_{{ $loop->index }}" {{ in_array($key, $selectedFeatures) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="feature_{{ $loop->index }}">
                                                        {{ $label }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="features" id="features_hidden" value="{{ old('features') }}">
                                        <small class="text-muted">Kerakli qulayliklarni belgilang</small>
                                        @error('features')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Yaqin joylar</label>
                                        <textarea name="nearby_places" class="form-control @error('nearby_places') is-invalid @enderror" rows="4" placeholder="Masalan: Maktab (500m), Do'kon (200m), Park (1km)">{{ old('nearby_places') }}</textarea>
                                        <small class="text-muted">Yaqin joylarni vergul bilan ajrating</small>
                                        @error('nearby_places')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEO avtomatik yaratiladi, qo'lda kiritilmaydi -->

                        <div class="form-group col-lg-12 col-md-12">
                            <button class="btn btn-main fw-medium px-5" type="submit">
                                <i class="bi bi-check-circle me-2"></i>Uy qo'shish
                            </button>
                            <a href="{{ route('provider.properties.index') }}" class="btn btn-secondary fw-medium px-5 ms-2">
                                <i class="bi bi-x-circle me-2"></i>Bekor qilish
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ============================ Submit Property End ================================== -->

<!-- Map Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Xaritadan joylashuvni belgilang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 500px; width: 100%;"></div>
                <div class="mt-3">
                    <input type="text" id="mapSearch" class="form-control" placeholder="Manzil yoki joy nomini qidiring...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Yopish</button>
                <button type="button" class="btn btn-primary" onclick="saveMapLocation()">Saqlash</button>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map;
let marker;
let currentLat = {{ old('latitude', 41.311081) }};
let currentLng = {{ old('longitude', 69.240562) }};

document.addEventListener('DOMContentLoaded', function() {
    // Agar mavjud koordinatalar bo'lsa, ularni ishlatish
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    
    if (latInput.value && lngInput.value) {
        currentLat = parseFloat(latInput.value);
        currentLng = parseFloat(lngInput.value);
    }
    
    // Modal ochilganda xarita yaratish
    const mapModal = document.getElementById('mapModal');
    mapModal.addEventListener('shown.bs.modal', function() {
        if (!map) {
            initMap();
        }
    });
});

function initMap() {
    // Xarita yaratish
    map = L.map('map').setView([currentLat, currentLng], 13);
    
    // Tile layer qo'shish
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Marker yaratish
    marker = L.marker([currentLat, currentLng], {draggable: true}).addTo(map);
    
    // Marker harakatlanganda koordinatalarni yangilash
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        currentLat = position.lat;
        currentLng = position.lng;
        updateCoordinates();
    });
    
    // Xaritada bosilganda marker qo'yish
    map.on('click', function(e) {
        currentLat = e.latlng.lat;
        currentLng = e.latlng.lng;
        marker.setLatLng([currentLat, currentLng]);
        updateCoordinates();
    });
    
    // Qidiruv funksiyasi (Nominatim API)
    const searchInput = document.getElementById('mapSearch');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value;
        
        if (query.length > 3) {
            searchTimeout = setTimeout(() => {
                searchLocation(query);
            }, 500);
        }
    });
}

function searchLocation(query) {
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const result = data[0];
                currentLat = parseFloat(result.lat);
                currentLng = parseFloat(result.lon);
                
                map.setView([currentLat, currentLng], 15);
                marker.setLatLng([currentLat, currentLng]);
                updateCoordinates();
            }
        })
        .catch(error => {
            console.error('Qidiruv xatosi:', error);
        });
}

function updateCoordinates() {
    document.getElementById('latitude').value = currentLat.toFixed(8);
    document.getElementById('longitude').value = currentLng.toFixed(8);
}

function saveMapLocation() {
    updateCoordinates();
    const modal = bootstrap.Modal.getInstance(document.getElementById('mapModal'));
    modal.hide();
}

// Qulayliklar checkbox'larini boshqarish
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="features_array[]"]');
    const hiddenInput = document.getElementById('features_hidden');
    
    function updateFeaturesInput() {
        const selected = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        hiddenInput.value = selected.join(', ');
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateFeaturesInput);
    });
    
    // Form submit qilishdan oldin yangilash
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            updateFeaturesInput();
        });
    }
    
    // Dastlabki holatni yangilash
    updateFeaturesInput();
});
</script>

@endsection








