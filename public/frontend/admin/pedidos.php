<?php
// admin.php


session_start();

// 1) Si no hay sesión -> fuera
if (empty($_SESSION['user'])) {
    header('Location: /Raices/public/frontend/cliente/index.html');
    exit;
}

// 2) Si no es admin -> fuera 
$rol = $_SESSION['user']['rol'] ?? ($_SESSION['user']['role'] ?? null);
if ($rol !== 'admin') {
    header('Location: /Raices/public/frontend/cliente/index.html');
    exit;
}

// 3) Datos para pintar en HTML
$userName = $_SESSION['user']['nombre'] ?? $_SESSION['user']['name'] ?? 'Administrador';

$activeTab = 'pedidos';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raíces/Pedidos</title>

    <!-- Estilos generales  -->
    <link rel="stylesheet" href="http://localhost/Raices/public/frontend/cliente/assets/css/app.css" />

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

      <!-- header tabla---->

     <section class="mt-8 flex items-end justify-between gap-4">
      <div>
        <h2 class="text-2xl font-extrabold text-brand">Gestión de Pedidos</h2>
        
      </div>
       
    </section>

     <section class="mt-4 rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-[#EAF2E3] text-gray-700">
            <tr>
              <th class="px-6 py-4 font-bold">Pedido</th>
              <th class="px-6 py-4 font-bold">Cliente</th>
              <th class="px-6 py-4 font-bold">Dirección</th>
              <th class="px-6 py-4 font-bold">Entrega</th>
              <th class="px-6 py-4 font-bold">Total</th>
              <th class="px-6 py-4 font-bold">Estado</th>
            </tr>
          </thead>

          <tbody id="adminPedidosTbody" class="divide-y divide-gray-100">
            <!-- filas desde API -->
          </tbody>
        </table>
      </div>
    </section>




    </main>

       <!-- Flowbite JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.js"></script>

  <script src="/Raices/public/frontend/admin/assets/js/admin_pedidos.js"></script>
  <script src="/Raices/public/frontend/cliente/assets/js/auth.js?v=2"></script>
</body>

</html>