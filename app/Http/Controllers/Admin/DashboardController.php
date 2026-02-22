<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPosts = Post::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $totalViews = Post::sum('views');
        $latestPosts = Post::with(['category', 'user'])->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalPosts', 'totalCategories', 'totalUsers', 'totalViews', 'latestPosts'
        ));
    }
}
