@extends('layouts.app')
@section('title', 'Pedidos')
@section('page-title', 'Pedidos')

@section('content')
<div class="page-header">
    <div>
        <h1>Pedidos</h1>
        <div class="sub">Lista general de pedidos activos</div>
    </div>
    @can('create', App\Models\Order::class)
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Nuevo pedido
    </a>
    @endcan
</div>

{{-- FILTERS --}}
<form method="GET" action="{{ route('orders.index') }}" class="filter-strip">
    <div class="filter-field">
        <label class="form-label">Factura</label>
        <input type="text" name="factura" class="form-input" style="width:160px"
               placeholder="FAC-2024-001" value="{{ request('factura') }}">
    </div>
    <div class="filter-field">
        <label class="form-label">N° Cliente</label>
        <input type="text" name="cliente" class="form-input" style="width:130px"
               placeholder="CLI-0042" value="{{ request('cliente') }}">
    </div>
    <div class="filter-field">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-input" style="width:150px" value="{{ request('fecha') }}">
    </div>
    <div class="filter-field">
        <label class="form-label">Estado</label>
        <select name="status" class="form-input" style="width:140px">
            <option value="">Todos</option>
            <option value="ordered"    {{ request('status') === 'ordered'    ? 'selected' : '' }}>Pedido</option>
            <option value="in_process" {{ request('status') === 'in_process' ? 'selected' : '' }}>En proceso</option>
            <option value="in_route"   {{ request('status') === 'in_route'   ? 'selected' : '' }}>En ruta</option>
            <option value="delivered"  {{ request('status') === 'delivered'  ? 'selected' : '' }}>Entregado</option>
        </select>
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
        @if(request()->hasAny(['factura','cliente','fecha','status']))
        <a href="{{ route('orders.index') }}" class="btn btn-ghost">
            <i class="fa-solid fa-xmark"></i>
        </a>
        @endif
    </div>
</form>

{{-- TABLE --}}
<div class="card">
    <div class="card-body-flush">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Factura</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th style="text-align:right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="tbl-mono" style="font-weight:600">{{ $order->invoice_number }}</td>
                    <td>
                        <div style="font-weight:500;font-size:.82rem">{{ $order->customer_name }}</div>
                        <div style="font-size:.72rem;color:var(--text-3)">{{ $order->customer_number }}</div>
                    </td>
                    <td class="tbl-muted" style="font-size:.78rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $order->delivery_address }}
                    </td>
                    <td style="font-size:.75rem;color:var(--text-3)">
                        {{ $order->created_at->format('d M Y') }}<br>
                        <span style="font-size:.68rem">{{ $order->created_at->format('H:i') }}</span>
                    </td>
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
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-ghost btn-sm btn-icon" title="Ver">
                                <i class="fa-solid fa-eye" style="font-size:.7rem"></i>
                            </a>
                            @can('update', $order)
                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-ghost btn-sm btn-icon" title="Editar">
                                <i class="fa-solid fa-pen" style="font-size:.7rem"></i>
                            </a>
                            @endcan
                            @can('delete', $order)
                            <form method="POST" action="{{ route('orders.destroy', $order) }}"
                                  onsubmit="return confirm('¿Archivar este pedido?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger-ghost btn-sm btn-icon" title="Archivar">
                                    <i class="fa-regular fa-folder" style="font-size:.7rem"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fa-solid fa-box-open"></i></div>
                            <p>No se encontraron pedidos con los filtros actuales.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="pagination-wrap">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
