@extends('admin.layout')

@section('title', 'Telegram Kanalni Tahrirlash')
@section('page-title', 'Telegram Kanalni Tahrirlash')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-table">
            <div class="p-4">
                <form action="{{ route('admin.telegram-channels.update', $telegramChannel) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kanal Nomi <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $telegramChannel->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $telegramChannel->username) }}" required>
                            <button type="button" class="btn btn-outline-primary" onclick="fetchChannelInfo()" id="fetchBtn" title="Ma'lumotlarni yangilash">
                                <i class="bi bi-arrow-clockwise" id="fetchIcon"></i>
                            </button>
                        </div>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Username o'zgarmaydi, lekin ma'lumotlarni yangilash mumkin.</small>
                        <div id="fetchStatus" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Chat ID</label>
                        <input type="text" name="chat_id" id="chat_id" class="form-control @error('chat_id') is-invalid @enderror" value="{{ old('chat_id', $telegramChannel->chat_id) }}">
                        @error('chat_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tavsif</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $telegramChannel->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $telegramChannel->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">
                                Faol
                            </label>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Scraper Sozlamalari</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bir marta yig'ish limiti</label>
                        <input type="number" name="scrape_limit" class="form-control @error('scrape_limit') is-invalid @enderror" value="{{ old('scrape_limit', $telegramChannel->scrape_limit) }}" min="1" max="1000">
                        @error('scrape_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kunlar</label>
                        <input type="number" name="scrape_days" class="form-control @error('scrape_days') is-invalid @enderror" value="{{ old('scrape_days', $telegramChannel->scrape_days) }}" min="1" max="365">
                        @error('scrape_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                <h5 class="mb-3"><i class="bi bi-bar-chart me-2"></i>Statistika</h5>
                
                <div class="mb-3">
                    <strong>Jami yig'ilgan:</strong>
                    <span class="badge bg-info ms-2">{{ number_format($telegramChannel->total_scraped) }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Oxirgi yig'ilgan:</strong>
                    <div class="text-muted small">
                        @if($telegramChannel->last_scraped_at)
                            {{ $telegramChannel->last_scraped_at->format('d.m.Y H:i') }}
                            <br>
                            <small>{{ $telegramChannel->last_scraped_at->diffForHumans() }}</small>
                        @else
                            Hali yig'ilmagan
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Yaratilgan:</strong>
                    <div class="text-muted small">
                        {{ $telegramChannel->created_at->format('d.m.Y H:i') }}
                    </div>
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
    fetchStatus.innerHTML = '<div class="alert alert-info mb-0"><i class="bi bi-hourglass-split me-1"></i>Ma\'lumotlar yangilanmoqda...</div>';
    
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
            // Ma'lumotlarni yangilash
            if (data.name) {
                document.getElementById('name').value = data.name;
            }
            if (data.chat_id) {
                document.getElementById('chat_id').value = data.chat_id;
            }
            if (data.description) {
                document.getElementById('description').value = data.description;
            }
            
            fetchStatus.innerHTML = '<div class="alert alert-success mb-0"><i class="bi bi-check-circle me-1"></i>Ma\'lumotlar muvaffaqiyatli yangilandi!</div>';
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
</script>
@endsection

