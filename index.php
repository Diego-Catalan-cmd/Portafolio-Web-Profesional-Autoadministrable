<?php
// ==========================================
// 1. BACKEND: CONEXIÓN Y PROCESAMIENTO
// ==========================================
require_once 'conexion.php';

// Variable para manejar estados del formulario (Éxito o Error)
$mensaje_estado = "";

// Procesar el formulario de contacto si se envía por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar entradas para evitar ataques XSS básicas
    $nombre = strip_tags(trim($_POST['nombre']));
    $correo = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);
    $asunto = strip_tags(trim($_POST['asunto']));
    $mensaje = strip_tags(trim($_POST['mensaje']));

    // Validar campos obligatorios y formato de correo
    if (!empty($nombre) && !empty($correo) && !empty($asunto) && !empty($mensaje) && filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        try {
            // Consulta preparada con marcadores de posición para evitar Inyección SQL (Buenas Prácticas)
            $sql = "INSERT INTO mensajes (nombre, correo, asunto, mensaje) VALUES (:nombre, :correo, :asunto, :mensaje)";
            $stmt = $conexion->prepare($sql);
            
            // Ejecutar pasando el arreglo asociativo seguro
            $stmt->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':asunto' => $asunto,
                ':mensaje' => $mensaje
            ]);

            $mensaje_estado = "<div class='alert alert-success border-0 shadow-sm rounded-pill text-center mb-4'><i class='fa-regular fa-circle-check me-2'></i>¡Mensaje enviado con éxito! Se ha guardado en la base de datos.</div>";
        } catch (PDOException $e) {
            $mensaje_estado = "<div class='alert alert-danger border-0 shadow-sm rounded-pill text-center mb-4'><i class='fa-solid fa-circle-exclamation me-2'></i>Error en el servidor al enviar el mensaje.</div>";
        }
    } else {
        $mensaje_estado = "<div class='alert alert-warning border-0 shadow-sm rounded-pill text-center mb-4'><i class='fa-solid fa-triangle-exclamation me-2'></i>Por favor, rellena todos los campos con un correo válido.</div>";
    }
}

