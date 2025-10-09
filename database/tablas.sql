-- tablas.sql

-- Configuración inicial para asegurar compatibilidad con caracteres internacionales
SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- Preparar el entorno eliminando restricciones temporales
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET @OLD_SQL_NOTES = @@SQL_NOTES;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_NOTES = 0;

-- Crear y seleccionar la base de datos que utiliza la aplicación
CREATE DATABASE IF NOT EXISTS camila_textil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE camila_textil;

-- Eliminar tablas previas para permitir la reimportación limpia del esquema
DROP TABLE IF EXISTS producto_existencias;
DROP TABLE IF EXISTS producto_presentaciones;
DROP TABLE IF EXISTS producto_colores;
DROP TABLE IF EXISTS inventarios;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS comentarios;
DROP TABLE IF EXISTS empleados;
DROP TABLE IF EXISTS sucursales;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS usuarios;

-- Crear la tabla de usuarios (clientes y administradores)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'administrador') NOT NULL DEFAULT 'cliente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear la tabla de productos textiles
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255) NOT NULL,  -- Usamos URL externa para la imagen
    visible BOOLEAN DEFAULT TRUE,  -- Define si el producto es visible en el catálogo
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear la tabla de inventarios para productos sin variantes específicas
CREATE TABLE inventarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    cantidad DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Catálogo de colores específicos por producto
CREATE TABLE producto_colores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    nombre VARCHAR(120) NOT NULL,
    codigo_hex CHAR(7) DEFAULT NULL,
    imagen_muestra VARCHAR(255) DEFAULT NULL,
    descripcion TEXT DEFAULT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Presentaciones comerciales (por metro, por rollo, etc.) con su precio
CREATE TABLE producto_presentaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    tipo ENUM('rollo', 'metro') NOT NULL,
    metros_por_unidad DECIMAL(10, 2) DEFAULT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Existencias por combinación de color y presentación
