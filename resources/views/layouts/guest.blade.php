<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Medly') }}</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Estilos de autentificación -->
  <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
</head>
<body class="guest-body">
  <div class="guest-viewport">

    <main class="guest-card">
      @yield('content')
    </main>

    <footer class="guest-footer" style="text-align:center; width:100%; margin-top:18px; color:#6b6b6b;">
    © {{ date('Y') }} {{ config('app.name') }}
    </footer>
  </div>
</body>
</html>