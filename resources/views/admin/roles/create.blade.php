@extends('layouts.admin')

@section('title', 'Add Role')

@section('content')
<h1 class="h3 mb-4">Add Role</h1>
<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Slug (optional, auto from name)</label>
        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="mb-4">
        <label class="form-label">Permissions</label>
        @foreach($permissions as $group => $items)
        <div class="border rounded p-2 mb-2">
            <strong>{{ ucfirst($group) }}</strong>
            <div class="ms-3 mt-1">
                @foreach($items as $p)
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="permissions[]" value="{{ $p->id }}" class="form-check-input" id="perm{{ $p->id }}" {{ in_array($p->id, old('permissions', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="perm{{ $p->id }}">{{ $p->name }}</label>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    <button type="submit" class="btn btn-primary">Create Role</button>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
