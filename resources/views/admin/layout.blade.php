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
            background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%);
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: #0f172a;
        }
        .admin-wrapper {
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 270px;
            background: #0f172a;
            color: #e2e8f0;
        }
        .sidebar-logo {
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.06);
            padding: 0.85rem 1rem;
        }
        .sidebar-section-label {
            letter-spacing: 1px;
            margin-top: 1rem;
            display: block;
            color: #cbd5f5;
            font-size: 0.7rem;
            text-transform: uppercase;
        }
        .admin-sidebar .nav-link {
            color: #cbd5f5;
            border-radius: 0.85rem;
            padding: 0.65rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .admin-sidebar .nav-link.active {
            background: linear-gradient(120deg, #3b82f6, #6366f1);
            color: #fff;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }
        .admin-sidebar .nav-link:hover {
            background: rgba(99, 102, 241, 0.12);
            color: #fff;
        }
        .admin-content {
            flex: 1;
        }
        .admin-topbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }
        .page-header h1 {
            font-size: 1.6rem;
            font-weight: 600;
        }
        .admin-card {
            border-radius: 1.25rem;
            border: 1px solid rgba(148, 163, 184, 0.2);
            background: #fff;
            padding: 1.5rem;
            box-shadow: 0 25px 40px rgba(15, 23, 42, 0.08);
        }
        .admin-table {
            border-radius: 1.25rem;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.2);
            background: #fff;
            box-shadow: 0 25px 40px rgba(15, 23, 42, 0.07);
        }
        .admin-table thead {
            background: #f1f5f9;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .badge-status {
            border-radius: 999px;
            font-size: 0.75rem;
            padding: 0.35rem 0.85rem;
            text-transform: capitalize;
        }
        .badge-status.badge-published {
            background: rgba(34, 197, 94, 0.15);
            color: #15803d;
        }
        .badge-status.badge-pending {
            background: rgba(251, 191, 36, 0.2);
            color: #92400e;
        }
        .badge-status.badge-draft,
        .badge-status.badge-rejected {
            background: rgba(248, 113, 113, 0.18);
            color: #b91c1c;
        }
        .pagination .page-item .page-link {
            border: none;
            border-radius: 0.75rem;
            margin: 0 0.15rem;
            color: #475569;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(120deg, #3b82f6, #6366f1);
            color: #fff;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper d-flex">
        <aside class="admin-sidebar d-flex flex-column p-4">
            <div class="sidebar-logo mb-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="bg-primary bg-opacity-25 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </span>
                    <div>
                        <div class="fs-6 fw-semibold text-white">UYTV Boshqaruv</div>
                        <small class="opacity-75">Admin panel</small>
                    </div>
                </div>
            </div>

            <nav class="nav flex-column gap-1">
                @php
                    $navSections = [
                        'Asosiy' => [
                            ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
                            ['label' => 'Foydalanuvchilar', 'icon' => 'bi-people', 'route' => 'admin.users.index', 'pattern' => 'admin.users.*'],
                        ],
                        'B2C (Provider)' => [
                            ['label' => 'Providerlar', 'icon' => 'bi-person-badge', 'route' => 'admin.providers.index', 'pattern' => 'admin.providers.*'],
                            ['label' => "Uy-joylar", 'icon' => 'bi-building', 'route' => 'admin.properties.index', 'pattern' => 'admin.properties.*'],
                            ['label' => 'Izohlar', 'icon' => 'bi-chat-dots', 'route' => 'admin.comments.index', 'pattern' => 'admin.comments.*'],
                        ],
                        'B2B (Quruvchi)' => [
                            ['label' => 'Quruvchilar', 'icon' => 'bi-person-workspace', 'route' => 'admin.builders.index', 'pattern' => 'admin.builders.*'],
                            ['label' => 'Qurilishlar', 'icon' => 'bi-bricks', 'route' => 'admin.developments.index', 'pattern' => 'admin.developments.*'],
                        ],
                        'Tizim' => [
                            ['label' => 'Sozlamalar', 'icon' => 'bi-gear', 'route' => 'admin.settings.index', 'pattern' => 'admin.settings.*'],
                        ],
                    ];
                @endphp

                @foreach ($navSections as $section => $items)
                    <span class="sidebar-section-label">{{ $section }}</span>
                    @foreach ($items as $item)
                        @php
                            $routeExists = Route::has($item['route']);
                            $isActive = $routeExists && request()->routeIs($item['pattern']);
                        @endphp
                        <a class="nav-link {{ $isActive ? 'active' : '' }} {{ $routeExists ? '' : 'disabled opacity-50' }}"
                           href="{{ $routeExists ? route($item['route']) : '#' }}">
                            <i class="bi {{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                        </a>
                    @endforeach
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
