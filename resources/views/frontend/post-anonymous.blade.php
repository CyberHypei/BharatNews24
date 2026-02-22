@extends('layouts.frontend')

@section('title', 'Post News')

@section('meta')
<meta name="description" content="Submit your news. Choose to publish anonymously or with your details. Your post will be reviewed before publishing.">
@endsection

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3 border-bottom">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-theme">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Post News</li>
        </ol>
    </div>
</nav>

<!-- Page header (hero-style) -->
<section class="py-4" style="background: linear-gradient(135deg, var(--light-orange, #fff3e6), #fff8f0); border-bottom: 2px solid var(--primary-color, #f48120);">
    <div class="container">
        <h1 class="section-header mb-2">Post News</h1>
        <p class="text-muted mb-0">अपनी कहानी हमारे रीडर्स के साथ शेयर करें। आप <strong>Anonymous (गुमनाम लेखक)</strong> के तौर पर या अपने नाम से पब्लिश कर सकते हैं। पब्लिश करने से पहले सभी पोस्ट्स को रिव्यू किया जाता है।</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-body p-4 p-lg-5">
                        <form action="{{ route('post.anonymous.store') }}" method="POST" enctype="multipart/form-data" id="anonymousPostForm">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-heading me-2 text-theme"></i>Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Enter your headline" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-folder me-2 text-theme"></i>Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select form-select-lg @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select category</option>
                                    @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-align-left me-2 text-theme"></i>Short description</label>
                                <textarea name="short_description" class="form-control" rows="2" placeholder="A brief summary (optional)">{{ old('short_description') }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-file-alt me-2 text-theme"></i>Content <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="10" placeholder="Write your full story here..." required>{{ old('content') }}</textarea>
                                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-tags me-2 text-theme"></i>Tags (comma separated)</label>
                                <input type="text" name="tags" class="form-control" value="{{ old('tags') }}" placeholder="e.g. news, local, event">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-image me-2 text-theme"></i>Cover image (optional)</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Max 2MB. JPEG, PNG, GIF, WebP.</small>
                                @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <!-- Author section (sidebar-widget style) -->
                            <div class="sidebar-widget mt-4">
                                <div class="widget-header">
                                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Author</h5>
                                </div>
                                <div class="widget-content">
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" name="is_anonymous" value="1" class="form-check-input" id="post_as_anonymous" {{ old('is_anonymous', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="post_as_anonymous">Post as Anonymous</label>
                                    </div>
                                    <p class="text-muted small mb-3">When enabled, your name and email will not be shown. Disable to display your details as the author.</p>

                                    <div id="author-fields" class="author-fields" style="display: {{ old('is_anonymous', true) ? 'none' : 'block' }};">
                                        <div class="mb-3">
                                            <label class="form-label">Your Name <span class="text-danger">*</span></label>
                                            <input type="text" name="author_name" class="form-control @error('author_name') is-invalid @enderror" value="{{ old('author_name') }}" placeholder="Enter your name">
                                            @error('author_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Your Email <span class="text-danger">*</span></label>
                                            <input type="email" name="author_email" class="form-control @error('author_email') is-invalid @enderror" value="{{ old('author_email') }}" placeholder="Enter your email">
                                            @error('author_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label">Mobile (optional)</label>
                                            <input type="text" name="author_mobile" class="form-control @error('author_mobile') is-invalid @enderror" value="{{ old('author_mobile') }}" placeholder="Enter mobile">
                                            @error('author_mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Submit for review
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar (like home) -->
            <div class="col-lg-4">
                <div class="sidebar-widget">
                    <div class="widget-header">
                        <h5><i class="fas fa-info-circle me-2"></i>What happens next?</h5>
                    </div>
                    <div class="widget-content">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex">
                                <span class="badge bg-primary rounded-circle me-2" style="width:28px;height:28px;line-height:28px;font-size:0.85rem;">1</span>
                                <span>Your post is saved as a <strong>draft</strong>.</span>
                            </li>
                            <li class="mb-3 d-flex">
                                <span class="badge bg-primary rounded-circle me-2" style="width:28px;height:28px;line-height:28px;font-size:0.85rem;">2</span>
                                <span>Our team will <strong>review</strong> it shortly.</span>
                            </li>
                            <li class="mb-0 d-flex">
                                <span class="badge bg-primary rounded-circle me-2" style="width:28px;height:28px;line-height:28px;font-size:0.85rem;">3</span>
                                <span>Once approved, it will be <strong>published</strong>.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <div class="widget-header">
                        <h5><i class="fas fa-th-large me-2"></i>Categories</h5>
                    </div>
                    <div class="widget-content">
                        <div class="row g-2">
                            @foreach($categories as $c)
                            <div class="col-6">
                                <a href="{{ route('category.show', $c->slug) }}" class="btn btn-outline-primary btn-sm w-100">{{ $c->name }}</a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <div class="widget-header">
                        <h5><i class="fas fa-shield-alt me-2"></i>Tips</h5>
                    </div>
                    <div class="widget-content">
                        <p class="small text-muted mb-2">• Use a clear, descriptive title.</p>
                        <p class="small text-muted mb-2">• Add a short description for listing pages.</p>
                        <p class="small text-muted mb-0">• Choose the right category so readers can find your story.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var checkbox = document.getElementById('post_as_anonymous');
    var authorFields = document.getElementById('author-fields');
    var nameInput = document.querySelector('input[name="author_name"]');
    var emailInput = document.querySelector('input[name="author_email"]');

    function toggleAuthorFields() {
        if (checkbox.checked) {
            authorFields.style.display = 'none';
            if (nameInput) nameInput.removeAttribute('required');
            if (emailInput) emailInput.removeAttribute('required');
        } else {
            authorFields.style.display = 'block';
            if (nameInput) nameInput.setAttribute('required', 'required');
            if (emailInput) emailInput.setAttribute('required', 'required');
        }
    }

    if (checkbox) {
        checkbox.addEventListener('change', toggleAuthorFields);
        toggleAuthorFields();
    }
});
</script>
@endpush
@endsection
