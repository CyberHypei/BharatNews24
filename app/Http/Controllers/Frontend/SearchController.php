<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q', '');
        $posts = collect();
        $categories = Category::where('status', 'active')->withCount('publishedPosts')->orderBy('name')->get();

        if (strlen($q) >= 2) {
            $posts = Post::published()
                ->with(['category', 'user'])
                ->where('title', 'like', '%' . $q . '%')
                ->latest('published_at')
                ->paginate(12)
                ->withQueryString();
        }

        return view('frontend.search', compact('posts', 'q', 'categories'));
    }
}
