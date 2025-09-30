<!doctype html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Medly') }}</title>

  <!-- estilos del panel -->
  <link rel="stylesheet" href="{{ asset('css/panel.css') }}" />
</head>
<body class="app-body">
  <div class="app-root">
    {{-- Sidebar siempre presente --}}
    @include('layouts.sidebar')

    {{-- Contenido principal (dashboard u otras vistas) --}}
    <main class="main-content with-sidebar">
      @yield('content')
    </main>
  </div>

  <!-- Feather icons -->
  <script src="https://unpkg.com/feather-icons"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Renderizar icons Feather
      if (window.feather && typeof window.feather.replace === 'function') {
        window.feather.replace();
      }

      // Toggle global para abrir/cerrar la sidenav (usa body.sidenav-open)
      document.querySelectorAll('.icon-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          document.body.classList.toggle('sidenav-open');
        });
      });

      // Perfil dropdown (botón dentro del sidebar)
      var profileBtn = document.querySelector('.navlink--profile');
      var profileMenu = document.getElementById('profile-menu');

      if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function (e) {
          e.preventDefault();
          var isOpen = profileMenu.classList.toggle('show');
          profileBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
          profileMenu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        });

        // Cerrar submenú al hacer click fuera
        document.addEventListener('click', function (e) {
          if (! profileBtn.contains(e.target) && ! profileMenu.contains(e.target)) {
            profileMenu.classList.remove('show');
            profileBtn.setAttribute('aria-expanded', 'false');
            profileMenu.setAttribute('aria-hidden', 'true');
          }
        });

        // Cerrar con Escape
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape') {
            profileMenu.classList.remove('show');
            profileBtn.setAttribute('aria-expanded', 'false');
            profileMenu.setAttribute('aria-hidden', 'true');
          }
        });
      }
    });
  </script>
</body>
</html>