<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $posts = $category->publishedPosts()->with(['user'])->latest('published_at')->paginate(12);
        $categories = Category::where('status', 'active')->withCount('publishedPosts')->orderBy('name')->get();

        return view('frontend.category', compact('category', 'posts', 'categories'));
    }
}
