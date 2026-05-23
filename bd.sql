-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS portafolio_db;
USE portafolio_db;

-- 1. Tabla de Usuarios (Para el Dashboard / Login)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL -- Aquí guardaremos contraseñas encriptadas con password_hash()
);

-- 2. Tabla de Biografía
CREATE TABLE biografia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    titulo_profesional VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    cv_url VARCHAR(255) DEFAULT NULL
);

-- 3. Tabla de Habilidades (Iconos)
CREATE TABLE habilidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    icono_clase VARCHAR(50) NOT NULL -- Ej: 'fa-brands fa-html5' o ruta de imagen
);

-- 4. Tabla de Tecnologías (Barras de progreso)
CREATE TABLE tecnologias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    porcentaje INT NOT NULL -- Ej: 95, 80, 75
);

-- 5. Tabla de Proyectos
CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen_url VARCHAR(255) NOT NULL,
    link_demo VARCHAR(255) DEFAULT NULL,
    link_github VARCHAR(255) DEFAULT NULL
);

-- 6. Tabla de Mensajes (Formulario de contacto)
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    asunto VARCHAR(150) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Inserción de un usuario Administrador de prueba
-- La contraseña por defecto es: admin123 (encriptada con BCRYPT)
INSERT INTO usuarios (nombre, email, password) 
VALUES ('Administrador', 'admin@correo.com', '$2y$10$e.w2z/r3hZfQ.U4W1z.5oe.bN/bN/bN/bN/bN/bN/bN/bN/bN/bN');

-- Inserción de biografía inicial
INSERT INTO biografia (nombre_completo, titulo_profesional, descripcion)
VALUES ('Tu Nombre Completo', 'Desarrollador Web & Apasionado por la Tecnología', 'Soy desarrollador web con experiencia en la creación de sitios y aplicaciones modernas, funcionales y responsivas.');