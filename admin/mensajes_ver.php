<?php
// ==========================================
// 1. SEGURIDAD: VERIFICACIÓN DE SESIÓN ACTIVADA
// ==========================================
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../conexion.php';
$mensaje_estado = "";

// ==========================================
// 2. BACKEND: PROCESAR ELIMINACIÓN (DELETE)
// ==========================================
if (isset($_GET['eliminar'])) {
    $id_eliminar = filter_var($_GET['eliminar'], FILTER_VALIDATE_INT);
    
    if ($id_eliminar) {
        try {
            $stmt = $conexion->prepare("DELETE FROM mensajes WHERE id = :id");
            $stmt->execute([':id' => $id_eliminar]);
            $mensaje_estado = "<div class='alert alert-success border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-regular fa-circle-check me-2'></i>Mensaje eliminado de la bandeja de entrada.</div>";
        } catch (PDOException $e) {
            $mensaje_estado = "<div class='alert alert-danger border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-solid fa-circle-exclamation me-2'></i>Error al eliminar el mensaje.</div>";
        }
    }
}

// ==========================================
// 3. BACKEND: OBTENER LISTADO DE MENSAJES (READ)
// ==========================================
try {
    // Obtenemos los mensajes ordenados del más reciente al más antiguo
    $stmt = $conexion->query("SELECT * FROM mensajes ORDER BY id DESC");
    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar la bandeja de entrada: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes Recibidos | Panel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; }
        .sidebar .nav-link { color: #c2c7d0; transition: all 0.2s; border-radius: 5px; margin-bottom: 5px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #495057; color: #fff; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3 shadow">
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-secondary">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold" style="font-size: 14px;"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h6>
                        <span class="badge bg-success" style="font-size: 10px;">Administrador</span>
                    </div>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fa-solid fa-chart-pie me-2"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="biografia_crud.php"><i class="fa-regular fa-id-card me-2"></i> Mi Biografía</a></li>
                    <li class="nav-item"><a class="nav-link" href="habilidades_crud.php"><i class="fa-regular fa-star me-2"></i> Habilidades</a></li>
                    <li class="nav-item"><a class="nav-link" href="tecnologias_crud.php"><i class="fa-solid fa-layer-group me-2"></i> Tecnologías</a></li>
                    <li class="nav-item"><a class="nav-link" href="proyectos_crud.php"><i class="fa-solid fa-folder-open me-2"></i> Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link active" href="mensajes_ver.php"><i class="fa-regular fa-envelope me-2"></i> Mensajes</a></li>
                </ul>

                <div class="mt-5 pt-5">
                    <a href="../logout.php" class="btn btn-outline-danger w-100 rounded-pill btn-sm"><i class="fa-solid fa-power-off me-2"></i> Cerrar Sesión</a>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2 fw-bold text-dark">Bandeja de Entrada</h1>
                        <p class="text-muted small mb-0">Revisa las consultas de tus clientes o reclutadores.</p>
                    </div>
                    <a href="../index.php#contacto" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa-regular fa-paper-plane me-1"></i> Probar Formulario
                    </a>
                </div>

                <?php echo $mensaje_estado; ?>

                <div class="card card-custom bg-white p-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-inbox text-primary me-2"></i> Correos Recibidos</h6>
                    
                    <?php if(!empty($mensajes)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Fecha / Hora</th>
                                        <th class="border-0">Remitente</th>
                                        <th class="border-0">Asunto</th>
                                        <th class="border-0 text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($mensajes as $msg): ?>
                                        <tr>
                                            <td class="text-muted small">
                                                <i class="fa-regular fa-calendar-days me-1"></i> 
                                                <?php 
                                                    // Formatear la fecha si existe la columna fecha_registro
                                                    $fecha = isset($msg['fecha_registro']) ? date("d/m/Y H:i", strtotime($msg['fecha_registro'])) : 'Reciente';
                                                    echo $fecha;
                                                ?>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark small"><?php echo htmlspecialchars($msg['nombre']); ?></div>
                                                <a href="mailto:<?php echo htmlspecialchars($msg['correo']); ?>" class="text-decoration-none text-primary" style="font-size: 12px;">
                                                    <?php echo htmlspecialchars($msg['correo']); ?>
                                                </a>
                                            </td>
                                            <td class="text-muted small fw-medium">
                                                <?php echo htmlspecialchars($msg['asunto']); ?>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-light rounded-pill border me-1" data-bs-toggle="modal" data-bs-target="#modalMensaje<?php echo $msg['id']; ?>">
                                                    <i class="fa-regular fa-eye text-primary"></i> Leer
                                                </button>
                                                <a href="mensajes_ver.php?eliminar=<?php echo $msg['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('¿Borrar este mensaje de forma permanente?');">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="modalMensaje<?php echo $msg['id']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $msg['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header bg-light border-0">
                                                        <h5 class="modal-title fw-bold" id="modalLabel<?php echo $msg['id']; ?>">
                                                            <i class="fa-regular fa-envelope-open text-primary me-2"></i> Detalles del Mensaje
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3 border-bottom pb-3">
                                                            <div class="small text-muted text-uppercase fw-bold mb-1">De:</div>
                                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($msg['nombre']); ?> <span class="text-muted fw-normal">&lt;<?php echo htmlspecialchars($msg['correo']); ?>&gt;</span></div>
                                                        </div>
                                                        <div class="mb-3 border-bottom pb-3">
                                                            <div class="small text-muted text-uppercase fw-bold mb-1">Asunto:</div>
                                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($msg['asunto']); ?></div>
                                                        </div>
                                                        <div>
                                                            <div class="small text-muted text-uppercase fw-bold mb-2">Mensaje:</div>
                                                            <p class="text-secondary mb-0" style="white-space: pre-wrap; font-size: 15px;"><?php echo htmlspecialchars($msg['mensaje']); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 bg-light">
                                                        <a href="mailto:<?php echo htmlspecialchars($msg['correo']); ?>" class="btn btn-primary rounded-pill px-4">
                                                            <i class="fa-solid fa-reply me-1"></i> Responder Email
                                                        </a>
                                                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted my-5">
                            <i class="fa-solid fa-envelope-open-text fa-3x mb-3 opacity-50"></i>
                            <p>Tu bandeja de entrada está limpia. No hay mensajes nuevos.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>