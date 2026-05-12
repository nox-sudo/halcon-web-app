@extends('layouts.app')
@section('title', 'Archivados')
@section('page-title', 'Pedidos Archivados')

@section('content')
<div class="page-header">
    <div>
        <h1>Archivados</h1>
        <div class="sub">Pedidos archivados — restaurables</div>
    </div>
    <a href="{{ route('orders.index') }}" class="btn btn-ghost">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body-flush">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Factura</th>
                    <th>Cliente</th>
                    <th>Pedido</th>
                    <th>Archivado</th>
                    <th>Estado previo</th>
                    <th style="text-align:right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr style="opacity:.65">
                    <td class="tbl-mono" style="font-weight:600">{{ $order->invoice_number }}</td>
                    <td>
                        <div style="font-size:.82rem;font-weight:500">{{ $order->customer_name }}</div>
                        <div style="font-size:.72rem;color:var(--text-3)">{{ $order->customer_number }}</div>
                    </td>
                    <td style="font-size:.75rem;color:var(--text-3)">{{ $order->created_at->format('d M Y') }}</td>
                    <td style="font-size:.75rem;color:var(--text-3)">{{ $order->deleted_at->format('d M Y') }}</td>
                    <td>
                        @php
                            $map = ['ordered'=>['badge-ordered','Pedido'],'in_process'=>['badge-process','En proceso'],'in_route'=>['badge-route','En ruta'],'delivered'=>['badge-delivered','Entregado']];
                            [$cls,$lbl] = $map[$order->status] ?? ['badge-archived',$order->status];
                        @endphp
                        <span class="badge {{ $cls }}">{{ $lbl }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-ghost btn-sm btn-icon" title="Ver">
                                <i class="fa-solid fa-eye" style="font-size:.7rem"></i>
                            </a>
                            @can('restore', $order)
                            <form method="POST" action="{{ route('orders.restore', $order) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success-ghost btn-sm btn-icon" title="Restaurar">
                                    <i class="fa-solid fa-rotate-left" style="font-size:.7rem"></i>
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
                            <div class="empty-icon"><i class="fa-regular fa-folder"></i></div>
                            <p>No hay pedidos archivados.</p>
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
