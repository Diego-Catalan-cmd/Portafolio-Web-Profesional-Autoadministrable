# Portafolio Web Profesional Autoadministrable

Este proyecto consiste en una aplicación web dinámica, moderna y completamente responsive diseñada para presentar la información profesional de un desarrollador de manera atractiva, integrando un completo sistema de gestión de contenidos (CMS/Dashboard) en el backend que permite administrar de forma autónoma toda la información visible.

Desarrollado como entregable oficial para la **Evaluación N°3** de la asignatura **Diseño y Desarrollo Web + IA**.

---

## 🚀 Proyecto en Producción
El sistema se encuentra desplegado y plenamente operativo en el servidor institucional a través del siguiente enlace:
👉 **[https://teclab.uct.cl/~usuario/](https://teclab.uct.cl/~usuario/)** *(Nota: Reemplazar `~usuario` con el directorio correspondiente asignado por la institución).*

---

## 🛠️ Stack Tecnológico Utilizado

### **Frontend**
* **HTML5:** Estructuración semántica rigurosa para SEO y accesibilidad (`<nav>`, `<header>`, `<section>`, `<article>`, `<footer>`).
* **CSS3:** Estilos personalizados, manejo de variables globales y optimización de transiciones.
* **Bootstrap 5.3.8:** Framework ágil para el diseño responsive estructurado con el sistema de grillas (`Grid System`) y componentes nativos avanzados.
* **JavaScript (ES6+):** Validación de formularios en el lado del cliente, interactividad dinámica y manejo de peticiones asíncronas.

### **Backend & Base de Datos**
* **PHP 8.x:** Lógica del lado del servidor, gestión de sesiones seguras y arquitectura modular.
* **MySQL:** Almacenamiento relacional de datos estructurado eficientemente.
* **PDO (PHP Data Objects):** Conexión segura implementando consultas preparadas para mitigar vulnerabilidades de Inyección SQL (SQLi).

---

## 📋 Características Principales

### **Vista Pública (Portafolio)**
1.  **Navbar Colapsable:** Menú de navegación interactivo y fluido adaptado para dispositivos móviles, tablets y ordenadores de escritorio.
2.  **Biografía Profesional:** Presentación del perfil, fotografía o avatar del desarrollador y botón de descarga de Currículum Vitae.
3.  **Habilidades & Herramientas:** Cuadrícula limpia y moderna que muestra los frameworks y herramientas dominadas organizadas mediante tarjetas.
4.  **Tecnologías Dominadas:** Representación visual precisa del nivel técnico del estudiante mediante barras de progreso dinámicas alimentadas desde la base de datos.
5.  **Galería de Proyectos:** Tarjetas estructuradas con título, descripción, capturas de pantalla, enlace directo al despliegue en vivo (Demo) y enlace al código fuente en GitHub.
6.  **Formulario de Contacto:** Captura de datos con validación en tiempo real. Envía los mensajes directamente a la base de datos para su posterior lectura en el panel de administración.

### **Panel Administrativo (Dashboard)**
1.  **Control de Acceso Seguro:** Formulario de inicio de sesión autenticado que valida credenciales encriptadas mediante sesiones PHP (`session_start()`). Prevención de accesos no autorizados a las rutas protegidas.
2.  **Métricas en Tiempo Real:** Tarjetas informativas que realizan un conteo automático de los registros existentes en cada tabla (Proyectos, Habilidades, Tecnologías y Mensajes recibidos).
3.  **Gestión de Contenidos (CRUD Completo):**
    * Actualizar información de la Biografía.
    * Agregar, editar y eliminar Habilidades y Tecnologías.
    * Crear, modificar y borrar Proyectos Realizados (carga de imágenes y enlaces).
4.  **Bandeja de Entrada de Mensajes:** Listado de consultas enviadas por los visitantes a través del formulario de contacto con opciones de lectura rápida.

---

## 📂 Estructura del Proyecto

El código fuente se encuentra organizado bajo buenas prácticas de desarrollo, separando la lógica del negocio de los recursos estáticos y los módulos administrativos:

```text
├── index.php                 # Vista pública principal del Portafolio
├── conexion.php              # Archivo de conexión PDO centralizada a la BD
├── login.php                 # Interfaz de autenticación para el administrador
├── logout.php                # Cierre de sesión seguro y destrucción de cookies
├── bd.sql                    # Script SQL con la estructura e inserciones iniciales
├── README.md                 # Documentación técnica del proyecto (este archivo)
├── /admin                    # Directorio protegido del Dashboard Administrativo
│   ├── dashboard.php         # Panel principal con métricas de control y resumen
│   ├── biografia_crud.php    # Gestión e inserción de datos biográficos
│   ├── habilidades_crud.php  # Altas, bajas y modificaciones de habilidades
│   ├── tecnologias_crud.php  # Mantenimiento de barras de progreso técnicas
│   ├── proyectos_crud.php    # Módulo de administración de proyectos realizados
│   └── mensajes_ver.php      # Visualización y gestión de mensajes de contacto
└── /assets                   # Recursos estáticos de la aplicación
    ├── /css
    │   └── style.css         # Estilos CSS personalizados complementarios
    ├── /js
    │   └── main.js           # Validaciones de formularios e interactividad JavaScript
    └── /img
        └── /uploads          # Carpeta destinada al almacenamiento de imágenes subidas