CREATE TABLE producto_existencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_color INT NOT NULL,
    id_presentacion INT NOT NULL,
    stock DECIMAL(10, 2) NOT NULL DEFAULT 0,
    UNIQUE KEY uniq_variacion (id_producto, id_color, id_presentacion),
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_color) REFERENCES producto_colores(id) ON DELETE CASCADE,
    FOREIGN KEY (id_presentacion) REFERENCES producto_presentaciones(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear la tabla de pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    id_color INT DEFAULT NULL,
    id_presentacion INT DEFAULT NULL,
    cantidad DECIMAL(10, 2) NOT NULL,
    unidad ENUM('rollo', 'metro') NOT NULL DEFAULT 'metro',
    precio_unitario DECIMAL(10, 2) NOT NULL DEFAULT 0,
    total DECIMAL(12, 2) NOT NULL DEFAULT 0,
    estado ENUM('pendiente', 'confirmado', 'completado', 'cancelado') NOT NULL DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_producto) REFERENCES productos(id),
    FOREIGN KEY (id_color) REFERENCES producto_colores(id),
    FOREIGN KEY (id_presentacion) REFERENCES producto_presentaciones(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear la tabla de comentarios
CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,
    comentario TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear la tabla de empleados
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    puesto VARCHAR(255) NOT NULL,
    salario DECIMAL(10, 2) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear la tabla de sucursales
CREATE TABLE sucursales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    horario_apertura VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================
-- Datos de ejemplo realistas
-- ========================

-- Usuarios (un administrador y dos clientes)
-- Las contraseñas en texto plano son:
--   Administrador: AdminCamila2024!
--   Lucía Fernández: ClienteLuz#1
--   Marcos Delgado: ClienteMarcos#1
INSERT INTO usuarios (id, nombre, email, clave, rol) VALUES
(1, 'Camila Rivas', 'admin@camilatextiles.com', '$2y$12$uDAkRi3woqTwT3QC2dizwuIagONG3kbZ5YA2G4D0fRUv3KEeXe2LS', 'administrador'),
(2, 'Lucía Fernández', 'lucia.fernandez@example.com', '$2y$12$3jhMOzbJIBSUFRt1uAd97.sZ2QjCPeUvKLg5IevmWr.6XN2GiJGuK', 'cliente'),
(3, 'Marcos Delgado', 'marcos.delgado@example.com', '$2y$12$PRbyvWM4DMBUF7fVIM72d.At88famT7P4PQdmxWwXqLImlUHf5pL2', 'cliente');

-- Portafolio principal de telas para costura
INSERT INTO productos (id, nombre, descripcion, precio, imagen, visible) VALUES
(1, 'Tela Morley Premium para Blusas', 'Tejido acanalado con alto rebote y suavidad, importado para colecciones de blusas ajustadas y básicos de moda.', 39.50, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80', TRUE),
(2, 'Tela Baby Rib Importada', 'Algodón peinado con elasticidad bidireccional que realza camisetas, bodies y líneas deportivas de alta rotación.', 34.80, 'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?auto=format&fit=crop&w=900&q=80', TRUE),
(3, 'Tela Jersey Pima Suavizada', 'Punto jersey de algodón pima con mercerizado liviano para prendas premium que buscan caída fluida.', 42.00, 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=900&q=80', TRUE),
(4, 'Tela Lino Lavado Mediterráneo', 'Lino europeo lavado enzimáticamente con estructura aireada para camisería y resortwear.', 57.40, 'https://images.unsplash.com/photo-1517677129300-07b130802f46?auto=format&fit=crop&w=900&q=80', TRUE),
(5, 'Tela Denim Stretch Industrial', 'Denim índigo con elastano pensado para producciones masivas de jeans y uniformes resistentes.', 64.90, 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=900&q=80', TRUE),
(6, 'Tela Sarga Antifluido Premium', 'Tejido sarga con acabado repelente que protege contra líquidos sin sacrificar transpirabilidad.', 48.20, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=80', TRUE);

-- Inventario disponible por producto (los productos con variantes usan stock específico por combinación)
INSERT INTO inventarios (id, id_producto, cantidad) VALUES
(1, 1, 0),
(2, 2, 0),
(3, 3, 320),
(4, 4, 150),
(5, 5, 420),
(6, 6, 380);

-- Colores disponibles para las telas técnicas
INSERT INTO producto_colores (id, id_producto, nombre, codigo_hex, imagen_muestra, descripcion) VALUES
(1, 1, 'Crema vainilla', '#F3E5D8', 'https://images.unsplash.com/photo-1551232864-3f0890e580d4?auto=format&fit=crop&w=600&q=80', 'Matiz neutro que realza prendas minimalistas y colecciones resort.'),
(2, 1, 'Negro grafito', '#1F1F1F', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=600&q=80', 'Clásico atemporal que resiste el uso continuo sin perder color.'),
(3, 1, 'Verde olivo', '#556B2F', 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=600&q=80', 'Tono orgánico con matiz militar que funciona para colecciones cápsula y athleisure.'),
(4, 1, 'Rosa empolvado', '#F6D1C1', 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=600&q=80', 'Color femenino con alto movimiento en blusas y tops para retail.'),
(5, 2, 'Azul petróleo', '#1F3C5A', 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=600&q=80', 'Color corporate ideal para uniformes y líneas casual premium.'),
(6, 2, 'Terracota desértica', '#C26841', 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=600&q=80', 'Matiz cálido que conecta con colecciones boho y urbanas.'),
(7, 2, 'Blanco perla', '#F9F7F2', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80', 'Básico esencial para líneas de lencería y prendas de bebé.');

-- Presentaciones comerciales por producto
INSERT INTO producto_presentaciones (id, id_producto, tipo, metros_por_unidad, precio) VALUES
(1, 1, 'rollo', 25.00, 915.00),
(2, 1, 'metro', 1.00, 39.50),
(3, 2, 'rollo', 30.00, 945.00),
(4, 2, 'metro', 1.00, 34.80);

-- Existencias por variación (color + presentación)
INSERT INTO producto_existencias (id, id_producto, id_color, id_presentacion, stock) VALUES
(1, 1, 1, 1, 38),
(2, 1, 1, 2, 480),
(3, 1, 2, 1, 42),
(4, 1, 2, 2, 520),
(5, 1, 3, 1, 27),
(6, 1, 3, 2, 360),
(7, 1, 4, 1, 19),
(8, 1, 4, 2, 290),
(9, 2, 5, 3, 40),
(10, 2, 5, 4, 620),
(11, 2, 6, 3, 35),
(12, 2, 6, 4, 540),
(13, 2, 7, 3, 48),
(14, 2, 7, 4, 710);

-- Pedidos registrados por los clientes
INSERT INTO pedidos (id, id_usuario, id_producto, id_color, id_presentacion, cantidad, unidad, precio_unitario, total, estado) VALUES
(1, 2, 1, 2, 1, 2, 'rollo', 915.00, 1830.00, 'pendiente'),
(2, 3, 2, 6, 4, 60, 'metro', 34.80, 2088.00, 'confirmado'),
(3, 2, 3, NULL, NULL, 150, 'metro', 42.00, 6300.00, 'confirmado'),
(4, 3, 4, NULL, NULL, 120, 'metro', 57.40, 6888.00, 'pendiente'),
(5, 2, 2, 5, 3, 3, 'rollo', 945.00, 2835.00, 'pendiente'),
(6, 3, 1, 4, 2, 180, 'metro', 39.50, 7110.00, 'completado');

-- Comentarios visibles en el detalle de productos
INSERT INTO comentarios (id, id_producto, id_usuario, comentario) VALUES
(1, 1, 2, 'La elasticidad del Morley permitió lanzar una cápsula de básicos sin problemas de entalle.'),
(2, 2, 3, 'Los colores del Baby Rib se mantienen intensos tras varias lavadas industriales.'),
(3, 3, 2, 'El jersey pima tiene una caída impecable para nuestras camisetas premium.');

-- Equipo de la tienda
INSERT INTO empleados (id, nombre, puesto, salario) VALUES
(1, 'Valeria Nuñez', 'Gerente de operaciones', 5200.00),
(2, 'Javier Morales', 'Especialista en logística', 3200.00),
(3, 'Anaïs Campos', 'Diseñadora textil', 3800.00);

-- Sucursales activas de la marca
INSERT INTO sucursales (id, nombre, direccion, telefono, horario_apertura) VALUES
(1, 'Showroom Miraflores', 'Av. Larco 1021, Miraflores, Lima', '+51 1 456 7890', 'Lunes a sábado de 10:00 a 21:00'),
(2, 'Taller Artesanal Arequipa', 'Jr. Misti 548, Arequipa', '+51 54 321 654', 'Lunes a viernes de 09:00 a 18:00'),
(3, 'Boutique Cusco', 'Calle Triunfo 347, Cusco', '+51 84 765 432', 'Todos los días de 10:00 a 20:00');

-- Restaurar parámetros originales
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET SQL_NOTES = @OLD_SQL_NOTES;
