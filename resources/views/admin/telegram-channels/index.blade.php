@extends('admin.layout')

@section('title', 'Telegram Kanallar')


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

<div class="row mb-4">
    <div class="col-md-3">
        <div class="admin-table">
            <div class="p-3 text-center">
                <h5 class="text-muted mb-1">Jami Kanallar</h5>
                <h3 class="mb-0">{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-table">
            <div class="p-3 text-center">
                <h5 class="text-muted mb-1">Faol Kanallar</h5>
                <h3 class="mb-0 text-success">{{ $stats['active'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-table">
            <div class="p-3 text-center">
                <h5 class="text-muted mb-1">Jami Yig'ilgan</h5>
                <h3 class="mb-0 text-primary">{{ number_format($stats['total_scraped']) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-table">
            <div class="p-3 text-center">
                <a href="{{ route('admin.telegram-channels.create') }}" class="btn btn-primary w-100">
                    <i class="bi bi-plus-circle me-1"></i>Yangi Kanal
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Scraper Status Panel -->
<div class="admin-table mb-4" id="scraperStatusPanel">
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Scraper Status</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshStatus()">
                <i class="bi bi-arrow-clockwise" id="refreshIcon"></i> Yangilash
            </button>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="text-center p-3 border rounded">
                    <h6 class="text-muted mb-2">Queue Holati</h6>
                    <div id="queueStatus">
                        <span class="badge bg-secondary">Yuklanmoqda...</span>
                    </div>
                    <small class="text-muted d-block mt-2" id="queueInfo">-</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-3 border rounded">
                    <h6 class="text-muted mb-2">Kutilayotgan Job'lar</h6>
                    <h4 class="mb-0" id="pendingJobs">-</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-3 border rounded">
                    <h6 class="text-muted mb-2">Muvaffaqiyatsiz Job'lar</h6>
                    <h4 class="mb-0 text-danger" id="failedJobs">-</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-3 border rounded">
                    <h6 class="text-muted mb-2">Oxirgi Yig'ilgan</h6>
                    <div id="lastScraped">
                        <span class="text-muted">-</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3" id="recentLogsContainer" style="display: none;">
            <h6 class="mb-2">Oxirgi Log'lar:</h6>
            <div class="bg-dark text-light p-3 rounded" style="max-height: 200px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                <div id="recentLogs">-</div>
            </div>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-telegram me-2"></i>Telegram Kanallar Ro'yxati</h4>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#runScraperModal">
                <i class="bi bi-play-circle me-1"></i>Scraper'ni Ishga Tushirish
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nomi</th>
                        <th>Username</th>
                        <th>Limit</th>
                        <th>Kunlar</th>
                        <th>Yig'ilgan</th>
                        <th>Oxirgi Yig'ilgan</th>
                        <th>Holat</th>
                        <th>Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($channels as $channel)
                    <tr>
                        <td>{{ $channel->id }}</td>
                        <td>
                            <strong>{{ $channel->name }}</strong>
                            @if($channel->description)
                                <br><small class="text-muted">{{ Str::limit($channel->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <a href="https://t.me/{{ $channel->username }}" target="_blank" class="text-decoration-none">
                                @{{ $channel->username }}
                            </a>
                        </td>
                        <td>{{ $channel->scrape_limit }}</td>
                        <td>{{ $channel->scrape_days }} kun</td>
                        <td>
                            <span class="badge bg-info">{{ number_format($channel->total_scraped) }}</span>
                        </td>
                        <td>
                            @if($channel->last_scraped_at)
                                {{ $channel->last_scraped_at->diffForHumans() }}
                            @else
                                <span class="text-muted">Hali yig'ilmagan</span>
                            @endif
                        </td>
                        <td>
                            @if($channel->is_active)
                                <span class="badge bg-success">Faol</span>
                            @else
                                <span class="badge bg-secondary">Nofaol</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.telegram-channels.edit', $channel) }}" class="btn btn-outline-primary" title="Tahrirlash">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.telegram-channels.toggle-active', $channel) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $channel->is_active ? 'warning' : 'success' }}" title="{{ $channel->is_active ? 'O\'chirish' : 'Yoqish' }}">
                                        <i class="bi bi-{{ $channel->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.telegram-channels.destroy', $channel) }}" method="POST" class="d-inline" onsubmit="return confirm('Kanalni o\'chirmoqchimisiz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="O'chirish">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted">Hech qanday kanal topilmadi.</p>
                            <a href="{{ route('admin.telegram-channels.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Birinchi Kanalni Qo'shing
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($channels->hasPages())
        <div class="mt-4">
            {{ $channels->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Run Scraper Modal -->
<div class="modal fade" id="runScraperModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.telegram-channels.run-scraper') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-play-circle me-2"></i>Scraper'ni Ishga Tushirish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kanal tanlash</label>
                        <select name="channel_ids[]" class="form-select" multiple size="5">
                            @foreach($channels as $channel)
                                <option value="{{ $channel->id }}" {{ $channel->is_active ? 'selected' : '' }}>
                                    {{ $channel->name }} (@{{ $channel->username }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Ctrl yoki Cmd tugmasini bosib bir nechta kanal tanlash mumkin. Hech narsa tanlanmasa, barcha faol kanallar ishlatiladi.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Limit (har bir kanal uchun)</label>
                        <input type="number" name="limit" class="form-control" value="50" min="1" max="1000">
                        <small class="text-muted">Bir marta nechta uy-joy yig'ish kerak (1-1000). Bo'sh qoldirilsa, kanal sozlamalari ishlatiladi.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kunlar</label>
                        <input type="number" name="days" class="form-control" value="7" min="1" max="365">
                        <small class="text-muted">Necha kunga qadar eski postlarni olish (1-365). Bo'sh qoldirilsa, kanal sozlamalari ishlatiladi.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-play-circle me-1"></i>Ishga Tushirish
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
let statusInterval;

// Status'ni yangilash
function refreshStatus() {
    const refreshIcon = document.getElementById('refreshIcon');
    refreshIcon.classList.add('spinning');

    fetch('{{ route("admin.telegram-channels.scraper-status") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Queue status
                const queueStatus = document.getElementById('queueStatus');
                const queueInfo = document.getElementById('queueInfo');
                if (data.queue.status === 'processing') {
                    queueStatus.innerHTML = '<span class="badge bg-warning"><i class="bi bi-hourglass-split me-1"></i>Ishlayapti</span>';
                    queueInfo.textContent = `${data.queue.pending} ta job kutilmoqda`;
                } else {
                    queueStatus.innerHTML = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Bo\'sh</span>';
                    queueInfo.textContent = 'Hech qanday job yo\'q';
                }

                // Pending jobs
                document.getElementById('pendingJobs').textContent = data.queue.pending;

                // Failed jobs
                document.getElementById('failedJobs').textContent = data.queue.failed;

                // Last scraped
                const lastScraped = document.getElementById('lastScraped');
                if (data.last_scraped) {
                    lastScraped.innerHTML = `
                        <strong>${data.last_scraped.channel}</strong><br>
                        <small class="text-muted">${data.last_scraped.time}</small>
                    `;
                } else {
                    lastScraped.innerHTML = '<span class="text-muted">Hali yig\'ilmagan</span>';
                }

                // Recent logs
                if (data.recent_logs && data.recent_logs.length > 0) {
                    const logsContainer = document.getElementById('recentLogsContainer');
                    const logsDiv = document.getElementById('recentLogs');
                    logsContainer.style.display = 'block';
                    logsDiv.innerHTML = data.recent_logs.slice(0, 5).map(log => {
                        // Log formatini chiroyli qilish
                        let logText = log;
                        if (logText.length > 200) {
                            logText = logText.substring(0, 200) + '...';
                        }
                        return `<div class="mb-1">${escapeHtml(logText)}</div>`;
                    }).join('');
                }
            }
        })
        .catch(error => {
            console.error('Status yangilash xatosi:', error);
        })
        .finally(() => {
            refreshIcon.classList.remove('spinning');
        });
}

// HTML escape
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Avtomatik yangilash (har 5 soniyada)
document.addEventListener('DOMContentLoaded', function() {
    refreshStatus(); // Birinchi marta yuklash
    statusInterval = setInterval(refreshStatus, 5000); // Har 5 soniyada yangilash
});

// Spinning animation
const style = document.createElement('style');
style.textContent = `
    .spinning {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>

@endsection

