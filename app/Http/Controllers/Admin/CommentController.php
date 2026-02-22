<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['post', 'user']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $comments = $query->latest()->paginate(20)->withQueryString();
        return view('admin.comments.index', compact('comments'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'approved']);
        return back()->with('success', 'Comment approved.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
