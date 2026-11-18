<nav class="module-nav" aria-label="Navegación principal">
    <div class="module-nav-inner">
        <a class="module-brand" href="{{ auth()->check() ? route('dashboard') : url('/') }}">
            <strong aria-hidden="true">EMI</strong>
            <span>Gestión de Residencias</span>
        </a>

        <div class="module-nav-actions">
            @auth
                <a href="{{ route('dashboard') }}">Volver al panel</a>
                <a href="{{ route('profile.edit') }}">Mi perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Cerrar sesión</button>
                </form>
            @else
                <a href="{{ route('login') }}">Iniciar sesión</a>
            @endauth
        </div>
    </div>
</nav>
