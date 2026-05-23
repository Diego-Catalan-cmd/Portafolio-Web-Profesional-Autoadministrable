<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portafolio Web | Tu Nombre</title>
    <!-- Bootstrap 5.3.8 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Pequeños ajustes CSS para fidelidad al wireframe */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .hero-section { background-color: #ffffff; padding: 60px 0; border-bottom: 1px solid #e9ecef; }
        .avatar-placeholder { width: 250px; height: 250px; background-color: #dee2e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
        .avatar-placeholder i { font-size: 8rem; color: #adb5bd; }
        .section-padding { padding: 80px 0; background-color: #ffffff; border-bottom: 1px solid #e9ecef;}
        .bg-light-gray { background-color: #f8f9fa; }
        .skill-card { border: 1px solid #e9ecef; border-radius: 12px; padding: 20px; transition: transform 0.2s; background: #fff;}
        .skill-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .skill-icon { font-size: 3rem; margin-bottom: 10px; }
        
        /* Colores de las marcas según wireframe */
        .color-html { color: #E34F26; }
        .color-css { color: #1572B6; }
        .color-js { color: #F7DF1E; }
        .color-php { color: #777BB4; }
        .color-mysql { color: #4479A1; }
        .color-bs { color: #7952B3; }
        .color-github { color: #181717; }
        .color-ia { color: #10a37f; }

        .progress { height: 12px; margin-bottom: 15px; border-radius: 10px;}
        .card-img-top { height: 200px; object-fit: cover; background-color: #e9ecef; }
        
        footer { background-color: #ffffff; border-top: 1px solid #e9ecef; padding: 40px 0; }
    </style>
</head>
<body>

    <!-- 1. NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <div class="me-2 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Tu Nombre</h6>
                    <small class="text-muted" style="font-size: 12px;">Desarrollador Web</small>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link px-3" href="#biografia"><i class="fa-regular fa-user me-1"></i> Biografía</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#habilidades"><i class="fa-regular fa-star me-1"></i> Habilidades</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#tecnologias"><i class="fa-solid fa-code me-1"></i> Tecnologías</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#proyectos"><i class="fa-solid fa-briefcase me-1"></i> Proyectos</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#contacto"><i class="fa-regular fa-envelope me-1"></i> Contacto</a></li>
                </ul>
                <a href="login.php" class="btn btn-outline-primary rounded-pill px-4"><i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Iniciar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- 2. HERO / BIOGRAFÍA -->
    <section id="biografia" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <div class="avatar-placeholder shadow-sm">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
                <div class="col-md-8">
                    <p class="text-primary fw-bold mb-1">HOLA, SOY</p>
                    <h1 class="fw-bold display-5 mb-2">Tu Nombre Completo</h1>
                    <h4 class="text-primary mb-4">Desarrollador Web & Apasionado por la Tecnología</h4>
                    <p class="text-muted lead mb-4">Soy desarrollador web con experiencia en la creación de sitios y aplicaciones modernas, funcionales y responsivas. Me apasiona resolver problemas y crear soluciones digitales que generen impacto.</p>
                    <div class="d-flex align-items-center gap-3">
                        <a href="#" class="btn btn-primary btn-lg rounded-pill px-4"><i class="fa-solid fa-download me-2"></i> Descargar CV</a>
                        <a href="#" class="text-dark fs-3"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="text-dark fs-3"><i class="fa-brands fa-linkedin"></i></a>
                        <a href="#" class="text-dark fs-3"><i class="fa-solid fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. HABILIDADES Y TECNOLOGÍAS -->
    <section id="habilidades" class="section-padding bg-light-gray">
        <div class="container">
            <div class="row">
                <!-- Mitad Izquierda: Habilidades y Herramientas (Cards) -->
                <div class="col-md-6 mb-5 mb-md-0 pe-md-5">
                    <h4 class="mb-4"><i class="fa-regular fa-star text-primary me-2"></i> Habilidades y Herramientas</h4>
                    <div class="row g-3 text-center">
                        <div class="col-6 col-sm-3"><div class="skill-card"><i class="fa-brands fa-html5 skill-icon color-html"></i><h6 class="mb-0">HTML5</h6></div></div>
                        <div class="col-6 col-sm-3"><div class="skill-card"><i class="fa-brands fa-css3-alt skill-icon color-css"></i><h6 class="mb-0">CSS3</h6></div></div>
                        <div class="col-6 col-sm-3"><div class="skill-card"><i class="fa-brands fa-js skill-icon color-js"></i><h6 class="mb-0">JavaScript</h6></div></div>
                        <div class="col-6 col-sm-3"><div class="skill-card"><i class="fa-brands fa-php skill-icon color-php"></i><h6 class="mb-0">PHP</h6></div></div>
                        <div class="col-6 col-sm-3"><div class="skill-card"><i class="fa-solid fa-database skill-icon color-mysql"></i><h6 class="mb-0">MySQL</h6></div></div>
                        <div class="col-6 col-sm-3"><div class="skill-card"><i class="fa-brands fa-bootstrap skill-icon color-bs"></i><h6 class="mb-0">Bootstrap</h6></div></div>
                        <div class="col-6 col-sm-3"><div class="skill-card"><i class="fa-brands fa-github skill-icon color-github"></i><h6 class="mb-0">GitHub</h6></div></div>
                        <div class="col-6 col-sm-3"><div class="skill-card"><i class="fa-solid fa-brain skill-icon color-ia"></i><h6 class="mb-0">IA Aplicada</h6></div></div>
                    </div>
                </div>
                
                <!-- Mitad Derecha: Tecnologías Dominadas (Progress Bars) -->
                <div id="tecnologias" class="col-md-6 ps-md-5">
                    <h4 class="mb-4"><i class="fa-solid fa-code text-primary me-2"></i> Tecnologías Dominadas</h4>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between"><span class="fw-bold">HTML5</span><span class="text-muted">95%</span></div>
                        <div class="progress"><div class="progress-bar bg-primary" style="width: 95%;"></div></div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between"><span class="fw-bold">CSS3</span><span class="text-muted">90%</span></div>
                        <div class="progress"><div class="progress-bar bg-primary" style="width: 90%;"></div></div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between"><span class="fw-bold">JavaScript</span><span class="text-muted">85%</span></div>
                        <div class="progress"><div class="progress-bar bg-primary" style="width: 85%;"></div></div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between"><span class="fw-bold">PHP</span><span class="text-muted">80%</span></div>
                        <div class="progress"><div class="progress-bar bg-primary" style="width: 80%;"></div></div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between"><span class="fw-bold">MySQL</span><span class="text-muted">80%</span></div>
                        <div class="progress"><div class="progress-bar bg-primary" style="width: 80%;"></div></div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between"><span class="fw-bold">Bootstrap</span><span class="text-muted">90%</span></div>
                        <div class="progress"><div class="progress-bar bg-primary" style="width: 90%;"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. PROYECTOS -->
    <section id="proyectos" class="section-padding">
        <div class="container">
            <h4 class="mb-4"><i class="fa-solid fa-briefcase text-primary me-2"></i> Proyectos Realizados</h4>
            <div class="row g-4">
                <!-- Proyecto 1 -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-img-top d-flex align-items-center justify-content-center text-muted">
                            <i class="fa-regular fa-image fa-3x"></i>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold">Nombre del Proyecto</h5>
                            <p class="card-text text-muted" style="font-size: 14px;">Breve descripción del proyecto, tecnologías utilizadas y objetivo principal del desarrollo.</p>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="#" class="btn btn-primary rounded-pill px-3"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Ver Demo</a>
                                <a href="#" class="btn btn-outline-dark rounded-pill px-3"><i class="fa-brands fa-github me-1"></i> GitHub</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Proyecto 2 -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-img-top d-flex align-items-center justify-content-center text-muted">
                            <i class="fa-regular fa-image fa-3x"></i>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold">Nombre del Proyecto</h5>
                            <p class="card-text text-muted" style="font-size: 14px;">Breve descripción del proyecto, tecnologías utilizadas y objetivo principal del desarrollo.</p>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="#" class="btn btn-primary rounded-pill px-3"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Ver Demo</a>
                                <a href="#" class="btn btn-outline-dark rounded-pill px-3"><i class="fa-brands fa-github me-1"></i> GitHub</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Proyecto 3 -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-img-top d-flex align-items-center justify-content-center text-muted">
                            <i class="fa-regular fa-image fa-3x"></i>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold">Nombre del Proyecto</h5>
                            <p class="card-text text-muted" style="font-size: 14px;">Breve descripción del proyecto, tecnologías utilizadas y objetivo principal del desarrollo.</p>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="#" class="btn btn-primary rounded-pill px-3"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Ver Demo</a>
                                <a href="#" class="btn btn-outline-dark rounded-pill px-3"><i class="fa-brands fa-github me-1"></i> GitHub</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="#" class="btn btn-outline-primary rounded-pill px-4">Ver todos los proyectos <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- 5. CONTACTO -->
    <section id="contacto" class="section-padding bg-light-gray">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 p-4 p-md-5">
                        <h4 class="mb-4"><i class="fa-regular fa-envelope text-primary me-2"></i> Formulario de Contacto</h4>
                        <form action="#" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control bg-light" placeholder="Nombre Completo" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" class="form-control bg-light" placeholder="Correo Electrónico" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control bg-light" placeholder="Asunto" required>
                            </div>
                            <div class="mb-4">
                                <textarea class="form-control bg-light" rows="5" placeholder="Mensaje" required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5"><i class="fa-regular fa-paper-plane me-2"></i> Enviar Mensaje</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. FOOTER -->
    <footer>
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0 d-flex justify-content-center justify-content-md-start gap-3 fs-4">
                    <a href="#" class="text-dark"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="text-dark"><i class="fa-brands fa-linkedin"></i></a>
                    <a href="#" class="text-dark"><i class="fa-regular fa-envelope"></i></a>
                </div>
                <div class="col-md-8 text-center text-md-end text-muted">
                    <p class="mb-1 fw-bold">2026 MiMarca</p>
                    <small>Pie de página con las redes sociales y correo del desarrollador<br>Horario de atención y logo personal.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>