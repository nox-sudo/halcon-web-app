@extends('layouts.app')
@section('title', isset($user) ? 'Editar Usuario' : 'Nuevo Usuario')
@section('page-title', isset($user) ? 'Editar Usuario' : 'Nuevo Usuario')

@section('content')
<div class="page-header">
    <div>
        <h1>{{ isset($user) ? 'Editar usuario' : 'Nuevo usuario' }}</h1>
        <div class="sub">
            <a href="{{ route('users.index') }}">Usuarios</a>
            <span style="color:var(--text-3)">/</span>
            {{ isset($user) ? $user->name : 'Crear' }}
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:14px;align-items:start">

    {{-- FORM --}}
    <div class="card">
        <div class="card-head">
            <div class="card-head-icon"><i class="fa-solid fa-user-gear"></i></div>
            <span class="card-head-title">Datos del usuario</span>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
                @csrf
                @if(isset($user)) @method('PUT') @endif

                <div style="display:flex;flex-direction:column;gap:16px">
                    <div>
                        <label class="form-label">Nombre Completo *</label>
                        <input type="text" name="name"
                               class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $user->name ?? '') }}"
                               placeholder="Juan García López" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div>
                            <label class="form-label">Correo Electrónico *</label>
                            <input type="email" name="email"
                                   class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   value="{{ old('email', $user->email ?? '') }}"
                                   placeholder="juan@halcon.mx" required>
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Departamento / Rol *</label>
                            <select name="role_id" class="form-input {{ $errors->has('role_id') ? 'is-invalid' : '' }}" required>
                                <option value="">Seleccionar…</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('role_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div>
                            <label class="form-label">Contraseña {{ isset($user) ? '(vacío = sin cambio)' : '*' }}</label>
                            <input type="password" name="password"
                                   class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Mínimo 8 caracteres"
                                   {{ isset($user) ? '' : 'required' }}>
                            @error('password')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation"
                                   class="form-input" placeholder="Repetir contraseña"
                                   {{ isset($user) ? '' : 'required' }}>
                        </div>
                    </div>

                    @if(isset($user))
                    <div>
                        <label class="form-label">Estado</label>
                        <div style="display:flex;gap:20px;margin-top:4px">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.83rem;color:var(--text-2)">
                                <input type="radio" name="is_active" value="1"
                                       {{ old('is_active', $user->is_active) == 1 ? 'checked' : '' }}>
                                Activo
                            </label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.83rem;color:var(--text-2)">
                                <input type="radio" name="is_active" value="0"
                                       {{ old('is_active', $user->is_active) == 0 ? 'checked' : '' }}>
                                Inactivo
                            </label>
                        </div>
                    </div>
                    @endif

                    <div style="display:flex;gap:8px;justify-content:flex-end;padding-top:8px;border-top:1px solid var(--border)">
                        <a href="{{ route('users.index') }}" class="btn btn-ghost">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            {{ isset($user) ? 'Guardar cambios' : 'Crear usuario' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ROLES INFO --}}
    <div class="card">
        <div class="card-head">
            <div class="card-head-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <span class="card-head-title">Roles y permisos</span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:0">
            @php
                $rolesInfo = [
                    ['Administrador', 'fa-crown',        '#C084FC', 'rgba(168,85,247,.12)',  'Acceso total, gestión de usuarios y roles.'],
                    ['Ventas',        'fa-handshake',    '#60A5FA', 'rgba(59,130,246,.12)',  'Registra y edita pedidos de clientes.'],
                    ['Compras',       'fa-cart-shopping','#FCD34D', 'rgba(245,158,11,.12)',  'Gestiona adquisición de materiales faltantes.'],
                    ['Almacén',       'fa-warehouse',    '#4ADE80', 'rgba(34,197,94,.12)',   'Avanza estados En Proceso y En Ruta.'],
                    ['Ruta',          'fa-truck',        '#F9A8D4', 'rgba(244,114,182,.12)', 'Entrega pedidos y sube fotos de evidencia.'],
                ];
            @endphp
            @foreach($rolesInfo as $i => [$name, $icon, $color, $bg, $desc])
            <div style="display:flex;gap:12px;padding:14px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--border)' : '' }}">
                <div style="width:32px;height:32px;border-radius:8px;background:{{ $bg }};color:{{ $color }};display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <div>
                    <div style="font-family:'Syne',sans-serif;font-size:.82rem;font-weight:700;color:var(--text);margin-bottom:3px">{{ $name }}</div>
                    <div style="font-size:.75rem;color:var(--text-3);line-height:1.5">{{ $desc }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
