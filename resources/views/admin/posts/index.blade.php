@extends('layouts.admin')

@section('title', 'Posts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Post Management</h1>
    @if(auth()->user()->hasPermission('create-post'))
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Create Post</a>
    @endif
</div>
<form class="row g-2 mb-3" method="GET">
    <div class="col-auto">
        <select name="status" class="form-select form-select-sm">
            <option value="">All statuses</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
        </select>
    </div>
    <div class="col-auto">
        <select name="category_id" class="form-select form-select-sm">
            <option value="">All categories</option>
            @foreach(\App\Models\Category::orderBy('name')->get() as $c)
            <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto"><button type="submit" class="btn btn-sm btn-secondary">Filter</button></div>
</form>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th>Views</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>{{ Str::limit($post->title, 45) }} @if($post->is_featured)<span class="badge bg-warning">Featured</span>@endif</td>
                    <td>{{ $post->category->name ?? '-' }}</td>
                    <td>{{ $post->user->name ?? '-' }}</td>
                    <td><span class="badge bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">{{ $post->status }}</span></td>
                    <td>{{ $post->views }}</td>
                    <td>{{ $post->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('post.show', $post->slug) }}" class="btn btn-sm btn-outline-secondary" target="_blank">View</a>
                        @if(auth()->user()->hasPermission('edit-post'))
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        @endif
                        @if(auth()->user()->hasPermission('delete-post'))
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $posts->links() }}</div>
</div>
@endsection
