<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['property', 'user']);

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        // Filter by approval status
        if ($request->has('approved')) {
            $query->where('approved', $request->approved == '1');
        }

        $comments = $query->latest()->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function show($id)
    {
        $comment = Comment::with(['property', 'user', 'replies'])->findOrFail($id);
        return view('admin.comments.show', compact('comment'));
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['approved' => true]);

        return back()->with('success', 'Izoh tasdiqlandi.');
    }

    public function reject($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['approved' => false]);

        return back()->with('success', 'Izoh rad etildi.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Izoh muvaffaqiyatli o\'chirildi.');
    }
}






