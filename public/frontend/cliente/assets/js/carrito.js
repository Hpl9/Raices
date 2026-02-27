const STORAGE_KEY = "raices_cart";
const ENVIO_COSTE = 2.95;

//  Estado (fuente real: localStorage, pero trabajamos con un array en memoria)
let carrito = JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");

//  Persistencia
function save() {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(carrito));
  render();
}

//  Lógica
function addToCart(p) {
  if (!p?.id) return;

  const item = carrito.find((x) => x.id == p.id);
  if (item) item.qty = Math.min(99, item.qty + 1);
  else
    carrito.push({
      id: p.id,
      nombre: p.nombre,
      precio: Number(p.precio) || 0,
      unidad: p.unidad_medida || "",
      imagen_url: p.imagen_url || "https://via.placeholder.com/120x120?text=Ra%C3%ADces",
      qty: 1,
    });

  save();
}

function changeQty(id, delta) {
  const item = carrito.find((x) => x.id == id);
  if (!item) return;

  item.qty += delta;

  if (item.qty <= 0) carrito = carrito.filter((x) => x.id != id);
  if (item.qty > 99) item.qty = 99;

  save();
}

function removeItem(id) {
  carrito = carrito.filter((x) => x.id != id);
  save();
}

function clearCart() {
  carrito = [];
  save();
}

// Cálculos
function getSubtotal() {
  return carrito.reduce((acc, it) => acc + it.precio * it.qty, 0);
}

function getEntrega() {
  return document.getElementById("entrega-envio")?.checked ? "envio" : "recogida";
}

function formatEUR(n) {
  return (Number(n) || 0).toLocaleString("es-ES", { style: "currency", currency: "EUR" });
}

//  UI
function render() {
  const list = document.getElementById("cart-list");
  const empty = document.getElementById("cart-empty");
  const countEl = document.getElementById("cart-count");
  const totalEl = document.getElementById("cart-total");
  if (!list || !countEl || !totalEl) return;

  const count = carrito.reduce((acc, it) => acc + it.qty, 0);
  countEl.textContent = `${count} producto${count === 1 ? "" : "s"}`;

  list.querySelectorAll("[data-cart-row]").forEach((n) => n.remove());

  if (!carrito.length) {
    empty?.classList.remove("hidden");
    totalEl.textContent = formatEUR(0);
    return;
  }
  empty?.classList.add("hidden");

  let html = "";
  carrito.forEach((it) => {
    const unidad = it.unidad ? `/${it.unidad}` : "";

    const nombreSafe = escapeHtml(it.nombre);
    const unidadSafe = escapeHtml(unidad);

    html += `
      <div data-cart-row class="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-4">
        <img src="${it.imagen_url}" class="h-16 w-16 rounded-2xl object-cover border border-gray-200" />

        <div class="min-w-0 flex-1">
          <p class="truncate text-sm font-extrabold text-gray-900">${nombreSafe}</p>
          <p class="mt-1 text-xs text-gray-500">${formatEUR(it.precio)} <span class="text-gray-400">${unidadSafe}</span></p>
        </div>

        <div class="flex items-center gap-2">
          <button type="button" data-action="dec" data-id="${it.id}" class="h-9 w-9 rounded-xl border border-gray-200 hover:bg-gray-50">−</button>
          <span class="w-8 text-center text-sm font-semibold">${it.qty}</span>
          <button type="button" data-action="inc" data-id="${it.id}" class="h-9 w-9 rounded-xl border border-gray-200 hover:bg-gray-50">+</button>
        </div>

        <div class="text-right">
          <p class="text-sm font-extrabold text-gray-900">${formatEUR(it.precio * it.qty)}</p>
          <button type="button" data-action="del" data-id="${it.id}" class="mt-1 inline-flex items-center justify-center rounded-xl p-2 hover:bg-red-50" aria-label="Eliminar">🗑️</button>
        </div>
      </div>
    `;
  });

  if (empty) empty.insertAdjacentHTML("beforebegin", html);
  else list.insertAdjacentHTML("beforeend", html);

  const subtotal = getSubtotal();
  const total = subtotal + (getEntrega() === "envio" ? ENVIO_COSTE : 0);
  totalEl.textContent = formatEUR(total);
}

//  Eventos
document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("cart-list")?.addEventListener("click", (e) => {
    const btn = e.target.closest("button[data-action]");
    if (!btn) return;

    const { action, id } = btn.dataset;
    if (action === "inc") changeQty(id, +1);
    if (action === "dec") changeQty(id, -1);
    if (action === "del") removeItem(id);
  });

  document.getElementById("entrega-recogida")?.addEventListener("change", render);
  document.getElementById("entrega-envio")?.addEventListener("change", render);

  render();
});


// API pública
window.Cart = { addToCart, clearCart, render };



// UTILIDADES

function escapeHtml(text) {
  return String(text ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}
