<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Halcón — @yield('title', 'Panel')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #080808;
            --surface:   #101010;
            --surface-2: #181818;
            --surface-3: #202020;
            --border:    rgba(255,255,255,.07);
            --border-2:  rgba(255,255,255,.13);
            --text:      #EFEFEF;
            --text-2:    rgba(239,239,239,.50);
            --text-3:    rgba(239,239,239,.28);
            --brand:     #E8540E;
            --brand-dim: rgba(232,84,14,.12);
            --brand-hi:  rgba(232,84,14,.22);
            --green:     #22C55E;
            --blue:      #3B82F6;
            --amber:     #F59E0B;
            --red:       #EF4444;
            --sidebar-w: 248px;
            --topbar-h:  60px;
        }

        html { font-size: 16px; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5, .font-display { font-family: 'Syne', sans-serif; }

        /* ───── SCROLLBAR ───── */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 4px; }

        /* ───── SIDEBAR ───── */
        .sidebar {
            position: fixed; inset: 0 auto 0 0;
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 200;
        }

        .sidebar-logo {
            padding: 26px 24px 22px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .logo-icon {
            width: 32px; height: 32px; border-radius: 8px;
            background: var(--brand); display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
        }
        .logo-icon svg { width: 18px; height: 18px; fill: #fff; }
        .logo-text {
            font-family: 'Syne', sans-serif; font-size: .95rem;
            font-weight: 800; color: var(--text); letter-spacing: -.2px;
        }
        .logo-sub {
            font-size: .65rem; color: var(--text-3);
            letter-spacing: 1.5px; text-transform: uppercase;
            margin-top: 1px;
        }

        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }

        .nav-group { margin-bottom: 4px; }
        .nav-label {
            font-size: .6rem; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--text-3);
            padding: 14px 20px 6px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 20px; margin: 1px 8px;
            color: var(--text-2); text-decoration: none;
            font-size: .82rem; font-weight: 400;
            border-radius: 8px;
            transition: color .15s, background .15s;
        }
        .nav-item .nav-icon {
            width: 20px; height: 20px; display: flex;
            align-items: center; justify-content: center;
            font-size: .78rem; flex-shrink: 0;
            color: var(--text-3); transition: color .15s;
        }
        .nav-item:hover {
            color: var(--text);
            background: var(--surface-2);
        }
        .nav-item:hover .nav-icon { color: var(--text-2); }
        .nav-item.active {
            color: var(--text);
            background: var(--brand-dim);
        }
        .nav-item.active .nav-icon { color: var(--brand); }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        .user-row {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 10px;
            background: var(--surface-2);
        }
        .user-avatar {
            width: 30px; height: 30px; border-radius: 8px;
            background: var(--brand-dim); border: 1px solid var(--brand-hi);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif; font-weight: 700;
            color: var(--brand); font-size: .7rem; flex-shrink: 0;
        }
        .user-name-sm  { font-size: .8rem; font-weight: 500; color: var(--text); line-height: 1.2; }
        .user-role-sm  { font-size: .68rem; color: var(--text-3); }
        .logout-btn {
            margin-left: auto; background: none; border: none;
            color: var(--text-3); cursor: pointer; padding: 4px;
            border-radius: 6px; transition: color .15s, background .15s;
            font-size: .85rem;
        }
        .logout-btn:hover { color: var(--red); background: rgba(239,68,68,.1); }

        /* ───── TOPBAR ───── */
        .topbar {
            position: fixed; top: 0;
            left: var(--sidebar-w); right: 0;
            height: var(--topbar-h);
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 32px; z-index: 100; gap: 12px;
        }
        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-size: .88rem; font-weight: 600; color: var(--text);
            letter-spacing: -.1px;
        }
        .topbar-sep { color: var(--text-3); font-size: .75rem; }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
        .topbar-date {
            font-size: .75rem; color: var(--text-3);
            background: var(--surface-2); border: 1px solid var(--border);
            border-radius: 6px; padding: 4px 10px;
        }
        .hamburger {
            display: none; background: none; border: none;
            color: var(--text-2); font-size: 1rem; cursor: pointer;
        }

        /* ───── MAIN ───── */
        .main-content { margin-left: var(--sidebar-w); padding-top: var(--topbar-h); min-height: 100vh; }
        .page-body { padding: 36px 40px; max-width: 1400px; }

        /* ───── PAGE HEADER ───── */
        .page-header {
            display: flex; align-items: flex-start;
            justify-content: space-between; margin-bottom: 32px; gap: 16px;
        }
        .page-header h1 {
            font-size: 1.5rem; font-weight: 700; color: var(--text);
            letter-spacing: -.3px; line-height: 1.2;
        }
        .page-header .sub {
            font-size: .78rem; color: var(--text-3);
            margin-top: 4px; display: flex; align-items: center; gap: 6px;
        }
        .page-header .sub a { color: var(--brand); text-decoration: none; }

        /* ───── CARDS ───── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
        }
        .card-head {
            display: flex; align-items: center; gap: 10px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
        }
        .card-head-icon {
            width: 28px; height: 28px; border-radius: 7px;
            background: var(--surface-3); display: flex; align-items: center;
            justify-content: center; font-size: .75rem; color: var(--brand);
            flex-shrink: 0;
        }
        .card-head-title {
            font-family: 'Syne', sans-serif; font-size: .85rem;
            font-weight: 700; color: var(--text);
        }
        .card-body { padding: 22px; }
        .card-body-flush { padding: 0; }

        /* ───── STAT CARDS ───── */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px 22px 20px;
            position: relative; overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute; top: 0; left: 0;
            right: 0; height: 2px;
        }
        .stat-card.c-orange::after { background: var(--brand); }
        .stat-card.c-blue::after   { background: var(--blue); }
        .stat-card.c-amber::after  { background: var(--amber); }
        .stat-card.c-green::after  { background: var(--green); }
        .stat-num {
            font-family: 'Syne', sans-serif; font-size: 2.2rem;
            font-weight: 800; line-height: 1; color: var(--text);
            letter-spacing: -.5px;
        }
        .stat-lbl {
            font-size: .75rem; color: var(--text-3);
            margin-top: 6px; text-transform: uppercase;
            letter-spacing: .8px; font-weight: 500;
        }
        .stat-icon-bg {
            position: absolute; right: 18px; top: 50%;
            transform: translateY(-50%);
            font-size: 2.5rem; opacity: .06;
        }

        /* ───── TABLE ───── */
        .tbl { width: 100%; border-collapse: collapse; }
        .tbl th {
            font-family: 'Syne', sans-serif;
            font-size: .65rem; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            color: var(--text-3); padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            text-align: left; white-space: nowrap;
        }
        .tbl td {
            padding: 13px 16px; font-size: .82rem;
            border-bottom: 1px solid var(--border);
            color: var(--text); vertical-align: middle;
        }
        .tbl tr:last-child td { border-bottom: none; }
        .tbl tbody tr { transition: background .1s; }
        .tbl tbody tr:hover td { background: var(--surface-2); }
        .tbl-muted { color: var(--text-2); }
        .tbl-mono { font-family: 'SF Mono', 'Fira Code', monospace; font-size: .78rem; }

        /* ───── BADGES ───── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 9px; border-radius: 20px;
            font-size: .68rem; font-weight: 700; letter-spacing: .4px;
            text-transform: uppercase;
        }
        .badge::before {
            content: ''; width: 5px; height: 5px; border-radius: 50%;
            background: currentColor; flex-shrink: 0;
        }
        .badge-ordered  { background: rgba(245,158,11,.12); color: #F59E0B; }
        .badge-process  { background: rgba(59,130,246,.12);  color: #60A5FA; }
        .badge-route    { background: rgba(168,85,247,.12);  color: #C084FC; }
        .badge-delivered{ background: rgba(34,197,94,.12);   color: #4ADE80; }
        .badge-archived { background: var(--surface-3); color: var(--text-3); }
        .badge-active   { background: rgba(34,197,94,.12);   color: #4ADE80; }
        .badge-inactive { background: var(--surface-3); color: var(--text-3); }

        /* Role badges */
        .role-badge {
            display: inline-flex; align-items: center;
            padding: 3px 9px; border-radius: 20px;
            font-size: .68rem; font-weight: 700; letter-spacing: .3px;
        }

        /* ───── BUTTONS ───── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 16px; border-radius: 9px;
            font-family: 'Syne', sans-serif; font-weight: 600;
            font-size: .8rem; cursor: pointer; border: none;
            text-decoration: none; transition: all .15s; white-space: nowrap;
        }
        .btn-primary {
            background: var(--brand); color: #fff;
        }
        .btn-primary:hover { background: #c4470b; color: #fff; }
        .btn-ghost {
            background: var(--surface-2); color: var(--text-2);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { background: var(--surface-3); color: var(--text); }
        .btn-danger-ghost {
            background: transparent; color: var(--red);
            border: 1px solid rgba(239,68,68,.25);
        }
        .btn-danger-ghost:hover { background: rgba(239,68,68,.1); color: var(--red); }
        .btn-success-ghost {
            background: transparent; color: var(--green);
            border: 1px solid rgba(34,197,94,.25);
        }
        .btn-success-ghost:hover { background: rgba(34,197,94,.1); color: var(--green); }
        .btn-sm { padding: 5px 10px; font-size: .72rem; border-radius: 7px; gap: 5px; }
        .btn-icon { padding: 7px; aspect-ratio: 1; }

        /* ───── FORMS ───── */
        .form-label {
            display: block; font-family: 'Syne', sans-serif;
            font-size: .68rem; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: var(--text-3); margin-bottom: 7px;
        }
        .form-input {
            width: 100%; padding: 10px 13px;
            background: var(--surface-2); border: 1px solid var(--border-2);
            border-radius: 9px; color: var(--text);
            font-family: 'DM Sans', sans-serif; font-size: .85rem;
            outline: none; transition: border-color .15s, background .15s;
            -webkit-appearance: none;
        }
        .form-input::placeholder { color: var(--text-3); }
        .form-input:focus {
            border-color: var(--brand); background: var(--surface-3);
        }
        .form-input.is-invalid { border-color: var(--red); }
        .form-error { font-size: .73rem; color: var(--red); margin-top: 5px; }
        .form-hint { font-size: .73rem; color: var(--text-3); margin-top: 5px; }
        .form-section-divider {
            font-family: 'Syne', sans-serif; font-size: .65rem;
            font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            color: var(--text-3); padding: 18px 0 10px;
            border-top: 1px solid var(--border); margin-top: 8px;
        }

        /* ───── TIMELINE ───── */
        .timeline { display: flex; flex-direction: column; gap: 0; }
        .tl-step { display: flex; align-items: flex-start; gap: 12px; }
        .tl-dot-wrap { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; }
        .tl-dot {
            width: 24px; height: 24px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .6rem; font-weight: 800; flex-shrink: 0;
        }
        .tl-dot.done { background: var(--brand); color: #fff; }
        .tl-dot.active { background: var(--brand-dim); border: 2px solid var(--brand); color: var(--brand); }
        .tl-dot.pending { background: var(--surface-3); color: var(--text-3); border: 1px solid var(--border-2); }
        .tl-line { width: 1px; flex: 1; min-height: 20px; background: var(--border-2); margin: 3px 0; }
        .tl-line.done { background: var(--brand); opacity: .4; }
        .tl-content { padding-bottom: 20px; flex: 1; }
        .tl-label { font-size: .82rem; font-weight: 500; color: var(--text-2); padding-top: 3px; }
        .tl-label.done { color: var(--text); }
        .tl-label.active { color: var(--text); font-weight: 700; }

        /* ───── TOASTR OVERRIDES ───── */
        #toast-container > div {
            border-radius: 10px !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: .85rem !important;
            box-shadow: 0 8px 32px rgba(0,0,0,.4) !important;
            border: 1px solid rgba(255,255,255,.08) !important;
        }
        .toast-success { background: #052e16 !important; }
        .toast-error   { background: #2d0a0a !important; }
        .toast-info    { background: #0c1a3a !important; }
        .toast-warning { background: #1c1205 !important; }

        /* ───── DETAIL FIELDS ───── */
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 32px; }
        .detail-item .detail-label {
            font-size: .63rem; text-transform: uppercase; letter-spacing: 1.2px;
            font-weight: 700; color: var(--text-3); margin-bottom: 5px;
        }
        .detail-item .detail-val { font-size: .88rem; color: var(--text); line-height: 1.5; }
        .detail-note {
            background: var(--surface-2); border: 1px solid var(--border);
            border-radius: 9px; padding: 12px 14px;
            font-size: .85rem; color: var(--text-2); line-height: 1.6;
        }

        /* ───── FILTER STRIP ───── */
        .filter-strip {
            display: flex; gap: 10px; align-items: flex-end;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 14px; padding: 18px 20px; margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-field { display: flex; flex-direction: column; gap: 5px; }
        .filter-actions { display: flex; gap: 8px; align-items: flex-end; }

        /* ───── PAGINATION ───── */
        .pagination-wrap {
            display: flex; justify-content: flex-end; align-items: center;
            padding: 14px 20px; border-top: 1px solid var(--border);
            gap: 8px;
        }
        .pagination-wrap nav { display: flex; align-items: center; gap: 4px; }
        .pagination-wrap .pagination {
            display: flex; list-style: none; gap: 4px;
        }
        .pagination-wrap .pagination li > * {
            display: flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 8px;
            font-size: .78rem; font-family: 'Syne', sans-serif;
            color: var(--text-2); text-decoration: none;
            background: var(--surface-2); border: 1px solid var(--border);
            transition: all .15s;
        }
        .pagination-wrap .pagination li > *:hover { color: var(--text); border-color: var(--border-2); }
        .pagination-wrap .pagination li.active > * {
            background: var(--brand); color: #fff; border-color: transparent;
        }
        .pagination-wrap .pagination li.disabled > * { opacity: .3; pointer-events: none; }

        /* ───── EMPTY STATE ───── */
        .empty-state {
            text-align: center; padding: 60px 20px; color: var(--text-3);
        }
        .empty-state .empty-icon { font-size: 2rem; opacity: .3; margin-bottom: 12px; }
        .empty-state p { font-size: .85rem; }

        /* ───── QUICK ACTIONS ───── */
        .quick-action {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 14px; border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surface-2); text-decoration: none;
            transition: all .15s; color: var(--text);
        }
        .quick-action:hover {
            background: var(--surface-3); border-color: var(--border-2);
            color: var(--text);
        }
        .quick-action-icon {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; flex-shrink: 0;
        }
        .quick-action-label { font-size: .82rem; font-weight: 500; }
        .quick-action-sub { font-size: .7rem; color: var(--text-3); }

        /* ───── RESPONSIVE ───── */
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s ease; }
            .sidebar.open { transform: translateX(0); box-shadow: 24px 0 60px rgba(0,0,0,.6); }
            .main-content { margin-left: 0; }
            .topbar { left: 0; }
            .hamburger { display: flex; }
            .page-body { padding: 24px 20px; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- ── SIDEBAR ───────────────────────────────── -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 3L3 10.5l7.5 3L18 6l-7.5 7.5 3 7.5L21 3z"/></svg>
        </div>
        <div>
            <div class="logo-text">Halcón</div>
            <div class="logo-sub">Distribuidora</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group">
            <div class="nav-label">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
                Dashboard
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-label">Pedidos</div>
            <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.index') || (request()->routeIs('orders.*') && !request()->routeIs('orders.archived') && !request()->routeIs('orders.create')) ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-box"></i></span>
                Todos los pedidos
            </a>
            @can('create', App\Models\Order::class)
            <a href="{{ route('orders.create') }}" class="nav-item {{ request()->routeIs('orders.create') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-regular fa-square-plus"></i></span>
                Nuevo pedido
            </a>
            @endcan
            <a href="{{ route('orders.archived') }}" class="nav-item {{ request()->routeIs('orders.archived') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-regular fa-folder"></i></span>
                Archivados
            </a>
        </div>

        @if(auth()->user()->isAdmin())
        <div class="nav-group">
            <div class="nav-label">Admin</div>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-regular fa-user"></i></span>
                Usuarios
            </a>
        </div>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-row">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div>
                <div class="user-name-sm">{{ auth()->user()->name }}</div>
                <div class="user-role-sm">{{ auth()->user()->role->name ?? '—' }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="Cerrar sesión">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- ── TOPBAR ─────────────────────────────────── -->
<header class="topbar">
    <button class="hamburger" onclick="document.getElementById('sidebar').classList.toggle('open')">
        <i class="fa-solid fa-bars"></i>
    </button>
    <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
    <div class="topbar-right">
        <span class="topbar-date">{{ now()->locale('es')->isoFormat('D MMM YYYY') }}</span>
    </div>
</header>

<!-- ── MAIN ──────────────────────────────────── -->
<main class="main-content">
    <div class="page-body">
        @yield('content')
    </div>
</main>

<!-- overlay for mobile -->
<div id="sidebar-overlay" onclick="document.getElementById('sidebar').classList.remove('open');this.style.display='none'"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:199;"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-right', timeOut:4000 };
    @if(session('success')) toastr.success("{{ session('success') }}"); @endif
    @if(session('error'))   toastr.error("{{ session('error') }}");     @endif
    @if(session('info'))    toastr.info("{{ session('info') }}");       @endif
    @if(session('warning')) toastr.warning("{{ session('warning') }}"); @endif

    document.getElementById('sidebar').addEventListener('transitionend', function() {
        var overlay = document.getElementById('sidebar-overlay');
        overlay.style.display = this.classList.contains('open') ? 'block' : 'none';
    });
</script>
@stack('scripts')
</body>
</html>
