@extends('layouts.admin')

@section('title', 'Roles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Role Manager</h1>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Add Role</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Name</th><th>Slug</th><th>Users</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td><code>{{ $role->slug }}</code></td>
                    <td>{{ $role->users_count }}</td>
                    <td>
                        <span class="badge bg-{{ $role->status === 'active' ? 'success' : 'secondary' }}">{{ $role->status }}</span>
                        <form action="{{ route('admin.roles.toggle-status', $role) }}" method="POST" class="d-inline">@csrf @method('POST')<button type="submit" class="btn btn-link btn-sm p-0 ms-1">Toggle</button></form>
                    </td>
                    <td>
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $roles->links() }}</div>
</div>
@endsection
