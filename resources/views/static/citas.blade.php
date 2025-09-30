<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Medly – Mis citas</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=DM+Mono:wght@500&display=swap" rel="stylesheet">

  <!-- Estilos (coloca tu CSS en public/css/citas.css) -->
  <link rel="stylesheet" href="{{ asset('css/citas.css') }}">
</head>
<body>
  <!-- ===== SIDEBAR ===== -->
  <aside class="sidenav" aria-label="Navegación principal">
    <header class="sidenav__header">
      <a href="#" class="brand" aria-label="Inicio Medly">
        <img src="{{ asset('img/logo.svg') }}" alt="Medly" class="brand__logo">
      </a>
      <button class="icon-btn" aria-label="Abrir menú">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M3 6h18v2H3V6Zm0 5h18v2H3v-2Zm0 5h18v2H3v-2Z"/>
        </svg>
      </button>
    </header>

    <nav class="sidenav__nav" aria-label="Secciones">
      <ul class="menu" role="list">
        <li>
          <a class="menu__link" href="#">
            <span class="menu__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M7 6h2v12H7V6Zm4 3h2v9h-2V9Zm4-2h2v11h-2V7Z"/></svg>
            </span>
            <span class="menu__text">Panel de control</span>
          </a>
        </li>
        <li>
          <a class="menu__link" href="#">
            <span class="menu__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M6 2h12v20l-6-4-6 4V2Z"/></svg>
            </span>
            <span class="menu__text">Agendar cita</span>
          </a>
        </li>
        <li>
          <a class="menu__link is-active" href="#" aria-current="page">
            <span class="menu__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M7 2h2v2h6V2h2v2h3v18H4V4h3V2Zm13 8H4v10h16V10Z"/></svg>
            </span>
            <span class="menu__text">Mis citas</span>
          </a>
        </li>
        <li>
          <a class="menu__link" href="#">
            <span class="menu__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7Zm0 9a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>
            </span>
            <span class="menu__text">Mapa de hospitales</span>
          </a>
        </li>
        <li>
          <a class="menu__link" href="#">
            <span class="menu__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M6 2h9l5
