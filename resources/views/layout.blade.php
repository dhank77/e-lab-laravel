<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | E-Lab</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('elab-tab-blank.svg') }}">
    <link rel="stylesheet" href="{{ asset('elab.css') }}">
</head>
<body>
    <header class="topbar">
        <div class="topbar-start">
            @yield('header-start')
            <a class="brand" href="{{ url('/') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                    <path d="M9 3h6M10 3v7l-5 8a2 2 0 0 0 2 3h10a2 2 0 0 0 2-3l-5-8V3M8 15h8"/>
                </svg>
                E-Lab
            </a>
        </div>
        @yield('header')
    </header>
    <main class="shell">@yield('content')</main>
    <footer class="site-footer">
        <p>&copy; {{ now()->year }} E-Lab &middot; Data Laboratorium dan Peminjaman</p>
    </footer>
    @yield('scripts')
</body>
</html>
