<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <title>@yield('title', 'Home') - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('images/bharat-news-logo_uuid_26a06428-8751-4ef9-b723-382a686195f4.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('frontend_assets/css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/bharat-news-logo_uuid_26a06428-8751-4ef9-b723-382a686195f4.png') }}" alt="{{ config('app.name') }}" class="me-2" height="100" style="object-fit: contain;">
                <!-- <span class="d-none d-sm-inline">{{ config('app.name') }}</span> -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    @if(isset($categories) && $categories->isNotEmpty())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="newsDropdown" role="button" data-bs-toggle="dropdown">News</a>
                        <ul class="dropdown-menu">
                            @foreach($categories as $cat)
                            <li><a class="dropdown-item" href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    @else
                    @foreach($categories ?? [] as $cat)
                    <li class="nav-item"><a class="nav-link" href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endforeach
                    @endif
                    <!-- <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact*') ? 'active' : '' }}" href="{{ route('contact.index') }}">Contact</a></li> -->
                </ul>
                <form action="{{ route('search') }}" method="GET" class="d-flex me-3 search-form">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search news..." value="{{ request('q') }}" aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                </form>
                <a class="{{ request()->routeIs('post.anonymous*') ? 'active' : '' }} btn btn-anonymous" href="{{ route('post.anonymous.create') }}">Post News</a>
                @auth
                <!-- <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm me-2">Admin</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button></form>
                -->
                
                @else
                <!-- <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a> -->
                @endauth
                <!-- <button class="btn btn-outline-secondary ms-2 d-none d-lg-block" onclick="toggleDarkMode()" title="Dark mode"><i class="fas fa-moon"></i></button> -->
            </div>
        </div>
    </nav>

    @hasSection('breaking_news')
    <div class="breaking-news">
        <div class="container">
            <div class="row align-items-center flex-nowrap">
                <div class="col-auto bg-dark text-white px-3 py-2" style="z-index:2;">
                    <strong><i class="fas fa-bolt me-2"></i>Breaking News:</strong>
                </div>
                <div class="col overflow-hidden" style="min-width:0;">
                    <div class="ticker text-nowrap">
                        @yield('breaking_news')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="container mt-3"><div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>
    @endif
    @if(session('error'))
    <div class="container mt-3"><div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>
    @endif

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>{{ config('app.name') }}</h5>
                    <p>Your trusted source for the latest news. Fast, accurate and unbiased coverage.</p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 mb-4">
                    <h5>News</h5>
                    <ul class="list-unstyled">
                        @foreach(($categories ?? collect())->take(5) as $cat)
                        <li><a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-2 col-md-3 mb-4">
                    <h5>Company</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('search') }}">Search</a></li>
                        @guest<li><a href="{{ route('login') }}">Login</a></li>@endguest
                    </ul>
                </div>
                <div class="col-lg-2 col-md-3 mb-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('contact.index') }}"><i class="fas fa-envelope me-2"></i>Contact us</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small>Your trusted news source</small>
                </div>
            </div>
        </div>
    </footer>

    <button class="back-to-top" title="Back to top"><i class="fas fa-arrow-up"></i></button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend_assets/js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>
