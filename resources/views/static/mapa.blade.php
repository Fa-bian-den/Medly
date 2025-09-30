{{-- resources/views/static/mapa.blade.php --}}
@extends('layouts.app')

@section('content')
<link rel="stylesheet"  href="{{ asset('css/mapa.css') }}">
<main class="main">
  <div class="app-panel">

    <!-- Contenido principal -->
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
      <!-- Si tenés elementos SVG adicionales, inclúyelos aquí dentro del mismo contenedor SVG según tu diseño -->
    </section>

  </div>
</main>
@endsection