@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
<h1 class="h3 mb-4">Edit Post</h1>
<form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $post->slug) }}">
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Short Description</label>
                <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $post->short_description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="12">{{ old('content', $post->content) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Tags (comma separated)</label>
                <input type="text" name="tags" class="form-control" value="{{ old('tags', $post->tags) }}">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">Publish</h6>
                    <div class="mb-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="feat" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="feat">Featured</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_anonymous" value="1" class="form-check-input" id="is_anonymous" {{ old('is_anonymous', $post->is_anonymous) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_anonymous">Post as Anonymous</label>
                    </div>
                    <small class="text-muted d-block mb-2">When checked, author details will be hidden on the frontend and shown as "Anonymous".</small>
                    <div class="mb-2">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id', $post->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">Featured Image</h6>
                    @if($post->image)
                    <img src="{{ asset($post->image) }}" alt="" class="img-fluid rounded mb-2" style="max-height:120px">
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">Video</h6>
                    @if($post->video)
                    <p class="small text-muted">Current: {{ basename($post->video) }}</p>
                    @endif
                    <input type="file" name="video" class="form-control" accept="video/mp4,video/webm,video/ogg">
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">Gallery</h6>
                    <div id="gallery-images-list">
                    @foreach($post->postImages as $img)
                    <div class="d-flex align-items-center gap-2 mb-2 gallery-image-row" data-image-id="{{ $img->id }}">
                        <img src="{{ asset($img->image) }}" alt="" class="rounded" style="height:40px;width:50px;object-fit:cover">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeGalleryImage({{ $img->id }}, this)">Remove</button>
                    </div>
                    @endforeach
                    </div>
                    <input type="file" name="images[]" class="form-control mt-2" accept="image/*" multiple>
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title">SEO</h6>
                    <input type="text" name="meta_title" class="form-control mb-2" placeholder="Meta title" value="{{ old('meta_title', $post->meta_title) }}">
                    <textarea name="meta_description" class="form-control mb-2" rows="2" placeholder="Meta description">{{ old('meta_description', $post->meta_description) }}</textarea>
                    <input type="text" name="meta_keywords" class="form-control" placeholder="Meta keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}">
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Update Post</button>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@push('scripts')
<script>
function removeGalleryImage(imageId, btn) {
    if (!confirm('Remove this image from the gallery?')) return;
    const row = btn.closest('.gallery-image-row');
    const url = '{{ url("admin/post-images") }}/' + imageId;
    const token = document.querySelector('input[name="_token"]').value;
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.ok ? res.json().catch(() => ({})) : Promise.reject(res))
    .then(() => {
        row.remove();
    })
    .catch(() => {
        alert('Failed to remove image. Please try again.');
    });
}
</script>
@endpush
@endsection
