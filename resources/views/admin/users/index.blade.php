@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">User Management</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
</div>
<form class="mb-3" method="GET">
    <select name="role" class="form-select form-select-sm d-inline-block w-auto">
        <option value="">All roles</option>
        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="editor" {{ request('role') === 'editor' ? 'selected' : '' }}>Editor</option>
        <option value="author" {{ request('role') === 'author' ? 'selected' : '' }}>Author</option>
        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
    </select>
    <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
</form>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Roles</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>@foreach($u->roles as $r)<span class="badge bg-secondary me-1">{{ $r->name }}</span>@endforeach</td>
                    <td>
                        <span class="badge bg-{{ $u->status === 'active' ? 'success' : 'secondary' }}">{{ $u->status }}</span>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.users.toggle-status', $u) }}" method="POST" class="d-inline">@csrf @method('POST')<button type="submit" class="btn btn-link btn-sm p-0 ms-1">Toggle</button></form>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $users->links() }}</div>
</div>
@endsection
