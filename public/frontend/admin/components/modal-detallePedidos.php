<!-- Modal Detalles Pedido -->
 
<div id="pedidoDetalleModal" tabindex="-1" aria-hidden="true"
  class="hidden fixed inset-0 z-50 flex items-start justify-center pt-20 pb-10 px-6 overflow-y-auto">
  <div class="absolute inset-0 bg-black/40"></div>

  <div class="relative w-full max-w-lg">
    <div class="relative rounded-3xl bg-white shadow-xl border border-gray-200 overflow-hidden">

      <div class="flex items-center justify-between px-6 pt-6">
        <div>
          <p class="text-sm text-gray-500">Detalles del pedido</p>
          <h3 id="pedidoDetalleTitle" class="text-xl font-extrabold text-gray-900">Pedido #—</h3>
        </div>

        <button type="button" data-modal-hide="pedidoDetalleModal"
          class="ml-3 rounded-xl p-2 text-gray-500 hover:bg-gray-100">
          <span class="sr-only">Cerrar</span> ✕
        </button>
      </div>

      <div class="px-6 pb-6 pt-4">
        <div id="pedidoDetalleBody" class="text-sm text-gray-700">
          <!-- contenido dinámico -->
        </div>

        <div class="mt-4 flex items-center justify-between border-t pt-4">
          <span class="text-sm text-gray-500">Total</span>
          <span id="pedidoDetalleTotal" class="text-lg font-extrabold text-gray-900">—</span>
        </div>
      </div>

    </div>
  </div>
</div>