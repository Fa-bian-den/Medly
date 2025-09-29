<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Medly — Panel de control</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <aside class="sidenav" aria-label="Navegación principal">
      <header class="sidenav__header">
        <a href="#" class="brand" aria-label="Medly">
          <img src="{{ asset('img/logo.svg') }}" alt="Logo Medly" class="brand__logo logo-full">
        </a>
        <button class="icon-btn" aria-label="Abrir menú"><i data-feather="menu"></i></button>
      </header>

      <nav class="sidenav__nav">
        <ul class="navlist" role="list">
          <li><a class="navlink is-active" href="#" aria-current="page"><i data-feather="sliders"></i><span>Panel de control</span></a></li>
          <li><a class="navlink" href="#"><i data-feather="bookmark"></i><span>Agendar cita</span></a></li>
          <li><a class="navlink" href="#"><i data-feather="calendar"></i><span>Mis citas</span></a></li>
          <li><a class="navlink" href="#"><i data-feather="map-pin"></i><span>Mapa de hospitales</span></a></li>
          <li><a class="navlink" href="#"><i data-feather="file"></i><span>Historial médico</span></a></li>
          <li><a class="navlink" href="#"><i data-feather="user"></i><span>Perfil</span></a></li>
        </ul>
      </nav>
    </aside>

    <!-- Contenido -->
    <main class="main" aria-label="Panel de citas">
      <!-- CARDS SUPERIORES -->
      <section class="cards" aria-label="Resumen de cita">
        <article class="card" aria-labelledby="card-proxima-cita">
          <header class="card__header">
            <h2 id="card-proxima-cita" class="card__title">Próxima cita</h2>
            <div class="card__icon" aria-hidden="true"><i data-feather="calendar"></i></div>
          </header>
          <div class="card__content">
            <p class="card__heading">Hospital San Juan de Dios</p>
            <p class="card__meta">11 de Septiembre – Turno #15</p>
          </div>
        </article>

        <article class="card" aria-labelledby="card-doctor">
          <header class="card__header">
            <h2 id="card-doctor" class="card__title">Doctor asignado</h2>
            <div class="card__icon" aria-hidden="true"><i data-feather="calendar"></i></div>
          </header>
          <div class="card__content">
            <p class="card__heading">Doctor Cardozo</p>
            <p class="card__meta">Sala de pediatría</p>
          </div>
        </article>

        <article class="card" aria-labelledby="card-turno">
          <header class="card__header">
            <h2 id="card-turno" class="card__title">Lista de turnos</h2>
            <div class="card__icon" aria-hidden="true"><i data-feather="calendar"></i></div>
          </header>
          <div class="card__content">
            <p class="card__heading">Turno #15</p>
            <p class="card__meta">11 de Septiembre – Turno #15</p>
          </div>
        </article>
      </section>

      <!-- HISTORIAL DE CITAS -->
      <section class="history" aria-label="Historial de cita">
        <div class="history-frame">
          <header class="history-frame__header">
            <h2 class="history-frame__title">Historial de cita</h2>
            <button type="button" class="history-frame__filter" aria-haspopup="listbox" aria-expanded="false">
              Hoy <span aria-hidden="true">▾</span>
            </button>
          </header>

          <div class="history-frame__tablewrap" role="region" aria-label="Tabla de historial">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Doctor</th>
                  <th scope="col">Centro Médico</th>
                  <th scope="col">Tipo</th>
                  <th scope="col">Fecha</th>
                  <th scope="col">Turno</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Doctor Cardozo</td>
                  <td>Hospital San Juan de Dios</td>
                  <td>Público</td>
                  <td>11/09/25</td>
                  <td>15</td>
                </tr>
                <tr>
                  <td>Nurse Smith</td>
                  <td>Clínica Santa María</td>
                  <td>Privado</td>
                  <td>12/15/25</td>
                  <td>20</td>
                </tr>
                <tr>
                  <td>Dr. Martínez</td>
                  <td>Centro Médico</td>
                  <td>Público</td>
                  <td>01/05/26</td>
                  <td>10</td>
                </tr>
                <tr>
                  <td>Dr. Johnson</td>
                  <td>Hospital General</td>
                  <td>Privado</td>
                  <td>02/20/26</td>
                  <td>25</td>
                </tr>
                <tr>
                  <td>Technician Lee</td>
                  <td>Laboratorio Clínico</td>
                  <td>Público</td>
                  <td>03/12/26</td>
                  <td>30</td>
                </tr>
              </tbody>
            </table>
          </div>

          <nav class="pagination" aria-label="Paginación">
            <button class="page-btn" aria-label="Anterior">‹</button>
            <button class="page-btn is-active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">4</button>
            <button class="page-btn">5</button>
            <button class="page-btn" aria-label="Siguiente">›</button>
          </nav>
        </div>
      </section>
    </main>
  </div>

  <!-- Feather icons -->
  <script src="https://unpkg.com/feather-icons"></script>
  <script>feather.replace();</script>
</body>
</html>
