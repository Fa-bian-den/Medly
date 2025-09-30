<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Olvidé mi contraseña</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Estilos (public/css/login.css) -->
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
  <main class="page">
    <section class="auth" aria-labelledby="forgot-title">
      <header class="auth__header">
        <h1 id="forgot-title" class="auth__title">¿Olvidaste tu contraseña? <span>🔐</span></h1>
        <p class="auth__subtitle">
          Indica el correo asociado a tu cuenta y te enviaremos un enlace para restablecerla.
        </p>
      </header>

      <div class="divider" role="separator" aria-label="o" style="margin-bottom: 8px;">
        <span class="divider__line" aria-hidden="true"></span>
        <span class="divider__dot" aria-hidden="true"></span>
        <span class="divider__line" aria-hidden="true"></span>
      </div>

      <form class="form" method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        @if (session('status'))
          <div class="field" role="status" style="margin-bottom:12px;">
            <div class="pill-link" style="display:inline-block; background:#eaf6ea; border-color:#d6efd0; color:#155724;">
              {{ session('status') }}
            </div>
          </div>
        @endif

        <label class="field">
          <span class="field__label">Correo electrónico</span>
          <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Escribe tu dirección de correo" required autofocus autocomplete="email" />
          @if($errors->has('email'))
            <p class="field__error" role="alert">{{ $errors->first('email') }}</p>
          @endif
        </label>

        <div class="form__meta" style="margin-top:6px; margin-bottom:6px;">
          <a class="link--muted" href="{{ route('login') }}">¿Recordaste tu contraseña? Iniciar sesión</a>
        </div>

        <button class="btn btn--primary" type="submit">Enviar enlace de restablecimiento</button>
      </form>

      <footer class="auth__footer" aria-label="Navegación secundaria">
        <div class="auth__footer-left">
          <span>¿Aún no tienes cuenta?</span>
          <a class="pill-link" href="{{ route('register') }}" aria-label="Regístrate">Regístrate</a>
        </div>
        <nav class="auth__footer-right" aria-label="Enlaces">
          <a href="#">Privacidad</a>
          <a href="#">Términos</a>
        </nav>
      </footer>
    </section>
  </main>
</body>
</html>
