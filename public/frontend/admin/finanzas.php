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

$activeTab = 'finanzas'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raíces/Finanzas</title>
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

        
    <section class="mt-8">
      <h2 class="text-2xl font-extrabold text-brand">Finanzas</h2>
      <p class="text-sm text-gray-600 mt-1">Resumen  basado en pedidos y ventas por socio.</p>
    </section>

    <section class="mt-4" id="finanzasCards"></section>
    <div id="finanzasSocios"></div>

   <!---------------------CARDS ----------------------------->
   <section class="mt-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5">
          <p class="text-xs text-gray-500">Pedidos</p>
          <p id="finCardPedidos" class="mt-1 text-2xl font-extrabold text-gray-900">—</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5">
          <p class="text-xs text-gray-500">Envíos</p>
          <p id="finCardEnvios" class="mt-1 text-2xl font-extrabold text-gray-900">—</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5">
          <p class="text-xs text-gray-500">Total productos</p>
          <p id="finCardTotalProductos" class="mt-1 text-2xl font-extrabold text-gray-900">—</p>
        </div>
      </div>
    </section>
    
   <!-------------TABLA BENEFICIOS ----------------------------->
   <section class="mt-8 rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
      <div class="px-6 py-4 bg-[#EAF2E3] text-gray-700 font-bold">
        Beneficios por socio
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
          <thead class="bg-white text-gray-700">
            <tr>
              <th class="px-6 py-4 font-bold">Socio</th>
              <th class="px-6 py-4 font-bold text-right">Ventas (productos)</th>
            </tr>
          </thead>

          <tbody id="finanzasSociosBody" class="divide-y divide-gray-100">
            <tr>
              <td colspan="2" class="px-6 py-6 text-gray-500">Cargando…</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    </main>

<!-- Auth  -->
<script src="/Raices/public/frontend/cliente/assets/js/auth.js?v=2"></script>

<script src="/Raices/public/frontend/admin/assets/js/finanzas.js?v=2"></script>

</body>

</html>