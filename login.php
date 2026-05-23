<?php
// ==========================================
// 1. INICIO DE SESIÓN Y SEGURIDAD
// ==========================================
// session_start() debe ser la primera línea ejecutada para manejar las variables de sesión
session_start();

// Si el usuario ya está logueado, lo redirigimos directamente al dashboard para que no vea el login de nuevo
if (isset($_SESSION['usuario_id'])) {
    header("Location: admin/dashboard.php");
    exit();
}

require_once 'conexion.php';

$mensaje_error = '';

// Procesar el formulario cuando se envía por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validar que no estén vacíos
    if (!empty($email) && !empty($password)) {
        try {
            // Consultar si el correo existe en la base de datos
            $stmt = $conexion->prepare("SELECT id, nombre, password FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Validar si el usuario existe y si la contraseña coincide con el HASH almacenado
            if ($usuario && password_verify($password, $usuario['password'])) {
                // Credenciales correctas: Crear variables de sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                
                // Redirigir al panel de administración (asegúrate de que crearemos esta carpeta luego)
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $mensaje_error = "Credenciales incorrectas. Por favor, verifica tu correo y contraseña.";
            }
        } catch (PDOException $e) {
            $mensaje_error = "Error en el servidor. Intenta nuevamente.";
        }
    } else {
        $mensaje_error = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .login-card { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .login-icon { font-size: 3rem; color: #0d6efd; margin-bottom: 15px; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card login-card p-4 bg-white">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-user-shield login-icon"></i>
                        <h4 class="mb-4 fw-bold">Acceso Administrativo</h4>

                        <?php if(!empty($mensaje_error)): ?>
                            <div class="alert alert-danger shadow-sm border-0 py-2" role="alert" style="font-size: 14px;">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo $mensaje_error; ?>
                            </div>
                        <?php endif; ?>

                        <form action="login.php" method="POST">
                            <div class="mb-3 text-start">
                                <label class="form-label text-muted small fw-bold">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-start-0" placeholder="admin@correo.com" required autofocus>
                                </div>
                            </div>

                            <div class="mb-4 text-start">
                                <label class="form-label text-muted small fw-bold">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm mb-3">
                                <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Iniciar Sesión
                            </button>
                        </form>

                        <a href="index.php" class="text-decoration-none text-muted small">
                            <i class="fa-solid fa-arrow-left me-1"></i> Volver al Portafolio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>