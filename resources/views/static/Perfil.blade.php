<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Medly — Perfil</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">  {{-- antes: styles.css --}}
  <link rel="stylesheet" href="{{ asset('css/cita.css') }}">    {{-- frame + calendario --}}
</head>
<body>
  <div class="app">
    <!-- ===== Sidebar ===== -->
    <aside class="sidenav" aria-label="Navegación principal">
      <header class="sidenav__header">
        <a href="#" class="brand" aria-label="Medly">
          <img src="{{ asset('img/logo.svg') }}" alt="Logo Medly" class="brand__logo logo-full">
        </a>
        <button class="icon-btn" aria-label="Abrir menú">
          <i data-feather="menu"></i>
        </button>
      </header>

      <nav class="sidenav__nav">
        <ul class="navlist" role="list">
          <li><a class="navlink" href="#"><i data-feather="sliders"></i><span>Panel de control</span></a></li>
          <li><a class="navlink" href="#"><i data-feather="bookmark"></i><span>Agendar cita</span></a></li>
          <li><a class="navlink" href="#"><i data-feather="calendar"></i><span>Mis citas</span></a></li>
          <li><a class="navlink" href="#"><i data-feather="map-pin"></i><span>Mapa de hospitales</span></a></li>
          <li><a class="navlink" href="#"><i data-feather="file"></i><span>Historial médico</span></a></li>
          <li><a class="navlink is-active" href="#" aria-current="page"><i data-feather="user"></i><span>Perfil</span></a></li>
        </ul>
      </nav>
    </aside>

    <!-- ===== Contenido ===== -->
    <main class="content">
      <section class="card">
        <!-- Columna izquierda -->
        <section class="left">
          <header class="intro">
            <h1>Compártenos los detalles de tu cita</h1>
            <p>Cuéntanos un poco sobre tu consulta para agendar tu cita rápidamente y que todo esté listo para cuando vengas al hospital.</p>
          </header>

          <form class="form" action="#" method="post">
            <div class="field">
              <label for="tipo">¿Cuál es el tipo de consulta que necesitas?</label>
              <input id="tipo" name="tipo" type="text" placeholder="Tipo de consulta" />
            </div>

            <div class="field">
              <label for="motivo">¿Cuál es el motivo de tu consulta?</label>
              <textarea id="motivo" name="motivo" placeholder="Motivo de la cita"></textarea>
            </div>

            <div class="field">
              <label for="condicion">¿Tienes alguna condición médica que debamos considerar? (Opcional)</label>
              <input id="condicion" name="condicion" type="text" placeholder="Indícanos su condición médica" />
            </div>
          </form>
        </section>

        <!-- Columna derecha (calendario) -->
        <aside class="right" aria-label="Selector de fecha">
          <header class="cal__header">
            <button class="nav-btn" type="button" aria-label="Mes anterior"><i data-feather="chevron-left"></i></button>
            <div class="month">
              <span class="month__label">Marzo 1998</span>
              <i data-feather="chevron-down" class="chev"></i>
            </div>
            <button class="nav-btn" type="button" aria-label="Mes siguiente"><i data-feather="chevron-right"></i></button>
          </header>

          <div class="cal" role="grid" aria-label="Calendario mensual">
            <div class="cal__dow">L</div>
            <div class="cal__dow">M</div>
            <div class="cal__dow">M</div>
            <div class="cal__dow">J</div>
            <div class="cal__dow">V</div>
            <div class="cal__dow">S</div>
            <div class="cal__dow">D</div>

            <!-- semana 1 -->
            <button class="day" type="button">01</button>
            <button class="day is-selected" type="button" aria-current="date">02</button>
            <button class="day" type="button">03</button>
            <button class="day" type="button">04</button>
            <button class="day" type="button">05</button>
            <button class="day" type="button">06</button>
            <button class="day" type="button">07</button>

            <!-- semana 2 -->
            <button class="day" type="button">08</button>
            <button class="day" type="button">09</button>
            <button class="day" type="button">10</button>
            <button class="day" type="button">11</button>
            <button class="day" type="button">12</button>
            <button class="day" type="button">13</button>
            <button class="day" type="button">14</button>

            <!-- semana 3 -->
            <button class="day" type="button">15</button>
            <button class="day" type="button">16</button>
            <button class="day" type="button">17</button>
            <button class="day" type="button">18</button>
            <button class="day" type="button">19</button>
            <button class="day" type="button">20</button>
            <button class="day" type="button">21</button>

            <!-- semana 4 -->
            <button class="day" type="button">22</button>
            <button class="day" type="button">23</button>
            <button class="day" type="button">24</button>
            <button class="day" type="button">25</button>
            <button class="day" type="button">26</button>
            <button class="day" type="button">27</button>
            <button class="day" type="button">28</button>

            <!-- semana 5 -->
            <button class="day" type="button">29</button>
            <button class="day" type="button">30</button>
            <button class="day" type="button">31</button>
            <button class="day is-faded" type="button" disabled>01</button>
            <button class="day is-faded" type="button" disabled>02</button>
            <button class="day is-faded" type="button" disabled>03</button>
            <button class="day is-faded" type="button" disabled>04</button>
          </div>
        </aside>

        <!-- Botón inferior del frame -->
        <div class="card-actions">
          <button class="btn-primary" type="button">Continuar</button>
        </div>
      </section>
    </main>
  </div>

  <!-- Feather icons -->
  <script src="https://unpkg.com/feather-icons"></script>
  <script>feather.replace();</script>
</body>
</html>
