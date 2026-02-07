
// Objetivo:
// 1) Comprobar sesión (me.php) al cargar y actualizar navbar:
//    - Mostrar "Iniciar sesión" si NO hay sesión
//    - Mostrar "Salir" + "perfil (icono+nombre)" si sí hay sesión
// 2) Enviar login desde el modal (auth.php)
// 3) Cerrar sesión (auth.php)
// 4) Redirigir según rol:
//    - admin  -> /Raices/public/admin/admin.php
//    - cliente-> /Raices/public/cliente/index.html


// Endpoints  API 
const API_ME = "/Raices/public/api/me.php";
const API_AUTH = "/Raices/public/api/auth.php";

// Rutas destino tras login 
const URL_ADMIN_DASHBOARD = "/Raices/public/frontend/admin/admin.html";
const URL_CLIENTE_HOME = "/Raices/public/frontend/cliente/index.html";

document.addEventListener("DOMContentLoaded", () => {
  // Referencias a elementos del DOM (navbar + modal)
  const btnLogin = document.getElementById("btn-login");
  const btnLogout = document.getElementById("btn-logout");

  // (icono + nombre) -> aparece solo con sesión
  const userPill = document.getElementById("user-pill");
  const userName = document.getElementById("user-name");

  // Form de login en el modal
  const loginForm = document.getElementById("loginForm");
  
  // Por defecto: ocultamos logout y user-pill hasta comprobar sesión
  if (btnLogout) btnLogout.classList.add("hidden");
  if (userPill) userPill.classList.add("hidden");
  
  // Actualizamos navbar según sesión actual
  refreshNavbarSession();

  
  //------------------ LOGIN (submit del modal) REDIRECCIÓN URL----------------------

  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();  // Evita recarga

      clearLoginError();

      const formData = new FormData(loginForm);
      const email = (formData.get("email") || "").toString().trim();
      const password = (formData.get("password") || "").toString();

      //  Validación mínima 
      if (!email || !password) {
        showLoginError("Email y contraseña son obligatorios.");
        return;
      }

      //  Enviamos como x-www-form-urlencoded porque en PHP se lee fácil con $_POST
      const body = new URLSearchParams();
      body.append("action", "login");
      body.append("email", email);
      body.append("password", password);

      fetch(API_AUTH, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body.toString(),
      })
        .then((res) => res.json())
        .then((data) => {
          if (!data.ok) {
            showLoginError(data.error || "Credenciales incorrectas.");
            return;
          }

          // Login OK:
            // 1) Cerrar modal
            // 2) Refrescar navbar
            // 3) Redirigir según rol
          closeLoginModal();
          loginForm.reset();

          //  Refrescamos el navbar ( cambio antes de redirigir)
          refreshNavbarSession().then(() => {
            // Redirección por rol
            const rol = data.user?.rol || "cliente";
            if (rol === "admin") {
              window.location.href = URL_ADMIN_DASHBOARD;
            } else {
              window.location.href = URL_CLIENTE_HOME;
            }
          });
        })
        .catch(() => {
          showLoginError("Error de red. Inténtalo de nuevo.");
        });
    });
  }

  
  //----------------- LOGOUT (botón navbar)----------------------
 
  if (btnLogout) {
    btnLogout.addEventListener("click", () => {
      const body = new URLSearchParams();
      body.append("action", "logout");

      fetch(API_AUTH, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body.toString(),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.ok) {
            // Tras logout, refrescamos navbar y nos quedamos en la página a no ser que estemos en admin que redirige a tienda
            refreshNavbarSession();
            window.location.replace("http://localhost/Raices/public/frontend/cliente/index.html");
          }
        })
        .catch(() => {});
    });
  }

  
  // -----------------------FUNCIONES-------------------------
 
  
  function refreshNavbarSession() {
    return fetch(API_ME)
      .then((res) => res.json())
      .then((data) => {
        //  me.php puede devolver:
        // - { ok:true, logged:true/false, user:{...} }
        // - { ok:true, user:{...} } (sin "logged")
        const logged =
          typeof data.logged === "boolean" ? data.logged : Boolean(data.user);

        if (logged) {
          //  Ocultar botón login
          if (btnLogin) btnLogin.style.display = "none";

          //  Mostrar logout
          if (btnLogout) btnLogout.classList.remove("hidden");

          // Mostrar perfil + nombre
          if (userPill) userPill.classList.remove("hidden");                   // El operador '?.' (si no existe-> devuelve undefined) evita errores si 'user' no existe
          if (userName) userName.textContent = data.user?.name ?? "Usuario";   // El operador '??' pone "Usuario" por defecto si el nombre viene vacío

        } else {
          // Mostrar botón login
          if (btnLogin) btnLogin.style.display = "inline-flex";

          //  Ocultar logout
          if (btnLogout) btnLogout.classList.add("hidden");

          //  Ocultar perfil
          if (userPill) userPill.classList.add("hidden");
        }
      })
      .catch(() => {
        if (btnLogin) btnLogin.style.display = "inline-flex";
        if (btnLogout) btnLogout.classList.add("hidden");
        if (userPill) userPill.classList.add("hidden");
      });
  }

  function closeLoginModal() {
    const closeBtn = document.querySelector('[data-modal-hide="loginModal"]');
    if (closeBtn) closeBtn.click();
  }

  function showLoginError(msg) {
    // Inserta error dentro del modal
    let box = document.getElementById("loginError");
    if (!box) {
      box = document.createElement("div");
      box.id = "loginError";
      box.className =
        "mt-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700";

      const btn = document.getElementById("btn-login-modal");
      btn?.parentElement?.appendChild(box);
    }
    box.textContent = msg;
  }

  function clearLoginError() {
    const box = document.getElementById("loginError");
    if (box) box.remove();
  }
});
