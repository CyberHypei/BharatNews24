@extends('layouts.frontend')

@section('title', $category->name)

@section('meta')
<meta name="description" content="{{ Str::limit($category->description, 160) }}">
@endsection

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="bg-light py-3 rounded px-3 mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="section-header">{{ $category->name }}</h2>
            @if($category->description)
            <p class="text-muted">{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div id="newsList" class="row">
                @forelse($posts as $post)
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4" data-category="{{ $category->slug }}">
                    <div class="card news-card shadow-sm h-100">
                        @if($post->image)
                        <img src="{{ asset($post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="height:200px;object-fit:cover">
                        @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:200px"><i class="fas fa-image fa-3x text-white"></i></div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><a href="{{ route('post.show', $post->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($post->title, 55) }}</a></h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($post->short_description, 80) }}</p>
                            <div class="news-meta">
                                <small><i class="fas fa-clock me-1"></i>{{ $post->published_at?->diffForHumans() }} | <i class="fas fa-user me-1"></i>{{ $post->user->name ?? 'Admin' }}</small>
                                <a href="{{ route('post.show', $post->slug) }}" class="link-info float-end small">Read more</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted col-12">No posts in this category yet.</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $posts->links() }}</div>
        </div>
    </div>
</div>
@endsection
