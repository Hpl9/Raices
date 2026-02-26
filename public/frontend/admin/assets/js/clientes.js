
const API_CLIENTES = "/Raices/public/api/clientes.php";


  // CARGAR CLIENTES

async function cargarClientes() {

  const tbody = document.getElementById("adminClientesTbody");
  if (!tbody) return;

  tbody.innerHTML = `<tr><td colspan="9" class="px-6 py-4">Cargando...</td></tr>`;

  try {

    const res = await fetch(API_CLIENTES, {
      credentials: "include"
    });

    const data = await res.json();

    if (!data.ok) {
      tbody.innerHTML = `<tr><td colspan="9" class="px-6 py-4 text-red-600">Error al cargar clientes</td></tr>`;
      return;
    }

    const clientes = data.clientes || [];

    if (clientes.length === 0) {
      tbody.innerHTML = `<tr><td colspan="9" class="px-6 py-4 text-gray-500">No hay clientes registrados</td></tr>`;
      return;
    }

    tbody.innerHTML = clientes.map(c => `
      <tr class="hover:bg-gray-50">
        <td class="px-6 py-4">${escapeHtml(c.id)}</td>
        <td class="px-6 py-4">
          ${escapeHtml(c.nombre)} ${escapeHtml(c.apellido ?? "")}
        </td>
        <td class="px-6 py-4">${escapeHtml(c.email ?? "-")}</td>
        <td class="px-6 py-4">${escapeHtml(c.telefono ?? "-")}</td>
        <td class="px-6 py-4">${escapeHtml(c.direccion ?? "-")}</td>
        <td class="px-6 py-4">${escapeHtml(c.codigo_postal ?? "-")}</td>
        <td class="px-6 py-4">${escapeHtml(c.poblacion ?? "-")}</td>
        <td class="px-6 py-4">${escapeHtml(c.municipio ?? "-")}</td>
        <td class="px-6 py-4">
          <button 
            onclick="eliminarCliente(${Number(c.id)})"
            class="rounded-lg p-2 hover:bg-red-50 text-red-600"
            aria-label="Eliminar">
            🗑️
            </button>
        </td>
      </tr>
    `).join("");

  } catch (err) {

    tbody.innerHTML = `<tr><td colspan="9" class="px-6 py-4 text-red-600">Error de conexión</td></tr>`;
  }
}



  // ELIMINAR CLIENTE

async function eliminarCliente(id) {

  if (!confirm("¿Eliminar cliente?")) return;

  const formData = new FormData();
  formData.append("id", id);

  try {

    const res = await fetch(API_CLIENTES, {
      method: "POST",
      body: formData,
      credentials: "include"
    });

    const data = await res.json();

    if (data.ok) {
      cargarClientes();
    } else {
      alert("No se pudo eliminar");
    }

  } catch (err) {
    alert("Error de conexión");
  }
}



 //  INICIALIZAR

document.addEventListener("DOMContentLoaded", cargarClientes);



  // SANITIZAR HTML (XSS)

function escapeHtml(s) {
  return String(s ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}