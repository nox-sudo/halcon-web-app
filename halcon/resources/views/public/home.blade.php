@extends('layouts.guest')

@section('title', 'Rastreo de Pedido')

@push('styles')
<style>
    .hero {
        min-height: calc(100vh - 88px);
        display: flex; align-items: center; justify-content: center;
        padding: 40px 20px;
        position: relative; z-index: 5;
    }
    .hero-inner { max-width: 480px; width: 100%; }

    .hero-eyebrow {
        font-size: .75rem; letter-spacing: 3px; text-transform: uppercase;
        color: var(--brand-light); font-weight: 600; margin-bottom: 12px;
    }
    .hero-title {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 800; line-height: 1.1;
        margin-bottom: 12px;
    }
    .hero-sub {
        color: rgba(255,255,255,.5); font-size: .95rem;
        margin-bottom: 36px; line-height: 1.6;
    }

    .search-card {
        background: rgba(255,255,255,.06);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 16px;
        padding: 28px;
    }
    .search-card label {
        display: block; font-size: .75rem; font-weight: 700;
        letter-spacing: 1px; text-transform: uppercase;
        color: rgba(255,255,255,.5); margin-bottom: 6px;
    }
    .search-card input {
        width: 100%; background: rgba(255,255,255,.08);
        border: 1.5px solid rgba(255,255,255,.12);
        border-radius: 10px; padding: 12px 16px;
        color: #fff; font-family: 'DM Sans', sans-serif;
        font-size: .95rem; outline: none;
        transition: border-color .2s;
    }
    .search-card input::placeholder { color: rgba(255,255,255,.3); }
    .search-card input:focus { border-color: var(--brand-light); }

    .btn-search {
        width: 100%; background: var(--brand); color: #fff;
        border: none; border-radius: 10px; padding: 13px;
        font-family: 'Syne', sans-serif; font-weight: 700;
        font-size: 1rem; cursor: pointer; margin-top: 20px;
        transition: background .2s; display: flex;
        align-items: center; justify-content: center; gap: 8px;
    }
    .btn-search:hover { background: var(--brand-dark); }

    /* Result card */
    .result-card {
        margin-top: 24px;
        background: rgba(255,255,255,.06);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 16px; padding: 28px;
    }
    .result-label {
        font-size: .7rem; letter-spacing: 2px; text-transform: uppercase;
        color: rgba(255,255,255,.4); margin-bottom: 4px;
    }
    .result-value { font-size: 1.05rem; color: #fff; margin-bottom: 16px; }

    .status-pill {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 16px; border-radius: 30px;
        font-family: 'Syne', sans-serif; font-weight: 700;
        font-size: .85rem;
    }
    .status-pill .dot { width: 8px; height: 8px; border-radius: 50%; }

    .s-ordered   { background: rgba(251,191,36,.15); color: #FCD34D; }
    .s-ordered .dot { background: #FCD34D; }
    .s-process   { background: rgba(59,130,246,.15); color: #93C5FD; }
    .s-process .dot { background: #93C5FD; }
    .s-route     { background: rgba(167,139,250,.15); color: #C4B5FD; }
    .s-route .dot { background: #C4B5FD; }
    .s-delivered { background: rgba(52,211,153,.15); color: #6EE7B7; }
    .s-delivered .dot { background: #6EE7B7; }

    .evidence-img {
        margin-top: 20px; border-radius: 12px;
        overflow: hidden; border: 1px solid rgba(255,255,255,.1);
    }
    .evidence-img img { width: 100%; display: block; }
    .evidence-caption {
        padding: 10px 14px; font-size: .75rem;
        color: rgba(255,255,255,.4);
        background: rgba(0,0,0,.2);
    }

    .error-msg {
        background: rgba(239,68,68,.15);
        border: 1px solid rgba(239,68,68,.3);
        border-radius: 10px; padding: 14px 18px;
        color: #FCA5A5; font-size: .875rem; margin-top: 16px;
        display: flex; align-items: center; gap: 10px;
    }

    .timeline {
        margin-top: 20px; padding-top: 16px;
        border-top: 1px solid rgba(255,255,255,.08);
    }
    .tl-item {
        display: flex; gap: 12px; margin-bottom: 12px;
    }
    .tl-dot {
        width: 10px; height: 10px; border-radius: 50%;
        background: rgba(255,255,255,.2); flex-shrink: 0;
        margin-top: 4px;
    }
    .tl-dot.active { background: var(--brand-light); }
    .tl-text { font-size: .82rem; color: rgba(255,255,255,.5); }
    .tl-text strong { color: rgba(255,255,255,.8); display: block; }
</style>
@endpush

@section('content')
<section class="hero">
    <div class="hero-inner">
        <div class="hero-eyebrow"><i class="fa-solid fa-location-dot me-1"></i> Rastreo en tiempo real</div>
        <h1 class="hero-title">¿Dónde está<br>tu pedido?</h1>
        <p class="hero-sub">Ingresa tu número de cliente y número de factura para consultar el estado de tu orden.</p>

        <div class="search-card">
            <form method="GET" action="{{ route('home') }}">
                <div class="mb-3">
                    <label>Número de Cliente</label>
                    <input type="text" name="cliente" placeholder="Ej. CLI-0042"
                           value="{{ request('cliente') }}" required>
                </div>
                <div>
                    <label>Número de Factura</label>
                    <input type="text" name="factura" placeholder="Ej. FAC-2024-001"
                           value="{{ request('factura') }}" required>
                </div>
                <button type="submit" class="btn-search">
                    <i class="fa-solid fa-magnifying-glass"></i> Consultar Pedido
                </button>
            </form>
        </div>

        {{-- RESULTADO --}}
        @if(isset($order))
            <div class="result-card">
                <div class="result-label">Factura</div>
                <div class="result-value">{{ $order->invoice_number }}</div>

                <div class="result-label">Cliente</div>
                <div class="result-value">{{ $order->customer_name }}</div>

                <div class="result-label">Estado actual</div>
                <div class="mb-3">
                    @php
                        $statusMap = [
                            'ordered'   => ['class' => 's-ordered',   'label' => 'Pedido Registrado'],
                            'in_process'=> ['class' => 's-process',   'label' => 'En Proceso'],
                            'in_route'  => ['class' => 's-route',     'label' => 'En Ruta'],
                            'delivered' => ['class' => 's-delivered', 'label' => 'Entregado'],
                        ];
                        $s = $statusMap[$order->status] ?? ['class' => '', 'label' => $order->status];
                    @endphp
                    <span class="status-pill {{ $s['class'] }}">
                        <span class="dot"></span> {{ $s['label'] }}
                    </span>
                </div>

                {{-- ① EN PROCESO: mostrar nombre del estado y fecha explícitamente --}}
                @if($order->status === 'in_process')
                    <div style="background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.25);border-radius:12px;padding:16px;margin-bottom:16px">
                        <div style="font-size:.65rem;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:6px">
                            Estado del pedido
                        </div>
                        <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;color:#93C5FD;margin-bottom:8px">
                            <i class="fa-solid fa-gears me-2"></i>En Proceso
                        </div>
                        <div style="font-size:.82rem;color:rgba(255,255,255,.5)">
                            <i class="fa-regular fa-calendar me-1"></i>
                            Actualizado el {{ $order->updated_at->format('d \d\e F \d\e Y') }}
                            a las {{ $order->updated_at->format('H:i') }} hrs
                        </div>
                        <div style="font-size:.8rem;color:rgba(255,255,255,.4);margin-top:8px;line-height:1.5">
                            Tu pedido está siendo preparado en nuestro almacén.<br>
                            Pronto estará listo para salir a ruta.
                        </div>
                    </div>
                @endif

                {{-- ② EN RUTA: mostrar nombre y fecha --}}
                @if($order->status === 'in_route')
                    <div style="background:rgba(167,139,250,.12);border:1px solid rgba(167,139,250,.25);border-radius:12px;padding:16px;margin-bottom:16px">
                        <div style="font-size:.65rem;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:6px">
                            Estado del pedido
                        </div>
                        <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;color:#C4B5FD;margin-bottom:8px">
                            <i class="fa-solid fa-truck me-2"></i>En Ruta
                        </div>
                        <div style="font-size:.82rem;color:rgba(255,255,255,.5)">
                            <i class="fa-regular fa-calendar me-1"></i>
                            Salió a ruta el {{ $order->updated_at->format('d \d\e F \d\e Y') }}
                            a las {{ $order->updated_at->format('H:i') }} hrs
                        </div>
                        <div style="font-size:.8rem;color:rgba(255,255,255,.4);margin-top:8px">
                            Tu pedido está en camino a tu domicilio.
                        </div>
                    </div>
                @endif

                {{-- ③ ENTREGADO: mostrar foto de evidencia --}}
                @if($order->status === 'delivered')
                    <div style="background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.2);border-radius:12px;padding:16px;margin-bottom:16px">
                        <div style="font-size:.65rem;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:6px">
                            Estado del pedido
                        </div>
                        <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;color:#6EE7B7;margin-bottom:8px">
                            <i class="fa-solid fa-circle-check me-2"></i>Entregado
                        </div>
                        <div style="font-size:.82rem;color:rgba(255,255,255,.5)">
                            <i class="fa-regular fa-calendar me-1"></i>
                            Entregado el {{ $order->updated_at->format('d \d\e F \d\e Y') }}
                            a las {{ $order->updated_at->format('H:i') }} hrs
                        </div>
                    </div>

                    @if($order->delivery_photo)
                        <div style="margin-bottom:16px">
                            <div style="font-size:.7rem;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:8px">
                                <i class="fa-solid fa-camera me-1"></i> Foto de Evidencia de Entrega
                            </div>
                            <div class="evidence-img">
                                <img src="{{ Storage::url($order->delivery_photo) }}" alt="Evidencia de entrega">
                                <div class="evidence-caption">
                                    Foto tomada el {{ $order->updated_at->format('d M Y') }} — {{ $order->updated_at->format('H:i') }} hrs
                                </div>
                            </div>
                        </div>
                    @else
                        <div style="font-size:.8rem;color:rgba(255,255,255,.35);margin-bottom:16px">
                            <i class="fa-solid fa-image-slash me-1"></i> Foto de evidencia aún no disponible.
                        </div>
                    @endif
                @endif

                {{-- TIMELINE general --}}
                <div class="timeline">
                    @php
                        $steps = [
                            'ordered'    => 'Pedido Registrado',
                            'in_process' => 'En Proceso',
                            'in_route'   => 'En Ruta',
                            'delivered'  => 'Entregado',
                        ];
                    @endphp
                    @foreach($steps as $key => $label)
                        <div class="tl-item">
                            <div class="tl-dot {{ $order->statusReached($key) ? 'active' : '' }}"></div>
                            <div class="tl-text">
                                <strong>{{ $label }}</strong>
                                @if($order->status === $key)
                                    <span style="color:var(--brand-light);font-size:.72rem;"> ← actual</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif(request()->has('factura'))
            <div class="error-msg">
                <i class="fa-solid fa-circle-exclamation"></i>
                No encontramos ningún pedido con esos datos. Verifica tu número de cliente y factura.
            </div>
        @endif
    </div>
</section>
@endsection
