<?php
require_once 'conexion.php'; 

try {
    $pdo = db();
    
    // 1. Preparamos la consulta (tabla usuarios)
    
    $stmt = $pdo->query("SELECT id, nombre, email FROM usuarios");
    $clientes = $stmt->fetchAll();

    echo "<h1>Lista de Clientes</h1>";

    if (count($clientes) > 0) {
        echo "<ul>";
        // 2. Recorremos los resultados
        foreach ($clientes as $cliente) {
            // Usamos htmlspecialchars por seguridad (evita XSS)
            echo "<li>";
            echo "<strong>ID:</strong> " . htmlspecialchars($cliente['id']) . " - ";
            echo "<strong>Nombre:</strong> " . htmlspecialchars($cliente['nombre']) . " - ";
            echo "<strong>Email:</strong> " . htmlspecialchars($cliente['email']);
            echo "</li>";
        }
        echo "</ul>";
        echo "<p>Total de clientes: " . count($clientes) . "</p>";
    } else {
        echo "<p>Conexión exitosa, pero la tabla está vacía.</p>";
    }

} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}