 <!-- Titulo -->
    <section>
      <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Panel de Administración</h1>
      <p class="mt-2 text-gray-600">
        Gestiona productos, pedidos y visualiza estadísticas
      </p>
    </section>

    <!-- TABS -->
    <section class="mt-8">
      <div class="inline-flex rounded-2xl border border-gray-200 bg-white p-1 shadow-sm">
        <button onclick="location.href='admin.php'" 
          class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold
            <?= ($activeTab === 'productos') 
                ? 'bg-gray-900 text-white' 
                : 'text-gray-700 hover:bg-gray-100' ?>">
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
              d="M21 8V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8" />
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
              d="M3 8l9-5 9 5" />
          </svg>
          Productos
        </button>

        <button onclick="location.href='pedidos.php'"
          class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold
            <?= ($activeTab === 'pedidos') 
                ? 'bg-gray-900 text-white' 
                : 'text-gray-700 hover:bg-gray-100' ?>">
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
              d="M6 6h15l-2 9H7L6 6Z" />
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
              d="M6 6H4" />
          </svg>
          Pedidos
        </button>

        <button onclick="location.href='finanzas.php'"
          class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold
            <?= ($activeTab === 'finanzas') 
                ? 'bg-gray-900 text-white' 
                : 'text-gray-700 hover:bg-gray-100' ?>">
          <span class="font-extrabold">€</span>
          Finanzas
        </button>

        <button onclick="location.href='clientes.php'"
         class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold
            <?= ($activeTab === 'clientes') 
                ? 'bg-gray-900 text-white' 
                : 'text-gray-700 hover:bg-gray-100' ?>">
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
              d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" />
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
              d="M12 14c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5Z" />
          </svg>
          Clientes
        </button>
      </div>
    </section>