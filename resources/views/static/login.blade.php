<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
  <main class="page">
    <section class="auth">
      <header class="auth__header">
        <h1 class="auth__title">¡Quiero ingresar a mi cuenta! <span>🧑‍💻</span></h1>
        <p class="auth__subtitle">
          Puedes ingresar con tu cuenta o crear una nueva para iniciar a explorar nuestra web
        </p>
      </header>

      <!-- Botón Google -->
      <button class="btn btn--google" type="button" aria-label="Accede con Google">
        <span class="g-logo" aria-hidden="true">
          <!-- Google SVG -->
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true">
            <path d="M23.5 12.3c0-.86-.08-1.48-.24-2.12H12v4h6.52c-.13 1.02-.83 2.56-2.39 3.59l-.02.15 3.47 2.69.24.02c2.23-2.06 3.68-5.1 3.68-8.33Z" fill="#4285F4" />
            <path d="M12 24c3.33 0 6.13-1.1 8.17-3l-3.89-3c-1.06.73-2.49 1.24-4.28 1.24-3.28 0-6.07-2.2-7.06-5.17l-.15.01-4.02 3.1-.05.14C2.79 21.17 7.04 24 12 24Z" fill="#34A853" />
            <path d="M4.94 14.07A7.99 7.99 0 0 1 4.5 12c0-.72.12-1.43.32-2.07l-.01-.14L.75 6.64.64 6.78A11.96 11.96 0 0 0 0 12c0 1.92.47 3.72 1.3 5.3l3.64-3.23Z" fill="#FBBC05" />
            <path d="M12 4.75c2.31 0 3.86 1 4.75 1.83l3.47-3.39C18.11 1.2 15.33 0 12 0 7.04 0 2.79 2.83 1.3 6.7l3.52 3.23C5.81 6.97 8.72 4.75 12 4.75Z" fill="#EA4335" />
          </svg>
        </span>
        Accede con Google
      </button>

      <!-- Separador -->
      <div class="divider" role="separator" aria-label="o">
        <span class="divider__line" aria-hidden="true"></span>
        <span class="divider__dot" aria-hidden="true"></span>
        <span class="divider__line" aria-hidden="true"></span>
      </div>

      <!-- Formulario -->
      <form class="form" action="#" method="post" novalidate>
        <label class="field">
          <span class="field__label">Correo electrónico</span>
          <input class="input" type="email" name="email" placeholder="Escribe tu dirección de correo" autocomplete="email" />
        </label>

        <label class="field">
          <span class="field__label">Contraseña</span>
          <div class="input input--with-icon">
            <input type="password" name="password" placeholder="Elige una contraseña" autocomplete="current-password" />
            <button class="input__icon" type="button" aria-label="Mostrar/Ocultar contraseña">
              <!-- Icono ojo -->
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true">
                <path d="M12 5C6.5 5 2.1 8.6 1 12c1.1 3.4 5.5 7 11 7s9.9-3.6 11-7c-1.1-3.4-5.5-7-11-7Z"
                  stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                <circle cx="12" cy="12" r="3.25" stroke="currentColor" stroke-width="1.6" />
              </svg>
            </button>
          </div>
        </label>

        <button class="btn btn--primary" type="submit">Usa tu correo electrónico</button>
      </form>

      <!-- Barra inferior -->
      <footer class="auth__footer">
        <div class="auth__footer-left">
          <span>¿Aún no tienes cuenta?</span>
          <a class="pill-link" href="#" aria-label="Regístrate">Regístrate</a>
        </div>
        <nav class="auth__footer-right" aria-label="Legal">
          <a href="#">Privacidad</a>
          <a href="#">Términos</a>
        </nav>
      </footer>
    </section>
  </main>
</body>
</html>
