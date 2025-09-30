<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registro</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Estilos (public/css/login.css) -->
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
  <main class="page">
    <section class="auth" aria-labelledby="register-title">
      <header class="auth__header">
        <h1 id="register-title" class="auth__title">Crea tu cuenta <span>✳️</span></h1>
        <p class="auth__subtitle">
          Regístrate para gestionar citas, ver historial y usar la plataforma.
        </p>
      </header>

      <!-- Google OAuth (opcional) -->
      <a href="{{ route('google.redirect') }}" class="btn btn--google" role="button" aria-label="Registrar con Google" style="margin-bottom:12px;">
        <span class="g-logo" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true">
            <path d="M23.5 12.3c0-.86-.08-1.48-.24-2.12H12v4h6.52c-.13 1.02-.83 2.56-2.39 3.59l-.02.15 3.47 2.69.24.02c2.23-2.06 3.68-5.1 3.68-8.33Z" fill="#4285F4" />
            <path d="M12 24c3.33 0 6.13-1.1 8.17-3l-3.89-3c-1.06.73-2.49 1.24-4.28 1.24-3.28 0-6.07-2.2-7.06-5.17l-.15.01-4.02 3.1-.05.14C2.79 21.17 7.04 24 12 24Z" fill="#34A853" />
            <path d="M4.94 14.07A7.99 7.99 0 0 1 4.5 12c0-.72.12-1.43.32-2.07l-.01-.14L.75 6.64.64 6.78A11.96 11.96 0 0 0 0 12c0 1.92.47 3.72 1.3 5.3l3.64-3.23Z" fill="#FBBC05" />
            <path d="M12 4.75c2.31 0 3.86 1 4.75 1.83l3.47-3.39C18.11 1.2 15.33 0 12 0 7.04 0 2.79 2.83 1.3 6.7l3.52 3.23C5.81 6.97 8.72 4.75 12 4.75Z" fill="#EA4335" />
          </svg>
        </span>
        Regístrate con Google
      </a>

      <div class="divider" role="separator" aria-label="o" style="margin-bottom:8px;">
        <span class="divider__line" aria-hidden="true"></span>
        <span class="divider__dot" aria-hidden="true"></span>
        <span class="divider__line" aria-hidden="true"></span>
      </div>

      <!-- Formulario registro -->
      <form class="form" method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <label class="field">
          <span class="field__label">Nombre</span>
          <input class="input" id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Tu nombre" required autocomplete="given-name" />
          @if($errors->has('first_name'))<p class="field__error" role="alert">{{ $errors->first('first_name') }}</p>@endif
        </label>

        <label class="field">
          <span class="field__label">Apellido</span>
          <input class="input" id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Tu apellido" required autocomplete="family-name" />
          @if($errors->has('last_name'))<p class="field__error" role="alert">{{ $errors->first('last_name') }}</p>@endif
        </label>

        <label class="field">
          <span class="field__label">Correo electrónico</span>
          <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Escribe tu dirección de correo" required autocomplete="username" />
          @if($errors->has('email'))<p class="field__error" role="alert">{{ $errors->first('email') }}</p>@endif
        </label>

        <div class="field">
          <span class="field__label">Soy</span>
          <div style="display:flex; gap:16px; margin-top:8px;">
            <label style="display:inline-flex; align-items:center; gap:8px;">
              <input type="radio" name="role" value="paciente" {{ old('role','paciente') === 'paciente' ? 'checked' : '' }}>
              <span>Paciente</span>
            </label>
            <label style="display:inline-flex; align-items:center; gap:8px;">
              <input type="radio" name="role" value="doctor" {{ old('role') === 'doctor' ? 'checked' : '' }}>
              <span>Doctor</span>
            </label>
          </div>
          @if($errors->has('role'))<p class="field__error" role="alert">{{ $errors->first('role') }}</p>@endif
        </div>

        <div class="field" id="carnet-group" style="display: {{ old('role') === 'doctor' ? 'block' : 'none' }};">
          <span class="field__label">Carnet MINSA (solo Doctores)</span>
          <input class="input" id="carnet_minsa" type="text" name="carnet_minsa" value="{{ old('carnet_minsa') }}" placeholder="Ingrese su carnet MINSA" autocomplete="off" />
          @if($errors->has('carnet_minsa'))<p class="field__error" role="alert">{{ $errors->first('carnet_minsa') }}</p>@endif
        </div>

        <label class="field">
          <span class="field__label">Contraseña</span>
          <div class="input input--with-icon">
            <input id="password" type="password" name="password" placeholder="Elige una contraseña" required autocomplete="new-password" />
            <button class="input__icon" type="button" aria-label="Mostrar contraseña" onclick="togglePassword('password')">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true">
                <path d="M12 5C6.5 5 2.1 8.6 1 12c1.1 3.4 5.5 7 11 7s9.9-3.6 11-7c-1.1-3.4-5.5-7-11-7Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                <circle cx="12" cy="12" r="3.25" stroke="currentColor" stroke-width="1.6" />
              </svg>
            </button>
          </div>
          @if($errors->has('password'))<p class="field__error" role="alert">{{ $errors->first('password') }}</p>@endif
        </label>

        <label class="field">
          <span class="field__label">Confirmar contraseña</span>
          <div class="input input--with-icon">
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Repite la contraseña" required autocomplete="new-password" />
            <button class="input__icon" type="button" aria-label="Mostrar contraseña" onclick="togglePassword('password_confirmation')">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" aria-hidden="true">
                <path d="M12 5C6.5 5 2.1 8.6 1 12c1.1 3.4 5.5 7 11 7s9.9-3.6 11-7c-1.1-3.4-5.5-7-11-7Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                <circle cx="12" cy="12" r="3.25" stroke="currentColor" stroke-width="1.6" />
              </svg>
            </button>
          </div>
          @if($errors->has('password_confirmation'))<p class="field__error" role="alert">{{ $errors->first('password_confirmation') }}</p>@endif
        </label>

        <div class="form__meta" style="margin-top:8px; margin-bottom:4px;">
          <a class="link--muted" href="{{ route('login') }}">¿Ya tienes cuenta? Iniciar sesión</a>
        </div>

        <button class="btn btn--primary" type="submit">Regístrate</button>
      </form>

      <footer class="auth__footer" style="margin-top:20px;">
        <div class="auth__footer-left">
          <span>¿Necesitas ayuda?</span>
          <a class="pill-link" href="#">Soporte</a>
        </div>
        <nav class="auth__footer-right" aria-label="Legal">
          <a href="#">Privacidad</a>
          <a href="#">Términos</a>
        </nav>
      </footer>
    </section>
  </main>

  <script>
    (function(){
      const roleRadios = document.querySelectorAll('input[name="role"]');
      const carnetGroup = document.getElementById('carnet-group');

      function toggleCarnet() {
        const selected = document.querySelector('input[name="role"]:checked')?.value;
        if (selected === 'doctor') {
          carnetGroup.style.display = 'block';
        } else {
          carnetGroup.style.display = 'none';
        }
      }

      roleRadios.forEach(r => r.addEventListener('change', toggleCarnet));
      toggleCarnet();
    })();

    function togglePassword(id) {
      const pwd = document.getElementById(id);
      if (!pwd) return;
      pwd.type = pwd.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>