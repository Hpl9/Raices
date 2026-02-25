const API_ADMIN_PEDIDOS = "/Raices/public/api/admin_pedidos.php";
const API_PEDIDO_DETALLE = "/Raices/public/api/admin_pedidos_detalle.php";

document.addEventListener("DOMContentLoaded", async () => {
  const tbody = document.getElementById("adminPedidosTbody");
  if (!tbody) return;

  // 🟩 Formatea números a euros (ej: 4.8 -> 4,80 €)
  const formatEUR = (n) =>
    (Number(n) || 0).toLocaleString("es-ES", { style: "currency", currency: "EUR" });

  // 🟩 Cierra el modal de detalles (simple: ocultar + volver a permitir scroll)
  function closePedidoDetalleModal() {
    const modal = document.getElementById("pedidoDetalleModal");
    if (modal) modal.classList.add("hidden");
    document.body.classList.remove("overflow-hidden");
  }

  try {
    // 🟩 1) Cargar lista de pedidos + estados
    const res = await fetch(API_ADMIN_PEDIDOS, { credentials: "include" });
    const { ok, pedidos = [], estados = [], error } = await res.json().catch(() => ({}));

    if (!res.ok || !ok) {
      tbody.innerHTML = `<tr><td class="px-6 py-6 text-red-600" colspan="7">${escapeHtml(
        error || "Error"
      )}</td></tr>`;
      return;
    }

    if (!pedidos.length) {
      tbody.innerHTML = `<tr><td class="px-6 py-6 text-gray-500" colspan="7">No hay pedidos.</td></tr>`;
      return;
    }

    // 🟩 2) Pintar filas de la tabla (incluye botón lupa con data-pedido-detalle)
    tbody.innerHTML = pedidos
      .map((p) => {
        const id = p.pedido_id ?? p.id;
        const cliente = `${p.cliente_nombre ?? ""} ${p.cliente_apellido ?? ""}`.trim() || "—";

        const opciones = estados
          .map(
            (e) => `
          <option value="${e.id}" ${Number(e.id) === Number(p.estado_id) ? "selected" : ""}>
            ${escapeHtml(e.nombre)}
          </option>`
          )
          .join("");

        return `
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-5 text-gray-900"># ${escapeHtml(id)}</td>
          <td class="px-6 py-5 text-gray-900">${escapeHtml(cliente)}</td>
          <td class="px-6 py-5 text-gray-700">${escapeHtml(p.direccion_entrega ?? "—")}</td>
          <td class="px-6 py-5 text-gray-700">${escapeHtml(p.tipo_entrega ?? "—")}</td>
          <td class="px-6 py-5 text-gray-900">${formatEUR(p.total)}</td>
          <td class="px-6 py-5">
            <select class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-900 focus:ring-2 focus:ring-green-600"
                    data-pedido-id="${escapeHtml(id)}">
              ${opciones}
            </select>
          </td>
          <td class="px-6 py-5">
            <button type="button"
              class="btn-detalles inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 hover:bg-gray-50"
              data-pedido-detalle="${escapeHtml(id)}"
              title="Ver detalles">
              🔍
            </button>
          </td>
        </tr>`;
      })
      .join("");
  } catch (e) {
    tbody.innerHTML = `<tr><td class="px-6 py-6 text-red-600" colspan="7">Error de red</td></tr>`;
  }

  // Clicks globales
  document.addEventListener("click", async (e) => {
    // click cerrar modal
    if (e.target.closest('[data-modal-hide="pedidoDetalleModal"]')) {
      closePedidoDetalleModal();
      return;
    }

    //  Si se pulsa una lupa, abrimos modal y cargamos detalles
    const btn = e.target.closest("[data-pedido-detalle]");
    if (!btn) return;

    const pedidoId = btn.getAttribute("data-pedido-detalle");
    const modal = document.getElementById("pedidoDetalleModal");
    const title = document.getElementById("pedidoDetalleTitle");
    const body = document.getElementById("pedidoDetalleBody");
    const totalEl = document.getElementById("pedidoDetalleTotal");

    if (title) title.textContent = `Pedido #${pedidoId}`;
    if (body) body.innerHTML = "Cargando…";
    if (totalEl) totalEl.textContent = "—";

    // Abrir modal (simple)
    if (modal) {
      modal.classList.remove("hidden");
      document.body.classList.add("overflow-hidden");
    }

    try {
      const res = await fetch(`${API_PEDIDO_DETALLE}?id=${encodeURIComponent(pedidoId)}`, {
        credentials: "include",
      });
      const json = await res.json().catch(() => null);

      if (!res.ok || !json?.ok) {
        if (body) {
          body.innerHTML = `<div class="rounded-xl border border-red-200 bg-red-50 p-3 text-red-700">
            ${escapeHtml(json?.error || `Error (${res.status})`)}
          </div>`;
        }
        return;
      }

      const items = Array.isArray(json.items) ? json.items : [];
      if (!items.length) {
        if (body) body.innerHTML = `<p class="text-gray-500">Este pedido no tiene detalles.</p>`;
      } else {
        const rows = items
          .map(
            (it) => `
          <tr class="border-b last:border-b-0">
            <td class="py-2 pr-3 text-gray-900">${escapeHtml(it.producto ?? "—")}</td>
            <td class="py-2 px-2 text-right">${escapeHtml(it.cantidad ?? 0)}</td>
            <td class="py-2 px-2 text-right">${formatEUR(it.precio_unitario)}</td>
            <td class="py-2 pl-2 text-right font-semibold">${formatEUR(it.subtotal)}</td>
          </tr>
        `
          )
          .join("");

        if (body) {
          body.innerHTML = `
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead class="text-gray-500">
                  <tr class="border-b">
                    <th class="py-2 text-left font-semibold">Producto</th>
                    <th class="py-2 text-right font-semibold">Cant.</th>
                    <th class="py-2 text-right font-semibold">Precio</th>
                    <th class="py-2 text-right font-semibold">Subtotal</th>
                  </tr>
                </thead>
                <tbody>${rows}</tbody>
              </table>
            </div>
          `;
        }
      }

      if (totalEl) totalEl.textContent = formatEUR(json.total);
    } catch (err) {
      console.error(err);
      if (body) {
        body.innerHTML = `<div class="rounded-xl border border-red-200 bg-red-50 p-3 text-red-700">
          Error de conexión.
        </div>`;
      }
    }
  });

  // Escapar HTML 
  function escapeHtml(text) {
    return String(text ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }
});