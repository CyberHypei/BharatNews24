@extends('layouts.admin')

@section('title', 'Permissions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Permission Manager</h1>
    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">Add Permission</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Name</th><th>Slug</th><th>Group</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($permissions as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td><code>{{ $p->slug }}</code></td>
                    <td>{{ $p->group_name }}</td>
                    <td>
                        <span class="badge bg-{{ $p->status === 'active' ? 'success' : 'secondary' }}">{{ $p->status }}</span>
                        <form action="{{ route('admin.permissions.toggle-status', $p) }}" method="POST" class="d-inline">@csrf @method('POST')<button type="submit" class="btn btn-link btn-sm p-0 ms-1">Toggle</button></form>
                    </td>
                    <td>
                        <a href="{{ route('admin.permissions.edit', $p) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.permissions.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $permissions->links() }}</div>
</div>
@endsection
