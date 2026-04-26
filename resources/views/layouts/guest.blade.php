<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Catering Family Jakarta') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --cfj-primary:#C0392B; --cfj-secondary:#E67E22; }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background: linear-gradient(135deg, #FFF5F3 0%, #FFF0E8 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .auth-wrapper { width: 100%; max-width: 440px; padding: 20px 16px; }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(192,57,43,.12);
            overflow: hidden;
        }
        .auth-header {
            background: linear-gradient(135deg, var(--cfj-primary), var(--cfj-secondary));
            padding: 32px 32px 28px;
            text-align: center;
        }
        .auth-brand-icon {
            width: 56px; height: 56px; border-radius: 16px;
            background: rgba(255,255,255,.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; margin: 0 auto 14px;
        }
        .auth-header h4 { color: #fff; font-weight: 800; margin: 0 0 4px; font-size: 1.2rem; }
        .auth-header p  { color: rgba(255,255,255,.8); margin: 0; font-size: .85rem; }
        .auth-body { padding: 28px 32px 32px; }
        .form-label { font-weight: 700; font-size: .85rem; color: #444; margin-bottom: 6px; }
        .form-control, .form-select {
            border-radius: 10px; border: 1.5px solid #e8e4e0;
            padding: 10px 14px; font-size: .9rem; transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--cfj-primary);
            box-shadow: 0 0 0 3px rgba(192,57,43,.1);
        }
        .input-group-text { border-radius: 10px 0 0 10px; border: 1.5px solid #e8e4e0; border-right: none; }
        .input-group .form-control { border-radius: 0 10px 10px 0; }
        .btn-auth {
            background: linear-gradient(135deg, var(--cfj-primary), var(--cfj-secondary));
            border: none; color: #fff; font-weight: 700;
            border-radius: 50px; padding: 11px 20px; width: 100%; font-size: .95rem;
            transition: opacity .2s, transform .1s;
        }
        .btn-auth:hover { opacity: .92; transform: translateY(-1px); color: #fff; }
        .btn-auth:active { transform: translateY(0); }
        a.link-auth { color: var(--cfj-primary); font-weight: 600; text-decoration: none; }
        a.link-auth:hover { text-decoration: underline; }
        .divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; color: #bbb; font-size: .8rem; }
        .divider::before, .divider::after { content:''; flex:1; height:1px; background:#eee; }
        .fw-400 { font-weight: 400 !important; }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="Logo"
                         style="width:56px;height:56px;border-radius:16px;object-fit:cover;margin:0 auto 14px;display:block">
                @else
                    <div class="auth-brand-icon">🍱</div>
                @endif
                <h4>Catering Family Jakarta</h4>
                <p>
                    @if(request()->routeIs('register'))
                        Buat akun baru untuk mulai memesan
                    @else
                        Masuk ke akun Anda
                    @endif
                </p>
            </div>
            <div class="auth-body">
                {{ $slot }}
            </div>
        </div>
        <p class="text-center text-muted small mt-4">
            &copy; {{ date('Y') }} Catering Family Jakarta
        </p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
