@extends('admin.layout')

@section('title', 'Yangi Telegram Kanal')
@section('page-title', 'Yangi Telegram Kanal Qo\'shish')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-table">
            <div class="p-4">
                <form action="{{ route('admin.telegram-channels.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kanal Nomi <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Masalan: Uy-joylar Toshkent (avtomatik to'ldiriladi)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required placeholder="kanal_username" onblur="fetchChannelInfo()">
                            <button type="button" class="btn btn-outline-primary" onclick="fetchChannelInfo()" id="fetchBtn" title="Ma'lumotlarni olish">
                                <i class="bi bi-arrow-clockwise" id="fetchIcon"></i>
                            </button>
                        </div>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Telegram kanal username'i (@ belgisiz). Kiritib, tugmani bosing yoki Enter bosing.</small>
                        <div id="fetchStatus" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Chat ID</label>
                        <input type="text" name="chat_id" id="chat_id" class="form-control @error('chat_id') is-invalid @enderror" value="{{ old('chat_id') }}" placeholder="-1001234567890">
                        @error('chat_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Ixtiyoriy. Telegram chat ID (avtomatik to'ldiriladi)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tavsif</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kanal tavsifi (avtomatik to'ldiriladi)</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">
                                Faol
                            </label>
                        </div>
                        <small class="text-muted">Kanalni faol qilish yoki o'chirish</small>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Scraper Sozlamalari</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bir marta yig'ish limiti</label>
                        <input type="number" name="scrape_limit" class="form-control @error('scrape_limit') is-invalid @enderror" value="{{ old('scrape_limit', 50) }}" min="1" max="1000">
                        @error('scrape_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Bir marta nechta uy-joy yig'ish kerak (1-1000). Default: 50</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kunlar</label>
                        <input type="number" name="scrape_days" class="form-control @error('scrape_days') is-invalid @enderror" value="{{ old('scrape_days', 7) }}" min="1" max="365">
                        @error('scrape_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Necha kunga qadar eski postlarni olish (1-365). Default: 7 kun</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Saqlash
                        </button>
                        <a href="{{ route('admin.telegram-channels.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Orqaga
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-table">
            <div class="p-4">
                <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Ma'lumot</h5>
                
                <div class="mb-3">
                    <h6>Username qanday topiladi?</h6>
                    <p class="text-muted small mb-0">
                        Telegram kanal linkini oching. Masalan: <code>https://t.me/uyjoylar_toshkent</code><br>
                        Bu yerda <code>uyjoylar_toshkent</code> - bu username.
                    </p>
                </div>
                
                <div class="mb-3">
                    <h6>Chat ID qanday topiladi?</h6>
                    <p class="text-muted small mb-0">
                        Chat ID'ni olish uchun Telegram bot yaratib, kanalga qo'shing va bot orqali chat ID'ni oling. Bu ixtiyoriy.
                    </p>
                </div>
                
                <div class="mb-3">
                    <h6>Scraper qanday ishlaydi?</h6>
                    <p class="text-muted small mb-0">
                        1. Telegram kanaldan postlar olinadi<br>
                        2. OpenAI orqali tahlil qilinadi<br>
                        3. Uy-joy ma'lumotlari ajratiladi<br>
                        4. Database'ga saqlanadi (pending holatda)
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let isFetching = false;

function fetchChannelInfo() {
    const usernameInput = document.getElementById('username');
    const username = usernameInput.value.trim();
    
    if (!username) {
        return;
    }
    
    if (isFetching) {
        return;
    }
    
    isFetching = true;
    const fetchBtn = document.getElementById('fetchBtn');
    const fetchIcon = document.getElementById('fetchIcon');
    const fetchStatus = document.getElementById('fetchStatus');
    
    // Loading holat
    fetchBtn.disabled = true;
    fetchIcon.classList.add('bi-hourglass-split');
    fetchIcon.classList.remove('bi-arrow-clockwise');
    fetchStatus.innerHTML = '<div class="alert alert-info mb-0"><i class="bi bi-hourglass-split me-1"></i>Ma\'lumotlar olinmoqda...</div>';
    
    fetch('{{ route("admin.telegram-channels.get-info") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            username: username
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ma'lumotlarni to'ldirish
            if (data.name) {
                document.getElementById('name').value = data.name;
            }
            if (data.chat_id) {
                document.getElementById('chat_id').value = data.chat_id;
            }
            if (data.description) {
                document.getElementById('description').value = data.description;
            }
            
            fetchStatus.innerHTML = '<div class="alert alert-success mb-0"><i class="bi bi-check-circle me-1"></i>Ma\'lumotlar muvaffaqiyatli olindi!</div>';
        } else {
            fetchStatus.innerHTML = '<div class="alert alert-warning mb-0"><i class="bi bi-exclamation-triangle me-1"></i>' + (data.message || 'Ma\'lumotlar olinmadi') + '</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        fetchStatus.innerHTML = '<div class="alert alert-danger mb-0"><i class="bi bi-x-circle me-1"></i>Xatolik yuz berdi. Iltimos, qayta urinib ko\'ring.</div>';
    })
    .finally(() => {
        isFetching = false;
        fetchBtn.disabled = false;
        fetchIcon.classList.remove('bi-hourglass-split');
        fetchIcon.classList.add('bi-arrow-clockwise');
    });
}

// Enter tugmasi bosilganda
document.getElementById('username').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        fetchChannelInfo();
    }
});
</script>
@endsection

