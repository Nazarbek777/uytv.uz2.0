@extends('admin.layout')

@section('title', 'AI Price Predictor')
@section('page-title', 'AI Narx Bashoratchisi')

@section('content')
<div class="admin-card">
    <h5 class="mb-4"><i class="bi bi-calculator me-2"></i>Uy-joy narxini bashorat qilish</h5>
    
    <form id="predictForm">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Maydon (m²) <span class="text-danger">*</span></label>
                <input type="number" name="area" class="form-control" required min="1" step="0.01">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Xonalar soni <span class="text-danger">*</span></label>
                <input type="number" name="bedrooms" class="form-control" required min="1">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Hammomlar soni <span class="text-danger">*</span></label>
                <input type="number" name="bathrooms" class="form-control" required min="1">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Shahar <span class="text-danger">*</span></label>
                <input type="text" name="city" class="form-control" required placeholder="Masalan: Toshkent">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Uy-joy turi <span class="text-danger">*</span></label>
                <select name="property_type" class="form-select" required>
                    <option value="apartment">Kvartira</option>
                    <option value="house">Uy</option>
                    <option value="villa">Villa</option>
                    <option value="land">Yer</option>
                    <option value="commercial">Savdo</option>
                    <option value="office">Ofis</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">E'lon turi <span class="text-danger">*</span></label>
                <select name="listing_type" class="form-select" required>
                    <option value="sale">Sotish</option>
                    <option value="rent">Ijaraga</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-calculator me-1"></i>Bashorat qilish
                </button>
            </div>
        </div>
    </form>

    <div id="predictionResult" class="mt-4" style="display: none;">
        <div class="admin-card">
            <h6 class="mb-3">Bashorat natijalari</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded p-3 text-center">
                        <small class="text-muted d-block mb-2">Bashorat qilingan narx</small>
                        <h3 class="mb-0 text-primary" id="predictedPrice">—</h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3 text-center">
                        <small class="text-muted d-block mb-2">Ishonchlilik</small>
                        <h4 class="mb-0 text-success" id="confidence">—</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <small class="text-muted d-block mb-2">Bozor o'rtacha</small>
                        <strong id="marketAvg">—</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <small class="text-muted d-block mb-2">Min narx</small>
                        <strong id="marketMin">—</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <small class="text-muted d-block mb-2">Max narx</small>
                        <strong id="marketMax">—</strong>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block mb-2">Tahlil</small>
                        <p class="mb-0" id="reasoning">—</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('predictForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Hisoblanmoqda...';
    
    try {
        const response = await fetch('{{ route("admin.ai.price-predictor.predict") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('predictedPrice').textContent = new Intl.NumberFormat('uz-UZ').format(data.predicted_price) + ' UZS';
            document.getElementById('confidence').textContent = data.confidence + '%';
            document.getElementById('marketAvg').textContent = new Intl.NumberFormat('uz-UZ').format(data.market_avg) + ' UZS';
            document.getElementById('marketMin').textContent = new Intl.NumberFormat('uz-UZ').format(data.market_min) + ' UZS';
            document.getElementById('marketMax').textContent = new Intl.NumberFormat('uz-UZ').format(data.market_max) + ' UZS';
            document.getElementById('reasoning').textContent = data.reasoning || 'Tahlil mavjud emas';
            document.getElementById('predictionResult').style.display = 'block';
        } else {
            alert('Xatolik: ' + (data.error || 'Noma\'lum xatolik'));
        }
    } catch (error) {
        alert('Xatolik: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-calculator me-1"></i>Bashorat qilish';
    }
});
</script>
@endsection


