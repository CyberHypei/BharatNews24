@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1 class="h3 mb-4">Dashboard</h1>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Posts</h6>
                        <h3 class="mb-0">{{ $totalPosts }}</h3>
                    </div>
                    <i class="bi bi-newspaper fs-1 text-primary opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Categories</h6>
                        <h3 class="mb-0">{{ $totalCategories }}</h3>
                    </div>
                    <i class="bi bi-folder fs-1 text-success opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Users</h6>
                        <h3 class="mb-0">{{ $totalUsers }}</h3>
                    </div>
                    <i class="bi bi-people fs-1 text-info opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Views</h6>
                        <h3 class="mb-0">{{ number_format($totalViews) }}</h3>
                    </div>
                    <i class="bi bi-eye fs-1 text-warning opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Latest Posts</h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th>Date</th><th></th></tr></thead>
                <tbody>
                    @forelse($latestPosts as $post)
                    <tr>
                        <td>{{ Str::limit($post->title, 40) }}</td>
                        <td>{{ $post->category->name ?? '-' }}</td>
                        <td>{{ $post->user->name ?? '-' }}</td>
                        <td><span class="badge bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">{{ $post->status }}</span></td>
                        <td>{{ $post->created_at->format('M d, Y') }}</td>
                        <td><a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">Edit</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No posts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
