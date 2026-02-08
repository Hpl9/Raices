<?php
// components/navbar.php
// Espera que exista $userName en admin.php
?>

<!-------------------- MODAL PRODUCTO (Crear / Editar)--------------------->

<div id="productoModal" tabindex="-1" aria-hidden="true"
  class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
  <div class="relative w-full max-w-2xl">

    <div class="relative rounded-3xl bg-white shadow-xl border border-gray-200 overflow-hidden">

      <!-- Header -->
      <div class="flex items-start justify-between px-6 pt-6">
        <div>
          <h3 id="productoModalTitle" class="text-xl sm:text-2xl font-extrabold text-gray-900">
            Nuevo producto
          </h3>
          <p class="mt-1 text-sm text-gray-600">
            Completa los datos para crear o editar un producto.
          </p>
        </div>

        <button type="button" data-modal-hide="productoModal"
          class="rounded-xl p-2 text-gray-500 hover:bg-gray-100">
          <span class="sr-only">Cerrar</span> ✕
        </button>
      </div>

      <!-- Body -->
      <div class="px-6 pb-6 pt-5 max-h-[70vh] overflow-y-auto">
        <div class="rounded-2xl bg-[#EAF2E3]/60 border border-[#5B7B2F]/15 p-5">

          <form id="productoForm" class="space-y-4">
            <!-- hidden id (si existe => editar) -->
            <input type="hidden" id="producto-id" name="id" value="">

            <!-- Grid 2 columnas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Nombre -->
              <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-800">Nombre del producto</label>
                <input id="producto-nombre" name="nombre" type="text" required
                  
                  class="mt-1 w-full rounded-2xl border border-gray-200 bg-white p-3
                         focus:outline-none focus:ring-2 focus:ring-[#5B7B2F]">
              </div>

              <!-- Categoría -->
              <div>
                <label class="block text-sm font-semibold text-gray-800">Categoría</label>
                <select id="producto-categoria" name="categoria" required
                  class="mt-1 w-full rounded-2xl border border-gray-200 bg-white p-3
                         focus:outline-none focus:ring-2 focus:ring-[#5B7B2F]">
                  <option value="verdura">Verdura</option>
                  <option value="fruta">Fruta</option>
                  <option value="regional">Regional</option>
                </select>
              </div>

              <!-- Procedencia -->
              <div>
                <label class="block text-sm font-semibold text-gray-800">Procedencia</label>
                <input id="producto-procedencia" name="procedencia" type="text" required
                 
                  class="mt-1 w-full rounded-2xl border border-gray-200 bg-white p-3
                         focus:outline-none focus:ring-2 focus:ring-[#5B7B2F]">
                <p class="mt-1 text-xs text-gray-600">Ej: Fuerteventura, Lanzarote,Gran Canaria…</p>
              </div>

              <!-- Precio -->
              <div>
                <label class="block text-sm font-semibold text-gray-800">Precio (€)</label>
                <input id="producto-precio" name="precio" type="number" step="0.01" min="0" required
                 
                  class="mt-1 w-full rounded-2xl border border-gray-200 bg-white p-3
                         focus:outline-none focus:ring-2 focus:ring-[#5B7B2F]">
              </div>

              <!-- Stock -->
              <div>
                <label class="block text-sm font-semibold text-gray-800">Stock</label>
                <input id="producto-stock" name="stock" type="number" step="1" min="0" required
                  
                  class="mt-1 w-full rounded-2xl border border-gray-200 bg-white p-3
                         focus:outline-none focus:ring-2 focus:ring-[#5B7B2F]">
              </div>

              <!-- Unidad -->
              <div>
                <label class="block text-sm font-semibold text-gray-800">Unidad de medida</label>
                <select id="producto-unidad" name="unidad_medida" required
                  class="mt-1 w-full rounded-2xl border border-gray-200 bg-white p-3
                         focus:outline-none focus:ring-2 focus:ring-[#5B7B2F]">
                  <option value="kg">kg</option>
                  <option value="g">g</option>
                  <option value="unidad">unidad</option>
                </select>
              </div>

             
              <!-- Imagen URL -->
              <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-800">Imagen (URL)</label>
                <input id="producto-imagen" name="imagen_url" type="url" required
                  placeholder="http://... o https://..."
                  class="mt-1 w-full rounded-2xl border border-gray-200 bg-white p-3
                         focus:outline-none focus:ring-2 focus:ring-[#5B7B2F]">
              </div>

              <!-- Descripción -->
              <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-800">Descripción</label>
                <textarea id="producto-descripcion" name="descripcion" rows="3" required
                  placeholder="Escribe una descripción breve del producto. Max. 150 caracteres"
                  maxlength="150"
                  class="mt-1 w-full rounded-2xl border border-gray-200 bg-white p-3
                         focus:outline-none focus:ring-2 focus:ring-[#5B7B2F]"></textarea>
              </div>
            </div>

            <!-- Footer botones -->
            <div class="pt-2 flex flex-col sm:flex-row gap-3 sm:justify-end">
              <button type="button" data-modal-hide="productoModal"
                class="inline-flex justify-center rounded-2xl border border-gray-200 bg-white px-5 py-3
                       text-sm font-semibold text-gray-900 hover:bg-gray-50">
                Cancelar
              </button>

              <button id="producto-submit" type="submit"
                class="inline-flex justify-center rounded-2xl bg-[#5B7B2F] px-5 py-3
                       text-sm font-semibold text-white hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#5B7B2F]">
                Guardar
              </button>
            </div>

            <!-- caja de error -->
            <div id="productoError" class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>