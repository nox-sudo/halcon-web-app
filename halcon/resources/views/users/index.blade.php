@extends('layouts.app')
@section('title', 'Usuarios')
@section('page-title', 'Usuarios')

@section('content')
<div class="page-header">
    <div>
        <h1>Usuarios</h1>
        <div class="sub">Gestión de acceso del equipo</div>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Nuevo usuario
    </a>
</div>

<div class="card">
    <div class="card-body-flush">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Departamento</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th style="text-align:right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:32px;height:32px;border-radius:8px;background:var(--brand-dim);border:1px solid rgba(232,84,14,.2);display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;color:var(--brand);font-size:.72rem;flex-shrink:0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div style="font-size:.83rem;font-weight:500">{{ $user->name }}</div>
                        </div>
                    </td>
                    <td style="font-size:.78rem;color:var(--text-2)">{{ $user->email }}</td>
                    <td>
                        @php
                            $roleStyles = [
                                'admin'      => 'background:rgba(168,85,247,.12);color:#C084FC',
                                'sales'      => 'background:rgba(59,130,246,.12);color:#60A5FA',
                                'purchasing' => 'background:rgba(245,158,11,.12);color:#FCD34D',
                                'warehouse'  => 'background:rgba(34,197,94,.12);color:#4ADE80',
                                'route'      => 'background:rgba(244,114,182,.12);color:#F9A8D4',
                            ];
                            $style = $roleStyles[$user->role->slug ?? ''] ?? 'background:var(--surface-3);color:var(--text-3)';
                        @endphp
                        <span class="badge" style="{{ $style }};text-transform:none;letter-spacing:0;font-size:.73rem">
                            {{ $user->role->name ?? 'Sin rol' }}
                        </span>
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge badge-active">Activo</span>
                        @else
                            <span class="badge badge-inactive">Inactivo</span>
                        @endif
                    </td>
                    <td style="font-size:.75rem;color:var(--text-3)">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-ghost btn-sm btn-icon" title="Editar">
                                <i class="fa-solid fa-pen" style="font-size:.7rem"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.toggle', $user) }}">
                                @csrf @method('PATCH')
                                @if($user->is_active)
                                <button type="submit" class="btn btn-danger-ghost btn-sm btn-icon" title="Desactivar">
                                    <i class="fa-solid fa-user-slash" style="font-size:.7rem"></i>
                                </button>
                                @else
                                <button type="submit" class="btn btn-success-ghost btn-sm btn-icon" title="Activar">
                                    <i class="fa-solid fa-user-check" style="font-size:.7rem"></i>
                                </button>
                                @endif
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fa-solid fa-users"></i></div>
                            <p>No hay usuarios registrados.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="pagination-wrap">{{ $users->links() }}</div>
    @endif
</div>
@endsection
