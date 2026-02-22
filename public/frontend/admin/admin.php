<?php
// admin.php


session_start();

// 1) Si no hay sesión -> fuera
if (empty($_SESSION['user'])) {
  header('Location: /frontend/cliente/index.html');
  exit;
}

// 2) Si no es admin -> fuera 
$rol = $_SESSION['user']['rol'] ?? ($_SESSION['user']['role'] ?? null);
if ($rol !== 'admin') {
  header('Location: /frontend/cliente/index.html');
  exit;
}

// 3) Datos para pintar en HTML
$userName = $_SESSION['user']['nombre'] ?? $_SESSION['user']['name'] ?? 'Administrador';

$activeTab = 'productos';
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Raíces/GestiónProductos</title>

  <!-- Estilos generales  -->
  <link rel="stylesheet" href="/frontend/cliente/assets/css/app.css" />

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Flowbite -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.css" />
</head>

<body class="bg-white text-gray-900">

  <!-------NAVBAR ADMIN -------->

  <?php require __DIR__ . '/components/navbar-admin.php'; ?>

  <!---------------------MAIN ----------------------------->
  <main class="mx-auto max-w-6xl px-4 py-10">

    <!----- Panel admin + tabs ------>
    
  <?php require __DIR__ . '/components/panel-tabs.php'; ?>

    <!-- header tabla + botón añadir -->
    <section class="mt-8 flex items-end justify-between gap-4">
      <div>
        <h2 class="text-2xl font-extrabold text-brand">Gestión de Productos</h2>
        <p id="productos-count" class="mt-1 text-sm text-gray-600">— productos en catálogo</p>
      </div>
       
      <button
        id="btn-add-product"
        type="button"
        data-modal-target="productoModal"
        data-modal-toggle="productoModal" 
        class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95">
        <span class="text-lg leading-none">＋</span>
        Añadir Producto
      </button> 

      
    </section>

    <!------------------TABLA PRODUCTOS-------------------------->
    <section class="mt-4 rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-[#EAF2E3] text-gray-700">
            <tr>
              <th class="px-6 py-4 font-bold">Producto</th>
              <th class="px-6 py-4 font-bold">Categoría</th>
              <th class="px-6 py-4 font-bold">Socio</th>
              <th class="px-6 py-4 font-bold">Precio</th>
              <th class="px-6 py-4 font-bold">Stock</th>
              <th class="px-6 py-4 font-bold">Procedencía</th>
              <th class="px-6 py-4 font-bold text-right">Acciones</th>
            </tr>
          </thead>

          <tbody id="adminProductosTbody" class="divide-y divide-gray-100">
            <!-- filas desde API -->
          </tbody>
        </table>
      </div>
    </section>

  </main>


  <!--------- MODAL PRODUCTO (Crear / Editar)--------------------->

  <?php require __DIR__ . '/components/modal-producto.php'; ?>

   <!-- Flowbite JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.js"></script>

  <script src="/frontend/admin/assets/js/gestionProductos.js"></script>
  <script src="/frontend/cliente/assets/js/auth.js?v=2"></script>

 
</body>

</html>