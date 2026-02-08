 <header class="sticky top-0 z-40 w-full bg-white/90 backdrop-blur border-b border-gray-200">
    <nav class="mx-auto max-w-6xl px-4 py-3 flex items-center justify-between">

      <!-- Izquierda : logo -->
      <div class="flex items-center gap-3">
        <a href="http://localhost/Raices/public/frontend/cliente/index.html" class="flex items-center gap-3 group">
          <img
            src="http://localhost/Raices/public/frontend/cliente/assets/img/logo.png"
            alt="Logo Raíces"
            class="h-11 w-11 rounded-2xl object-contain bg-white"
          />
          <div class="leading-tight">
            <p class="text-lg sm:text-xl font-extrabold text-brand group-hover:opacity-90">Raíces</p>
            <p class="text-xs sm:text-sm font-medium text-gray-500">
              Panel de administración
            </p>
          </div>
        </a>
      </div>

      <!-- Derecha: usuario + logout -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Usuario -->
        <div id="user-pill" class="sm:inline-flex items-center gap-2 rounded-xl bg-gray-100 px-3 py-2 text-sm">
          <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
              d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
              d="M12 14c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5Z"/>
          </svg>
          <span id="user-name" class="font-semibold text-gray-800">
            <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
          </span>
        </div>

        <!-- Logout -->
        <button id="btn-logout"
          type="button"
          class="inline-flex items-center gap-2 rounded-xl bg-[#5B7B2F] px-4 py-2 text-sm font-semibold text-white hover:opacity-95 focus:outline-none focus:ring-2 ring-brand border-0"
        >
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
              d="M10 7V6a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2v-1"/>
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
              d="M15 12H3m0 0 3-3m-3 3 3 3"/>
          </svg>
          Salir
        </button>
      </div>
    </nav>
  </header>