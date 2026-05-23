<?php
// ==========================================
// 1. SEGURIDAD: VERIFICACIÓN DE SESIÓN ACTIVADA
// ==========================================
session_start();

// Si la sesión no existe, expulsamos al usuario al inicio de sesión externo
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

// Conectamos a la base de datos subiendo un nivel con '../'
require_once '../conexion.php';

$mensaje_estado = "";

// ==========================================
// 2. BACKEND: PROCESAR ACTUALIZACIÓN (UPDATE)
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y limpiar entradas
    $nombre = strip_tags(trim($_POST['nombre_completo']));
    $titulo = strip_tags(trim($_POST['titulo_profesional']));
    $descripcion = strip_tags(trim($_POST['descripcion']));
    $cv_url = strip_tags(trim($_POST['cv_url']));

    // Validar que los campos obligatorios no estén vacíos
    if (!empty($nombre) && !empty($titulo) && !empty($descripcion)) {
        try {
            // Como solo manejamos un registro de biografía, actualizamos el primero que encuentre (o ID 1)
            // Usamos consultas preparadas robustas para evitar Inyección SQL (Exigencia de la Rúbrica)
            $sql = "UPDATE biografia SET 
                        nombre_completo = :nombre, 
                        titulo_profesional = :titulo, 
                        descripcion = :descripcion, 
                        cv_url = :cv_url 
                    WHERE id = 1"; // Ajusta el ID según corresponda en tus registros iniciales
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre'      => $nombre,
                ':titulo'      => $titulo,
                ':descripcion' => $descripcion,
                ':cv_url'      => $cv_url
            ]);

            $mensaje_estado = "<div class='alert alert-success border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-regular fa-circle-check me-2'></i>Biografía actualizada con éxito en la base de datos.</div>";
        } catch (PDOException $e) {
            $mensaje_estado = "<div class='alert alert-danger border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-solid fa-circle-exclamation me-2'></i>Error al actualizar los datos en el servidor.</div>";
        }
    } else {
        $mensaje_estado = "<div class='alert alert-warning border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-solid fa-triangle-exclamation me-2'></i>Por favor, rellena todos los campos obligatorios.</div>";
    }
}

// ==========================================
// 3. BACKEND: LEER DATOS ACTUALES (READ)
// ==========================================
try {
    $stmt = $conexion->query("SELECT * FROM biografia LIMIT 1");
    $biografia = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Si la tabla estuviera vacía, creamos un arreglo vacío por consistencia estructural
    if (!$biografia) {
        $biografia = [
            'nombre_completo' => '',
            'titulo_profesional' => '',
            'descripcion' => '',
            'cv_url' => ''
        ];
    }
} catch (PDOException $e) {
    die("Error crítico al recuperar la información biográfica: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Biografía | Panel Administrativo</title>
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
                    <li class="nav-item"><a class="nav-link active" href="biografia_crud.php"><i class="fa-regular fa-id-card me-2"></i> Mi Biografía</a></li>
                    <li class="nav-item"><a class="nav-link" href="habilidades_crud.php"><i class="fa-regular fa-star me-2"></i> Habilidades</a></li>
                    <li class="nav-item"><a class="nav-link" href="tecnologias_crud.php"><i class="fa-solid fa-layer-group me-2"></i> Tecnologías</a></li>
                    <li class="nav-item"><a class="nav-link" href="proyectos_crud.php"><i class="fa-solid fa-folder-open me-2"></i> Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link" href="mensajes_ver.php"><i class="fa-regular fa-envelope me-2"></i> Mensajes</a></li>
                </ul>

                <div class="mt-5 pt-5">
                    <a href="../logout.php" class="btn btn-outline-danger w-100 rounded-pill btn-sm"><i class="fa-solid fa-power-off me-2"></i> Cerrar Sesión</a>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2 fw-bold text-dark">Gestionar Biografía</h1>
                        <p class="text-muted small mb-0">Modifica los datos principales del perfil profesional que se visualizan en el Hero de la web pública.</p>
                    </div>
                    <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa-solid fa-globe me-1"></i> Ver Sitio Público
                    </a>
                </div>

                <?php echo $mensaje_estado; ?>

                <div class="card card-custom bg-white p-4 p-md-5">
                    <h5 class="fw-bold text-dark mb-4"><i class="fa-regular fa-pen-to-square text-primary me-2"></i> Actualizar Perfil</h5>
                    
                    <form action="biografia_crud.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" name="nombre_completo" class="form-control bg-light border-0 py-2" 
                                       value="<?php echo htmlspecialchars($biografia['nombre_completo']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Título Profesional / Técnico <span class="text-danger">*</span></label>
                                <input type="text" name="titulo_profesional" class="form-control bg-light border-0 py-2" 
                                       value="<?php echo htmlspecialchars($biografia['titulo_profesional']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">URL del Currículum Vitae (Google Drive / Dropbox)</label>
                            <input type="url" name="cv_url" class="form-control bg-light border-0 py-2" 
                                   placeholder="https://drive.google.com/... (Opcional)"
                                   value="<?php echo htmlspecialchars($biografia['cv_url']); ?>">
                            <div class="form-text text-muted" style="font-size: 11px;">Enlace público para la descarga de tu documento desde el portafolio público.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Descripción Profesional Breve <span class="text-danger">*</span></label>
                            <textarea name="descripcion" class="form-control bg-light border-0 py-2" rows="5" required><?php echo htmlspecialchars($biografia['descripcion']); ?></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="fa-regular fa-floppy-disk me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </main>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>