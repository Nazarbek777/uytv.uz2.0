@extends('admin.layout')

@section('title', 'Izohlar')
@section('page-title', 'Izohlar Boshqaruvi')

@section('content')
<div class="admin-table mb-4">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Barcha Izohlar</h5>
        <div>
            <span class="badge bg-primary">{{ $comments->total() }} ta izoh</span>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="p-3 bg-light border-bottom">
        <form method="GET" action="{{ route('admin.comments.index') }}" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Qidirish (ism, email, izoh)..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="approved" class="form-select">
                    <option value="">Barcha</option>
                    <option value="1" {{ request('approved') == '1' ? 'selected' : '' }}>Tasdiqlangan</option>
                    <option value="0" {{ request('approved') == '0' ? 'selected' : '' }}>Tasdiqlanmagan</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Qidirish</button>
                <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i>Tozalash</a>
            </div>
        </form>
    </div>
    
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ism</th>
                <th>Email</th>
                <th>Uy-joy</th>
                <th>Izoh</th>
                <th>Reyting</th>
                <th>Tasdiqlangan</th>
                <th>Sana</th>
                <th>Amallar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comments as $comment)
            <tr>
                <td>#{{ $comment->id }}</td>
                <td class="fw-semibold">{{ $comment->name }}</td>
                <td>{{ $comment->email }}</td>
                <td>
                    @if($comment->property)
                        <a href="{{ route('admin.properties.show', $comment->property->id) }}" class="text-decoration-none">
                            {{ Str::limit($comment->property->title, 30) }}
                        </a>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>{{ Str::limit($comment->comment, 50) }}</td>
                <td>
                    @if($comment->rating)
                        <div class="d-flex align-items-center">
                            <span class="text-warning me-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $comment->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </span>
                            <span class="text-muted">({{ $comment->rating }})</span>
                        </div>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @if($comment->approved)
                        <span class="badge bg-success">Ha</span>
                    @else
                        <span class="badge bg-warning">Yo'q</span>
                    @endif
                </td>
                <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.comments.show', $comment->id) }}" class="btn btn-sm btn-primary" title="Ko'rish">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if(!$comment->approved)
                            <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" title="Tasdiqlash">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.comments.reject', $comment->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning" title="Rad etish">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="O'chirish">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-5">
                    <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                    Izohlar topilmadi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($comments->hasPages())
    <div class="p-3 border-top">
        {{ $comments->links() }}
    </div>
    @endif
</div>
@endsection





