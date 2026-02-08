const API_PRODUCTOS = "/Raices/public/api/gestionProductos.php";

//al cargar la pagina pedimos los productoss a la API
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
      headers: { "Accept": "application/json" },
      credentials: "include"
      
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

    //pintar filas de la tabla
    tbody.innerHTML = items.map(pintarFila).join("");

    //activar botones de eliminar tras pintar
    tbody.querySelectorAll("[data-del]").forEach(btn => {
    btn.addEventListener("click", () => {
    eliminarProducto(btn.dataset.del);
       });
       });
    

  } catch (err) {
    tbody.innerHTML = `
      <tr>
        <td class="px-6 py-6 text-red-600" colspan="7">${escapeHtml(err.message)}</td>
      </tr>
    `;
  }
}

// Generea una fila de producto (el html) que Recibe un objeto producto (JSON de la API)
function pintarFila(p) {
  const img = p.imagen_url || "https://llerena.org/wp-content/uploads/2017/11/imagen-no-disponible-1.jpg";
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


//eliminar un producto

async function eliminarProducto(id) {
  if (!confirm("¿Eliminar este producto?")) return;

  const res = await fetch(`${API_PRODUCTOS}?id=${id}`, {
    method: "DELETE",
    headers: { "Accept": "application/json" },
    credentials: "include"
  });

  const data = await res.json();

  if (!res.ok || !data.ok) {
    alert(data.error || "Error al eliminar");
    return;
  }

  cargarProductos(); // refresca tabla
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
