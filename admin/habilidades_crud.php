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
// Verificamos si se envió un ID por la URL (método GET) para eliminar
if (isset($_GET['eliminar'])) {
    $id_eliminar = filter_var($_GET['eliminar'], FILTER_VALIDATE_INT);
    
    if ($id_eliminar) {
        try {
            $stmt = $conexion->prepare("DELETE FROM habilidades WHERE id = :id");
            $stmt->execute([':id' => $id_eliminar]);
            $mensaje_estado = "<div class='alert alert-success border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-regular fa-circle-check me-2'></i>Habilidad eliminada correctamente.</div>";
        } catch (PDOException $e) {
            $mensaje_estado = "<div class='alert alert-danger border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-solid fa-circle-exclamation me-2'></i>Error al eliminar la habilidad.</div>";
        }
    }
}

// ==========================================
// 3. BACKEND: PROCESAR INSERCIÓN (CREATE)
// ==========================================
// Verificamos si se envió el formulario para agregar una nueva habilidad (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_habilidad'])) {
    $nombre = strip_tags(trim($_POST['nombre']));
    $icono_clase = strip_tags(trim($_POST['icono_clase']));

    if (!empty($nombre) && !empty($icono_clase)) {
        try {
            $stmt = $conexion->prepare("INSERT INTO habilidades (nombre, icono_clase) VALUES (:nombre, :icono_clase)");
            $stmt->execute([
                ':nombre' => $nombre,
                ':icono_clase' => $icono_clase
            ]);
            $mensaje_estado = "<div class='alert alert-success border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-regular fa-circle-check me-2'></i>Nueva habilidad agregada al portafolio.</div>";
        } catch (PDOException $e) {
            $mensaje_estado = "<div class='alert alert-danger border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-solid fa-circle-exclamation me-2'></i>Error al guardar la habilidad.</div>";
        }
    } else {
        $mensaje_estado = "<div class='alert alert-warning border-0 shadow-sm rounded-pill small py-2 mb-4'><i class='fa-solid fa-triangle-exclamation me-2'></i>Todos los campos son obligatorios.</div>";
    }
}

// ==========================================
// 4. BACKEND: OBTENER LISTADO ACTUAL (READ)
// ==========================================
try {
    $stmt = $conexion->query("SELECT * FROM habilidades ORDER BY id DESC");
    $habilidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar las habilidades: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilidades | Panel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; }
        .sidebar .nav-link { color: #c2c7d0; transition: all 0.2s; border-radius: 5px; margin-bottom: 5px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #495057; color: #fff; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .icon-preview { font-size: 1.5rem; color: #0d6efd; }
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
                    <li class="nav-item"><a class="nav-link active" href="habilidades_crud.php"><i class="fa-regular fa-star me-2"></i> Habilidades</a></li>
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
                        <h1 class="h2 fw-bold text-dark">Gestionar Habilidades</h1>
                        <p class="text-muted small mb-0">Agrega o elimina las herramientas y aptitudes que dominas.</p>
                    </div>
                    <a href="../index.php#habilidades" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa-solid fa-eye me-1"></i> Ver Cambios
                    </a>
                </div>

                <?php echo $mensaje_estado; ?>

                <div class="row">
                    <div class="col-md-5 mb-4">
                        <div class="card card-custom bg-white p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-plus text-primary me-2"></i> Nueva Habilidad</h6>
                            <form action="habilidades_crud.php" method="POST">
                                <input type="hidden" name="agregar_habilidad" value="1">
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Nombre de la Herramienta</label>
                                    <input type="text" name="nombre" class="form-control bg-light border-0 py-2" placeholder="Ej: React JS" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-secondary">Clase del Ícono (FontAwesome)</label>
                                    <input type="text" name="icono_clase" class="form-control bg-light border-0 py-2" placeholder="Ej: fa-brands fa-react" required>
                                    <div class="form-text text-muted" style="font-size: 11px;">
                                        Encuentra íconos en <a href="https://fontawesome.com/search?o=r&m=free" target="_blank">FontAwesome Free</a>.
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                    <i class="fa-solid fa-check me-2"></i> Agregar Habilidad
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card card-custom bg-white p-4 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list text-primary me-2"></i> Habilidades Actuales</h6>
                            
                            <?php if(!empty($habilidades)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0">Ícono</th>
                                                <th class="border-0">Nombre</th>
                                                <th class="border-0">Código de Clase</th>
                                                <th class="border-0 text-end">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($habilidades as $hab): ?>
                                                <tr>
                                                    <td><i class="<?php echo htmlspecialchars($hab['icono_clase']); ?> icon-preview"></i></td>
                                                    <td class="fw-bold text-dark small"><?php echo htmlspecialchars($hab['nombre']); ?></td>
                                                    <td><code class="text-muted" style="font-size: 12px;"><?php echo htmlspecialchars($hab['icono_clase']); ?></code></td>
                                                    <td class="text-end">
                                                        <a href="habilidades_crud.php?eliminar=<?php echo $hab['id']; ?>" 
                                                           class="btn btn-sm btn-outline-danger rounded-pill"
                                                           onclick="return confirm('¿Estás seguro de que deseas eliminar esta habilidad?');">
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
                                    <i class="fa-regular fa-folder-open fa-3x mb-3 opacity-50"></i>
                                    <p>No tienes habilidades registradas.</p>
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