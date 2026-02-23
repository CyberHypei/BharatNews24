<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\ContactController;
use Illuminate\Support\Facades\Route;

// Auth routes (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Anonymous Post (must be before /post/{slug} so "anonymous" is not treated as a slug)
Route::get('/post/anonymous', [PostController::class, 'createAnonymousPost'])->name('post.anonymous.create');
Route::post('/post/anonymous', [PostController::class, 'storeAnonymousPost'])->name('post.anonymous.store');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');

Route::get('/post/{slug}', [PostController::class, 'show'])->name('post.show');
Route::post('/post/{post:slug}/comment', [PostController::class, 'storeComment'])->name('post.comment.store')->middleware('auth');


// Admin panel (auth + admin role required; permission middleware applied per section)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('permission:manage-roles')->group(function () {
        Route::resource('roles', App\Http\Controllers\Admin\RoleController::class)->except(['show']);
        Route::post('roles/{role}/toggle-status', [App\Http\Controllers\Admin\RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
    });

    Route::middleware('permission:manage-permissions')->group(function () {
        Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class)->except(['show']);
        Route::post('permissions/{permission}/toggle-status', [App\Http\Controllers\Admin\PermissionController::class, 'toggleStatus'])->name('permissions.toggle-status');
    });

    Route::middleware('permission:create-category|edit-category|delete-category')->group(function () {
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
        Route::post('categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    });

    Route::middleware('permission:create-post|edit-post|delete-post|publish-post')->group(function () {
        Route::resource('posts', App\Http\Controllers\Admin\PostController::class)->except(['show']);
        Route::delete('post-images/{post_image}', [App\Http\Controllers\Admin\PostController::class, 'deletePostImage'])->name('posts.image.destroy');
    });

    Route::middleware('permission:manage-comments')->group(function () {
        Route::get('comments', [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
        Route::post('comments/{comment}/approve', [App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
        Route::delete('comments/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');
    });

    Route::middleware('permission:manage-contacts')->group(function () {
        Route::get('contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
        Route::patch('contacts/{contact}/mark-read', [App\Http\Controllers\Admin\ContactController::class, 'markRead'])->name('contacts.mark-read');
        Route::delete('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    });

    Route::middleware('permission:manage-users')->group(function () {
        Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show']);
        Route::post('users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
});
