<?php
// ==========================================
// CIERRE DE SESIÓN SEGURO
// ==========================================

// 1. Iniciar o reanudar la sesión actual para poder destruirla
session_start();

// 2. Vaciar todas las variables de sesión registradas (limpiar el arreglo $_SESSION)
$_SESSION = array();

// 3. Si se desea destruir la sesión completamente, también se borra la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finalmente, destruir la sesión en el servidor
session_destroy();

// 5. Redirigir al usuario de vuelta a la página principal (o al login)
header("Location: index.php");
exit();
?>