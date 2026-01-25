
// 1) Pedir el catálogo público (tienda.php)
// 2) Separar productos por categoria (verdura / fruta / regional)
// 3) Pintar cards en cada sección





// Endpoint público del catálogo
const API_TIENDA = "/Raices/public/api/tienda.php";

document.addEventListener("DOMContentLoaded", () => {
  cargarCatalogo();
});

function cargarCatalogo() {
  fetch(API_TIENDA)
    .then((res) => res.json())
    .then((data) => {
      if (!data.ok) throw new Error("Error cargando productos");

      //  Categorías  BD 
      const verduras = data.items.filter(p => p.categoria === "verdura");
      const frutas = data.items.filter(p => p.categoria === "fruta");
      const regionales = data.items.filter(p => p.categoria === "regional");

      pintarGrid("grid-verduras", verduras);
      pintarGrid("grid-frutas", frutas);
      pintarGrid("grid-regionales", regionales);

      //  Preparar botone "Añadir"
      document.querySelectorAll("[data-add-to-cart]").forEach(btn => {
        btn.addEventListener("click", () => {
          const id = btn.dataset.id;
          console.log("Añadir al carrito -> producto id:", id);

          //  Próximo paso: guardar en localStorage
          // addToCart(id);
        });
      });
    })
    .catch((err) => {
      console.error(err);
      mostrarError("grid-verduras");
      mostrarError("grid-frutas");
      mostrarError("grid-regionales");
    });
}

function pintarGrid(gridId, productos) {
  const grid = document.getElementById(gridId);
  if (!grid) return;

  grid.innerHTML = "";

  if (!productos.length) {
    grid.innerHTML = `<p class="text-sm text-gray-500">No hay productos disponibles.</p>`;
    return;
  }

  productos.forEach(producto => {
    grid.appendChild(crearCardProducto(producto));
  });
}

function crearCardProducto(p) {
  const card = document.createElement("article");

  const imagen = p.imagen_url || "https://via.placeholder.com/600x400?text=Raíces";
  const precio = Number(p.precio).toFixed(2);
  const unidad = p.unidad_medida ? `/${p.unidad_medida}` : "";

  card.className =
    "rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col";

  card.innerHTML = `
    <!-- Imagen-->
    <div class="relative h-44 bg-gray-100">
      <img src="${imagen}" alt="${escapeHtml(p.nombre)}"
        class="h-full w-full object-cover">
    </div>

    <!--  Contenido  card -->
    <div class="p-5 flex flex-col flex-1">
      <!--  Nombre -->
      <h4 class="text-lg font-extrabold text-gray-900">
        ${escapeHtml(p.nombre)}
      </h4>

      <!-- Descripción -->
      <p class="mt-2 text-sm text-gray-600 line-clamp-2">
        ${escapeHtml(p.descripcion || "Producto fresco de proximidad")}
      </p>

      <!-- Precio + botón -->
      <div class="mt-4 flex items-center justify-between">
        
        <p class="text-lg font-extrabold">
          ${precio}€
          <span class="text-xs font-medium text-gray-500">${unidad}</span>
        </p>

        
        <button
          type="button"
          data-add-to-cart
          data-id="${p.id}"
          class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-95"
        >
          +
          Añadir
        </button>
      </div>
    </div>
  `;

  return card;
}


function mostrarError(gridId) {
  const grid = document.getElementById(gridId);
  if (!grid) return;
  grid.innerHTML = `<p class="text-sm text-red-600">Error cargando productos.</p>`;
}

// Evita problemas con caracteres especiales
function escapeHtml(text) {
  return String(text)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}
