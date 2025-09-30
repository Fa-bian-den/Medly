<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ config('app.name', 'Medly') }}</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Estilos (public/css/login.css) -->
  <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
</head>

<body class="page">
  <header style="width:100%; max-width:1100px; margin:0 auto; padding:16px 24px; box-sizing:border-box;">
    @if (Route::has('login'))
    @endif
  </header>

  <main style="display:grid; place-items:center; min-height:calc(100vh - 80px); padding:24px;">
    <section class="auth" style="width:100%; max-width:1100px; display:flex; gap:28px; align-items:stretch; box-sizing:border-box;">

      <!-- Right: visual + actions (login-like card) -->
      <aside class="auth__right" style="width:380px; min-width:280px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <div style="width:100%; background:var(--btn-google-bg); border-radius:12px; padding:28px; box-shadow:var(--shadow); display:flex; flex-direction:column; align-items:center; gap:16px;">

          <!-- Logo -->
          <div style="width:180px; max-width:70%;">
            <img src="{{ asset('img/Logo.svg') }}" alt="{{ config('app.name', 'Logo') }}" style="width:100%; height:auto; display:block;" />
          </div>

          <h2 style="margin:0; font-size:20px; font-weight:700;">Hola — Bienvenido</h2>
          <p style="margin:0; color:var(--muted); text-align:center;">Accede a tu cuenta para gestionar citas y ver tu historial.</p>

          <!-- Google button -->
          <a href="{{ route('google.redirect') }}" class="btn btn--google" role="button" aria-label="Accede con Google" style="width:100%; max-width:320px;">
            <span class="g-logo" aria-hidden="true">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true">
                <path d="M23.5 12.3c0-.86-.08-1.48-.24-2.12H12v4h6.52c-.13 1.02-.83 2.56-2.39 3.59l-.02.15 3.47 2.69.24.02c2.23-2.06 3.68-5.1 3.68-8.33Z" fill="#4285F4" />
                <path d="M12 24c3.33 0 6.13-1.1 8.17-3l-3.89-3c-1.06.73-2.49 1.24-4.28 1.24-3.28 0-6.07-2.2-7.06-5.17l-.15.01-4.02 3.1-.05.14C2.79 21.17 7.04 24 12 24Z" fill="#34A853" />
                <path d="M4.94 14.07A7.99 7.99 0 0 1 4.5 12c0-.72.12-1.43.32-2.07l-.01-.14L.75 6.64.64 6.78A11.96 11.96 0 0 0 0 12c0 1.92.47 3.72 1.3 5.3l3.64-3.23Z" fill="#FBBC05" />
                <path d="M12 4.75c2.31 0 3.86 1 4.75 1.83l3.47-3.39C18.11 1.2 15.33 0 12 0 7.04 0 2.79 2.83 1.3 6.7l3.52 3.23C5.81 6.97 8.72 4.75 12 4.75Z" fill="#EA4335" />
              </svg>
            </span>
            Accede con Google
          </a>

          <div class="divider" role="separator" aria-label="o" style="width:100%; max-width:320px;"></div>

          <a href="{{ route('login') }}" class="btn btn--primary" style="width:100%; max-width:320px; text-align:center;">Iniciar sesión con correo</a>

          <div style="width:100%; max-width:320px; display:flex; justify-content:space-between; align-items:center; margin-top:8px;">
            <a href="{{ route('password.request') }}" class="link--muted">¿Olvidaste tu contraseña?</a>
            <a href="{{ route('register') }}" class="pill-link">Regístrate</a>
          </div>
        </div>
      </aside>

    </section>
  </main>
</body>
</html>
