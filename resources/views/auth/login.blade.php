<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Second and Destroy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #202124;
        }

        .sd-login-shell {
            width: 100%;
            max-width: 380px;
            padding-inline: 1.25rem;
        }

        @media (max-width: 575.98px) {
            .sd-login-shell {
                padding-inline: 1rem;
            }

            .sd-login-card .card-body {
                padding: 1.5rem !important;
            }

            .sd-login-title {
                font-size: 1.1rem;
            }

            .sd-login-sub {
                font-size: 0.8rem;
            }
        }

        .sd-login-card {
            border-radius: 12px;
            border: 1px solid #e4e7eb;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(60, 64, 67, 0.18);
            color: #202124;
        }

        .sd-login-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            padding: 0.2rem 0.8rem;
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            background-color: #e8f0fe;
            color: #1967d2;
            border: none;
        }

        .sd-login-pill-dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background-color: #1a73e8;
        }

        .sd-login-title {
            font-size: 1.25rem;
            letter-spacing: 0.02em;
        }

        .sd-login-sub {
            font-size: 0.85rem;
            color: #5f6368;
        }

        .sd-input {
            border-radius: 8px;
            border: 1px solid #dadce0;
            background-color: #ffffff;
            color: #202124;
            font-size: 0.9rem;
        }

        .sd-input:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 1px #1a73e8;
            background-color: #ffffff;
            color: #202124;
        }

        .sd-login-btn {
            border-radius: 999px;
            border: 1px solid transparent;
            background-color: #1a73e8;
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .sd-login-btn:hover {
            background-color: #1557b0;
            color: #ffffff;
        }

        .sd-login-footer {
            font-size: 0.75rem;
            color: #5f6368;
        }

        .sd-error {
            border-radius: 8px;
            background-color: #fce8e6;
            border: 1px solid #ea4335;
            color: #b31412;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
<div class="sd-login-shell">
    <div class="card sd-login-card">
        <div class="card-body p-4 p-md-4">
            <div class="mb-3 text-center">
                <span class="sd-login-pill">
                    <span class="sd-login-pill-dot"></span>
                    SECOND AND DESTROY
                </span>
            </div>
            <div class="text-center mb-4">
                <div class="sd-login-title fw-semibold text-slate-50 mb-1">
                    Access Console
                </div>
                <div class="sd-login-sub">
                    Panel pendataan pelanggan untuk toko pakaian Anda.
                </div>
            </div>

            @if($errors->any())
                <div class="sd-error alert py-2 px-3 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}" class="mb-3">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label small text-gray-300">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           class="form-control sd-input" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label small text-gray-300">Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control sd-input" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small text-gray-300" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn w-100 sd-login-btn">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="text-center sd-login-footer">
                &copy; {{ date('Y') }} Second and Destroy &mdash; Data pelanggan terenkripsi dengan AES.
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


