<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', __('messages.dashboard')) - {{ config('app.name') }}</title>
    {{-- Google Font: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Custom CSS Tema Navy + Abu-abu Premium --}}
    <style>
        /* ============================================
           VARIABEL WARNA UTAMA
           ============================================ */
        :root {
            --navy-900: #0a1628;
            --navy-800: #0f1b33;
            --navy-700: #152242;
            --navy-600: #1b2a4a;
            --navy-500: #243656;
            --navy-400: #2d4066;
            --navy-300: #3d5a80;
            --gray-50: #f8f9fb;
            --gray-100: #f1f3f6;
            --gray-150: #e8ecf1;
            --gray-200: #e2e6ed;
            --gray-300: #d1d6e0;
            --gray-400: #a0a8b8;
            --gray-500: #6b7385;
            --gray-600: #4a5568;
            --gray-700: #2d3748;
            --accent: #4a90d9;
            --accent-light: #6ba3e0;
            --accent-dark: #3a7bc8;
            --accent-glow: rgba(74, 144, 217, 0.12);
            --accent-glow-strong: rgba(74, 144, 217, 0.25);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --shadow-sm: 0 1px 3px rgba(15, 27, 51, 0.06);
            --shadow-md: 0 4px 16px rgba(15, 27, 51, 0.08);
            --shadow-lg: 0 8px 30px rgba(15, 27, 51, 0.12);
            --shadow-xl: 0 15px 50px rgba(15, 27, 51, 0.18);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        /* ============================================
           GLOBAL
           ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--gray-100);
            color: var(--gray-700);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        ::selection {
            background: var(--accent);
            color: #fff;
        }

        /* ============================================
           NAVBAR (Atas)
           ============================================ */
        .navbar-navy {
            background: linear-gradient(135deg, var(--navy-900) 0%, var(--navy-700) 40%, var(--navy-500) 100%);
            padding: 0;
            height: 64px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-navy .container-fluid {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }

        .navbar-navy .navbar-brand {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .navbar-navy .navbar-brand:hover {
            color: #ffffff;
            opacity: 0.9;
        }

        .navbar-navy .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            box-shadow: 0 3px 10px rgba(74, 144, 217, 0.3);
        }

        .navbar-navy .user-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }

        .navbar-navy .user-avatar {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, var(--navy-400), var(--navy-300));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.9rem;
            border: 2px solid rgba(255, 255, 255, 0.15);
        }

        .navbar-navy .user-details {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .navbar-navy .user-name {
            color: rgba(255, 255, 255, 0.95);
            font-weight: 600;
            font-size: 0.8rem;
        }

        .navbar-navy .user-role-text {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 500;
        }

        .navbar-navy .divider-v {
            width: 1px;
            height: 28px;
            background: rgba(255, 255, 255, 0.12);
        }

        .badge-role {
            padding: 0.3em 0.75em;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .badge-admin {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .badge-user {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #fff;
            box-shadow: 0 2px 8px rgba(74, 144, 217, 0.3);
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.78rem;
            font-weight: 500;
            padding: 0.4rem 1rem;
            border-radius: var(--radius-sm);
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border-color: rgba(239, 68, 68, 0.3);
            transform: translateY(-1px);
        }

        /* ============================================
           SIDEBAR (Kiri)
           ============================================ */
        .sidebar {
            background: linear-gradient(180deg, var(--navy-700) 0%, var(--navy-900) 100%);
            min-height: calc(100vh - 64px);
            padding: 1rem 0 2rem;
            position: sticky;
            top: 64px;
            overflow-y: auto;
            max-height: calc(100vh - 64px);
        }

        .sidebar::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 1px;
            background: linear-gradient(180deg, rgba(255,255,255,0.08) 0%, transparent 100%);
        }

        .sidebar .nav-section-title {
            color: var(--gray-400);
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 1.25rem 1.5rem 0.5rem;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.82rem;
            font-weight: 400;
            padding: 0.55rem 1.5rem;
            margin: 1px 0.6rem;
            border-radius: var(--radius-sm);
            border-left: none;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            position: relative;
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--accent-glow-strong), var(--accent-glow));
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(74, 144, 217, 0.15);
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .sidebar .nav-link i {
            font-size: 1rem;
            width: 1.3rem;
            text-align: center;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i {
            opacity: 1;
        }

        /* ============================================
           MAIN CONTENT
           ============================================ */
        .main-content {
            padding: 2rem 2.25rem;
            background: var(--gray-100);
            min-height: calc(100vh - 64px);
        }

        /* ============================================
           WELCOME HEADER
           ============================================ */
        .welcome-section {
            background: linear-gradient(135deg, var(--navy-700) 0%, var(--navy-500) 50%, var(--navy-300) 100%);
            border-radius: var(--radius-lg);
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(74, 144, 217, 0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .welcome-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .welcome-section h2 {
            font-weight: 800;
            font-size: 1.6rem;
            margin-bottom: 0.4rem;
            position: relative;
            z-index: 1;
        }

        .welcome-section p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .welcome-section .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3em 0.8em;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            vertical-align: middle;
        }

        .welcome-badge-admin {
            background: rgba(239, 68, 68, 0.25);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .welcome-badge-user {
            background: rgba(74, 144, 217, 0.25);
            color: #93c5fd;
            border: 1px solid rgba(74, 144, 217, 0.3);
        }

        .welcome-section .welcome-time {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.78rem;
            margin-top: 0.5rem;
            position: relative;
            z-index: 1;
        }

        /* ============================================
           KARTU STATISTIK (Premium)
           ============================================ */
        .stat-card {
            background: #ffffff;
            border: 1px solid var(--gray-150);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--gray-200);
        }

        .stat-card .card-body {
            padding: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .stat-card .stat-bg-icon {
            position: absolute;
            right: -5px;
            bottom: -10px;
            font-size: 4.5rem;
            opacity: 0.04;
            color: var(--navy-600);
            z-index: 0;
        }

        .stat-card .stat-icon-box {
            width: 46px;
            height: 46px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .stat-icon-navy {
            background: linear-gradient(135deg, rgba(27, 42, 74, 0.1), rgba(27, 42, 74, 0.05));
            color: var(--navy-600);
        }

        .stat-icon-accent {
            background: linear-gradient(135deg, var(--accent-glow-strong), var(--accent-glow));
            color: var(--accent);
        }

        .stat-icon-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.05));
            color: var(--success);
        }

        .stat-icon-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.05));
            color: var(--warning);
        }

        .stat-icon-info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.05));
            color: var(--info);
        }

        .stat-icon-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.05));
            color: var(--danger);
        }

        .stat-card .stat-label {
            color: var(--gray-500);
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.35rem;
        }

        .stat-card .stat-value {
            color: var(--navy-600);
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -1px;
        }

        .stat-card .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 0.5rem;
            padding: 0.15em 0.5em;
            border-radius: 12px;
        }

        .stat-change-up {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-change-neutral {
            background: rgba(107, 115, 133, 0.1);
            color: var(--gray-500);
        }

        /* ============================================
           KARTU INFO (Premium)
           ============================================ */
        .info-card {
            background: #ffffff;
            border: 1px solid var(--gray-150);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .info-card:hover {
            box-shadow: var(--shadow-md);
        }

        .info-card .card-header-navy {
            background: linear-gradient(135deg, var(--navy-700) 0%, var(--navy-500) 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 1rem 1.5rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card .card-header-navy i {
            opacity: 0.7;
            font-size: 1rem;
        }

        .info-card .card-body {
            padding: 1.5rem;
        }

        .info-card .card-body p {
            color: var(--gray-600);
            font-size: 0.85rem;
            line-height: 1.7;
        }

        .info-card .feature-list {
            list-style: none;
            padding: 0;
            margin: 0.75rem 0;
        }

        .info-card .feature-list li {
            color: var(--gray-600);
            font-size: 0.85rem;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .info-card .feature-list li:last-child {
            border-bottom: none;
        }

        .info-card .feature-list li .feature-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .feature-icon-navy {
            background: rgba(27, 42, 74, 0.08);
            color: var(--navy-600);
        }

        .feature-icon-accent {
            background: var(--accent-glow);
            color: var(--accent);
        }

        /* ============================================
           QUICK ACTION BUTTONS
           ============================================ */
        .quick-action {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: #fff;
            border: 1px solid var(--gray-150);
            border-radius: var(--radius-md);
            text-decoration: none;
            color: var(--gray-700);
            transition: all 0.25s ease;
            box-shadow: var(--shadow-sm);
        }

        .quick-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--accent);
            color: var(--navy-600);
        }

        .quick-action .qa-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .quick-action .qa-label {
            font-weight: 600;
            font-size: 0.82rem;
        }

        .quick-action .qa-desc {
            font-size: 0.7rem;
            color: var(--gray-400);
            margin-top: 1px;
        }

        /* ============================================
           FOOTER
           ============================================ */
        .footer-navy {
            background: var(--navy-900);
            color: rgba(255, 255, 255, 0.35);
            padding: 1.25rem 0;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
        }

        /* ============================================
           SCROLLBAR
           ============================================ */
        .sidebar::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        /* ============================================
           ANIMASI
           ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-15px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-in {
            opacity: 0;
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .animate-in-1 { animation-delay: 0.05s; }
        .animate-in-2 { animation-delay: 0.1s; }
        .animate-in-3 { animation-delay: 0.15s; }
        .animate-in-4 { animation-delay: 0.2s; }
        .animate-in-5 { animation-delay: 0.25s; }

        .animate-slide {
            opacity: 0;
            animation: slideInRight 0.4s ease-out forwards;
        }

        .animate-slide-1 { animation-delay: 0.3s; }
        .animate-slide-2 { animation-delay: 0.4s; }

        /* ============================================
           LANGUAGE SWITCHER
           ============================================ */
        .btn-lang-switch {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.2s;
        }

        .btn-lang-switch:hover,
        .btn-lang-switch:focus {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.25);
        }

        .btn-lang-switch::after {
            font-size: 0.6rem;
        }

        .dropdown-menu-lang {
            min-width: 150px;
            background: #fff;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-lg);
            padding: 0.4rem;
            margin-top: 0.5rem;
        }

        .lang-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--gray-600);
            border-radius: 6px;
            transition: all 0.15s;
        }

        .lang-item:hover {
            background: var(--gray-100);
            color: var(--navy-600);
        }

        .lang-item.active {
            background: linear-gradient(135deg, var(--navy-600), var(--navy-500));
            color: #fff;
        }

        .lang-item.active:hover {
            background: linear-gradient(135deg, var(--navy-500), var(--navy-400));
            color: #fff;
        }

        .lang-flag {
            font-size: 1.1rem;
            line-height: 1;
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .navbar-navy .navbar-brand span {
                display: none;
            }
            .main-content {
                padding: 1.25rem;
            }
            .welcome-section {
                padding: 1.5rem;
            }
            .welcome-section h2 {
                font-size: 1.25rem;
            }
        }
    </style>
@stack('styles')
</head>

<body class="bg-light">

@include('layouts.script')

@stack('scripts')

<div class="container-fluid p-0">

    <div class="row g-0">

        @include('layouts.sidebar')

        <main class="col-md-10 main-content">

            @yield('content')

        </main>

    </div>

</div>

@include('layouts.footer')

{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@include('layouts.script')

</body>

</html>