// Obtener datos dinámicos desde MySQL usando consultas PDO robustas
try {
    // 1. Biografía
    $stmtBio = $conexion->query("SELECT * FROM biografia LIMIT 1");
    $biografia = $stmtBio->fetch();

    // 2. Habilidades
    $stmtHab = $conexion->query("SELECT * FROM habilidades");
    $habilidades = $stmtHab->fetchAll();

    // 3. Tecnologías
    $stmtTech = $conexion->query("SELECT * FROM tecnologias");
    $tecnologias = $stmtTech->fetchAll();

    // 4. Proyectos
    $stmtProj = $conexion->query("SELECT * FROM proyectos");
    $proyectos = $stmtProj->fetchAll();
} catch (PDOException $e) {
    die("Error crítico de rendimiento. No se pudieron cargar los componentes del portafolio.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portafolio Profesional | <?php echo htmlspecialchars($biografia['nombre_completo'] ?? 'Tu Nombre'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Estilos globales y consistencia con el Wireframe */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; color: #333; }
        .hero-section { background-color: #ffffff; padding: 70px 0; border-bottom: 1px solid #e9ecef; }
        .avatar-placeholder { width: 240px; height: 240px; background-color: #dee2e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; border: 4px solid #fff; }
        .avatar-placeholder i { font-size: 7rem; color: #adb5bd; }
        .section-padding { padding: 80px 0; background-color: #ffffff; border-bottom: 1px solid #e9ecef;}
        .bg-light-gray { background-color: #f8f9fa; }
        .skill-card { border: 1px solid #e9ecef; border-radius: 12px; padding: 20px; transition: all 0.3s ease; background: #fff;}
        .skill-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .skill-icon { font-size: 2.8rem; }
        .progress { height: 12px; margin-bottom: 18px; border-radius: 10px; background-color: #e9ecef;}
        .card-img-top { height: 210px; object-fit: cover; background-color: #e9ecef; }
        footer { background-color: #ffffff; border-top: 1px solid #e9ecef; padding: 40px 0; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <div class="me-2 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-code"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($biografia['nombre_completo'] ?? 'Mi Portafolio'); ?></h6>
                    <small class="text-muted" style="font-size: 11px;">Egresado / Estudiante</small>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link px-3" href="#biografia"><i class="fa-regular fa-user me-1"></i> Biografía</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#habilidades"><i class="fa-regular fa-star me-1"></i> Habilidades</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#proyectos"><i class="fa-solid fa-briefcase me-1"></i> Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#contacto"><i class="fa-regular fa-envelope me-1"></i> Contacto</a></li>
                </ul>
                <a href="login.php" class="btn btn-outline-primary rounded-pill px-4 shadow-sm"><i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Dashboard Admin</a>
            </div>
        </div>
    </nav>

    <section id="biografia" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <div class="avatar-placeholder shadow-sm">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                </div>
                <div class="col-md-8">
                    <p class="text-primary fw-bold mb-1 tracking-wider">HOLA, MI PERFIL PROFESIONAL</p>
                    <h1 class="fw-bold display-5 mb-2"><?php echo htmlspecialchars($biografia['nombre_completo'] ?? 'Tu Nombre Completo'); ?></h1>
                    <h4 class="text-primary mb-4 fw-normal"><?php echo htmlspecialchars($biografia['titulo_profesional'] ?? 'Título Profesional / Técnico'); ?></h4>
                    <p class="text-muted lead mb-4" style="font-size: 16px; line-height: 1.7;"><?php echo htmlspecialchars($biografia['descripcion'] ?? 'Descripción del desarrollador...'); ?></p>
                    <div class="d-flex align-items-center gap-3">
                        <?php if (!empty($biografia['cv_url'])): ?>
                            <a href="<?php echo htmlspecialchars($biografia['cv_url']); ?>" target="_blank" class="btn btn-primary btn-lg rounded-pill px-4 shadow"><i class="fa-solid fa-download me-2"></i> Descargar CV</a>
                        <?php else: ?>
                            <a href="#contacto" class="btn btn-primary btn-lg rounded-pill px-4 shadow"><i class="fa-regular fa-paper-plane me-2"></i> Contáctame</a>
                        <?php endif; ?>
                        <a href="https://github.com" target="_blank" class="text-dark fs-3"><i class="fa-brands fa-github"></i></a>
                        <a href="https://linkedin.com" target="_blank" class="text-primary fs-3"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="habilidades" class="section-padding bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0 pe-md-4">
                    <h4 class="mb-4 fw-bold text-dark"><i class="fa-regular fa-star text-primary me-2"></i> Habilidades y Herramientas</h4>
                    <div class="row g-3 text-center">
                        <?php if (!empty($habilidades)): ?>
                            <?php foreach ($habilidades as $hab): ?>
                                <div class="col-6 col-sm-4">
                                    <div class="skill-card shadow-sm">
                                        <i class="<?php echo htmlspecialchars($hab['icono_clase'] ?? 'fa-solid fa-square-code'); ?> skill-icon text-primary"></i>
                                        <h6 class="mb-0 mt-2 fw-bold text-secondary" style="font-size: 14px;"><?php echo htmlspecialchars($hab['nombre']); ?></h6>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-6 col-sm-4"><div class="skill-card shadow-sm"><i class="fa-brands fa-html5 skill-icon text-danger"></i><h6 class="mb-0 mt-2">HTML5</h6></div></div>
                            <div class="col-6 col-sm-4"><div class="skill-card shadow-sm"><i class="fa-brands fa-css3-alt skill-icon text-primary"></i><h6 class="mb-0 mt-2">CSS3</h6></div></div>
                            <div class="col-6 col-sm-4"><div class="skill-card shadow-sm"><i class="fa-brands fa-js skill-icon text-warning"></i><h6 class="mb-0 mt-2">JavaScript</h6></div></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-md-6 ps-md-4">
                    <h4 class="mb-4 fw-bold text-dark"><i class="fa-solid fa-code text-primary me-2"></i> Tecnologías Dominadas</h4>
                    <?php if (!empty($tecnologias)): ?>
                        <?php foreach ($tecnologias as $tech): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold text-secondary"><?php echo htmlspecialchars($tech['nombre']); ?></span>
                                    <span class="text-muted fw-bold"><?php echo htmlspecialchars($tech['porcentaje']); ?>%</span>
                                </div>
                                <div class="progress shadow-sm">
                                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width: <?php echo htmlspecialchars($tech['porcentaje']); ?>%;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between"><span class="fw-bold text-secondary">Desarrollo Backend (PHP)</span><span class="text-muted fw-bold">80%</span></div>
                            <div class="progress"><div class="progress-bar bg-primary" style="width: 80%;"></div></div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between"><span class="fw-bold text-secondary">Bases de Datos (MySQL)</span><span class="text-muted fw-bold">75%</span></div>
                            <div class="progress"><div class="progress-bar bg-primary" style="width: 75%;"></div></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="proyectos" class="section-padding">
        <div class="container">
            <h4 class="mb-4 fw-bold text-dark"><i class="fa-solid fa-briefcase text-primary me-2"></i> Proyectos Realizados</h4>
            <div class="row g-4">
                <?php if (!empty($proyectos)): ?>
                    <?php foreach ($proyectos as $proy): ?>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0 bg-white">
                                <?php if(!empty($proy['imagen_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($proy['imagen_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($proy['titulo']); ?>">
                                <?php else: ?>
                                    <div class="card-img-top d-flex align-items-center justify-content-center text-muted">
                                        <i class="fa-regular fa-image fa-3x"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($proy['titulo']); ?></h5>
                                    <p class="card-text text-muted mb-4" style="font-size: 13.5px; line-height: 1.6;"><?php echo htmlspecialchars($proy['descripcion']); ?></p>
                                    <div class="mt-auto d-flex justify-content-between">
                                        <?php if(!empty($proy['link_demo'])): ?>
                                            <a href="<?php echo htmlspecialchars($proy['link_demo']); ?>" target="_blank" class="btn btn-primary rounded-pill px-3 shadow-sm btn-sm"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Demo</a>
                                        <?php endif; ?>
                                        <?php if(!empty($proy['link_github'])): ?>
                                            <a href="<?php echo htmlspecialchars($proy['link_github']); ?>" target="_blank" class="btn btn-outline-dark rounded-pill px-3 btn-sm"><i class="fa-brands fa-github me-1"></i> GitHub</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-img-top d-flex align-items-center justify-content-center text-muted"><i class="fa-regular fa-image fa-2x"></i></div>
                            <div class="card-body">
                                <h5 class="card-title text-primary fw-bold">Sistema E-Commerce</h5>
                                <p class="card-text text-muted" style="font-size: 13px;">Proyecto de tienda en línea integrado con bases de datos dinámicas.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="contacto" class="section-padding bg-light-gray">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <?php echo $mensaje_estado; ?>
                    
                    <div class="card shadow border-0 p-4 p-md-5 bg-white rounded-4">
                        <h4 class="mb-4 fw-bold text-center text-dark"><i class="fa-regular fa-envelope text-primary me-2"></i> Formulario de Contacto</h4>
                        <form action="index.php#contacto" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-secondary">Nombre Completo</label>
                                    <input type="text" name="nombre" class="form-control bg-light border-0 py-2 px-3" placeholder="Ej: Juan Pérez" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-secondary">Correo Electrónico</label>
                                    <input type="email" name="correo" class="form-control bg-light border-0 py-2 px-3" placeholder="juan@correo.com" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Asunto del Mensaje</label>
                                <input type="text" name="asunto" class="form-control bg-light border-0 py-2 px-3" placeholder="Ej: Propuesta de Proyecto" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Mensaje o Comentarios</label>
                                <textarea name="mensaje" class="form-control bg-light border-0 py-2 px-3" rows="4" placeholder="Escribe tu mensaje detallado aquí..." required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow"><i class="fa-regular fa-paper-plane me-2"></i> Enviar Mensaje</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0 d-flex justify-content-center justify-content-md-start gap-3 fs-4">
                    <a href="https://github.com" target="_blank" class="text-dark"><i class="fa-brands fa-github"></i></a>
                    <a href="https://linkedin.com" target="_blank" class="text-primary"><i class="fa-brands fa-linkedin"></i></a>
                </div>
                <div class="col-md-8 text-center text-md-end text-muted">
                    <p class="mb-1 fw-bold">&copy; 2026 Portafolio Profesional.</p>
                    <small>Diseñado en conformidad con las directrices y rúbricas de la evaluación institucional.</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>