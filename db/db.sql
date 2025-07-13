DROP DATABASE IF EXISTS db;
CREATE DATABASE IF NOT EXISTS db;
USE db;

CREATE TABLE IF NOT EXISTS usuario (
    usuario_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    rol ENUM("administrador", "usuario") NOT NULL DEFAULT "usuario"
);

CREATE TABLE tema (
    tema_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    descripcion TEXT
);

CREATE TABLE comentario (
    comentario_id INT AUTO_INCREMENT PRIMARY KEY,
    tema_id INT,
    usuario_id INT,
    contenido TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    respondido_a INT NULL,
    FOREIGN KEY (tema_id) REFERENCES tema(tema_id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id),
    FOREIGN KEY (respondido_a) REFERENCES comentario(comentario_id)
    ON DELETE CASCADE
)engine=InnoDB;

-- Password = 123

-- Usuarios
INSERT INTO usuario(email, password, nombre, rol) VALUES
("admin@gmail.com", "$2y$10$cFsbGP8RDLBx3ZOUfQ0l4.OcuC6zsWPggzsPGMnV9dOkHQKlX43GS", "admin", "administrador"),
("karen@gmail.com", "$2y$10$cFsbGP8RDLBx3ZOUfQ0l4.OcuC6zsWPggzsPGMnV9dOkHQKlX43GS", "Karen Sofia", "usuario"),
("lina@gmail.com", "$2y$10$cFsbGP8RDLBx3ZOUfQ0l4.OcuC6zsWPggzsPGMnV9dOkHQKlX43GS", "Lina Rodríguez", "usuario"),
("carlos@gmail.com", "$2y$10$cFsbGP8RDLBx3ZOUfQ0l4.OcuC6zsWPggzsPGMnV9dOkHQKlX43GS", "Carlos Ruiz", "usuario"),
("laura@gmail.com", "$2y$10$cFsbGP8RDLBx3ZOUfQ0l4.OcuC6zsWPggzsPGMnV9dOkHQKlX43GS", "Laura García", "usuario");

-- Temas
INSERT INTO tema(nombre, descripcion) VALUES
("Mejoras al sistema de riego", "Analizamos nuevas tecnologías de riego para aumentar la eficiencia en los cultivos."),
("Mercados locales para agricultores", "Discusión sobre cómo mejorar el acceso a mercados regionales.");

-- Comentarios
INSERT INTO comentario(tema_id, usuario_id, contenido) VALUES
(1, 2, "Una buena opción es el riego por goteo controlado por sensores."),
(1, 3, "En mi finca usamos riego automático solar, funciona bien."),
(1, 4, "El mayor problema que veo es el costo de implementación."),
(1, 5, "Podríamos hacer una compra comunitaria de equipos."),
(1, 2, "¿Alguien conoce empresas que vendan a buen precio estos sistemas?"),
(2, 3, "Los mercados locales aún son difíciles de acceder para los pequeños agricultores."),
(2, 4, "Creo que deberíamos crear una asociación para vender directamente."),
(2, 5, "¿Y si usamos redes sociales para promocionar nuestros productos?"),
(2, 2, "Buena idea. También podríamos vender en ferias locales."),
(2, 3, "Conozco a alguien del SENA que nos podría ayudar con logística.");
