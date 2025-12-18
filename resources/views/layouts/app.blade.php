<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Second and Destroy - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background-color: #ffffff;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #1f2933;
        }

        .sd-nav {
            background-color: #ffffff;
            border-bottom: 1px solid #e4e7eb;
        }

        .sd-brand {
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #202124;
        }

        .sd-pill {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 0.2rem 0.7rem;
            border-radius: 999px;
            background-color: #e8f0fe;
            color: #1967d2;
        }

        .sd-shell {
            padding: 1rem 0 2rem;
        }

        @media (min-width: 768px) {
            .sd-shell {
                padding: 1.5rem 0 3rem;
            }
        }

        .sd-card {
            border-radius: 12px;
            border: 1px solid #e4e7eb;
            background-color: #ffffff;
            box-shadow: 0 1px 2px rgba(60, 64, 67, 0.15);
        }

        .sd-card-header {
            border-bottom: 1px solid #e4e7eb;
            background-color: #ffffff;
        }

        .sd-table th {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom-width: 1px;
            color: #5f6368;
            background-color: #f8f9fa;
        }

        .sd-table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .sd-badge-soft {
            font-size: 0.78rem;
            border-radius: 999px;
            padding: 0.15rem 0.6rem;
            background-color: #e8f0fe;
            color: #1a73e8;
        }

        .sd-btn-primary {
            border-radius: 999px;
            border: 1px solid transparent;
            background-color: #1a73e8;
            color: #ffffff;
            font-weight: 500;
        }

        .sd-btn-primary:hover {
            background-color: #1557b0;
            color: #ffffff;
        }

        .sd-btn-ghost {
            border-radius: 999px;
            border: 1px solid #dadce0;
            color: #202124;
            background-color: #ffffff;
        }

        .sd-btn-ghost:hover {
            background-color: #f1f3f4;
        }

        .sd-alert {
            border-radius: 12px;
            border: 1px solid #34a85333;
            background-color: #e6f4ea;
            color: #0d652d;
            font-size: 0.85rem;
        }

        .sd-subtle {
            color: #56606b;
            font-size: 0.8rem;
        }

        /* Mobile Responsive */
        @media (max-width: 767.98px) {
            .sd-nav .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .sd-brand {
                font-size: 0.85rem;
            }

            .sd-pill {
                font-size: 0.65rem;
                padding: 0.15rem 0.5rem;
            }

            .sd-nav small {
                display: none;
            }

            .sd-nav .d-flex.align-items-center.ms-auto {
                flex-direction: column;
                align-items: flex-end !important;
                gap: 0.25rem !important;
            }

            .sd-nav .text-white-50 {
                font-size: 0.7rem !important;
                margin-right: 0 !important;
            }

            .sd-nav .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.75rem;
            }

            .container.sd-shell {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        .btn-outline-success {
            border-color: #34a853;
            color: #34a853;
            font-weight: 500;
        }

        .btn-outline-success:hover {
            background-color: #34a853;
            color: #ffffff;
            border-color: #34a853;
        }

        .btn-outline-secondary {
            border-color: #dadce0;
            color: #5f6368;
            font-weight: 500;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f3f4;
            color: #202124;
            border-color: #dadce0;
        }

        /* Dark mode */
        body.dark-mode {
            background-color: #0b1222;
            color: #f8fafc !important;
        }

        body.dark-mode .sd-nav {
            background-color: #0b1222;
            border-bottom-color: #1f2937;
            color: #f8fafc !important;
        }

        body.dark-mode .sd-brand,
        body.dark-mode .sd-pill,
        body.dark-mode .navbar-brand,
        body.dark-mode .sd-subtle {
            color: #f8fafc;
        }

        body.dark-mode .sd-pill {
            background-color: #1e293b;
        }

        body.dark-mode .sd-card,
        body.dark-mode .sd-card-header {
            background-color: #111827;
            border-color: #1f2937;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
        }

        body.dark-mode .sd-table th {
            background-color: #1e293b;
            color: #f8fafc !important;
            border-bottom-color: #1f2937;
            font-weight: 600;
        }

        body.dark-mode .sd-table td {
            color: #1e293b  !important;
            border-color: #1f2937;
            font-weight: 500;
        }

        body.dark-mode .sd-table tr {
            background-color: #152033;
        }

        body.dark-mode .sd-table tr:nth-child(even) {
            background-color: #1a2538;
        }



        body.dark-mode .sd-badge-soft {
            background-color: #1e293b;
            color: #e0f2fe;
        }

        body.dark-mode .sd-btn-primary {
            background-color: #2563eb;
        }

        body.dark-mode .sd-btn-ghost {
            background-color: #111827;
            border-color: #1f2937;
            color: #e5e7eb;
        }

        body.dark-mode .btn-outline-secondary {
            border-color: #475569;
            color: #f8fafc;
            background-color: #111827;
        }

        body.dark-mode .btn-outline-secondary:hover {
            background-color: #1e293b;
            color: #ffffff;
        }

        body.dark-mode .sd-alert {
            background-color: #052e16;
            border-color: #14532d;
            color: #bbf7d0;
        }

        /* Text utilities in dark mode */
        body.dark-mode .text-muted,
        body.dark-mode .form-label,
        body.dark-mode small,
        body.dark-mode label {
            color: #e5e7eb !important;
        }

        /* Inputs/selects */
        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: #111827;
            border-color: #1f2937;
            color: #f8fafc;
        }

        body.dark-mode .form-control::placeholder,
        body.dark-mode .form-select::placeholder {
            color: #cbd5e1;
        }

        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.15rem rgba(59, 130, 246, 0.35);
            background-color: #0f172a;
            color: #ffffff;
        }

        /* Dark mode: pastikan kolom Alamat (kolom ke-4) terbaca di tabel putih */
        body.dark-mode .sd-table tbody tr td:nth-child(4) {
            color: #111827 !important;
            font-weight: 600;
        }

        body.dark-mode .sd-table tbody tr td:nth-child(4) .text-muted {
            color: #111827 !important;
            opacity: 1 !important;
            font-weight: 600;
        }

        /* Toggle */
        .theme-toggle {
            border: 1px solid #dadce0;
            border-radius: 999px;
            padding: 0.3rem 0.9rem;
            background: #ffffff;
            color: #202124;
            font-size: 0.85rem;
        }

        .theme-toggle:hover {
            background: #f1f3f4;
        }

        body.dark-mode .theme-toggle {
            background: #111827;
            border-color: #1f2937;
            color: #e5e7eb;
        }

        body.dark-mode .theme-toggle:hover {
            background: #1f2937;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sd-nav sticky-top">
    <div class="container py-2">
        <div class="d-flex align-items-center gap-3">
            <span class="sd-pill">Second &amp; Destroy</span>
            <div class="d-flex flex-column">
                <span class="navbar-brand mb-0 sd-brand fw-semibold">
                    Apparel Data Console
                </span>
                <small class="text-secondary-emphasis" style="font-size: 0.72rem;">
                    Pelanggan • Transaksi • Keamanan Data
                </small>
            </div>
        </div>

        @auth
            <div class="d-flex align-items-center ms-auto gap-2">
                <button type="button" class="theme-toggle" id="themeToggle">Dark</button>
                <span class="text-white-50 me-2" style="font-size: 0.8rem;">
                    {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm sd-btn-ghost">
                        Keluar
                    </button>
                </form>
            </div>
        @endauth
    </div>
</nav>

<div class="container sd-shell mt-4">
    @if(session('success'))
        <div class="alert sd-alert d-flex align-items-center justify-content-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function() {
        const toggleBtn = document.getElementById('themeToggle');
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const stored = localStorage.getItem('sd-theme');

        function applyTheme(mode) {
            if (mode === 'dark') {
                document.body.classList.add('dark-mode');
                if (toggleBtn) toggleBtn.textContent = 'Light';
            } else {
                document.body.classList.remove('dark-mode');
                if (toggleBtn) toggleBtn.textContent = 'Dark';
            }
        }

        const initial = stored || (prefersDark ? 'dark' : 'light');
        applyTheme(initial);

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const next = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
                applyTheme(next);
                localStorage.setItem('sd-theme', next);
            });
        }
    })();
</script>
@stack('scripts')
</body>
</html>


