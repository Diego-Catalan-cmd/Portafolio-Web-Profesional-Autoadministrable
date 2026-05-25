<?php
// 1. INCLUSIÓN DE LA CONEXIÓN (Modo Dual: Local / Servidor UCT)
require_once 'conexion.php';

// 2. CONSULTAS A LA BASE DE DATOS MEDIANTE PDO
try {
    // Obtener datos de la Biografía (Fila única)
    $stmt_bio = $conexion->query("SELECT * FROM biografia LIMIT 1");
    $biografia = $stmt_bio->fetch();

    // Obtener Habilidades y Herramientas
    $stmt_skills = $conexion->query("SELECT * FROM habilidades ORDER BY id ASC");
    $habilidades = $stmt_skills->fetchAll();

    // Obtener Tecnologías Dominadas
    $stmt_tech = $conexion->query("SELECT * FROM tecnologias ORDER BY porcentaje DESC");
    $tecnologias = $stmt_tech->fetchAll();

    // Obtener Proyectos Realizados
    $stmt_proy = $conexion->query("SELECT * FROM proyectos ORDER BY id DESC");
    $proyectos = $stmt_proy->fetchAll();

} catch (PDOException $e) {
    die("Error crítico al consultar los datos: " . $e->getMessage());
}

// 3. PROCESAMIENTO DEL FORMULARIO DE CONTACTO (Inserción segura)
$mensaje_enviado = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_mensaje'])) {
    $nombre  = strip_tags(trim($_POST['nombre_completo']));
    $correo  = filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL);
    $asunto  = strip_tags(trim($_POST['asunto']));
    $mensaje = strip_tags(trim($_POST['mensaje']));

    if ($nombre && $correo && $asunto && $mensaje) {
        $sql_insert = "INSERT INTO mensajes (nombre_completo, correo, asunto, mensaje, fecha_envio) 
                       VALUES (:nombre, :correo, :asunto, :mensaje, NOW())";
        $stmt_insert = $conexion->prepare($sql_insert);
        $stmt_insert->execute([
            ':nombre'  => $nombre,
            ':correo'  => $correo,
            ':asunto'  => $asunto,
            ':mensaje' => $mensaje
        ]);
        $mensaje_enviado = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($biografia['nombre_completo'] ?? 'Mi Portafolio'); ?> | Desarrollador Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=1.4">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-code-slash me-2"></i><?php echo htmlspecialchars($biografia['nombre_completo'] ?? 'Portafolio'); ?>
            </a>
            <button class="navbar-brand navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto fw-medium">
                    <li class="nav-item"><a class="nav-link" href="#biografia">Biografía</a></li>
                    <li class="nav-item"><a class="nav-link" href="#habilidades">Habilidades</a></li>
                    <li class="nav-item"><a class="nav-link" href="#proyectos">Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-light text-primary fw-bold px-3 rounded-pill" href="admin/login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="biografia" class="py-5 bg-white">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-md-4 text-center">
                    <?php if (!empty($biografia['imagen_url'])): ?>
                        <img src="<?php echo htmlspecialchars($biografia['imagen_url']); ?>" alt="Foto de Perfil" class="img-fluid rounded-circle hero-img-wrapper" style="width: 260px; height: 260px; object-fit: cover;">
                    <?php else: ?>
                        <img src="assets/img/avatar.png" alt="Foto de Perfil de Respaldo" class="img-fluid rounded-circle hero-img-wrapper" style="width: 260px; height: 260px; object-fit: cover;">
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <span class="text-primary text-uppercase fw-bold letter-spacing">Hola, Soy</span>
                    <h1 class="display-4 fw-bold mt-1 text-dark"><?php echo htmlspecialchars($biografia['nombre_completo'] ?? 'Tu Nombre Completo'); ?></h1>
                    <h3 class="text-muted lead fs-4 mb-4"><?php echo htmlspecialchars($biografia['titulo_profesional'] ?? 'Tu Especialidad'); ?></h3>
                    <p class="text-secondary fs-5 mb-4 row-line-height">
                        <?php echo htmlspecialchars($biografia['descripcion'] ?? 'Agrega una descripción breve desde tu panel.'); ?>
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <?php if(!empty($biografia['cv_url'])): ?>
                            <a href="<?php echo htmlspecialchars($biografia['cv_url']); ?>" target="_blank" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="bi bi-download me-2"></i>Descargar CV
                            </a>
                        <?php endif; ?>
                        <a href="https://github.com/" target="_blank" class="btn btn-outline-secondary btn-lg rounded-circle p-2" style="width: 48px; height: 48px;"><i class="bi bi-github"></i></a>
                        <a href="https://linkedin.com/" target="_blank" class="btn btn-outline-secondary btn-lg rounded-circle p-2" style="width: 48px; height: 48px;"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="habilidades" class="py-5 bg-light border-top border-bottom">
        <div class="container py-3">
            <div class="row g-5">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4 d-flex align-items-center text-dark"><i class="bi bi-star me-2 text-primary"></i>Habilidades y Herramientas</h2>
                    <div class="row g-3">
                        <?php if (!empty($habilidades)): foreach ($habilidades as $skill): ?>
                            <div class="col-md-4 col-6">
                                <div class="skill-card">
                                    <i class="<?php echo htmlspecialchars($skill['icono_clase']); ?> skill-icon"></i>
                                    <span class="fw-semibold text-dark d-block"><?php echo htmlspecialchars($skill['nombre_habilidad']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                            <p class="text-muted">Aún no hay habilidades registradas.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4 d-flex align-items-center text-dark"><i class="bi bi-code me-2 text-primary"></i>Tecnologías Dominadas</h2>
                    <div class="bg-white p-4 rounded-4 border border-light shadow-sm">
                        <?php if (!empty($tecnologias)): foreach ($tecnologias as $tech): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold text-secondary"><?php echo htmlspecialchars($tech['nombre_tecnologia']); ?></span>
                                    <span class="text-primary fw-bold"><?php echo (int)$tech['porcentaje']; ?>%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo (int)$tech['porcentaje']; ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                            <p class="text-muted">Aún no hay porcentajes tecnológicos configurados.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="proyectos" class="py-5 bg-white">
        <div class="container py-3">
            <h2 class="fw-bold mb-5 d-flex align-items-center text-dark"><i class="bi bi-briefcase me-2 text-primary"></i>Proyectos Realizados</h2>
            <div class="row g-4">
                <?php if (!empty($proyectos)): foreach ($proyectos as $proyecto): ?>
                    <div class="col-md-4">
                        <div class="project-card">
                            <img src="<?php echo htmlspecialchars($proyecto['imagen_url'] ?: 'assets/img/default-proyecto.png'); ?>" alt="<?php echo htmlspecialchars($proyecto['titulo']); ?>" class="project-img">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($proyecto['titulo']); ?></h5>
                                <p class="card-text text-secondary"><?php echo htmlspecialchars($proyecto['descripcion']); ?></p>
                            </div>
                            <div class="card-footer">
                                <div class="row g-2">
                                    <?php if(!empty($proyecto['demo_url'])): ?>
                                        <div class="col-6">
                                            <a href="<?php echo htmlspecialchars($proyecto['demo_url']); ?>" target="_blank" class="btn btn-primary w-100 btn-sm rounded-pill"><i class="bi bi-box-arrow-up-right me-1"></i>Ver Demo</a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(!empty($proyecto['github_url'])): ?>
                                        <div class="col-6">
                                            <a href="<?php echo htmlspecialchars($proyecto['github_url']); ?>" target="_blank" class="btn btn-outline-dark w-100 btn-sm rounded-pill"><i class="bi bi-github me-1"></i>GitHub</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <div class="col-12"><p class="text-muted text-center">No hay proyectos para exhibir en este momento.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="contacto" class="py-5 bg-light border-top">
        <div class="container py-3" style="max-width: 700px;">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark"><i class="bi bi-envelope me-2 text-primary"></i>Formulario de Contacto</h2>
                <p class="text-muted">¿Tienes alguna consulta o propuesta? Envíame un mensaje directo.</p>
            </div>

            <?php if ($mensaje_enviado): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> ¡Mensaje enviado con éxito! Los datos fueron guardados correctamente en el servidor.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border border-light">
                <form action="#contacto" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Nombre Completo</label>
                            <input type="text" name="nombre_completo" class="form-control form-control-lg fs-6" placeholder="Ej: Juan Pérez" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control form-control-lg fs-6" placeholder="Ej: juan@correo.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary">Asunto</label>
                            <input type="text" name="asunto" class="form-control form-control-lg fs-6" placeholder="Motivo del contacto" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary">Mensaje</label>
                            <textarea name="mensaje" rows="4" class="form-control form-control-lg fs-6" placeholder="Escribe los detalles aquí..." required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" name="enviar_mensaje" class="btn btn-primary btn-lg w-100 rounded-pill fs-6 fw-bold shadow-sm">
                                <i class="bi bi-send me-2"></i>Enviar Mensaje
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 border-top border-secondary">
        <div class="container text-center">
            <p class="mb-1">&copy; <?php echo date('Y'); ?> - Portafolio Profesional Autoadministrable.</p>
            <p class="text-muted small">Desarrollado por <span class="text-light fw-semibold"><?php echo htmlspecialchars($biografia['nombre_completo'] ?? 'Diego Catalán'); ?></span> — Teclab / UCT.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>