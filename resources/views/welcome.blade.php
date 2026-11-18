@extends('layouts.institucional')

@section('title', 'Portal institucional')

@section('navigation')
    @auth
        <a class="header-link" href="{{ route('dashboard') }}">Panel administrativo <span aria-hidden="true">→</span></a>
    @else
        <a class="header-link" href="{{ route('login') }}">Acceso institucional <span aria-hidden="true">→</span></a>
    @endauth
@endsection

@section('main-class', 'portal-main')

@section('content')
    <section class="portal-hero" aria-labelledby="portal-title">
        <div class="hero-content">
            <p class="eyebrow">Portal institucional</p>
            <h1 id="portal-title">Gestión de Residencias</h1>
            <p class="hero-description">Unidad Académica Trópico</p>
            @auth
                <a class="button button-gold" href="{{ route('dashboard') }}">Ir al panel <span aria-hidden="true">→</span></a>
            @else
                <a class="button button-gold" href="{{ route('login') }}">Iniciar sesión <span aria-hidden="true">→</span></a>
            @endauth
        </div>
    </section>
@endsection
