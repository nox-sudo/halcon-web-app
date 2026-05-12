@extends('layouts.app')
@section('title', $order->invoice_number)
@section('page-title', 'Detalle de Pedido')

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $order->invoice_number }}</h1>
        <div class="sub">
            <a href="{{ route('orders.index') }}">Pedidos</a>
            <span style="color:var(--text-3)">/</span>
            {{ $order->invoice_number }}
        </div>
    </div>
    <div style="display:flex;gap:8px;align-items:center">
        @can('update', $order)
        <a href="{{ route('orders.edit', $order) }}" class="btn btn-ghost">
            <i class="fa-solid fa-pen"></i> Editar
        </a>
        @endcan
        @can('delete', $order)
        <form method="POST" action="{{ route('orders.destroy', $order) }}"
              onsubmit="return confirm('¿Archivar este pedido?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger-ghost">
                <i class="fa-regular fa-folder"></i> Archivar
            </button>
        </form>
        @endcan
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:14px;align-items:start">

    {{-- LEFT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:14px">

        {{-- Info card --}}
        <div class="card">
            <div class="card-head">
                <div class="card-head-icon"><i class="fa-solid fa-file-invoice"></i></div>
                <span class="card-head-title">Información del pedido</span>
            </div>
            <div class="card-body">
                <div class="detail-grid">
                    @php
                        $fields = [
                            ['N° Factura',        $order->invoice_number],
                            ['N° Cliente',        $order->customer_number],
                            ['Razón Social',      $order->customer_name],
                            ['Dirección de entrega', $order->delivery_address],
                            ['RFC',               $order->rfc ?: '—'],
                            ['Régimen Fiscal',    $order->fiscal_regime ?: '—'],
                            ['Domicilio Fiscal',  $order->fiscal_address ?: '—'],
                            ['Fecha del pedido',  $order->created_at->format('d M Y · H:i')],
                            ['Registrado por',    $order->creator->name ?? '—'],
                        ];
                    @endphp
                    @foreach($fields as [$label, $val])
                    <div class="detail-item">
                        <div class="detail-label">{{ $label }}</div>
                        <div class="detail-val">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>

                @if($order->notes)
                <div style="margin-top:22px">
                    <div class="detail-label" style="font-size:.63rem;text-transform:uppercase;letter-spacing:1.2px;font-weight:700;color:var(--text-3);margin-bottom:8px">Notas</div>
                    <div class="detail-note">{{ $order->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Photos --}}
        @if($order->route_photo || $order->delivery_photo)
        <div class="card">
            <div class="card-head">
                <div class="card-head-icon"><i class="fa-solid fa-camera"></i></div>
                <span class="card-head-title">Evidencias fotográficas</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    @if($order->route_photo)
                    <div>
                        <div class="detail-label" style="font-size:.63rem;text-transform:uppercase;letter-spacing:1.2px;font-weight:700;color:var(--text-3);margin-bottom:8px">Foto de carga</div>
                        <div style="border-radius:10px;overflow:hidden;border:1px solid var(--border)">
                            <img src="{{ Storage::url($order->route_photo) }}" style="width:100%;display:block">
                        </div>
                    </div>
                    @endif
                    @if($order->delivery_photo)
                    <div>
                        <div class="detail-label" style="font-size:.63rem;text-transform:uppercase;letter-spacing:1.2px;font-weight:700;color:var(--text-3);margin-bottom:8px">Foto de entrega</div>
                        <div style="border-radius:10px;overflow:hidden;border:1px solid var(--border)">
                            <img src="{{ Storage::url($order->delivery_photo) }}" style="width:100%;display:block">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:14px">

        {{-- Status --}}
        <div class="card">
            <div class="card-head">
                <div class="card-head-icon"><i class="fa-solid fa-signal"></i></div>
                <span class="card-head-title">Estado</span>
            </div>
            <div class="card-body">
                @php
                    $map = [
                        'ordered'    => ['badge-ordered',  'Pedido registrado'],
                        'in_process' => ['badge-process',  'En proceso'],
                        'in_route'   => ['badge-route',    'En ruta'],
                        'delivered'  => ['badge-delivered','Entregado'],
                    ];
                    [$cls, $lbl] = $map[$order->status] ?? ['badge-archived', $order->status];
                    $statuses    = ['ordered','in_process','in_route','delivered'];
                    $currentIdx  = array_search($order->status, $statuses);
                    $nextStatus  = ['ordered'=>'in_process','in_process'=>'in_route','in_route'=>'delivered'];
                    $nextLabels  = ['in_process'=>'Marcar En proceso','in_route'=>'Marcar En ruta','delivered'=>'Marcar Entregado'];
                @endphp

                <div style="margin-bottom:22px">
                    <span class="badge {{ $cls }}" style="font-size:.75rem;padding:5px 12px">{{ $lbl }}</span>
                </div>

                {{-- Timeline --}}
                <div class="timeline">
                    @foreach($statuses as $i => $s)
                        @php
                            $done   = $i < $currentIdx;
                            $active = $i === $currentIdx;
                            $pend   = $i > $currentIdx;
                            [,$stepLbl] = $map[$s];
                        @endphp
                        <div class="tl-step">
                            <div class="tl-dot-wrap">
                                <div class="tl-dot {{ $done ? 'done' : ($active ? 'active' : 'pending') }}">
                                    @if($done) <i class="fa-solid fa-check"></i> @else {{ $i+1 }} @endif
                                </div>
                                @if($i < 3)
                                <div class="tl-line {{ $done ? 'done' : '' }}"></div>
                                @endif
                            </div>
                            <div class="tl-content">
                                <div class="tl-label {{ $done ? 'done' : ($active ? 'active' : '') }}">{{ $stepLbl }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @can('changeStatus', $order)
                @if(isset($nextStatus[$order->status]))
                    @php $next = $nextStatus[$order->status]; @endphp
                    <form method="POST" action="{{ route('orders.status', $order) }}" style="margin-top:18px">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $next }}">
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                            <i class="fa-solid fa-arrow-right"></i> {{ $nextLabels[$next] }}
                        </button>
                    </form>
                @endif
                @endcan
            </div>
        </div>

        {{-- Photo upload --}}
        @if(auth()->user()->hasRole('route') || auth()->user()->isAdmin())
        @if(in_array($order->status, ['in_process','in_route','delivered']))
        <div class="card">
            <div class="card-head">
                <div class="card-head-icon"><i class="fa-solid fa-camera"></i></div>
                <span class="card-head-title">Subir fotografía</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('orders.photo', $order) }}" enctype="multipart/form-data">
                    @csrf @method('PATCH')
                    <div style="margin-bottom:14px">
                        <label class="form-label">
                            {{ $order->status === 'in_route' ? 'Foto de unidad cargada' : 'Foto de entrega' }}
                        </label>
                        <input type="file" name="photo" class="form-input" accept="image/*" required style="cursor:pointer">
                        <div class="form-hint">JPG, PNG o WEBP · Máx. 5 MB</div>
                    </div>
                    <input type="hidden" name="photo_type" value="{{ $order->status === 'in_route' ? 'route' : 'delivery' }}">
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        <i class="fa-solid fa-upload"></i> Subir foto
                    </button>
                </form>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
