<?php
// ==========================================
// CONFIGURACIÓN DE LA CONEXIÓN A LA BASE DE DATOS (PDO)
// ==========================================

// Parámetros de configuración del servidor local (XAMPP / Laragon)
$host     = 'localhost';
$db       = 'portafolio_db'; // Nombre de la base de datos que importaste en phpMyAdmin
$user     = 'root';          // Usuario por defecto en entornos locales
$password = '';              // Contraseña por defecto en XAMPP (vacía)
$charset  = 'utf8mb4';       // Codificación para soportar tildes, eñes y caracteres especiales

// Construcción del Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones avanzadas de configuración para PDO (Seguridad y Buenas Prácticas)
$options = [
    // 1. Activar el manejo de excepciones para capturar errores de SQL limpiamente
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    
    // 2. Configurar el modo de obtención de datos por defecto como arreglo asociativo
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    
    // 3. Desactivar la emulación de consultas preparadas para que MySQL maneje la seguridad de forma nativa
    // Esto es CRÍTICO para mitigar al 100% ataques de Inyección SQL (SQLi)
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Instanciar el objeto PDO para crear la conexión activa
    $conexion = new PDO($dsn, $user, $password, $options);
    
    // Nota académica: Dejamos la conexión silenciosa en producción por seguridad,
    // pero si necesitas testear que funciona, puedes descomentar la línea de abajo:
    // echo "Conexión exitosa"; 
    
} catch (\PDOException $e) {
    // En caso de error, detenemos la ejecución de la app y mostramos un mensaje controlado.
    // Esto evita que PHP exponga datos sensibles como rutas del servidor o contraseñas en pantalla.
    die("Error crítico del sistema: No se pudo establecer la comunicación con el repositorio de datos. " . $e->getMessage());
}
?>