@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<h1 class="h3 mb-4">Edit User</h1>
<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Mobile</label>
        <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">New Password (leave blank to keep)</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Roles</label>
        @php $selectedIds = old('roles', $user->roles->pluck('id')->toArray()); @endphp
        @foreach($roles as $r)
        <div class="form-check">
            <input type="checkbox" name="roles[]" value="{{ $r->id }}" class="form-check-input" id="role{{ $r->id }}" {{ in_array($r->id, $selectedIds) ? 'checked' : '' }}>
            <label class="form-check-label" for="role{{ $r->id }}">{{ $r->name }}</label>
        </div>
        @endforeach
        @error('roles')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <button type="submit" class="btn btn-primary">Update User</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
