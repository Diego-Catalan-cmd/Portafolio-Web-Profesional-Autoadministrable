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
            $stmt = $conexion->prepare("DELETE FROM proyectos WHERE id = :id");
            $stmt->execute([':id' => $id_eliminar]);
            $mensaje_estado = "<div class='alert alert-success border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-regular fa-circle-check me-2'></i>Proyecto eliminado exitosamente del portafolio.</div>";
        } catch (PDOException $e) {
            $mensaje_estado = "<div class='alert alert-danger border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-solid fa-circle-exclamation me-2'></i>Error al eliminar el proyecto.</div>";
        }
    }
}

// ==========================================
// 3. BACKEND: PROCESAR INSERCIÓN (CREATE)
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_proyecto'])) {
    // Limpieza de campos de texto
    $titulo = strip_tags(trim($_POST['titulo']));
    $descripcion = strip_tags(trim($_POST['descripcion']));
    
    // Limpieza y validación de URLs
    $imagen_url = filter_var(trim($_POST['imagen_url']), FILTER_SANITIZE_URL);
    $link_demo = filter_var(trim($_POST['link_demo']), FILTER_SANITIZE_URL);
    $link_github = filter_var(trim($_POST['link_github']), FILTER_SANITIZE_URL);

    if (!empty($titulo) && !empty($descripcion)) {
        try {
            $sql = "INSERT INTO proyectos (titulo, descripcion, imagen_url, link_demo, link_github) 
                    VALUES (:titulo, :descripcion, :imagen_url, :link_demo, :link_github)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':imagen_url' => $imagen_url,
                ':link_demo' => $link_demo,
                ':link_github' => $link_github
            ]);
            $mensaje_estado = "<div class='alert alert-success border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-regular fa-circle-check me-2'></i>Nuevo proyecto publicado correctamente.</div>";
        } catch (PDOException $e) {
            $mensaje_estado = "<div class='alert alert-danger border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-solid fa-circle-exclamation me-2'></i>Error al guardar el proyecto en la base de datos.</div>";
        }
    } else {
        $mensaje_estado = "<div class='alert alert-warning border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-solid fa-triangle-exclamation me-2'></i>El título y la descripción son obligatorios.</div>";
    }
}

// ==========================================
// 4. BACKEND: OBTENER LISTADO ACTUAL (READ)
// ==========================================
try {
    $stmt = $conexion->query("SELECT * FROM proyectos ORDER BY id DESC");
    $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar los proyectos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos | Panel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; }
        .sidebar .nav-link { color: #c2c7d0; transition: all 0.2s; border-radius: 5px; margin-bottom: 5px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #495057; color: #fff; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .img-thumbnail-custom { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;}
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
                    <li class="nav-item"><a class="nav-link active" href="proyectos_crud.php"><i class="fa-solid fa-folder-open me-2"></i> Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link" href="mensajes_ver.php"><i class="fa-regular fa-envelope me-2"></i> Mensajes</a></li>
                </ul>

                <div class="mt-5 pt-5">
                    <a href="../logout.php" class="btn btn-outline-danger w-100 rounded-pill btn-sm"><i class="fa-solid fa-power-off me-2"></i> Cerrar Sesión</a>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <div>
                        <h1 class="h2 fw-bold text-dark">Portafolio de Proyectos</h1>
                        <p class="text-muted small mb-0">Sube tus trabajos más recientes para mostrarlos al público.</p>
                    </div>
                    <a href="../index.php#proyectos" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa-solid fa-eye me-1"></i> Ver Cambios
                    </a>
                </div>

                <?php echo $mensaje_estado; ?>

                <div class="row">
                    <div class="col-md-12 col-lg-4 mb-4">
                        <div class="card card-custom bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-plus text-primary me-2"></i> Publicar Proyecto</h6>
                            <form action="proyectos_crud.php" method="POST">
                                <input type="hidden" name="agregar_proyecto" value="1">
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Título del Proyecto <span class="text-danger">*</span></label>
                                    <input type="text" name="titulo" class="form-control bg-light border-0 py-2" placeholder="Ej: Sistema de Ventas" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Descripción Breve <span class="text-danger">*</span></label>
                                    <textarea name="descripcion" class="form-control bg-light border-0 py-2" rows="3" placeholder="Herramientas usadas y objetivo..." required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">URL de la Imagen Promocional</label>
                                    <input type="url" name="imagen_url" class="form-control bg-light border-0 py-2" placeholder="https://ejemplo.com/imagen.jpg">
                                    <div class="form-text text-muted" style="font-size: 11px;">Enlace externo a la captura de pantalla del proyecto.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Enlace a Demo (Opcional)</label>
                                    <input type="url" name="link_demo" class="form-control bg-light border-0 py-2" placeholder="https://miproyecto.com">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-secondary">Enlace a GitHub (Opcional)</label>
                                    <input type="url" name="link_github" class="form-control bg-light border-0 py-2" placeholder="https://github.com/usuario/repo">
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                    <i class="fa-solid fa-cloud-arrow-up me-2"></i> Guardar Proyecto
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-8">
                        <div class="card card-custom bg-white p-4 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-briefcase text-primary me-2"></i> Proyectos Publicados</h6>
                            
                            <?php if(!empty($proyectos)): ?>
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0">Imagen</th>
                                                <th class="border-0">Información</th>
                                                <th class="border-0 text-center">Enlaces</th>
                                                <th class="border-0 text-end">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($proyectos as $proy): ?>
                                                <tr>
                                                    <td>
                                                        <?php if(!empty($proy['imagen_url'])): ?>
                                                            <img src="<?php echo htmlspecialchars($proy['imagen_url']); ?>" alt="Img" class="img-thumbnail-custom">
                                                        <?php else: ?>
                                                            <div class="img-thumbnail-custom text-muted"><i class="fa-regular fa-image"></i></div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark small mb-1"><?php echo htmlspecialchars($proy['titulo']); ?></div>
                                                        <div class="text-muted text-truncate" style="max-width: 200px; font-size: 12px;">
                                                            <?php echo htmlspecialchars($proy['descripcion']); ?>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if(!empty($proy['link_demo'])): ?>
                                                            <a href="<?php echo htmlspecialchars($proy['link_demo']); ?>" target="_blank" class="text-primary me-2" title="Ver Demo"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                                        <?php endif; ?>
                                                        <?php if(!empty($proy['link_github'])): ?>
                                                            <a href="<?php echo htmlspecialchars($proy['link_github']); ?>" target="_blank" class="text-dark" title="Ver Código"><i class="fa-brands fa-github"></i></a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="proyectos_crud.php?eliminar=<?php echo $proy['id']; ?>" 
                                                           class="btn btn-sm btn-outline-danger rounded-pill"
                                                           onclick="return confirm('¿Confirmas que deseas eliminar este proyecto definitivamente?');">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted my-5">
                                    <i class="fa-solid fa-folder-open fa-3x mb-3 opacity-50"></i>
                                    <p>No hay proyectos en tu portafolio actualmente.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>