<?php
// ==========================================
// 1. CONEXIÓN A LA BASE DE DATOS
// ==========================================
require_once 'conexion.php';

$alerta_contacto = "";

// ==========================================
// 2. PROCESAR FORMULARIO DE CONTACTO
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar_mensaje'])) {
    $nombre = strip_tags(trim($_POST['nombre']));
    $correo = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);
    $asunto = strip_tags(trim($_POST['asunto']));
    $mensaje = strip_tags(trim($_POST['mensaje']));

    if (!empty($nombre) && filter_var($correo, FILTER_VALIDATE_EMAIL) && !empty($asunto) && !empty($mensaje)) {
        try {
            $stmt = $conexion->prepare("INSERT INTO mensajes (nombre, correo, asunto, mensaje) VALUES (:nombre, :correo, :asunto, :mensaje)");
            $stmt->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':asunto' => $asunto,
                ':mensaje' => $mensaje
            ]);
            $alerta_contacto = "<div class='alert alert-success'>¡Mensaje enviado con éxito! Te responderé pronto.</div>";
        } catch (PDOException $e) {
            $alerta_contacto = "<div class='alert alert-danger'>Error al enviar el mensaje. Intenta nuevamente.</div>";
        }
    } else {
        $alerta_contacto = "<div class='alert alert-warning'>Por favor, completa todos los campos con información válida.</div>";
    }
}

