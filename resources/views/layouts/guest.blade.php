<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Global Risk Platform') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --navy-900: #0a1628;
                --navy-800: #0f1b33;
                --navy-700: #152242;
                --navy-600: #1b2a4a;
                --navy-500: #243656;
                --navy-400: #2d4066;
                --navy-300: #3d5a80;
                --gray-100: #f1f3f6;
                --gray-200: #e2e6ed;
                --gray-400: #a0a8b8;
                --gray-500: #6b7385;
                --gray-600: #4a5568;
                --gray-700: #2d3748;
                --accent: #4a90d9;
                --accent-dark: #3a7bc8;
                --accent-glow: rgba(74, 144, 217, 0.12);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                -webkit-font-smoothing: antialiased;
                min-height: 100vh;
                display: flex;
                background: linear-gradient(135deg, var(--navy-900) 0%, var(--navy-700) 40%, var(--navy-500) 100%);
                position: relative;
                overflow-x: hidden;
                overflow-y: auto;
            }

            body::before {
                content: '';
                position: fixed;
                top: -50%;
                right: -20%;
                width: 800px;
                height: 800px;
                background: radial-gradient(circle, rgba(74, 144, 217, 0.08) 0%, transparent 70%);
                border-radius: 50%;
                z-index: 0;
            }

            body::after {
                content: '';
                position: fixed;
                bottom: -40%;
                left: -10%;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(74, 144, 217, 0.06) 0%, transparent 70%);
                border-radius: 50%;
                z-index: 0;
            }

            .auth-wrapper {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
                position: relative;
                z-index: 1;
            }

            .auth-logo {
                text-align: center;
                margin-bottom: 2rem;
            }

            .auth-logo .logo-icon {
                width: 56px;
                height: 56px;
                background: linear-gradient(135deg, var(--accent), var(--accent-dark));
                border-radius: 16px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                color: #fff;
                margin-bottom: 1rem;
                box-shadow: 0 8px 25px rgba(74, 144, 217, 0.35);
            }

            .auth-logo h1 {
                color: #ffffff;
                font-size: 1.15rem;
                font-weight: 700;
                letter-spacing: -0.3px;
                margin-bottom: 0.25rem;
            }

            .auth-logo p {
                color: rgba(255, 255, 255, 0.4);
                font-size: 0.78rem;
                font-weight: 400;
            }

            .auth-card {
                width: 100%;
                max-width: 420px;
                background: rgba(255, 255, 255, 0.97);
                backdrop-filter: blur(20px);
                border-radius: 20px;
                padding: 2.5rem;
                box-shadow:
                    0 25px 60px rgba(0, 0, 0, 0.3),
                    0 0 0 1px rgba(255, 255, 255, 0.08);
                animation: cardSlideUp 0.6s ease-out;
            }

            .auth-card-login {
                margin-top: -18px;
                margin-bottom: 18px;
            }

            .login-page .auth-wrapper {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }

            .login-page .auth-logo {
                margin-bottom: 1.25rem;
            }

            .login-page .auth-logo .logo-icon {
                width: 48px;
                height: 48px;
                margin-bottom: 0.6rem;
            }

            .login-page .auth-card {
                padding-top: 1.75rem;
                padding-bottom: 1.75rem;
            }

            .login-page .auth-card .auth-subtitle {
                margin-bottom: 1rem;
            }

            .login-page .auth-card .auth-subtitle + div {
                margin-bottom: 1rem !important;
            }

            .login-page .form-group {
                margin-bottom: 0.9rem;
            }

            .login-page .form-check {
                margin: 0.75rem 0;
            }

            .login-page .auth-links {
                margin-top: 0.9rem;
            }

            .login-page .auth-footer {
                margin-top: 0.75rem;
            }

            @keyframes cardSlideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .auth-card h2 {
                color: var(--navy-600);
                font-size: 1.35rem;
                font-weight: 800;
                margin-bottom: 0.3rem;
                text-align: center;
            }

            .auth-card .auth-subtitle {
                color: var(--gray-500);
                font-size: 0.82rem;
                text-align: center;
                margin-bottom: 1.75rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-group label {
                display: block;
                color: var(--navy-600);
                font-size: 0.78rem;
                font-weight: 600;
                margin-bottom: 0.4rem;
                letter-spacing: 0.2px;
            }

            .form-group .input-wrapper {
                position: relative;
            }

            .form-group .input-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--gray-400);
                font-size: 0.95rem;
                z-index: 2;
                transition: color 0.2s;
            }

            .form-group input[type="text"],
            .form-group input[type="email"],
            .form-group input[type="password"] {
                width: 100%;
                padding: 0.7rem 0.9rem 0.7rem 2.75rem;
                border: 2px solid var(--gray-200);
                border-radius: 10px;
                font-size: 0.85rem;
                font-family: 'Inter', sans-serif;
                color: var(--gray-700);
                background: var(--gray-100);
                transition: all 0.25s ease;
                outline: none;
            }

            .form-group input:focus {
                border-color: var(--accent);
                background: #fff;
                box-shadow: 0 0 0 4px var(--accent-glow);
            }

            .form-group input:focus + .input-icon,
            .form-group input:focus ~ .input-icon {
                color: var(--accent);
            }

            .form-group input::placeholder {
                color: var(--gray-400);
                font-size: 0.82rem;
            }

            .input-error {
                color: #ef4444;
                font-size: 0.72rem;
                margin-top: 0.35rem;
                font-weight: 500;
            }

            .form-check {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin: 1rem 0;
            }

            .form-check input[type="checkbox"] {
                width: 16px;
                height: 16px;
                accent-color: var(--accent);
                cursor: pointer;
            }

            .form-check label {
                color: var(--gray-600);
                font-size: 0.8rem;
                cursor: pointer;
            }

            .btn-auth-primary {
                width: 100%;
                padding: 0.75rem;
                background: linear-gradient(135deg, var(--navy-600) 0%, var(--navy-500) 100%);
                color: #fff;
                border: none;
                border-radius: 10px;
                font-size: 0.85rem;
                font-weight: 600;
                font-family: 'Inter', sans-serif;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                letter-spacing: 0.2px;
            }

            .btn-auth-primary:hover {
                background: linear-gradient(135deg, var(--navy-500) 0%, var(--navy-400) 100%);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(27, 42, 74, 0.3);
            }

            .btn-auth-primary:active {
                transform: translateY(0);
            }

            .auth-links {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 1.25rem;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .auth-link {
                color: var(--accent);
                font-size: 0.78rem;
                font-weight: 500;
                text-decoration: none;
                transition: color 0.2s;
            }

            .auth-link:hover {
                color: var(--accent-dark);
                text-decoration: underline;
            }

            .auth-divider {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin: 1.5rem 0;
                color: var(--gray-400);
                font-size: 0.72rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-weight: 500;
            }

            .auth-divider::before,
            .auth-divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: var(--gray-200);
            }

            .auth-status {
                background: rgba(16, 185, 129, 0.1);
                color: #059669;
                padding: 0.75rem 1rem;
                border-radius: 10px;
                font-size: 0.8rem;
                margin-bottom: 1.25rem;
                border: 1px solid rgba(16, 185, 129, 0.2);
            }

            .auth-footer {
                text-align: center;
                margin-top: 1.5rem;
                color: rgba(255, 255, 255, 0.3);
                font-size: 0.72rem;
            }
        </style>
    </head>
    <body class="{{ request()->routeIs('login') ? 'login-page' : '' }}">
        <div class="auth-wrapper">

            <div class="auth-logo">
                <div class="logo-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h1>Global Risk Platform</h1>
                <p>Supply Chain Risk Intelligence</p>
            </div>

            <div class="auth-card {{ request()->routeIs('login') ? 'auth-card-login' : '' }}">
                {{ $slot }}
            </div>

            <div class="auth-footer">
                © {{ date('Y') }} Global Supply Chain Risk Intelligence Platform
            </div>

        </div>
    </body>
</html>
