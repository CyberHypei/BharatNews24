@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Category Management</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add Category</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Name</th><th>Slug</th><th>Posts</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td><code>{{ $cat->slug }}</code></td>
                    <td>{{ $cat->posts_count }}</td>
                    <td>
                        <span class="badge bg-{{ $cat->status === 'active' ? 'success' : 'secondary' }}">{{ $cat->status }}</span>
                        <form action="{{ route('admin.categories.toggle-status', $cat) }}" method="POST" class="d-inline">@csrf @method('POST')<button type="submit" class="btn btn-link btn-sm p-0 ms-1">Toggle</button></form>
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $categories->links() }}</div>
</div>
@endsection
