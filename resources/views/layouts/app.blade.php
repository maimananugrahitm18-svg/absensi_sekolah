<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Absensi Sekolah') | SMA Nusantara</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #eef2ff;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #0f172a;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active: #6366f1;
            --bg-main: #f1f5f9;
            --card-bg: #ffffff;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-main); margin: 0; overflow-x: hidden; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }

        .sidebar-brand {
            padding: 24px 20px 16px;
            border-bottom: 1px solid #1e293b;
        }
        .sidebar-brand .brand-logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            margin-right: 10px;
        }
        .sidebar-brand .brand-name {
            font-size: 15px;
            font-weight: 700;
            color: #f1f5f9;
            line-height: 1.2;
        }
        .sidebar-brand .brand-sub {
            font-size: 11px;
            color: var(--sidebar-text);
        }

        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid #1e293b;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-user .user-avatar {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
        }
        .sidebar-user .user-name { font-size: 13px; font-weight: 600; color: #f1f5f9; }
        .sidebar-user .user-role {
            font-size: 11px;
            color: #6366f1;
            background: #1e1b4b;
            padding: 1px 8px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 2px;
            text-transform: capitalize;
        }

        .sidebar-nav { flex: 1; padding: 12px 0; }
        .nav-section-title {
            font-size: 10px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: .8px;
            padding: 10px 20px 4px;
        }
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all .2s;
            border-radius: 0;
            position: relative;
        }
        .sidebar-nav .nav-link:hover {
            background: #1e293b;
            color: #f1f5f9;
        }
        .sidebar-nav .nav-link.active {
            background: #1e1b4b;
            color: var(--primary);
            font-weight: 600;
        }
        .sidebar-nav .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--primary);
            border-radius: 0 2px 2px 0;
        }
        .sidebar-nav .nav-link .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }
        .sidebar-nav .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 10px;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid #1e293b;
        }
        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            transition: color .2s;
        }
        .sidebar-footer a:hover { color: var(--danger); }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: #fff;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        .topbar-title { font-size: 18px; font-weight: 700; color: var(--dark); }
        .topbar-subtitle { font-size: 12px; color: #94a3b8; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }

        .page-content { padding: 24px; flex: 1; }

        /* ── CARDS ── */
        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid #e2e8f0;
            transition: transform .2s, box-shadow .2s;
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 12px;
        }
        .stat-card .stat-value { font-size: 28px; font-weight: 800; color: var(--dark); }
        .stat-card .stat-label { font-size: 13px; color: #64748b; font-weight: 500; }
        .stat-card .stat-trend { font-size: 12px; margin-top: 4px; }

        /* ── CONTENT CARD ── */
        .content-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .content-card-header {
            padding: 16px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafafa;
        }
        .content-card-title { font-size: 15px; font-weight: 700; color: var(--dark); margin: 0; }
        .content-card-body { padding: 0; }

        /* ── TABLE ── */
        .table-custom { margin: 0; }
        .table-custom thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 16px;
        }
        .table-custom tbody td {
            padding: 12px 16px;
            color: #334155;
            font-size: 13.5px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .table-custom tbody tr:hover { background: #fafbff; }
        .table-custom tbody tr:last-child td { border-bottom: none; }

        /* ── BADGES ── */
        .badge-hadir  { background: #dcfce7; color: #15803d; }
        .badge-izin   { background: #dbeafe; color: #1d4ed8; }
        .badge-sakit  { background: #fef9c3; color: #a16207; }
        .badge-alpha  { background: #fee2e2; color: #b91c1c; }
        .badge-menunggu  { background: #fef9c3; color: #92400e; }
        .badge-disetujui { background: #dcfce7; color: #15803d; }
        .badge-ditolak   { background: #fee2e2; color: #b91c1c; }
        .badge, [class^="badge-"] {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            display: inline-block;
        }

        /* ── BUTTONS ── */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-sm { font-size: 12px; padding: 5px 12px; }

        /* ── FORMS ── */
        .form-label { font-size: 13px; font-weight: 600; color: #374151; }
        .form-control, .form-select {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13.5px;
            padding: 9px 14px;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,.1);
        }

        /* ── ALERTS ── */
        .alert { border-radius: 12px; border: none; font-size: 13.5px; }
        .alert-success { background: #dcfce7; color: #15803d; }
        .alert-danger   { background: #fee2e2; color: #b91c1c; }
        .alert-warning  { background: #fef9c3; color: #92400e; }
        .alert-info     { background: #dbeafe; color: #1e40af; }

        /* ── PROGRESS ── */
        .progress { height: 8px; border-radius: 20px; background: #e2e8f0; }
        .progress-bar { border-radius: 20px; }

        /* ── RESPONSIVE ── */
        .sidebar-toggle { display: none; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: block; }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; }
            .sidebar-overlay.show { display: block; }
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp .35s ease both; }
        .fade-in-up-delay-1 { animation-delay: .05s; }
        .fade-in-up-delay-2 { animation-delay: .1s; }
        .fade-in-up-delay-3 { animation-delay: .15s; }
        .fade-in-up-delay-4 { animation-delay: .2s; }

        .avatar-sm {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 12px;
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Mobile overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center">
                <div class="brand-logo">S</div>
                <div>
                    <div class="brand-name">SMA Nusantara</div>
                    <div class="brand-sub">Sistem Absensi Digital</div>
                </div>
            </div>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <span class="user-role">{{ auth()->user()->role }}</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            @hasSection('sidebar-menu')
                @yield('sidebar-menu')
            @else
                @auth
                    @if(auth()->user()->role === 'admin')
                        @include('admin.partials.sidebar')
                    @elseif(auth()->user()->role === 'guru')
                        @include('guru.partials.sidebar')
                    @elseif(auth()->user()->role === 'siswa')
                        @include('siswa.partials.sidebar')
                    @endif
                @endauth
            @endif
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 w-100 text-start text-decoration-none" style="color:#64748b;font-size:13px;">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        {{-- Demo Showcase Banner --}}
        <div class="px-4 pt-3 pb-0">
            <div class="d-flex flex-wrap justify-content-between align-items-center p-2 px-3 rounded-3 shadow-sm border" style="background: linear-gradient(90deg, #0f172a 0%, #1e1b4b 60%, #312e81 100%); color: #fff;">
                <div class="d-flex align-items-center gap-2 small">
                    <span class="badge bg-warning text-dark fw-bold" style="font-size: 10px;">🎮 MODE DEMO</span>
                    <span class="d-none d-sm-inline" style="font-size: 12px;">Aktif sebagai: <strong>{{ ucfirst(auth()->user()->role) }} ({{ auth()->user()->name }})</strong></span>
                    <div class="d-none d-md-flex align-items-center gap-1 ms-2">
                        <span class="text-light opacity-50" style="font-size: 11px;">Ganti Cepat:</span>
                        <a href="{{ route('demo.login', ['role' => 'admin']) }}" class="btn btn-sm py-0 px-2 {{ auth()->user()->role === 'admin' ? 'btn-danger text-white' : 'btn-outline-light' }}" style="font-size:11px; border-radius:6px;" title="Ganti ke Akun Admin">
                            👑 Admin
                        </a>
                        <a href="{{ route('demo.login', ['role' => 'guru']) }}" class="btn btn-sm py-0 px-2 {{ auth()->user()->role === 'guru' ? 'btn-primary text-white' : 'btn-outline-light' }}" style="font-size:11px; border-radius:6px;" title="Ganti ke Akun Guru">
                            👨‍🏫 Guru
                        </a>
                        <a href="{{ route('demo.login', ['role' => 'siswa']) }}" class="btn btn-sm py-0 px-2 {{ auth()->user()->role === 'siswa' ? 'btn-success text-white' : 'btn-outline-light' }}" style="font-size:11px; border-radius:6px;" title="Ganti ke Akun Siswa">
                            🎓 Siswa
                        </a>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                    <button type="button" class="btn btn-sm btn-outline-light py-1 px-2" style="font-size:11.5px; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalTutorialDemo">
                        <i class="fas fa-book-open me-1 text-info"></i> Tutorial Demo
                    </button>
                    <button type="button" class="btn btn-sm btn-success py-1 px-3 fw-bold shadow-sm" style="font-size:11.5px; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalBeliSourceCode">
                        <i class="fas fa-shopping-cart me-1"></i> Beli Website Ini
                    </button>
                    <a href="https://maimandev.com" target="_blank" class="btn btn-sm btn-link text-light text-decoration-none py-1 px-1 ms-1" style="font-size:12px;" title="Kunjungi Portofolio maimandev.com">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Topbar --}}
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link sidebar-toggle p-0" id="sidebarToggle" onclick="openSidebar()">
                    <i class="fas fa-bars fs-5 text-dark"></i>
                </button>
                <div>
                    <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                    <div class="topbar-subtitle">@yield('page-subtitle', date('l, d F Y'))</div>
                </div>
            </div>
            <div class="topbar-right d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary d-none d-md-inline">
                    <i class="fas fa-calendar-day me-1"></i> {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>
                @auth
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border d-flex align-items-center gap-2 dropdown-toggle py-1 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius:8px;">
                        <span style="width:26px;height:26px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="d-none d-sm-inline fw-semibold small text-dark">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:10px; min-width: 190px;">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold small text-dark">{{ auth()->user()->name }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ auth()->user()->email }}</div>
                            <span class="badge bg-secondary-subtle text-secondary mt-1" style="font-size:10px;">{{ strtoupper(auth()->user()->role) }}</span>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger small">
                                    <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>

        {{-- Alerts --}}
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Terdapat kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <div class="page-content">
            @yield('content')
        </div>
    </div>

    @include('partials.demo-modal')
    @include('partials.payment-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('sidebarOverlay').classList.add('show');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }

        // Quick login saat berada di dalam dashboard — langsung ganti akun demo
        function quickLogin(email, pass) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('demo.login') }}";

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";

            const emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'email';
            emailInput.value = email;

            const passInput = document.createElement('input');
            passInput.type = 'hidden';
            passInput.name = 'password';
            passInput.value = pass;

            form.appendChild(csrf);
            form.appendChild(emailInput);
            form.appendChild(passInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    @stack('scripts')
</body>
</html>
