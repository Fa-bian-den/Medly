<nav class="topnav bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700" x-data="{ open: false }" aria-label="Barra superior">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
      <div class="flex items-center gap-4">
        <!-- Logo sin enlace para que no navegue -->
        <div class="shrink-0" aria-hidden="true">
          <img src="{{ asset('img/Logo.svg') }}" alt="{{ config('app.name','Medly') }}" class="h-9 w-auto" />
        </div>
      </div>

      <div class="flex items-center gap-4">
        @auth
          <div class="hidden sm:flex sm:items-center sm:gap-3">
            <span class="text-sm text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="pill-link">Cerrar sesión</button>
            </form>
          </div>
        @else
          <div class="hidden sm:flex sm:items-center sm:gap-3">
            <a href="{{ route('login') }}" class="pill-link">Iniciar sesión</a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="pill-link">Regístrate</a>
            @endif
          </div>
        @endauth

        <!-- Mobile hamburger -->
        <div class="-mr-2 flex sm:hidden">
          <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div x-show="open" x-cloak class="sm:hidden" id="mobile-menu">
    <div class="pt-2 pb-3 space-y-1">
      <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 text-base font-medium">Dashboard</a>
      @auth
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full text-left pl-3 pr-4 py-2">Cerrar sesión</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2">Iniciar sesión</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2">Regístrate</a>
        @endif
      @endauth
    </div>
  </div>
</nav>