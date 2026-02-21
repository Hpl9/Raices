


const API_PEDIDOS = "/Raices/public/api/pedidos.php";


function readCart() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    const items = raw ? JSON.parse(raw) : [];
    return Array.isArray(items) ? items : [];
  } catch {
    return [];
  }
}

function getEntrega() {
  return document.getElementById("entrega-envio")?.checked ? "envio" : "recogida";
}

function clearCartStorage() {
  localStorage.setItem(STORAGE_KEY, "[]");
  // Avisar al carrito para que repinte (si existe)
  window.Cart?.render?.();
}

document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("btn-checkout");
  if (!btn) return;

  btn.addEventListener("click", async () => {
    const cart = readCart();

    if (!cart.length) {
      alert("El carrito está vacío");
      return;
    }

    const payload = {
      tipo_entrega: getEntrega(),
      items: cart.map((it) => ({
        id: Number(it.id),
        qty: Number(it.qty || 1),
      })),
    };

    try {
      const res = await fetch(API_PEDIDOS, {
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json", "Accept": "application/json" },
        body: JSON.stringify(payload),
      });

      const text = await res.text();
      console.log("RESPONSE TEXT:", text);
      let data = {};
      try { data = JSON.parse(text); } catch {}
      console.log("PARSED:", data);
      

      if (!res.ok || !data.ok) {
        alert(data.error || "No se pudo crear el pedido");
        return;
      }

      window.Cart?.clearCart?.();
      alert("Pedido creado ✅ Nº " + data.pedido_id);
      document.querySelector('[data-modal-hide="carrito-modal"]')?.click();

    } catch (e) {
      alert("Error de red");
    }
  });
});