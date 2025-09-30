<aside class="sidenav" aria-label="Navegación principal">
  <header class="sidenav__header">
    <div class="brand" aria-label="{{ config('app.name') }}">
      <img src="{{ asset('img/Logo.svg') }}" alt="{{ config('app.name') }}" class="brand__logo logo-full" />
    </div>

    <button class="icon-btn" aria-label="Abrir menú" onclick="document.body.classList.toggle('sidenav-open')">
      <i data-feather="menu"></i>
    </button>
  </header>

  <nav class="sidenav__nav" aria-label="Principal">
    <ul class="navlist" role="list">
      <li>
        <a class="navlink {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ route('dashboard') }}" aria-current="{{ request()->routeIs('dashboard') ? 'page' : '' }}">
          <i data-feather="sliders"></i>
          <span>Panel de control</span>
        </a>
      </li>

      <li>
        <a class="navlink" href="#">
          <i data-feather="bookmark"></i>
          <span>Agendar cita</span>
        </a>
      </li>

      <li>
        <a class="navlink" href="#">
          <i data-feather="calendar"></i>
          <span>Mis citas</span>
        </a>
      </li>

      <li>
        <a class="navlink" href="{{ route('mapa') }}">
          <i data-feather="map-pin"></i>
          <span>Mapa de hospitales</span>
        </a>
      </li>

      <li>
        <a class="navlink" href="#">
          <i data-feather="file"></i>
          <span>Historial médico</span>
        </a>
      </li>

      <li class="nav-item--profile">
    <!-- Enlace visible del perfil (mantengo tu href tal cual) -->
    <button class="navlink navlink--profile" type="button" aria-expanded="false" aria-controls="profile-menu">
        <i data-feather="user"></i>
        <span>Perfil</span>
        <i data-feather="chevron-down" class="chevron-icon" aria-hidden="true"></i>
    </button>

    <!-- Menú desplegable (oculto por defecto) -->
        <ul id="profile-menu" class="nav-submenu" role="menu" aria-hidden="true">
            <li>
        <a class="navlink" href="{{ route('profile.edit') }}">
          <i data-feather="user"></i>
          <span>Mi perfil</span>
        </a>
      </li>


            <li role="none">
            <!-- Logout: formulario POST, disparado por enlace -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
            <a role="menuitem" class="navlink navlink--sub" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Cerrar sesión
            </a>
            </li>
        </ul>
    </li>

    </ul>
  </nav>
</aside>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (window.feather) { feather.replace(); }

    // mobile toggle uses body.sidenav-open
    document.querySelectorAll('.icon-btn').forEach(btn => {
      btn.addEventListener('click', () => document.body.classList.toggle('sidenav-open'));
    });
  });
</script>