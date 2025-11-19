@extends('admin.layout')

@section('title', 'Media Boshqaruv')
@section('page-title', 'Media Boshqaruv')

@section('content')
<div class="admin-table mb-4">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-folder me-2"></i>Media Boshqaruv</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-upload me-1"></i>Fayl yuklash
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#folderModal">
                <i class="bi bi-folder-plus me-1"></i>Papka yaratish
            </button>
        </div>
    </div>

    <div class="p-3 bg-light border-bottom">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                @foreach($breadcrumbs as $breadcrumb)
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.media.index', ['path' => $breadcrumb['path']]) }}">{{ $breadcrumb['name'] }}</a>
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>

    @if(!empty($dirList))
    <div class="p-3 border-bottom">
        <h6 class="mb-3"><i class="bi bi-folder me-2"></i>Papkalar</h6>
        <div class="row g-2">
            @foreach($dirList as $dir)
            <div class="col-md-3">
                <a href="{{ route('admin.media.index', ['path' => $dir['path']]) }}" class="text-decoration-none">
                    <div class="border rounded p-3 text-center hover-shadow">
                        <i class="bi bi-folder-fill text-warning" style="font-size: 48px;"></i>
                        <div class="mt-2 fw-semibold">{{ $dir['name'] }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($fileList))
    <div class="p-3">
        <h6 class="mb-3"><i class="bi bi-file-earmark me-2"></i>Fayllar ({{ count($fileList) }})</h6>
        <div class="row g-3">
            @foreach($fileList as $file)
            <div class="col-md-3">
                <div class="border rounded p-3 text-center position-relative">
                    @if(str_starts_with($file['type'], 'image/'))
                        <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="img-fluid rounded mb-2" style="max-height: 150px; object-fit: cover;">
                    @else
                        <i class="bi bi-file-earmark" style="font-size: 48px; color: #6c757d;"></i>
                    @endif
                    <div class="small text-truncate" title="{{ $file['name'] }}">{{ $file['name'] }}</div>
                    <div class="small text-muted">{{ number_format($file['size'] / 1024, 2) }} KB</div>
                    <div class="mt-2 d-flex gap-1 justify-content-center">
                        <a href="{{ $file['url'] }}" target="_blank" class="btn btn-sm btn-primary" title="Ko'rish">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form action="{{ route('admin.media.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="path" value="{{ $file['path'] }}">
                            <button type="submit" class="btn btn-sm btn-danger" title="O'chirish">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="p-5 text-center text-muted">
        <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
        <p>Bu papkada fayllar yo'q</p>
    </div>
    @endif
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="path" value="{{ $currentPath }}">
                <div class="modal-header">
                    <h5 class="modal-title">Fayl yuklash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fayl tanlash</label>
                        <input type="file" name="file" class="form-control" required>
                        <small class="text-muted">Maksimal hajm: 10MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="submit" class="btn btn-primary">Yuklash</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Folder Modal -->
<div class="modal fade" id="folderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.media.create-folder') }}" method="POST">
                @csrf
                <input type="hidden" name="path" value="{{ $currentPath }}">
                <div class="modal-header">
                    <h5 class="modal-title">Yangi papka yaratish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Papka nomi</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="submit" class="btn btn-success">Yaratish</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.hover-shadow:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s;
}
</style>
@endsection