// ==========================================
// 3. CONSULTAS PARA OBTENER LOS DATOS (READ)
// ==========================================
try {
    // Obtener Biografía
    $stmtBio = $conexion->query("SELECT * FROM biografia LIMIT 1");
    $bio = $stmtBio->fetch(PDO::FETCH_ASSOC);
    if (!$bio) {
        $bio = [
            'nombre_completo' => 'Tu Nombre', 
            'titulo_profesional' => 'Desarrollador Web', 
            'descripcion' => 'Descripción breve...', 
            'cv_url' => '#',
            'imagen_url' => ''
        ];
    }

    // Obtener Habilidades
    $stmtHab = $conexion->query("SELECT * FROM habilidades ORDER BY id DESC");
    $habilidades = $stmtHab->fetchAll(PDO::FETCH_ASSOC);

    // Obtener Tecnologías
    $stmtTech = $conexion->query("SELECT * FROM tecnologias ORDER BY porcentaje DESC");
    $tecnologias = $stmtTech->fetchAll(PDO::FETCH_ASSOC);

    // Obtener Proyectos
    $stmtProy = $conexion->query("SELECT * FROM proyectos ORDER BY id DESC");
    $proyectos = $stmtProy->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($bio['nombre_completo']); ?> | Portafolio</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/style.css?v=1.3">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <?php echo htmlspecialchars($bio['nombre_completo']); ?><br>
                <small class="fw-normal" style="font-size: 12px;"><?php echo htmlspecialchars($bio['titulo_profesional']); ?></small>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#biografia"><i class="fa-regular fa-user me-1"></i> Biografía</a></li>
                    <li class="nav-item"><a class="nav-link" href="#habilidades"><i class="fa-regular fa-star me-1"></i> Habilidades</a></li>
                    <li class="nav-item"><a class="nav-link" href="#proyectos"><i class="fa-solid fa-briefcase me-1"></i> Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto"><i class="fa-regular fa-envelope me-1"></i> Contacto</a></li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0"><a class="btn btn-outline-light btn-sm rounded-pill px-3" href="login.php"><i class="fa-solid fa-right-to-bracket me-1"></i> Iniciar Sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="biografia" class="hero-section py-5 bg-white border-bottom">
        <div class="container">
            <div class="row align-items-center">
                
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <?php if (!empty($bio['imagen_url'])): ?>
                        <img src="<?php echo htmlspecialchars($bio['imagen_url']); ?>" 
                             alt="Foto de <?php echo htmlspecialchars($bio['nombre_completo']); ?>" 
                             class="img-fluid rounded-circle shadow border border-4 border-light" 
                             style="width: 180px; height: 180px; object-fit: cover;">
                    <?php else: ?>
                        <div class="hero-img-wrapper mx-auto d-flex align-items-center justify-content-center bg-light text-muted rounded-circle shadow border border-4 border-light" style="width: 180px; height: 180px;">
                            <i class="fa-solid fa-user fa-5x text-secondary"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-8 text-center text-md-start">
                    <h5 class="text-primary fw-bold mb-1">HOLA, SOY</h5>
                    <h1 class="display-5 fw-bold text-dark mb-2"><?php echo htmlspecialchars($bio['nombre_completo']); ?></h1>
                    <h4 class="text-secondary mb-4" style="font-weight: 500;"><?php echo htmlspecialchars($bio['titulo_profesional']); ?></h4>
                    <p class="lead text-muted mb-4" style="font-size: 1.05rem; white-space: pre-line;">
                        <?php echo htmlspecialchars($bio['descripcion']); ?>
                    </p>
                    
                    <?php if(!empty($bio['cv_url'])): ?>
                        <a href="<?php echo htmlspecialchars($bio['cv_url']); ?>" target="_blank" class="btn btn-primary rounded-pill px-4 py-2 me-2">
                            <i class="fa-solid fa-download me-2"></i> Descargar CV
                        </a>
                    <?php endif; ?>
                    
                    <a href="https://github.com/Diego-Catalan-cmd" target="_blank" class="btn btn-outline-dark rounded-circle me-2"><i class="fa-brands fa-github"></i></a>
                    <a href="https://linkedin.com" target="_blank" class="btn btn-outline-primary rounded-circle"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-2">
        <div class="row" id="habilidades">
            <div class="col-lg-6 mb-5">
                <h3 class="section-title fw-bold mb-4"><i class="fa-regular fa-star text-primary me-2"></i>Habilidades y Herramientas</h3>
                <div class="row g-3">
                    <?php if(!empty($habilidades)): ?>
                        <?php foreach($habilidades as $hab): ?>
                            <div class="col-6 col-md-4">
                                <div class="skill-card h-100">
                                    <i class="<?php echo htmlspecialchars($hab['icono_clase']); ?> skill-icon"></i>
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;"><?php echo htmlspecialchars($hab['nombre']); ?></h6>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12"><p class="text-muted">Aún no hay habilidades registradas.</p></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-6 mb-5">
                <h3 class="section-title fw-bold mb-4"><i class="fa-solid fa-code text-primary me-2"></i>Tecnologías Dominadas</h3>
                <div class="card border-0 bg-white p-4 shadow-sm" style="border-radius: 12px; border: 1px solid #e2e8f0 !important;">
                    <?php if(!empty($tecnologias)): ?>
                        <?php foreach($tecnologias as $tech): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold small"><?php echo htmlspecialchars($tech['nombre']); ?></span>
                                    <span class="text-muted small fw-bold"><?php echo htmlspecialchars($tech['porcentaje']); ?>%</span>
                                </div>
                                <div class="progress" style="height: 8px; background-color: #f1f5f9;">
                                    <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: <?php echo htmlspecialchars($tech['porcentaje']); ?>%;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Aún no hay tecnologías registradas.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row mb-5" id="proyectos">
            <div class="col-12 mb-4">
                <h3 class="section-title fw-bold"><i class="fa-solid fa-briefcase text-primary me-2"></i>Proyectos Realizados</h3>
            </div>
            <?php if(!empty($proyectos)): ?>
                <?php foreach($proyectos as $proy): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card project-card">
                            <?php if(!empty($proy['imagen_url'])): ?>
                                <img src="<?php echo htmlspecialchars($proy['imagen_url']); ?>" class="project-img card-img-top" alt="Proyecto">
                            <?php else: ?>
                                <div class="project-img card-img-top text-muted d-flex align-items-center justify-content-center"><i class="fa-regular fa-image fa-3x"></i></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark mb-2" style="font-size: 1.15rem;"><?php echo htmlspecialchars($proy['titulo']); ?></h5>
                                <p class="card-text text-muted small"><?php echo htmlspecialchars($proy['descripcion']); ?></p>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <div class="d-flex gap-2">
                                    <?php if(!empty($proy['link_demo'])): ?>
                                        <a href="<?php echo htmlspecialchars($proy['link_demo']); ?>" target="_blank" class="btn btn-primary btn-sm rounded-pill flex-grow-1 py-2">Demo <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 11px;"></i></a>
                                    <?php endif; ?>
                                    <?php if(!empty($proy['link_github'])): ?>
                                        <a href="<?php echo htmlspecialchars($proy['link_github']); ?>" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill flex-grow-1 py-2">GitHub <i class="fa-brands fa-github ms-1"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12"><p class="text-muted">Aún no hay proyectos publicados.</p></div>
            <?php endif; ?>
        </div>

        <div class="row justify-content-center py-4" id="contacto">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 15px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
                    <h4 class="fw-bold mb-4 text-center"><i class="fa-regular fa-envelope text-primary me-2"></i> Formulario de Contacto</h4>
                    
                    <?php echo $alerta_contacto; ?>

                    <form action="index.php#contacto" method="POST">
                        <input type="hidden" name="enviar_mensaje" value="1">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="nombre" class="form-control bg-light border-0 py-2" placeholder="Nombre Completo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="email" name="correo" class="form-control bg-light border-0 py-2" placeholder="Correo Electrónico" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="asunto" class="form-control bg-light border-0 py-2" placeholder="Asunto" required>
                        </div>
                        <div class="mb-4">
                            <textarea name="mensaje" class="form-control bg-light border-0 py-2" rows="4" placeholder="Mensaje" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                            Enviar Mensaje <i class="fa-solid fa-paper-plane ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white border-top py-4 mt-5 text-center">
        <div class="container">
            <div class="d-flex justify-content-center gap-3 mb-3">
                <a href="https://github.com/Diego-Catalan-cmd" class="text-dark fs-4"><i class="fa-brands fa-github"></i></a>
                <a href="#" class="text-dark fs-4"><i class="fa-brands fa-linkedin"></i></a>
                <a href="mailto:correo@ejemplo.com" class="text-dark fs-4"><i class="fa-regular fa-envelope"></i></a>
            </div>
            <p class="text-muted small mb-1 fw-bold">Diego Christofer Catalán | Portafolio Profesional</p>
            <p class="text-muted small mb-2">Desarrollo Seguro, Programación Dinámica & Ciberseguridad.</p>
            <p class="fw-bold text-dark mb-0">&copy; <?php echo date("Y"); ?> Teclab UCT</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>