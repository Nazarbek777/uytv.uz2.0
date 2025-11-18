@extends('admin.layout')

@section('title', 'Sozlamalar')
@section('page-title', 'Sozlamalar')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="admin-table">
            <div class="p-4">
                <h4 class="mb-4"><i class="bi bi-robot me-2"></i>OpenAI Sozlamalari</h4>
                
                <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- OpenAI API Key -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            OpenAI API Key <span class="text-danger">*</span>
                            <button type="button" class="btn btn-sm btn-link p-0 ms-2" onclick="togglePassword('openai_api_key')" title="Ko'rsatish/Yashirish">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </label>
                        <div class="input-group">
                            <input type="password" name="openai_api_key" id="openai_api_key" class="form-control" value="{{ $settings['openai_api_key'] }}" required>
                            <button type="button" class="btn btn-outline-primary" onclick="testOpenAI()" id="testBtn">
                                <i class="bi bi-check-circle me-1"></i>Test qilish
                            </button>
                        </div>
                        <small class="text-muted">OpenAI API key'ingizni kiriting. <a href="https://platform.openai.com/api-keys" target="_blank">API key olish</a></small>
                        <div id="testResult" class="mt-2"></div>
                    </div>
                    
                    <!-- OpenAI Model -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">OpenAI Model <span class="text-danger">*</span></label>
                        <select name="openai_model" class="form-select" required>
                            <option value="gpt-4o-mini" {{ $settings['openai_model'] == 'gpt-4o-mini' ? 'selected' : '' }}>
                                GPT-4o Mini (Arzon, tez) - $0.15/$0.60 per 1M tokens
                            </option>
                            <option value="gpt-4o" {{ $settings['openai_model'] == 'gpt-4o' ? 'selected' : '' }}>
                                GPT-4o (Yaxshi) - $2.50/$10 per 1M tokens
                            </option>
                            <option value="gpt-4-turbo" {{ $settings['openai_model'] == 'gpt-4-turbo' ? 'selected' : '' }}>
                                GPT-4 Turbo (Eng yaxshi) - $10/$30 per 1M tokens
                            </option>
                            <option value="gpt-4" {{ $settings['openai_model'] == 'gpt-4' ? 'selected' : '' }}>
                                GPT-4 (Standart) - $30/$60 per 1M tokens
                            </option>
                        </select>
                        <small class="text-muted">Model tanlang. Qimmatroq modellar yaxshiroq natija beradi.</small>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3"><i class="bi bi-sliders me-2"></i>Scraper Sozlamalari</h5>
                    
                    <!-- Scraper Enabled -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="scraper_enabled" value="1" class="form-check-input" id="scraper_enabled" {{ ($settings['scraper_enabled'] == 'true' || $settings['scraper_enabled'] === true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="scraper_enabled">
                                Scraper'ni yoqish
                            </label>
                        </div>
                        <small class="text-muted">Scraper'ni yoqish yoki o'chirish</small>
                    </div>
                    
                    <!-- Scraper Limit -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Bir marta yig'ish limiti</label>
                        <input type="number" name="scraper_limit" class="form-control" value="{{ $settings['scraper_limit'] }}" min="1" max="1000">
                        <small class="text-muted">Bir marta nechta uy-joy yig'ish kerak (1-1000)</small>
                    </div>
                    
                    <!-- Scraper Sources -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Manba saytlar</label>
                        <input type="text" name="scraper_sources" class="form-control" value="{{ $settings['scraper_sources'] }}" placeholder="olx,uybor,exarid">
                        <small class="text-muted">Vergul bilan ajratilgan manba saytlar ro'yxati</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Saqlash
                        </button>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Bekor qilish
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Info Card -->
        <div class="admin-table mb-4">
            <div class="p-4">
                <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Ma'lumot</h5>
                
                <div class="mb-3">
                    <h6>OpenAI API Key</h6>
                    <p class="text-muted small mb-0">
                        OpenAI API key'ingizni <a href="https://platform.openai.com/api-keys" target="_blank">bu yerdan</a> olishingiz mumkin.
                    </p>
                </div>
                
                <div class="mb-3">
                    <h6>Model tanlash</h6>
                    <p class="text-muted small mb-0">
                        <strong>gpt-4o-mini</strong> - Arzon va tez, ko'p saytlar uchun yetarli.<br>
                        <strong>gpt-4o</strong> - Yaxshiroq tahlil, qimmatroq.<br>
                        <strong>gpt-4-turbo</strong> - Eng yaxshi natija.
                    </p>
                </div>
                
                <div class="mb-3">
                    <h6>Xarajatlar</h6>
                    <p class="text-muted small mb-0">
                        100 ta uy-joy uchun taxminan:<br>
                        • gpt-4o-mini: ~$0.10-0.50<br>
                        • gpt-4o: ~$1-5<br>
                        • gpt-4-turbo: ~$5-20
                    </p>
                </div>
                
                <div class="mb-3">
                    <h6>Qanday ishlaydi?</h6>
                    <p class="text-muted small mb-0">
                        1. Sayt HTML olinadi<br>
                        2. OpenAI'ga yuboriladi<br>
                        3. AI tahlil qiladi<br>
                        4. Database'ga saqlanadi
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="admin-table">
            <div class="p-4">
                <h5 class="mb-3"><i class="bi bi-lightning me-2"></i>Tezkor Amallar</h5>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                    <button type="button" class="btn btn-outline-success" onclick="runScraper()">
                        <i class="bi bi-play-circle me-1"></i>Scraper'ni ishga tushirish
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById('toggleIcon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

function testOpenAI() {
    const apiKey = document.getElementById('openai_api_key').value;
    const testBtn = document.getElementById('testBtn');
    const testResult = document.getElementById('testResult');
    
    if (!apiKey) {
        testResult.innerHTML = '<div class="alert alert-warning">Iltimos, API key kiriting!</div>';
        return;
    }
    
    testBtn.disabled = true;
    testBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Test qilinmoqda...';
    testResult.innerHTML = '<div class="alert alert-info">Test qilinmoqda...</div>';
    
    fetch('{{ route("admin.settings.test-openai") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            api_key: apiKey
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            testResult.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle me-1"></i>' + data.message + '</div>';
        } else {
            testResult.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle me-1"></i>' + data.message + '</div>';
        }
    })
    .catch(error => {
        testResult.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle me-1"></i>Xatolik: ' + error.message + '</div>';
    })
    .finally(() => {
        testBtn.disabled = false;
        testBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Test qilish';
    });
}

function runScraper() {
    if (confirm('Scraper\'ni ishga tushirmoqchimisiz? Bu biroz vaqt olishi mumkin.')) {
        // Bu yerda scraper'ni ishga tushirish uchun AJAX yoki redirect qilish mumkin
        window.location.href = '{{ route("admin.dashboard") }}?run_scraper=1';
    }
}
</script>
@endsection

