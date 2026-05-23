<?php
// Configuración para el servidor de la universidad
$host = 'localhost'; // 'localhost' porque el código PHP correrá dentro del mismo servidor de Teclab
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
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>