document.addEventListener("DOMContentLoaded", function() {
    
    // ==========================================
    // 1. EFECTO EN LA BARRA DE NAVEGACIÓN AL HACER SCROLL
    // ==========================================
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.style.boxShadow = "0 4px 15px rgba(0,0,0,0.1)";
            navbar.style.opacity = "0.98";
        } else {
            navbar.style.boxShadow = "none";
            navbar.style.opacity = "1";
        }
    });

    // ==========================================
    // 2. VALIDACIÓN DEL FORMULARIO DE CONTACTO
    // ==========================================
    const formContacto = document.querySelector('form[action="index.php#contacto"]');
    
    if (formContacto) {
        formContacto.addEventListener('submit', function(event) {
            // Obtenemos los valores y quitamos espacios al inicio y final
            const nombre = document.querySelector('input[name="nombre"]').value.trim();
            const correo = document.querySelector('input[name="correo"]').value.trim();
            const asunto = document.querySelector('input[name="asunto"]').value.trim();
            const mensaje = document.querySelector('textarea[name="mensaje"]').value.trim();

            // Validación básica de campos vacíos o llenos de espacios
            if (nombre === "" || correo === "" || asunto === "" || mensaje === "") {
                event.preventDefault(); // Detiene el envío del formulario
                alert("Por favor, completa todos los campos correctamente. No se permiten solo espacios en blanco.");
            }
            
            // Validación muy básica de correo electrónico
            const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regexCorreo.test(correo)) {
                event.preventDefault();
                alert("Por favor, ingresa una dirección de correo electrónico válida.");
            }
        });
    }

    // ==========================================
    // 3. ANIMACIONES AL HACER SCROLL (INTERSECTION OBSERVER)
    // ==========================================
    // Seleccionamos los elementos que queremos animar
    const elementosAnimar = document.querySelectorAll('.skill-card, .project-card, .section-title, .hero-img');

    // Preparamos los elementos en CSS desde JS (los ocultamos un poco y los bajamos)
    elementosAnimar.forEach(el => {
        el.style.opacity = "0";
        el.style.transform = "translateY(30px)";
        el.style.transition = "opacity 0.6s ease-out, transform 0.6s ease-out";
    });

    // Configuramos el observador
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            // Si el elemento entra en la pantalla del usuario
            if (entry.isIntersecting) {
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
                // Dejamos de observar el elemento para que la animación solo ocurra una vez
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15 // El 15% del elemento debe ser visible para disparar la animación
    });

    // Empezamos a observar los elementos
    elementosAnimar.forEach(el => {
        observer.observe(el);
    });

});