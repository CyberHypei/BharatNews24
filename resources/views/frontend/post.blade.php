@extends('layouts.frontend')

@section('title', $post->meta_title ?: $post->title)

@section('meta')
<meta name="description" content="{{ $post->meta_description ?: Str::limit($post->short_description, 160) }}">
@if($post->meta_keywords)<meta name="keywords" content="{{ $post->meta_keywords }}">@endif
@endsection

@section('content')
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.show', $post->category->slug) }}">{{ $post->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 40) }}</li>
        </ol>
    </div>
</nav>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="news-article">
                    <header class="mb-4">
                        <div class="mb-3">
                            <span class="badge bg-primary me-2">{{ $post->category->name }}</span>
                            @if($post->is_featured)<span class="badge bg-danger">Featured</span>@endif
                        </div>
                        <h1 class="display-5 fw-bold mb-3">{{ $post->title }}</h1>
                        @if($post->short_description)
                        <p class="lead text-muted mb-4">{{ $post->short_description }}</p>
                        @endif
                        <div class="article-meta d-flex align-items-center flex-wrap mb-4">
                            <div class="author-info d-flex align-items-center me-4 mb-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width:40px;height:40px"><i class="fas fa-user"></i></div>
                                <div>
                                    <strong>{{ $post->display_author_name }}</strong>
                                    @if($post->display_author_email)
                                    <!-- <small class="text-muted d-block"><a href="mailto:{{ $post->display_author_email }}" class="text-muted">{{ $post->display_author_email }}</a></small> -->
                                    @else
                                    <small class="text-muted d-block">Author</small>
                                    @endif
                                </div>
                            </div>
                            <div class="meta-info d-flex align-items-center flex-wrap">
                                <span class="me-4 mb-2"><i class="fas fa-calendar-alt me-1"></i>{{ $post->published_at?->format('F j, Y') }}</span>
                                <span class="me-4 mb-2"><i class="fas fa-eye me-1"></i>{{ number_format($post->views) }} views</span>
                                <span class="mb-2"><i class="fas fa-comment me-1"></i>{{ $post->approvedComments->count() }} comments</span>
                            </div>
                        </div>
                        <div class="share-buttons mb-4">
                            <span class="me-3"><strong>Share:</strong></span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-outline-dark btn-sm me-2"><i class="fab fa-facebook-f me-1"></i>Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-outline-info btn-sm me-2"><i class="fab fa-twitter me-1"></i>Twitter</a>
                            <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}" target="_blank" class="btn btn-outline-success btn-sm me-2"><i class="fab fa-whatsapp me-1"></i>WhatsApp</a>
                        </div>
                    </header>

                    @if($post->image)
                    <div class="featured-image mb-4">
                        <img src="{{ asset($post->image) }}" class="img-fluid rounded" alt="{{ $post->title }}">
                    </div>
                    @endif
                    @if($post->postImages->isNotEmpty())
                    <div class="row g-2 mb-4">
                        @foreach($post->postImages as $img)
                        <div class="col-6 col-md-4"><img src="{{ asset($img->image) }}" class="img-fluid rounded" alt=""></div>
                        @endforeach
                    </div>
                    @endif
                    @if($post->video)
                    <div class="mb-4">
                        <video controls class="w-100 rounded" style="max-height:400px">
                            <source src="{{ asset($post->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    @endif

                    <div class="article-body">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    @if($post->tags)
                    <div class="article-tags mt-5">
                        <strong>Tags:</strong>
                        @foreach($post->tags_array as $tag)
                        <a href="{{ route('search', ['q' => trim($tag)]) }}" class="badge bg-secondary text-decoration-none me-2">{{ trim($tag) }}</a>
                        @endforeach
                    </div>
                    @endif
                </article>

                <section class="comments-section mt-5">
                    <h3 class="mb-4"><i class="fas fa-comments me-2"></i>Comments ({{ $post->approvedComments->count() }})</h3>
                    @auth
                    <div class="comment-form card mb-4">
                        <div class="card-body">
                            <h5>Post a comment</h5>
                            <form action="{{ route('post.comment.store', $post->slug) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="4" placeholder="Write your comment..." required></textarea>
                                    @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Post comment</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <p class="text-muted"><a href="{{ route('login') }}">Login</a> to post a comment.</p>
                    @endauth

                    <div class="comments-list">
                        @foreach($post->approvedComments as $comment)
                        <div class="comment mb-4">
                            <div class="d-flex">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px"><i class="fas fa-user"></i></div>
                                <div class="flex-grow-1">
                                    <div class="comment-header d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $comment->user->name ?? 'User' }}</strong>
                                            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <p class="mt-2 mb-0">{{ $comment->comment }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="col-lg-4">
                @if($relatedPosts->isNotEmpty())
                <div class="sidebar-widget">
                    <div class="widget-header"><h5><i class="fas fa-newspaper me-2"></i>Related News</h5></div>
                    <div class="widget-content">
                        @foreach($relatedPosts as $rel)
                        <a href="{{ route('post.show', $rel->slug) }}" class="d-flex mb-3 text-decoration-none text-dark">
                            @if($rel->image)
                            <img src="{{ asset($rel->image) }}" class="rounded me-3" alt="" style="width:80px;height:60px;object-fit:cover">
                            @else
                            <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center" style="width:80px;height:60px"><i class="fas fa-newspaper text-muted"></i></div>
                            @endif
                            <div>
                                <h6 class="mb-1">{{ Str::limit($rel->title, 45) }}</h6>
                                <small class="text-muted">{{ $rel->published_at?->diffForHumans() }}</small>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="sidebar-widget">
                    <div class="widget-header"><h5><i class="fas fa-chart-line me-2"></i>Most read</h5></div>
                    <div class="widget-content">
                        <ol class="list-unstyled">
                            @foreach($relatedPosts->take(3) as $r)
                            <li class="mb-3">
                                <a href="{{ route('post.show', $r->slug) }}" class="text-decoration-none text-dark">
                                    <h6>{{ Str::limit($r->title, 50) }}</h6>
                                    <small class="text-muted">{{ number_format($r->views) }} views</small>
                                </a>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <div class="widget-header"><h5><i class="fas fa-envelope me-2"></i>Newsletter</h5></div>
                    <div class="widget-content">
                        <p>Get the latest news in your inbox.</p>
                        <form id="newsletterForm">
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Your email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane me-2"></i>Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
