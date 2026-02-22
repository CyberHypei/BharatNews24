<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPosts = Post::published()->featured()->latest('published_at')->take(5)->get();
        $latestPosts = Post::published()->with(['category', 'user'])->latest('published_at')->paginate(10);
        $categories = Category::where('status', 'active')->withCount('publishedPosts')->orderBy('name')->get();

        return view('frontend.home', compact('featuredPosts', 'latestPosts', 'categories'));
    }
}
