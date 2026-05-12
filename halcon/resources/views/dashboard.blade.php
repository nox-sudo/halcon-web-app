@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px;">
    <div class="stat-card c-orange">
        <div class="stat-lbl">Pedidos registrados</div>
        <div class="stat-num">{{ $stats['ordered'] ?? 0 }}</div>
        <i class="fa-solid fa-inbox stat-icon-bg"></i>
    </div>
    <div class="stat-card c-blue">
        <div class="stat-lbl">En proceso</div>
        <div class="stat-num">{{ $stats['in_process'] ?? 0 }}</div>
        <i class="fa-solid fa-gears stat-icon-bg"></i>
    </div>
    <div class="stat-card c-amber">
        <div class="stat-lbl">En ruta</div>
        <div class="stat-num">{{ $stats['in_route'] ?? 0 }}</div>
        <i class="fa-solid fa-truck stat-icon-bg"></i>
    </div>
    <div class="stat-card c-green">
        <div class="stat-lbl">Entregados</div>
        <div class="stat-num">{{ $stats['delivered'] ?? 0 }}</div>
        <i class="fa-solid fa-circle-check stat-icon-bg"></i>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 280px;gap:14px;align-items:start;">

    {{-- RECENT ORDERS --}}
    <div class="card">
        <div class="card-head">
            <div class="card-head-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <span class="card-head-title">Pedidos recientes</span>
            <a href="{{ route('orders.index') }}" class="btn btn-ghost btn-sm" style="margin-left:auto">
                Ver todos <i class="fa-solid fa-arrow-right" style="font-size:.65rem"></i>
            </a>
        </div>
        <div class="card-body-flush">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td class="tbl-mono">{{ $order->invoice_number }}</td>
                        <td>
                            <div style="font-size:.82rem;font-weight:500">{{ $order->customer_name }}</div>
                            <div style="font-size:.72rem;color:var(--text-3)">{{ $order->customer_number }}</div>
                        </td>
                        <td class="tbl-muted" style="font-size:.75rem">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            @php
                                $map = [
                                    'ordered'    => ['badge-ordered',  'Pedido'],
                                    'in_process' => ['badge-process',  'En proceso'],
                                    'in_route'   => ['badge-route',    'En ruta'],
                                    'delivered'  => ['badge-delivered','Entregado'],
                                ];
                                [$cls, $lbl] = $map[$order->status] ?? ['badge-archived', $order->status];
                            @endphp
                            <span class="badge {{ $cls }}">{{ $lbl }}</span>
                        </td>
                        <td style="text-align:right">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-ghost btn-sm btn-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:.65rem"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fa-solid fa-box-open"></i></div>
                                <p>No hay pedidos recientes.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="card">
        <div class="card-head">
            <div class="card-head-icon"><i class="fa-solid fa-bolt"></i></div>
            <span class="card-head-title">Acciones rápidas</span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
            @can('create', App\Models\Order::class)
            <a href="{{ route('orders.create') }}" class="quick-action">
                <div class="quick-action-icon" style="background:var(--brand-dim);color:var(--brand)">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div>
                    <div class="quick-action-label">Nuevo pedido</div>
                    <div class="quick-action-sub">Registrar pedido de cliente</div>
                </div>
            </a>
            @endcan

            <a href="{{ route('orders.index') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(59,130,246,.12);color:#60A5FA">
                    <i class="fa-solid fa-list"></i>
                </div>
                <div>
                    <div class="quick-action-label">Ver pedidos</div>
                    <div class="quick-action-sub">Lista general</div>
                </div>
            </a>

            @if(auth()->user()->isAdmin())
            <a href="{{ route('users.create') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(168,85,247,.12);color:#C084FC">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <div class="quick-action-label">Nuevo usuario</div>
                    <div class="quick-action-sub">Agregar empleado</div>
                </div>
            </a>
            <a href="{{ route('users.index') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(168,85,247,.12);color:#C084FC">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="quick-action-label">Usuarios</div>
                    <div class="quick-action-sub">Gestionar equipo</div>
                </div>
            </a>
            @endif

            <a href="{{ route('orders.archived') }}" class="quick-action">
                <div class="quick-action-icon" style="background:var(--surface-3);color:var(--text-3)">
                    <i class="fa-regular fa-folder"></i>
                </div>
                <div>
                    <div class="quick-action-label">Archivados</div>
                    <div class="quick-action-sub">Pedidos archivados</div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    @media (max-width: 900px) {
        .dash-grid { grid-template-columns: 1fr !important; }
        .stat-grid { grid-template-columns: repeat(2,1fr) !important; }
    }
</style>
@endsection
