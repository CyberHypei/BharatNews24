<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }} Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')
    <style>
        .sidebar { min-height: 100vh; background: #212529; }
        .sidebar .nav-link { color: rgba(255,255,255,.75); }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; }
        .btn-primary {
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(244, 129, 32, 0.1) !important;
            background: linear-gradient(135deg, #f48120, #d66f0a) !important;
            border-color: #f48120 !important;
            border-radius: 8px;
            transition: 0.3s ease;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar flex-shrink-0 p-3" style="width: 220px;">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none mb-4">
                <img src="{{ asset('images/bharat-news-logo_uuid_26a06428-8751-4ef9-b723-382a686195f4.jpg') }}" alt="{{ config('app.name') }} Admin" height="60" class="me-2 rounded" style="object-fit: contain;">
                <!-- <span class="fs-6 fw-semibold">{{ config('app.name') }} Admin</span> -->
            </a>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.posts.index') }}"><i class="bi bi-newspaper me-2"></i> Posts</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.categories.index') }}"><i class="bi bi-folder me-2"></i> Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.comments.index') }}"><i class="bi bi-chat-dots me-2"></i> Comments</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.contacts*') ? 'active' : '' }}" href="{{ route('admin.contacts.index') }}"><i class="bi bi-envelope me-2"></i> Contacts</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Users</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.roles.index') }}"><i class="bi bi-shield me-2"></i> Roles</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.permissions.index') }}"><i class="bi bi-key me-2"></i> Permissions</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i> View Site</a></li>
                <li class="nav-item mt-3">
                    <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="nav-link border-0 bg-transparent text-start w-100"><i class="bi bi-box-arrow-right me-2"></i> Logout</button></form>
                </li>
            </ul>
        </div>
        <div class="flex-grow-1 p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
