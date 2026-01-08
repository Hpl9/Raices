<?php


// Crea y devuelve una conexión PDO reutilizable.


function db(): PDO
{
    
    $host = "127.0.0.1";
    $dbname = "raices";   
    $user = "root";
    $pass = "";
    $charset = "utf8mb4";

    // DSN: indica motor, host, base de datos y charset
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

   
  
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // Devuelve conexión PDO
    return new PDO($dsn, $user, $pass, $options);
}
