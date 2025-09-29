<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Directorio de Salud</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/mapa.css') }}">

  <!-- Feather Icons -->
  <script defer src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <div class="app">

    <!-- Sidebar -->
    <aside class="sidebar-mini" aria-label="Navegación principal">
      <!-- Logo en la parte superior -->
      <a href="#" class="logo-btn" aria-label="Inicio">
        <img src="{{ asset('img/logo.svg') }}" alt="Logo" class="logo-img">
      </a>

      <!-- Nav vertical -->
      <nav class="side-nav" aria-label="Secciones">
        <a class="nav-ico is-active" href="#" aria-current="page" title="Panel">
          <i data-feather="sliders"></i>
        </a>
        <a class="nav-ico" href="#" title="Guardados">
          <i data-feather="bookmark"></i>
        </a>
        <a class="nav-ico" href="#" title="Calendario">
          <i data-feather="calendar"></i>
        </a>
        <a class="nav-ico nav-ico--highlight" href="#" title="Mapa">
          <i data-feather="map-pin"></i>
        </a>
        <a class="nav-ico" href="#" title="Archivos">
          <i data-feather="file-text"></i>
        </a>
        <a class="nav-ico" href="#" title="Perfil">
          <i data-feather="user"></i>
        </a>
      </nav>
    </aside>

    <!-- Contenido -->
    <main class="main">
      <section class="panel">
        <!-- Buscador y filtros -->
        <header class="panel__header">
          <form class="search" role="search" aria-label="Buscar establecimientos">
            <i class="search__icon" data-feather="search"></i>
            <input class="search__input" type="search" placeholder="Buscar" aria-label="Buscar" />
          </form>

          <ul class="filters" role="list">
            <li><button class="pill pill--active" type="button">Públicos <i data-feather="x"></i></button></li>
            <li><button class="pill" type="button">Privados</button></li>
            <li><button class="pill" type="button">Hospitales</button></li>
            <li><button class="pill" type="button">Centro de salud</button></li>
          </ul>
        </header>

        <!-- Listado por municipio -->
        <section class="block">
          <h2 class="block__title">Municipio de Estelí</h2>

          <ul class="cards" role="list">
            <!-- Card 1 -->
            <li class="card">
              <figure class="card__media">
                <img src="{{ asset('img/img3.svg') }}" alt="Fachada del centro San Juan de Dios">
              </figure>
              <div class="card__body">
                <h3 class="card__title">San Juan de Dios</h3>
                <p class="card__meta">
                  <span class="badge badge--open">Abierto</span>
                  <span class="dot"></span>
                  <time>09:30 - 06:30</time>
                </p>
              </div>
            </li>

            <!-- Card 2 -->
            <li class="card">
              <figure class="card__media">
                <img src="https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=1200&q=60" alt="Hospital Adventista">
              </figure>
              <div class="card__body">
                <h3 class="card__title">Hospital Adventista</h3>
                <p class="card__meta">
                  <span class="badge badge--open">Abierto</span>
                  <span class="dot"></span>
                  <time>09:30 - 06:30</time>
                </p>
              </div>
            </li>
          </ul>
        </section>

        <section class="block">
          <h2 class="block__title">Municipio de Managua</h2>

          <ul class="cards" role="list">
            <li class="card">
              <figure class="card__media">
                <img src="{{ asset('img/img2.svg') }}" alt="Hospital Salud Integral">
              </figure>
              <div class="card__body">
                <h3 class="card__title">Hospital Salud Integral</h3>
                <p class="card__meta">
                  <span class="badge badge--open">Abierto</span>
                  <span class="dot"></span>
                  <time>09:30 - 06:30</time>
                </p>
              </div>
            </li>

            <li class="card">
              <figure class="card__media">
                <img src="{{ asset('img/img1.svg') }}" alt="Hospital Vivian Pellas">
              </figure>
              <div class="card__body">
                <h3 class="card__title">Hospital Vivian Pellas</h3>
                <p class="card__meta">
                  <span class="badge badge--open">Abierto</span>
                  <span class="dot"></span>
                  <time>09:30 - 06:30</time>
                </p>
              </div>
            </li>
          </ul>
        </section>
      </section>

      <!-- Mapa -->
      <section class="map" aria-label="Mapa de Nicaragua">
        <img src="{{ asset('img/mapa.svg') }}" alt="Mapa de Nicaragua" class="map__img">
      </section>
    </main>
  </div>

  <script>
    // Renderiza los íconos de Feather
    window.addEventListener('DOMContentLoaded', () => feather.replace());
  </script>
</body>
</html>
