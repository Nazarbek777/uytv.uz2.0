@extends('admin.layout')

@section('title', 'Izoh Tafsilotlari')
@section('page-title', 'Izoh Tafsilotlari')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-table mb-4">
            <div class="p-4">
                <div class="mb-4">
                    <h3>Izoh #{{ $comment->id }}</h3>
                    <p class="text-muted mb-0">{{ $comment->created_at->format('d.m.Y H:i') }}</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Foydalanuvchi Ma'lumotlari</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Ism</th>
                                <td>{{ $comment->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $comment->email }}</td>
                            </tr>
                            @if($comment->user)
                            <tr>
                                <th>Foydalanuvchi</th>
                                <td>
                                    <a href="{{ route('admin.users.show', $comment->user->id) }}" class="text-decoration-none">
                                        {{ $comment->user->name }}
                                    </a>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Uy-joy Ma'lumotlari</h5>
                        @if($comment->property)
                        <table class="table table-bordered">
                            <tr>
                                <th>Uy-joy</th>
                                <td>
                                    <a href="{{ route('admin.properties.show', $comment->property->id) }}" class="text-decoration-none">
                                        {{ $comment->property->title }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <td>#{{ $comment->property->id }}</td>
                            </tr>
                        </table>
                        @else
                        <p class="text-muted">Uy-joy topilmadi</p>
                        @endif
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5>Reyting</h5>
                    @if($comment->rating)
                        <div class="d-flex align-items-center">
                            <span class="text-warning me-2" style="font-size: 24px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $comment->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </span>
                            <span class="text-muted">({{ $comment->rating }}/5)</span>
                        </div>
                    @else
                        <p class="text-muted">Reyting yo'q</p>
                    @endif
                </div>
                
                <div class="mb-4">
                    <h5>Izoh</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $comment->comment }}
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5>Holat</h5>
                    @if($comment->approved)
                        <span class="badge bg-success">Tasdiqlangan</span>
                    @else
                        <span class="badge bg-warning">Tasdiqlanmagan</span>
                    @endif
                </div>
                
                @if($comment->replies()->count() > 0)
                <div class="mb-4">
                    <h5>Javoblar ({{ $comment->replies()->count() }})</h5>
                    @foreach($comment->replies as $reply)
                    <div class="p-3 bg-light rounded mb-2">
                        <strong>{{ $reply->name }}</strong> - {{ $reply->created_at->format('d.m.Y H:i') }}
                        <p class="mb-0 mt-2">{{ $reply->comment }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="admin-table mb-4">
            <div class="p-3 border-bottom">
                <h5 class="mb-0">Amallar</h5>
            </div>
            <div class="p-3">
                <div class="d-grid gap-2">
                    @if(!$comment->approved)
                        <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-1"></i>Tasdiqlash
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.comments.reject', $comment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-x-circle me-1"></i>Rad etish
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Haqiqatan ham o\'chirmoqchimisiz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-1"></i>O'chirish
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Orqaga
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection






