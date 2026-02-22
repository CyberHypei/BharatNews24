@extends('layouts.admin')

@section('title', 'Edit Permission')

@section('content')
<h1 class="h3 mb-4">Edit Permission</h1>
<form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $permission->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $permission->slug) }}">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Group Name</label>
        <input type="text" name="group_name" class="form-control @error('group_name') is-invalid @enderror" value="{{ old('group_name', $permission->group_name) }}">
        @error('group_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ old('status', $permission->status) === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $permission->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
