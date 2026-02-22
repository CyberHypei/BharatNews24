@extends('layouts.frontend')

@section('title', 'Home')

@section('breaking_news')
    @foreach($featuredPosts->take(3) as $p)
        <a href="{{ route('post.show', $p->slug) }}" class="text-white text-decoration-none">{{ Str::limit($p->title, 60) }}</a>
        @if(!$loop->last) &bull; @endif
    @endforeach
    @if($featuredPosts->isEmpty() && $latestPosts->isNotEmpty())
        @foreach($latestPosts->take(3) as $p)
            <a href="{{ route('post.show', $p->slug) }}" class="text-white text-decoration-none">{{ Str::limit($p->title, 60) }}</a>
            @if(!$loop->last) &bull; @endif
        @endforeach
    @endif
    @if($featuredPosts->isEmpty() && $latestPosts->isEmpty())
        Welcome to {{ config('app.name') }} – Your trusted news source.
    @endif
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="slide-in-left">{{ config('app.name') }} – Your Trusted News Source</h1>
                <p class="slide-in-left">Get the fastest, most accurate and unbiased news</p>
                <div class="mt-4 slide-in-left">
                    <a href="#latest-news" class="btn btn-light btn-lg me-3 latest-news-btn"><i class="fas fa-newspaper me-2"></i>Latest News</a>
                    <a href="#featured" class="btn btn-outline-light btn-lg featured-btn"><i class="fas fa-star me-2"></i>Featured News</a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-globe-asia fa-10x opacity-75 slide-in-right"></i>
            </div>
        </div>
    </div>
</section>

<!-- Featured News Carousel -->
@if($featuredPosts->isNotEmpty())
<section class="py-5" id="featured">
    <div class="container">
        <h2 class="section-header">Featured News</h2>
        <div id="featuredCarousel" class="carousel slide featured-carousel" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($featuredPosts->take(5) as $i => $fp)
                <button type="button" data-bs-target="#featuredCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($featuredPosts->take(5) as $i => $post)
                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                    @if($post->image)
                    <img src="{{ asset($post->image) }}" class="d-block w-100" alt="{{ $post->title }}">
                    @else
                    <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1200&h=400&fit=crop" class="d-block w-100" alt="{{ $post->title }}">
                    @endif
                    <div class="carousel-caption">
                        <h5>{{ Str::limit($post->title, 80) }}</h5>
                        <p>{{ Str::limit($post->short_description, 120) }}</p>
                        <a href="{{ route('post.show', $post->slug) }}" class="btn btn-primary">Read more</a>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
            <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
        </div>
    </div>
</section>
@endif

<!-- Ad placeholder -->
<section class="py-3 text-center ad-banner-section d-none">
    <div class="container">
        <div class="ad-banner">
            <div class="border rounded d-inline-block p-4 bg-light"><small class="text-muted">Advertisement</small></div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5" id="latest-news">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="section-header">Latest News</h2>
                <div class="row">
                    @forelse($latestPosts as $post)
                    <div class="col-md-6 mb-4">
                        <div class="card news-card" data-category="{{ $post->category->slug ?? '' }}">
                            @if($post->image)
                            <a href="{{ route('post.show', $post->slug) }}"><img src="{{ asset($post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="height:200px;object-fit:cover"></a>
                            @else
                            <a href="{{ route('post.show', $post->slug) }}"><div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:200px"><i class="fas fa-image fa-3x text-white"></i></div></a>
                            @endif
                            <div class="card-body">
                                <span class="badge bg-success mb-2">{{ $post->category->name ?? '' }}</span>
                                <h5 class="card-title"><a href="{{ route('post.show', $post->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($post->title, 55) }}</a></h5>
                                <p class="card-text">{{ Str::limit($post->short_description, 100) }}</p>
                                <div class="news-meta">
                                    <small><i class="fas fa-clock me-1"></i>{{ $post->published_at?->diffForHumans() }} | <i class="fas fa-user me-1"></i>{{ $post->user->name ?? 'Admin' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No posts yet.</p>
                    @endforelse
                </div>
                <div class="mt-4">{{ $latestPosts->links() }}</div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-widget">
                    <div class="widget-header"><h5><i class="fas fa-fire me-2"></i>Trending News</h5></div>
                    <div class="widget-content">
                        <div class="list-group list-group-flush">
                            @foreach($latestPosts->take(5) as $p)
                            <a href="{{ route('post.show', $p->slug) }}" class="list-group-item list-group-item-action border-0">
                                <div class="d-flex align-items-start">
                                    @if($p->image)
                                    <img src="{{ asset($p->image) }}" class="rounded me-3" alt="" style="width:80px;height:60px;object-fit:cover">
                                    @else
                                    <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center" style="width:80px;height:60px"><i class="fas fa-newspaper text-muted"></i></div>
                                    @endif
                                    <div>
                                        <h6 class="mb-1">{{ Str::limit($p->title, 40) }}</h6>
                                        <small class="text-muted">{{ $p->published_at?->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <div class="widget-header"><h5><i class="fas fa-th-large me-2"></i>Categories</h5></div>
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
                    <div class="widget-header"><h5><i class="fas fa-envelope me-2"></i>Newsletter</h5></div>
                    <div class="widget-content">
                        <p>Get the latest news delivered to your inbox.</p>
                        <form id="newsletterForm">
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Your email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane me-2"></i>Subscribe</button>
                        </form>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <div class="widget-header"><h5><i class="fas fa-share-alt me-2"></i>Follow us</h5></div>
                    <div class="widget-content">
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-primary btn-sm"><i class="fab fa-facebook-f me-2"></i>Facebook</a>
                            <a href="#" class="btn btn-info btn-sm"><i class="fab fa-twitter me-2"></i>Twitter</a>
                            <a href="#" class="btn btn-danger btn-sm"><i class="fab fa-youtube me-2"></i>YouTube</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
