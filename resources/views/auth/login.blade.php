@extends('layouts.institucional')

@section('title', 'Acceso institucional')

@section('navigation')
    <a class="header-link" href="{{ url('/') }}"><span aria-hidden="true">←</span> Volver al portal</a>
@endsection

@section('main-class', 'login-main')

@section('content')
        <section class="login-panel" aria-labelledby="login-title">
            <p class="eyebrow">Acceso institucional</p>
            <h1 id="login-title">Iniciar sesión</h1>
            <p class="login-description">Ingresa con tu cuenta de administrador.</p>

            @if (session('status'))
                <p class="login-status" role="status">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <div class="form-field">
                    <label for="email">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')
                        <p class="field-error" id="email-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    @error('password')
                        <p class="field-error" id="password-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-options">
                    <label for="remember_me" class="remember">
                        <input id="remember_me" type="checkbox" name="remember">
                        Recordarme
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                <button class="button button-blue" type="submit">Ingresar al sistema <span aria-hidden="true">→</span></button>
            </form>
        </section>
@endsection
