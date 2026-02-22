<?php


// Crea y devuelve una conexión PDO reutilizable.


function db(): PDO
{
    
    $host = "db";
    $dbname = "raices";   
    $user = "root";
    $pass = "root";
    $charset = "utf8mb4";

    // DSN: indica motor, host, base de datos y charset
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

   
  
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ];

    // Devuelve conexión PDO
    return new PDO($dsn, $user, $pass, $options);
}
