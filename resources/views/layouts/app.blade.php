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
            color: #202124;
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
            padding: 1.5rem 0 3rem;
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
            color: #5f6368;
            font-size: 0.8rem;
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
</body>
</html>


