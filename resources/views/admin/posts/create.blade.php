@extends('layouts.admin')

@section('title', 'Create Post')

@section('content')
<h1 class="h3 mb-4">Create Post</h1>
<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Slug (optional)</label>
                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}">
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Short Description</label>
                <textarea name="short_description" class="form-control" rows="2">{{ old('short_description') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="12">{{ old('content') }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tags (comma separated)</label>
                <input type="text" name="tags" class="form-control" value="{{ old('tags') }}" placeholder="sports, world, tech">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">Publish</h6>
                    <div class="mb-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="feat" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="feat">Featured</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_anonymous" value="1" class="form-check-input" id="is_anonymous" {{ old('is_anonymous') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_anonymous">Post as Anonymous</label>
                    </div>
                    <small class="text-muted d-block mb-2">When checked, author details will be hidden on the frontend and shown as "Anonymous".</small>
                    <div class="mb-2">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">Featured Image</h6>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">Video</h6>
                    <input type="file" name="video" class="form-control" accept="video/mp4,video/webm,video/ogg">
                    @error('video')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">Gallery (multiple images)</h6>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">SEO</h6>
                    <input type="text" name="meta_title" class="form-control mb-2" placeholder="Meta title" value="{{ old('meta_title') }}">
                    <textarea name="meta_description" class="form-control mb-2" rows="2" placeholder="Meta description">{{ old('meta_description') }}</textarea>
                    <input type="text" name="meta_keywords" class="form-control" placeholder="Meta keywords" value="{{ old('meta_keywords') }}">
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Create Post</button>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
