const API_PRODUCTOS = "/Raices/public/api/productos.php";


document.addEventListener("DOMContentLoaded", () => {
  cargarProductos();
});

async function cargarProductos() {
  const tbody = document.querySelector("#adminProductosTbody");
  if (!tbody) return;

  tbody.innerHTML = `
    <tr>
      <td class="px-6 py-6 text-gray-500" colspan="7">Cargando productos...</td>
    </tr>
  `;

  try {
    const res = await fetch(API_PRODUCTOS, {
      method: "GET",
      headers: { "Accept": "application/json" }
      // de momento no ponemos credentials, porque aún no estamos protegiendo admin
    });

    const data = await res.json();

    if (!res.ok || !data.ok) {
      throw new Error(data.error || "No se pudo cargar el catálogo");
    }

    const items = Array.isArray(data.items) ? data.items : [];
    //contar productos y pintar
    const countEl = document.getElementById("productos-count");
    if (countEl) {
      countEl.textContent = `${items.length} productos en catálogo`;
    }


    if (items.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td class="px-6 py-6 text-gray-500" colspan="7">No hay productos.</td>
        </tr>
      `;

      return;
    }

    tbody.innerHTML = items.map(pintarFila).join("");

  } catch (err) {
    tbody.innerHTML = `
      <tr>
        <td class="px-6 py-6 text-red-600" colspan="7">${escapeHtml(err.message)}</td>
      </tr>
    `;
  }
}

function pintarFila(p) {
  const img = p.imagen_url || "https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?q=80&w=200&auto=format&fit=crop";
  const nombre = p.nombre ?? "Sin nombre";
  const categoria = p.categoria ?? "-";
  const socio = p.socio ?? "-";
  const precio = (p.precio != null) ? `${Number(p.precio).toFixed(2)}€` : "-";
  const stock = (p.stock != null) ? `${p.stock} ${p.unidad_medida ?? ""}`.trim() : "-";
  const procedencia = p.procedencia ?? "KM0";

  return `
    <tr class="hover:bg-gray-50">
      <td class="px-6 py-4">
        <div class="flex items-center gap-3">
          <img class="h-11 w-11 rounded-xl object-cover bg-gray-100"
               src="${escapeAttr(img)}"
               alt="${escapeAttr(nombre)}" />
          <div>
            <p class="font-semibold text-gray-900">${escapeHtml(nombre)}</p>
          </div>
        </div>
      </td>

      <td class="px-6 py-4">${escapeHtml(categoria)}</td>
      <td class="px-6 py-4">${escapeHtml(socio)}</td>
      <td class="px-6 py-4">${escapeHtml(precio)}</td>
      <td class="px-6 py-4">${escapeHtml(stock)}</td>
      <td class="px-6 py-4">${escapeHtml(procedencia)}</td>

      <td class="px-6 py-4">
        <div class="flex justify-end gap-2">
          <button class="rounded-lg p-2 hover:bg-gray-100" data-edit="${p.id}" aria-label="Editar">✏️</button>
          <button class="rounded-lg p-2 hover:bg-red-50" data-del="${p.id}" aria-label="Eliminar">🗑️</button>
        </div>
      </td>
    </tr>
  `;
}

// helpers anti-XSS
function escapeHtml(str) {
  return String(str).replace(/[&<>"']/g, (m) => ({
    "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
  }[m]));
}
function escapeAttr(str) {
  return escapeHtml(str).replace(/`/g, "&#096;");
}
