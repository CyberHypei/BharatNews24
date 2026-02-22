@extends('layouts.admin')

@section('title', 'Comments')

@section('content')
<h1 class="h3 mb-4">Comment Management</h1>
<form class="mb-3" method="GET">
    <select name="status" class="form-select form-select-sm d-inline-block w-auto">
        <option value="">All</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
    </select>
    <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
</form>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Post</th><th>User</th><th>Comment</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($comments as $c)
                <tr>
                    <td><a href="{{ route('post.show', $c->post->slug) }}" target="_blank">{{ Str::limit($c->post->title, 30) }}</a></td>
                    <td>{{ $c->user->name ?? '-' }}</td>
                    <td>{{ Str::limit($c->comment, 60) }}</td>
                    <td><span class="badge bg-{{ $c->status === 'approved' ? 'success' : 'warning' }}">{{ $c->status }}</span></td>
                    <td>{{ $c->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        @if($c->status === 'pending')
                        <form action="{{ route('admin.comments.approve', $c) }}" method="POST" class="d-inline">@csrf @method('POST')<button type="submit" class="btn btn-sm btn-outline-success">Approve</button></form>
                        @endif
                        <form action="{{ route('admin.comments.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this comment?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $comments->links() }}</div>
</div>
@endsection
