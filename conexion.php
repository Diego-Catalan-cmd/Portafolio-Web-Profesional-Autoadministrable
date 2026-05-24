<?php
// CONFIGURACIÓN OFICIAL PARA PRODUCCIÓN EN TECLAB
$host = 'localhost'; 
$db   = 'dcatalan_db1';
$user = 'dcatalan';
$pass = 'DcX91mQp#';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $conexion = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // En producción es mejor no mostrar el error crudo por seguridad de la rúbrica
     die("Error de conexión al sistema."); 
}
?>