<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') • UYTV.uz</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
        .admin-wrapper {
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 260px;
            background: #111827;
            color: #f9fafb;
        }
        .admin-sidebar a {
            color: inherit;
            text-decoration: none;
        }
        .admin-sidebar .nav-link {
            color: #d1d5db;
            border-radius: 0.75rem;
            padding: 0.65rem 1rem;
            font-weight: 500;
        }
        .admin-sidebar .nav-link.active,
        .admin-sidebar .nav-link:hover {
            background: rgba(59, 130, 246, 0.15);
            color: #fff;
        }
        .admin-content {
            flex: 1;
        }
        .admin-topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }
        .page-header h1 {
            font-size: 1.35rem;
            font-weight: 600;
        }
        .admin-card {
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            background: #fff;
            padding: 1.5rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper d-flex">
        <aside class="admin-sidebar d-flex flex-column p-4">
            <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-shield-lock-fill fs-4 text-primary"></i>
                    <span class="fs-5 fw-semibold">UYTV Admin</span>
                </div>
                <small class="opacity-75">Boshqaruv paneli</small>
            </div>

            <nav class="nav flex-column gap-1">
                @php
                    $navItems = [
                        ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
                        ['label' => 'Foydalanuvchilar', 'icon' => 'bi-people', 'route' => 'admin.users.index', 'pattern' => 'admin.users.*'],
                        ['label' => "E'lonlar", 'icon' => 'bi-buildings', 'route' => 'admin.properties.index', 'pattern' => 'admin.properties.*'],
                        ['label' => 'Qurilishlar', 'icon' => 'bi-bricks', 'route' => 'admin.developments.index', 'pattern' => 'admin.developments.*'],
                        ['label' => 'Izohlar', 'icon' => 'bi-chat-dots', 'route' => 'admin.comments.index', 'pattern' => 'admin.comments.*'],
                        ['label' => 'Sozlamalar', 'icon' => 'bi-gear', 'route' => 'admin.settings.index', 'pattern' => 'admin.settings.*'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    @php
                        $routeExists = Route::has($item['route']);
                        $isActive = $routeExists && request()->routeIs($item['pattern']);
                    @endphp
                    <a class="nav-link {{ $isActive ? 'active' : '' }} {{ $routeExists ? '' : 'disabled opacity-50' }}"
                       href="{{ $routeExists ? route($item['route']) : '#' }}">
                        <i class="bi {{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light w-100">
                        <i class="bi bi-box-arrow-right me-2"></i> Chiqish
                    </button>
                </form>
            </div>
        </aside>

        <div class="admin-content d-flex flex-column">
            <header class="admin-topbar py-3 px-4 d-flex justify-content-between align-items-center">
                <div class="page-header">
                    <p class="text-muted mb-0 small">Admin paneli</p>
                    <h1 class="mb-0">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end">
                        <div class="fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                    </div>
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </header>

            <main class="flex-grow-1 p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
