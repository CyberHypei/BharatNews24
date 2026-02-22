@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')
<h1 class="h3 mb-4">Add Category</h1>
<form action="{{ route('admin.categories.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Slug (optional)</label>
        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}">
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
