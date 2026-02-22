@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<h1 class="h3 mb-4">Contact Messages</h1>
<form class="mb-3" method="GET">
    <select name="status" class="form-select form-select-sm d-inline-block w-auto">
        <option value="">All</option>
        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
    </select>
    <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
</form>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $c)
                <tr class="{{ $c->status === 'unread' ? 'table-light' : '' }}">
                    <td>{{ $c->name }}</td>
                    <td><a href="mailto:{{ $c->email }}">{{ $c->email }}</a></td>
                    <td>{{ \App\Models\Contact::subjectOptions()[$c->subject] ?? $c->subject }}</td>
                    <td>{{ Str::limit($c->message, 50) }}</td>
                    <td><span class="badge bg-{{ $c->status === 'read' ? 'secondary' : 'primary' }}">{{ $c->status }}</span></td>
                    <td>{{ $c->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.contacts.show', $c) }}" class="btn btn-sm btn-outline-primary">View</a>
                        @if($c->status === 'unread')
                        <form action="{{ route('admin.contacts.mark-read', $c) }}" method="POST" class="d-inline">@csrf @method('PATCH')<button type="submit" class="btn btn-sm btn-outline-success">Mark read</button></form>
                        @endif
                        <form action="{{ route('admin.contacts.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this message?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No contact messages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $contacts->links() }}</div>
</div>
@endsection
