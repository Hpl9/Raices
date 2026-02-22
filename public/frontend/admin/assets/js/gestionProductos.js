const API_PRODUCTOS = "/api/gestionProductos.php";

// al cargar la página pedimos los productos a la API
document.addEventListener("DOMContentLoaded", () => {
  console.log("gestionProductos.js cargado");
  console.log("Modal existe?", typeof window.Modal);

  cargarProductos();

  // Botón "Añadir producto"
  const btnAdd = document.getElementById("btn-add-product");
  if (btnAdd) {
    btnAdd.addEventListener("click", (e) => {
      e.preventDefault();
      abrirModalNuevo();
    });
  }

  // Submit del formulario del modal
  const form = document.getElementById("productoForm");
  if (form) {
    form.addEventListener("submit", guardarProducto);
  }
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
      headers: { Accept: "application/json" },
      credentials: "include",
    });

    const data = await res.json();

    if (!res.ok || !data.ok) {
      throw new Error(data.error || "No se pudo cargar el catálogo");
    }

    const items = Array.isArray(data.items) ? data.items : [];

    // contar productos
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

    // pintar filas de la tabla
    tbody.innerHTML = items.map(pintarFila).join("");
    window.initFlowbite?.(); // re-inicializa Flowbite para elementos nuevos
    window.initModals?.(); // para carga del edit

    // activar botones de eliminar
    tbody.querySelectorAll("[data-del]").forEach((btn) => {
      btn.addEventListener("click", () => {
        eliminarProducto(btn.dataset.del);
      });
    });

    // activar botones de editar
    tbody.querySelectorAll("[data-edit]").forEach((btn) => {
      btn.addEventListener("click", () => {
        abrirModalEditar(btn.dataset.edit);
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

// Genera una fila de producto
function pintarFila(p) {
  const img =
    p.imagen_url ||
    "https://llerena.org/wp-content/uploads/2017/11/imagen-no-disponible-1.jpg";
  const nombre = p.nombre ?? "Sin nombre";
  const categoria = p.categoria ?? "-";
  const socio = p.socio ?? "-";
  const precio = p.precio != null ? `${Number(p.precio).toFixed(2)}€` : "-";
  const stock =
    p.stock != null ? `${p.stock} ${p.unidad_medida ?? ""}`.trim() : "-";
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
            <p class="text-sm text-gray-500 line-clamp-2">
              ${escapeHtml(p.descripcion ?? "")}
            </p>
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
         <button
            type="button"
            class="rounded-lg p-2 hover:bg-gray-100"
            data-edit="${p.id}"
            data-modal-target="productoModal"
            data-modal-toggle="productoModal"
            aria-label="Editar">✏️</button>

          <button class="rounded-lg p-2 hover:bg-red-50" data-del="${p.id}" aria-label="Eliminar">🗑️</button>
        </div>
      </td>
    </tr>
  `;
}

// ----------------------- DELETE ----------------------
async function eliminarProducto(id) {
  if (!confirm("¿Eliminar este producto?")) return;

  const res = await fetch(API_PRODUCTOS, {
    method: "DELETE",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    credentials: "include",
    body: JSON.stringify({ id: Number(id) }),
  });

  let data = {};
  try {
    data = await res.json();
  } catch {
    data = { ok: false, error: "Respuesta no JSON del servidor" };
  }

  if (!res.ok || !data.ok) {
    alert(data.error || "Error al eliminar");
    return;
  }

  cargarProductos();
}

// ------------------ MODAL: NUEVO (limpiar los campos)-------------------------
function abrirModalNuevo() {
  document.getElementById("productoModalTitle").textContent = "Nuevo producto";
  document.getElementById("producto-submit").textContent = "Guardar";

  // limpiar form
  document.getElementById("producto-id").value = "";
  document.getElementById("producto-nombre").value = "";
  document.getElementById("producto-categoria").value = "verdura";
  document.getElementById("producto-procedencia").value = "";
  document.getElementById("producto-precio").value = "";
  document.getElementById("producto-stock").value = "";
  document.getElementById("producto-unidad").value = "kg";
  document.getElementById("producto-imagen").value = "";
  document.getElementById("producto-descripcion").value = "";

  ocultarErrorProducto();
}

// ------------------------- MODAL: EDITAR------------------------
async function abrirModalEditar(id) {
  ocultarErrorProducto();

  const res = await fetch(API_PRODUCTOS, {
    method: "GET",
    headers: { Accept: "application/json" },
    credentials: "include",
  });

  const data = await res.json();

  if (!res.ok || !data.ok) {
    mostrarErrorProducto(data.error || "No se pudo cargar el producto");
    return;
  }

  const p = (data.items || []).find((x) => Number(x.id) === Number(id));

  if (!p) {
    mostrarErrorProducto("Producto no encontrado");
    return;
  }

  document.getElementById("productoModalTitle").textContent = "Editar producto";
  document.getElementById("producto-submit").textContent = "Guardar cambios";

  document.getElementById("producto-id").value = p.id ?? "";
  document.getElementById("producto-nombre").value = p.nombre ?? "";
  document.getElementById("producto-categoria").value =
    p.categoria ?? "verdura";
  document.getElementById("producto-procedencia").value = p.procedencia ?? "";
  document.getElementById("producto-precio").value = p.precio ?? "";
  document.getElementById("producto-stock").value = p.stock ?? "";
  document.getElementById("producto-unidad").value = p.unidad_medida ?? "kg";
  document.getElementById("producto-imagen").value = p.imagen_url ?? "";
  document.getElementById("producto-descripcion").value = p.descripcion ?? "";
}

// ======================= POST / PUT =======================
async function guardarProducto(e) {
  e.preventDefault();
  ocultarErrorProducto();

  const id = document.getElementById("producto-id").value.trim();

  const payload = {
    ...(id ? { id: Number(id) } : {}),
    nombre: document.getElementById("producto-nombre").value.trim(),
    categoria: document.getElementById("producto-categoria").value,
    procedencia: document.getElementById("producto-procedencia").value.trim(),
    precio: Number(document.getElementById("producto-precio").value),
    stock: Number(document.getElementById("producto-stock").value),
    unidad_medida: document.getElementById("producto-unidad").value,
    imagen_url: document.getElementById("producto-imagen").value.trim(),
    descripcion: document.getElementById("producto-descripcion").value.trim(),
  };

  if (!payload.nombre) {
    mostrarErrorProducto("El nombre es obligatorio");
    return;
  }

  const method = id ? "PUT" : "POST";

  const res = await fetch(API_PRODUCTOS, {
    method,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    credentials: "include",
    body: JSON.stringify(payload),
  });

  let data = {};
  try {
    data = await res.json();
  } catch {
    data = { ok: false, error: "Respuesta no JSON" };
  }

  if (!res.ok || !data.ok) {
    mostrarErrorProducto(data.error || "Error al guardar");
    return;
  }

  document.querySelector('[data-modal-hide="productoModal"]')?.click();

  cargarProductos();
}

// --------------------- ERRORES EN MODAL------------------
function mostrarErrorProducto(msg) {
  const box = document.getElementById("productoError");
  if (!box) return;
  box.textContent = msg;
  box.classList.remove("hidden");
}

function ocultarErrorProducto() {
  const box = document.getElementById("productoError");
  if (!box) return;
  box.textContent = "";
  box.classList.add("hidden");
}

// -------------------------- ANTI-XSS --------------------------
function escapeHtml(str) {
  return String(str).replace(
    /[&<>"']/g,
    (m) =>
      ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;",
      })[m],
  );
}

function escapeAttr(str) {
  return escapeHtml(str).replace(/`/g, "&#096;");
}
