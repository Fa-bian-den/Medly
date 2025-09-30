@extends('layouts.app')

@section('content')
<link rel="stylesheet"  href="{{ asset('css/perfil.css') }}">

<style>
/* Layout tarjetas (override puntual) */
.cards { display:flex; gap:16px; flex-wrap:wrap; margin-bottom:24px; align-items:flex-start; }
.cards > .card { flex:1 1 30%; min-width:240px; box-sizing:border-box; width:auto !important; height:auto !important; margin:0 !important; padding:16px !important; display:block !important; }

/* Contenido interno de la card */
.card__header { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:10px; position:relative; z-index:2; }
.card__title { font-size:14px; font-weight:700; margin:0; color:var(--ink); }

/* Asegura que el body de la card tenga espacio y no quede oculto */
.card__content { display:block; position:relative; z-index:1; color:var(--muted, #6b6b6b); }

/* Tipografías y separaciones explícitas para evitar colapso visual */
.card__heading { font-weight:600; margin:0 0 6px; color:var(--ink); }
.card__meta { margin:0; font-size:13px; color:#6b7280; line-height:1.3; display:block; }

/* Si algún estilo previo aplicó overflow/height, aseguramos visibilidad */
.card, .card__content, .card__header { overflow:visible !important; }

/* Mobile: apilar */
@media (max-width:900px) {
  .cards > .card { flex:1 1 100%; max-width:100%; }
}
</style>

<main class="main dashboard-root" aria-label="Panel de control">
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
        <div class="card__icon" aria-hidden="true"><i data-feather="user"></i></div>
      </header>
      <div class="card__content">
        <p class="card__heading">Doctor Cardozo</p>
        <p class="card__meta">Sala de pediatría</p>
      </div>
    </article>

    <article class="card" aria-labelledby="card-turno">
      <header class="card__header">
        <h2 id="card-turno" class="card__title">Turno</h2>
        <div class="card__icon" aria-hidden="true"><i data-feather="list"></i></div>
      </header>
      <div class="card__content">
        <p class="card__heading">Turno #15</p>
        <p class="card__meta">11/09/25</p>
      </div>
    </article>
  </section>

  <section class="history" aria-label="Historial de citas">
    <div class="history-frame">
      <header class="history-frame__header">
        <h2 class="history-frame__title">Historial de citas</h2>
        <button type="button" class="history-frame__filter" aria-haspopup="listbox" aria-expanded="false">
          Hoy <span aria-hidden="true">▾</span>
        </button>
      </header>

      <div class="history-frame__tablewrap" role="region" aria-label="Tabla de historial">
        <table class="table">
          <caption class="sr-only">Historial de citas</caption>
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
              <td>Clínica Santa Maria</td>
              <td>Privado</td>
              <td>15/12/25</td>
              <td>20</td>
            </tr>
            <tr>
              <td>Dr. Martinez</td>
              <td>Centro Médico</td>
              <td>Público</td>
              <td>05/01/26</td>
              <td>10</td>
            </tr>
            <tr>
              <td>Dr. Johnson</td>
              <td>Hospital General</td>
              <td>Privado</td>
              <td>20/02/26</td>
              <td>25</td>
            </tr>
            <tr>
              <td>Technician Lee</td>
              <td>Laboratorio Clínico</td>
              <td>Público</td>
              <td>12/03/26</td>
              <td>30</td>
            </tr>
          </tbody>
        </table>
      </div>

      <nav class="pagination" aria-label="Paginación">
        <button type="button" class="page-btn" aria-label="Anterior">‹</button>
        <button type="button" class="page-btn is-active">1</button>
        <button type="button" class="page-btn">2</button>
        <button type="button" class="page-btn">3</button>
        <button type="button" class="page-btn" aria-label="Siguiente">›</button>
      </nav>
    </div>
  </section>
</main>

<script src="https://unpkg.com/feather-icons" defer></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    feather.replace();
  });
</script>
@endsection