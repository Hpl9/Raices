const API_ADMIN_PEDIDOS = "/Raices/public/api/admin_pedidos.php";
// Carga  la lista de pedidos y estados desde la API,


document.addEventListener("DOMContentLoaded", async () => {
  const tbody = document.getElementById("adminPedidosTbody");
  if (!tbody) return;

  const API_URL = "/Raices/public/api/admin_pedidos.php";
  const formatEUR = (n) => (Number(n) || 0).toLocaleString("es-ES", { style: "currency", currency: "EUR" });

  try {
    const res = await fetch(API_URL, { credentials: "include" });
    const { ok, pedidos = [], estados = [], error } = await res.json().catch(() => ({}));

    if (!res.ok || !ok) {
      tbody.innerHTML = `<tr><td class="px-6 py-6 text-red-600" colspan="6">${escapeHtml(error || "Error")}</td></tr>`;
      return;
    }

    if (!pedidos.length) {
      tbody.innerHTML = `<tr><td class="px-6 py-6 text-gray-500" colspan="6">No hay pedidos.</td></tr>`;
      return;
    }

    // Generamos el HTML de la tabla
    tbody.innerHTML = pedidos.map(p => {
      const id = p.pedido_id ?? p.id;
      const cliente = `${p.cliente_nombre ?? ""} ${p.cliente_apellido ?? ""}`.trim() || "—";
      
      // Generar opciones del selector de estado
      const opciones = estados.map(e => `
        <option value="${e.id}" ${Number(e.id) === Number(p.estado_id) ? "selected" : ""}>
          ${escapeHtml(e.nombre)}
        </option>`).join("");

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
          
        </tr>`;
    }).join("");

  } catch (e) {
    tbody.innerHTML = `<tr><td class="px-6 py-6 text-red-600" colspan="6">Error de red</td></tr>`;
  }

  // Tu función favorita para desinfectar el HTML
  function escapeHtml(text) {
    return String(text ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }
});