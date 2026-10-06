<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SMA Nusantara - Sistem Absensi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Animated background blobs */
        body::before, body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 8s ease-in-out infinite;
        }
        body::before {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #6366f1, transparent);
            top: -100px; left: -100px;
        }
        body::after {
            width: 300px; height: 300px;
            background: radial-gradient(circle, #8b5cf6, transparent);
            bottom: -80px; right: -80px;
            animation-delay: -4s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: rgba(255,255,255,.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 24px;
            padding: 32px 28px;
            box-shadow: 0 24px 80px rgba(0,0,0,.5);
            animation: slideUp .5s ease both;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-logo {
            width: 58px; height: 58px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            margin: 0 auto 12px;
            box-shadow: 0 8px 24px rgba(99,102,241,.4);
        }

        .login-title {
            font-size: 22px;
            font-weight: 800;
            color: #f1f5f9;
            text-align: center;
            margin-bottom: 2px;
        }
        .login-subtitle {
            font-size: 12.5px;
            color: #94a3b8;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-label {
            color: #cbd5e1;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .form-control {
            background: rgba(255,255,255,.08);
            border: 1.5px solid rgba(255,255,255,.12);
            border-radius: 12px;
            color: #f1f5f9;
            padding: 11px 16px;
            font-size: 14px;
            transition: all .2s;
        }
        .form-control:focus {
            background: rgba(255,255,255,.12);
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.2);
            color: #f1f5f9;
        }
        .form-control::placeholder { color: #475569; }
        .form-control.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,.15);
        }
        .invalid-feedback { color: #fca5a5; font-size: 12px; }

        .input-group-text {
            background: rgba(255,255,255,.08);
            border: 1.5px solid rgba(255,255,255,.12);
            border-radius: 12px 0 0 12px;
            color: #64748b;
        }

        .btn-login {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            padding: 12px;
            width: 100%;
            transition: all .2s;
            box-shadow: 0 4px 16px rgba(99,102,241,.4);
            letter-spacing: .3px;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(99,102,241,.5);
            color: #fff;
        }
        .btn-login:active { transform: translateY(0); }

        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }
        .form-check-label { color: #94a3b8; font-size: 13px; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0 14px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,.1);
        }
        .divider span { color: #64748b; font-size: 11.5px; white-space: nowrap; font-weight: 600; }

        /* Modern 3-Column Demo Grid */
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .demo-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 12px 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-decoration: none;
            color: #f1f5f9;
            width: 100%;
        }
        .demo-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
        }
        .demo-card:active {
            transform: translateY(0);
        }

        /* Admin Demo Card */
        .demo-card.demo-admin {
            border-color: rgba(239, 68, 68, 0.28);
            background: linear-gradient(180deg, rgba(239, 68, 68, 0.12) 0%, rgba(255, 255, 255, 0.02) 100%);
        }
        .demo-card.demo-admin:hover {
            border-color: #ef4444;
            background: linear-gradient(180deg, rgba(239, 68, 68, 0.22) 0%, rgba(239, 68, 68, 0.05) 100%);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
        }
        .demo-card.demo-admin .demo-icon { color: #f87171; }
        .demo-card.demo-admin .demo-btn-action { background: rgba(239, 68, 68, 0.25); color: #fca5a5; }

        /* Guru Demo Card */
        .demo-card.demo-guru {
            border-color: rgba(99, 102, 241, 0.28);
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.12) 0%, rgba(255, 255, 255, 0.02) 100%);
        }
        .demo-card.demo-guru:hover {
            border-color: #6366f1;
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.22) 0%, rgba(99, 102, 241, 0.05) 100%);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
        }
        .demo-card.demo-guru .demo-icon { color: #818cf8; }
        .demo-card.demo-guru .demo-btn-action { background: rgba(99, 102, 241, 0.25); color: #c7d2fe; }

        /* Siswa Demo Card */
        .demo-card.demo-siswa {
            border-color: rgba(16, 185, 129, 0.28);
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.12) 0%, rgba(255, 255, 255, 0.02) 100%);
        }
        .demo-card.demo-siswa:hover {
            border-color: #10b981;
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.22) 0%, rgba(16, 185, 129, 0.05) 100%);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        }
        .demo-card.demo-siswa .demo-icon { color: #34d399; }
        .demo-card.demo-siswa .demo-btn-action { background: rgba(16, 185, 129, 0.25); color: #a7f3d0; }

        .demo-icon {
            font-size: 18px;
            margin-bottom: 2px;
        }
        .demo-role-name {
            font-size: 12.5px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.2px;
        }
        .demo-btn-action {
            font-size: 10px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 20px;
            letter-spacing: 0.2px;
            transition: all 0.2s;
            margin-top: 2px;
            white-space: nowrap;
        }
        .demo-card:hover .demo-btn-action {
            transform: scale(1.05);
        }

        .footer-text { text-align: center; margin-top: 18px; color: #475569; font-size: 11.5px; }

        .portfolio-badge {
            background: rgba(99,102,241,.15);
            border: 1px solid rgba(99,102,241,.3);
            color: #c7d2fe;
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            text-decoration: none;
            transition: all .2s;
        }
        .portfolio-badge:hover {
            background: rgba(99,102,241,.25);
            color: #fff;
            transform: translateY(-1px);
        }

        .action-cta-box {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }
        .btn-cta-demo {
            flex: 1;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            color: #f1f5f9;
            font-size: 12px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all .2s;
        }
        .btn-cta-demo:hover {
            background: rgba(99,102,241,.2);
            border-color: #6366f1;
            color: #fff;
        }
        .btn-cta-buy {
            flex: 1;
            background: linear-gradient(135deg, rgba(16,185,129,.2), rgba(5,150,105,.3));
            border: 1px solid rgba(16,185,129,.4);
            color: #a7f3d0;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all .2s;
        }
        .btn-cta-buy:hover {
            background: #10b981;
            color: #fff;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="login-container text-center">
        <!-- Badge Portfolio -->
        <a href="https://maimandev.com" target="_blank" class="portfolio-badge">
            <span style="color:#10b981;">●</span> Live Showcase Proyek &bull; <strong>maimandev.com</strong>
            <i class="fas fa-arrow-up-right-from-square ms-1" style="font-size:10px;"></i>
        </a>

        <div class="login-card text-start">
            <div class="login-logo">S</div>
            <h1 class="login-title">Selamat Datang</h1>
            <p class="login-subtitle">SMA Nusantara &bull; Sistem Absensi Digital</p>

            <!-- 2 Tombol Aksi Utama: Tutorial & Beli Website -->
            <div class="action-cta-box">
                <button type="button" class="btn btn-cta-demo" data-bs-toggle="modal" data-bs-target="#modalTutorialDemo">
                    <i class="fas fa-book-open text-primary"></i> Tutorial Demo
                </button>
                <button type="button" class="btn btn-cta-buy" data-bs-toggle="modal" data-bs-target="#modalBeliSourceCode">
                    <i class="fas fa-shopping-cart text-success"></i> Beli Source Code
                </button>
            </div>

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label"><i class="fas fa-envelope me-1"></i> Email</label>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="nama@gmail.com"
                           value="{{ old('email') }}" autocomplete="email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label"><i class="fas fa-lock me-1"></i> Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="••••••••" autocomplete="current-password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="btnSubmit">
                    <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Sistem
                </button>
            </form>

            <div class="divider"><span>⚡ Uji Coba Demo (Langsung Masuk Tanpa Sandi)</span></div>

            <div class="demo-grid">
                <a href="{{ route('demo.login', ['role' => 'admin']) }}" class="demo-card demo-admin text-decoration-none" title="Klik untuk langsung masuk sebagai Admin tanpa password" onclick="this.style.opacity='0.7'; this.querySelector('.demo-btn-action').innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Masuk...';">
                    <i class="fas fa-user-shield demo-icon"></i>
                    <span class="demo-role-name">Admin</span>
                    <span class="demo-btn-action">Masuk &rarr;</span>
                </a>
                <a href="{{ route('demo.login', ['role' => 'guru']) }}" class="demo-card demo-guru text-decoration-none" title="Klik untuk langsung masuk sebagai Guru tanpa password" onclick="this.style.opacity='0.7'; this.querySelector('.demo-btn-action').innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Masuk...';">
                    <i class="fas fa-chalkboard-teacher demo-icon"></i>
                    <span class="demo-role-name">Guru</span>
                    <span class="demo-btn-action">Masuk &rarr;</span>
                </a>
                <a href="{{ route('demo.login', ['role' => 'siswa']) }}" class="demo-card demo-siswa text-decoration-none" title="Klik untuk langsung masuk sebagai Siswa tanpa password" onclick="this.style.opacity='0.7'; this.querySelector('.demo-btn-action').innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Masuk...';">
                    <i class="fas fa-user-graduate demo-icon"></i>
                    <span class="demo-role-name">Siswa</span>
                    <span class="demo-btn-action">Masuk &rarr;</span>
                </a>
            </div>
        </div>
        <p class="footer-text">&copy; {{ date('Y') }} SMA Nusantara &bull; Karya Iman Mendrofa (<a href="https://maimandev.com" target="_blank" class="text-secondary text-decoration-none">maimandev.com</a>)</p>
    </div>

    @include('partials.demo-modal')
    @include('partials.payment-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function quickLogin(role) {
            window.location.href = "{{ url('/demo-login') }}?role=" + role;
        }
    </script>
</body>
</html>
