document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registerForm");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();



    //  Validación  HTML (required, pattern, etc.)
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    //  Recoger datos
    const data = Object.fromEntries(new FormData(form).entries());

    // Normalización básica
    if (data.email) data.email = data.email.trim().toLowerCase();
    if (data.nombre) data.nombre = data.nombre.trim();
    if (data.apellido) data.apellido = data.apellido.trim();

    try {
      const res = await fetch("/Raices/public/api/registro.php", {
        method: "POST",
        headers: { "Content-Type": "application/json; charset=utf-8" },
        body: JSON.stringify(data),
        });

        const json = await res.json().catch(() => null);

        if (!res.ok || !json?.ok) {
        alert(json?.error || `Error (${res.status})`);
        return;
        }

        alert("✅ Cuenta creada correctamente");
        
      form.reset();
     

      // 3️ Cerrar modal
      document.getElementById("registerModal")?.classList.add("hidden");

    } catch (error) {
      console.error(error);
      alert("Error de conexión con el servidor.");
    }
  });
});