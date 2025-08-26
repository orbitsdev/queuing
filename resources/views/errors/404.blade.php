<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 | Page Not Found</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            background-color: #f9fafb;
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
            background-color: white;
            border-radius: 0.5rem;

        }
        .error-code {
            font-size: 6rem;
            font-weight: bold;
            margin: 0;
            color: #4b5563;
        }
        .error-divider {
            display: inline-block;
            margin: 0 1rem;
            height: 3rem;
            border-left: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        .error-message {
            display: inline-block;
            font-size: 1.5rem;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            vertical-align: middle;
        }
        .error-description {
            margin-top: 2rem;
            color: #6b7280;
        }
        .button-container {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        .button {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background-color: #2563eb;
            color: white;
            font-weight: 500;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #1d4ed8;
        }
        .button-secondary {
            background-color: #6b7280;
        }
        .button-secondary:hover {
            background-color: #4b5563;
        }
        .icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div>
            <img src="{{ asset('images/security.svg') }}" alt="Security Icon" style="width: 120px; margin-bottom: 2rem;">
        </div>
        <div>
            <h1 class="error-code">404</h1>
            <div class="error-divider"></div>
            <div class="error-message">Page not found</div>
        </div>
        <div class="error-description">
            <p>The page you are looking for doesn't exist or has been moved. Please check the URL or navigate back to the dashboard.</p>
        </div>
        <div class="button-container">
            <a href="{{ route('dashboard') }}" class="button">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
               Go To Home
            </a>
            @guest
            <a href="{{ route('login') }}" class="button button-secondary">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Login
            </a>
            @endguest
        </div>
    </div>
</body>
</html>
