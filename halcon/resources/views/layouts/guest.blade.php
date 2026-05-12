<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halcón — @yield('title', 'Rastreo de Pedido')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #080808;
            --surface:   #101010;
            --surface-2: #181818;
            --border:    rgba(255,255,255,.07);
            --border-2:  rgba(255,255,255,.13);
            --text:      #EFEFEF;
            --text-2:    rgba(239,239,239,.50);
            --text-3:    rgba(239,239,239,.28);
            --brand:     #E8540E;
            --brand-dim: rgba(232,84,14,.12);
        }

        html, body { height: 100%; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5 { font-family: 'Syne', sans-serif; }

        /* subtle noise texture effect */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 70% 60% at 85% 10%, rgba(232,84,14,.09) 0%, transparent 60%),
                radial-gradient(ellipse 50% 70% at 5% 90%, rgba(232,84,14,.06) 0%, transparent 60%);
            pointer-events: none; z-index: 0;
        }

        /* ── NAV ── */
        .pub-nav {
            position: fixed; top: 0; left: 0; right: 0;
            padding: 0 48px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(8,8,8,.85); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            z-index: 100;
        }
        .pub-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .pub-logo-icon {
            width: 28px; height: 28px; border-radius: 7px;
            background: var(--brand); display: flex; align-items: center;
            justify-content: center;
        }
        .pub-logo-icon svg { width: 16px; height: 16px; fill: #fff; }
        .pub-logo-text {
            font-family: 'Syne', sans-serif; font-size: .9rem;
            font-weight: 800; color: var(--text); letter-spacing: -.1px;
        }
        .pub-login {
            font-size: .78rem; color: var(--text-3);
            text-decoration: none; display: flex; align-items: center; gap: 6px;
            border: 1px solid var(--border-2); border-radius: 8px;
            padding: 6px 14px; transition: all .15s;
        }
        .pub-login:hover { color: var(--text); border-color: rgba(255,255,255,.22); }

        main { position: relative; z-index: 1; padding-top: 64px; }

        @stack('inner-styles')
    </style>
    @stack('styles')
</head>
<body>
    <nav class="pub-nav">
        <a href="{{ route('home') }}" class="pub-logo">
            <div class="pub-logo-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 3L3 10.5l7.5 3L18 6l-7.5 7.5 3 7.5L21 3z"/></svg>
            </div>
            <span class="pub-logo-text">Halcón</span>
        </a>
        <a href="{{ route('login') }}" class="pub-login">
            <i class="fa-solid fa-lock" style="font-size:.7rem"></i> Acceso Empleados
        </a>
    </nav>

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
