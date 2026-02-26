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


    </main>

<!-- Auth  -->
<script src="/Raices/public/frontend/cliente/assets/js/auth.js?v=2"></script>

</body>

</html>