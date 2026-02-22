@extends('layouts.frontend')

@section('title', $q ? "Search: {$q}" : 'Search')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="bg-light py-3 rounded px-3 mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Search</li>
        </ol>
    </nav>

    <h2 class="section-header">Search @if($q) for "{{ $q }}"@endif</h2>
    <form action="{{ route('search') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control form-control-lg" placeholder="Search by title..." value="{{ $q }}">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search me-2"></i>Search</button>
        </div>
    </form>

    @if(strlen($q) >= 2)
    <div class="row">
        <div class="col-12">
            <div class="row" id="newsList">
                @if($posts->isNotEmpty())
                @foreach($posts as $post)
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="card news-card shadow-sm h-100">
                        @if($post->image)
                        <img src="{{ asset($post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="height:200px;object-fit:cover">
                        @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:200px"><i class="fas fa-image fa-3x text-white"></i></div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-primary mb-2">{{ $post->category->name ?? '' }}</span>
                            <h5 class="card-title"><a href="{{ route('post.show', $post->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($post->title, 55) }}</a></h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($post->short_description, 80) }}</p>
                            <div class="news-meta">
                                <small><i class="fas fa-clock me-1"></i>{{ $post->published_at?->format('M d, Y') }} | {{ $post->views }} views</small>
                                <a href="{{ route('post.show', $post->slug) }}" class="link-info float-end small">Read more</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $posts->links() }}</div>
            @else
            <p class="text-muted">No posts found for "{{ $q }}".</p>
            @endif
        </div>
    </div>
    @else
    <p class="text-muted">Enter at least 2 characters to search.</p>
    @endif
</div>
@endsection
