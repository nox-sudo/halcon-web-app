@extends('layouts.app')
@section('title', isset($order) ? 'Editar ' . $order->invoice_number : 'Nuevo Pedido')
@section('page-title', isset($order) ? 'Editar Pedido' : 'Nuevo Pedido')

@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($order) ? 'Editar pedido' : 'Nuevo pedido' }}</h1>
        <div class="sub">
            <a href="{{ route('orders.index') }}">Pedidos</a>
            <span style="color:var(--text-3)">/</span>
            {{ isset($order) ? $order->invoice_number : 'Crear' }}
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 280px;gap:14px;align-items:start">

    {{-- FORM --}}
    <div class="card">
        <div class="card-head">
            <div class="card-head-icon"><i class="fa-solid fa-file-invoice"></i></div>
            <span class="card-head-title">Datos del pedido</span>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="{{ isset($order) ? route('orders.update', $order) : route('orders.store') }}">
                @csrf
                @if(isset($order)) @method('PUT') @endif

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div>
                        <label class="form-label">N° Factura *</label>
                        <input type="text" name="invoice_number"
                               class="form-input {{ $errors->has('invoice_number') ? 'is-invalid' : '' }}"
                               value="{{ old('invoice_number', $order->invoice_number ?? '') }}"
                               placeholder="FAC-2024-001" required>
                        @error('invoice_number')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label">N° Cliente *</label>
                        <input type="text" name="customer_number"
                               class="form-input {{ $errors->has('customer_number') ? 'is-invalid' : '' }}"
                               value="{{ old('customer_number', $order->customer_number ?? '') }}"
                               placeholder="CLI-0042" required>
                        @error('customer_number')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div style="grid-column:1/-1">
                        <label class="form-label">Nombre / Razón Social *</label>
                        <input type="text" name="customer_name"
                               class="form-input {{ $errors->has('customer_name') ? 'is-invalid' : '' }}"
                               value="{{ old('customer_name', $order->customer_name ?? '') }}"
                               placeholder="Constructora Ejemplo S.A. de C.V." required>
                    </div>

                    <div style="grid-column:1/-1">
                        <label class="form-label">Domicilio de Entrega *</label>
                        <input type="text" name="delivery_address"
                               class="form-input"
                               value="{{ old('delivery_address', $order->delivery_address ?? '') }}"
                               placeholder="Calle, Número, Colonia, Ciudad" required>
                    </div>

                    <div style="grid-column:1/-1">
                        <div class="form-section-divider">Datos fiscales</div>
                    </div>

                    <div>
                        <label class="form-label">RFC</label>
                        <input type="text" name="rfc" class="form-input"
                               value="{{ old('rfc', $order->rfc ?? '') }}"
                               placeholder="CONS800101ABC">
                    </div>
                    <div>
                        <label class="form-label">Régimen Fiscal</label>
                        <input type="text" name="fiscal_regime" class="form-input"
                               value="{{ old('fiscal_regime', $order->fiscal_regime ?? '') }}"
                               placeholder="Persona Moral">
                    </div>
                    <div style="grid-column:1/-1">
                        <label class="form-label">Domicilio Fiscal</label>
                        <input type="text" name="fiscal_address" class="form-input"
                               value="{{ old('fiscal_address', $order->fiscal_address ?? '') }}"
                               placeholder="Calle, Número, Colonia, CP, Ciudad">
                    </div>

                    <div style="grid-column:1/-1">
                        <label class="form-label">Notas / Observaciones</label>
                        <textarea name="notes" class="form-input" rows="3"
                                  placeholder="Información adicional sobre el pedido...">{{ old('notes', $order->notes ?? '') }}</textarea>
                    </div>

                    <div style="grid-column:1/-1;display:flex;gap:8px;justify-content:flex-end;padding-top:8px;border-top:1px solid var(--border)">
                        <a href="{{ route('orders.index') }}" class="btn btn-ghost">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ isset($order) ? 'Guardar cambios' : 'Registrar pedido' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SIDEBAR INFO --}}
    <div class="card">
        <div class="card-head">
            <div class="card-head-icon"><i class="fa-solid fa-circle-info"></i></div>
            <span class="card-head-title">{{ isset($order) ? 'Detalles' : 'Información' }}</span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:16px">
            @if(isset($order))
                <div>
                    <div class="detail-label" style="font-size:.63rem;text-transform:uppercase;letter-spacing:1.2px;font-weight:700;color:var(--text-3);margin-bottom:6px">Estado actual</div>
                    @php
                        $map = ['ordered'=>['badge-ordered','Pedido'],'in_process'=>['badge-process','En proceso'],'in_route'=>['badge-route','En ruta'],'delivered'=>['badge-delivered','Entregado']];
                        [$cls,$bl] = $map[$order->status] ?? ['badge-archived','—'];
                    @endphp
                    <span class="badge {{ $cls }}">{{ $bl }}</span>
                </div>
                <div>
                    <div class="detail-label" style="font-size:.63rem;text-transform:uppercase;letter-spacing:1.2px;font-weight:700;color:var(--text-3);margin-bottom:4px">Registrado por</div>
                    <div style="font-size:.82rem;color:var(--text)">{{ $order->creator->name ?? '—' }}</div>
                </div>
                <div>
                    <div class="detail-label" style="font-size:.63rem;text-transform:uppercase;letter-spacing:1.2px;font-weight:700;color:var(--text-3);margin-bottom:4px">Fecha de creación</div>
                    <div style="font-size:.82rem;color:var(--text)">{{ $order->created_at->format('d M Y · H:i') }}</div>
                </div>
            @else
                <div style="font-size:.8rem;color:var(--text-2);display:flex;flex-direction:column;gap:10px;line-height:1.6">
                    <div style="display:flex;gap:8px;align-items:flex-start">
                        <i class="fa-solid fa-circle" style="font-size:.35rem;margin-top:7px;color:var(--brand);flex-shrink:0"></i>
                        El número de factura debe ser único y consecutivo.
                    </div>
                    <div style="display:flex;gap:8px;align-items:flex-start">
                        <i class="fa-solid fa-circle" style="font-size:.35rem;margin-top:7px;color:var(--brand);flex-shrink:0"></i>
                        El número de cliente es asignado por Ventas.
                    </div>
                    <div style="display:flex;gap:8px;align-items:flex-start">
                        <i class="fa-solid fa-circle" style="font-size:.35rem;margin-top:7px;color:var(--brand);flex-shrink:0"></i>
                        El estado inicial será <strong style="color:var(--text)">Pedido Registrado</strong>.
                    </div>
                    <div style="display:flex;gap:8px;align-items:flex-start">
                        <i class="fa-solid fa-circle" style="font-size:.35rem;margin-top:7px;color:var(--brand);flex-shrink:0"></i>
                        Será visible para todos los empleados al guardarse.
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
