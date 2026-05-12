@extends('layouts.guest')
@section('title', 'Iniciar Sesión')

@push('styles')
<style>
    .login-wrap {
        min-height: calc(100vh - 64px);
        display: flex; align-items: center; justify-content: center;
        padding: 60px 20px;
    }
    .login-box { width: 100%; max-width: 380px; }

    .login-eyebrow {
        font-size: .68rem; font-weight: 700; letter-spacing: 2.5px;
        text-transform: uppercase; color: var(--brand);
        margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
    }
    .login-eyebrow::before, .login-eyebrow::after {
        content: ''; flex: 1; height: 1px; background: var(--border-2);
    }
    .login-heading {
        text-align: center; margin-bottom: 36px;
    }
    .login-heading h1 {
        font-size: 1.9rem; font-weight: 800; letter-spacing: -.4px;
        color: var(--text); line-height: 1.1;
    }
    .login-heading p {
        font-size: .82rem; color: var(--text-3); margin-top: 8px;
    }

    .login-card {
        background: var(--surface);
        border: 1px solid var(--border-2);
        border-radius: 16px; padding: 30px 28px;
    }

    .field { margin-bottom: 18px; }
    .field label {
        display: block; font-family: 'Syne', sans-serif;
        font-size: .65rem; font-weight: 700; letter-spacing: 1.5px;
        text-transform: uppercase; color: var(--text-3); margin-bottom: 8px;
    }
    .field input {
        width: 100%; padding: 11px 14px;
        background: var(--surface-2); border: 1px solid var(--border-2);
        border-radius: 10px; color: var(--text);
        font-family: 'DM Sans', sans-serif; font-size: .88rem;
        outline: none; transition: border-color .15s, background .15s;
    }
    .field input::placeholder { color: var(--text-3); }
    .field input:focus { border-color: var(--brand); background: var(--surface-3, #202020); }
    .field-error { font-size: .73rem; color: #F87171; margin-top: 5px; }

    .error-banner {
        background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.2);
        border-radius: 10px; padding: 11px 14px;
        margin-bottom: 22px; color: #FCA5A5; font-size: .82rem;
        display: flex; align-items: center; gap: 8px;
    }

    .btn-submit {
        width: 100%; background: var(--brand); color: #fff;
        border: none; border-radius: 10px; padding: 12px;
        font-family: 'Syne', sans-serif; font-weight: 700;
        font-size: .88rem; cursor: pointer;
        transition: background .15s; margin-top: 6px;
    }
    .btn-submit:hover { background: #c4470b; }

    .back-link {
        display: block; text-align: center; margin-top: 22px;
        font-size: .78rem; color: var(--text-3); text-decoration: none;
        transition: color .15s;
    }
    .back-link:hover { color: var(--text-2); }
</style>
@endpush

@section('content')
<div class="login-wrap">
    <div class="login-box">
        <div class="login-heading">
            <div class="login-eyebrow">Acceso Interno</div>
            <h1>Bienvenido<br>de regreso</h1>
            <p>Ingresa tus credenciales de empleado</p>
        </div>

        <div class="login-card">
            @if ($errors->any())
            <div class="error-banner">
                <i class="fa-solid fa-circle-exclamation" style="flex-shrink:0"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="empleado@halcon.mx"
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required autofocus>
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="••••••••"
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                    @error('password') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn-submit">Ingresar al sistema</button>
            </form>
        </div>

        <a href="{{ route('home') }}" class="back-link">
            <i class="fa-solid fa-arrow-left" style="margin-right:5px"></i> Rastreo público
        </a>
    </div>
</div>
@endsection
