<?php
// ==========================================
// 1. SEGURIDAD: VERIFICACIÓN DE SESIÓN ACTIVADA
// ==========================================
session_start();

// Si la variable de sesión no existe, significa que no ha pasado por login.php -> ¡Expulsado!
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

// Conectar a la base de datos (subiendo un nivel con '../' ya que estamos dentro de /admin)
require_once '../conexion.php';

// ==========================================
// 2. BACKEND: OBTENER MÉTRICAS EN TIEMPO REAL
// ==========================================
try {
    // Contar total de proyectos
    $countProy = $conexion->query("SELECT COUNT(*) FROM proyectos")->fetchColumn();

    // Contar total de habilidades
    $countHab = $conexion->query("SELECT COUNT(*) FROM habilidades")->fetchColumn();

    // Contar total de tecnologías
    $countTech = $conexion->query("SELECT COUNT(*) FROM tecnologias")->fetchColumn();

    // Contar total de mensajes recibidos de visitantes
    $countMsg = $conexion->query("SELECT COUNT(*) FROM mensajes")->fetchColumn();
    
    // Obtener los 3 últimos mensajes para mostrar un resumen rápido
    $stmtUltimosMsg = $conexion->query("SELECT * FROM mensajes ORDER BY id DESC LIMIT 3");
    $ultimosMensajes = $stmtUltimosMsg->fetchAll();

} catch (PDOException $e) {
    die("Error al cargar las métricas del panel administrativo: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; }
        .sidebar .nav-link { color: #c2c7d0; transition: all 0.2s; border-radius: 5px; margin-bottom: 5px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #495057; color: #fff; }
        .metric-card { border: none; border-radius: 15px; transition: transform 0.2s; }
        .metric-card:hover { transform: translateY(-3px); }
        .icon-box { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
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
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fa-solid fa-chart-pie me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="biografia_crud.php">
                            <i class="fa-regular fa-id-card me-2"></i> Mi Biografía
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="habilidades_crud.php">
                            <i class="fa-regular fa-star me-2"></i> Habilidades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tecnologias_crud.php">
                            <i class="fa-solid fa-layer-group me-2"></i> Tecnologías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="proyectos_crud.php">
                            <i class="fa-solid fa-folder-open me-2"></i> Proyectos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" href="mensajes_ver.php">
                            <span><i class="fa-regular fa-envelope me-2"></i> Mensajes</span>
                            <?php if($countMsg > 0): ?>
                                <span class="badge bg-danger rounded-pill"><?php echo $countMsg; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>

                <div class="mt-5 pt-5">
                    <a href="../logout.php" class="btn btn-outline-danger w-100 rounded-pill btn-sm">
                        <i class="fa-solid fa-power-off me-2"></i> Cerrar Sesión
                    </a>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2 fw-bold text-dark">Panel de Control</h1>
                        <p class="text-muted small mb-0">Bienvenido al sistema de administración autónoma de tu portafolio web.</p>
                    </div>
                    <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa-solid fa-globe me-1"></i> Ver Sitio Público
                    </a>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card metric-card shadow-sm bg-white p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Proyectos</span>
                                    <h3 class="fw-bold text-dark mb-0"><?php echo $countProy; ?></h3>
                                </div>
                                <div class="icon-box bg-light-primary text-primary" style="background-color: #e6f0ff;">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card metric-card shadow-sm bg-white p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Habilidades</span>
                                    <h3 class="fw-bold text-dark mb-0"><?php echo $countHab; ?></h3>
                                </div>
                                <div class="icon-box text-warning" style="background-color: #fff9e6;">
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card metric-card shadow-sm bg-white p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Tecnologías</span>
                                    <h3 class="fw-bold text-dark mb-0"><?php echo $countTech; ?></h3>
                                </div>
                                <div class="icon-box text-success" style="background-color: #e6f7ed;">
                                    <i class="fa-solid fa-code"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card metric-card shadow-sm bg-white p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Mensajes</span>
                                    <h3 class="fw-bold text-dark mb-0"><?php echo $countMsg; ?></h3>
                                </div>
                                <div class="icon-box text-danger" style="background-color: #ffe6e6;">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="fa-regular fa-paper-plane text-primary me-2"></i> Mensajes Recientes de Contacto</h5>
                            
                            <?php if(!empty($ultimosMensajes)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0">Remitente</th>
                                                <th class="border-0">Asunto</th>
                                                <th class="border-0 text-end">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($ultimosMensajes as $msg): ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark" style="font-size: 14px;"><?php echo htmlspecialchars($msg['nombre']); ?></div>
                                                        <small class="text-muted" style="font-size: 11px;"><?php echo htmlspecialchars($msg['correo']); ?></small>
                                                    </td>
                                                    <td class="text-muted small"><?php echo htmlspecialchars($msg['asunto']); ?></td>
                                                    <td class="text-end">
                                                        <a href="mensajes_ver.php" class="btn btn-sm btn-light rounded-pill border"><i class="fa-regular fa-eye"></i> Leer</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small my-3">No has recibido mensajes a través del formulario público todavía.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 mt-3 mt-lg-0">
                        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="fw-bold mb-2">Instrucciones de Uso</h5>
                                <p class="small opacity-75" style="line-height: 1.6;">Utiliza el menú de navegación lateral para gestionar la información de tu portafolio. Cualquier cambio realizado se reflejará inmediatamente en la vista pública.</p>
                            </div>
                            <div class="border-top border-white border-opacity-25 pt-3">
                                <small class="d-block opacity-50">Estado de Seguridad:</small>
                                <small class="fw-bold"><i class="fa-solid fa-shield-halved me-1 text-warning"></i> Sesión SSL/PHP Protegida</small>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>