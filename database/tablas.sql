-- tablas.sql

-- Crear y seleccionar la base de datos que utiliza la aplicación
CREATE DATABASE IF NOT EXISTS camila_textil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE camila_textil;

-- Crear la tabla de usuarios (clientes y administradores)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'administrador') NOT NULL DEFAULT 'cliente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear la tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255) NOT NULL,  -- Usamos URL externa para la imagen
    visible BOOLEAN DEFAULT TRUE,  -- Define si el producto es visible en el catálogo
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear la tabla de inventarios
CREATE TABLE inventarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

-- Catálogo de colores específicos por producto
CREATE TABLE producto_colores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    nombre VARCHAR(120) NOT NULL,
    codigo_hex CHAR(7) DEFAULT NULL,
    imagen_muestra VARCHAR(255) DEFAULT NULL,
    descripcion TEXT DEFAULT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

-- Presentaciones comerciales (por metro, por rollo, etc.) con su precio
CREATE TABLE producto_presentaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    tipo ENUM('rollo', 'metro') NOT NULL,
    metros_por_unidad DECIMAL(10, 2) DEFAULT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

-- Existencias por combinación de color y presentación
CREATE TABLE producto_existencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_color INT NOT NULL,
    id_presentacion INT NOT NULL,
    stock DECIMAL(10, 2) NOT NULL DEFAULT 0,
    UNIQUE KEY uniq_variacion (id_color, id_presentacion),
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_color) REFERENCES producto_colores(id) ON DELETE CASCADE,
    FOREIGN KEY (id_presentacion) REFERENCES producto_presentaciones(id) ON DELETE CASCADE
);

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
);

-- Crear la tabla de comentarios
CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,
    comentario TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- Crear la tabla de empleados
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    puesto VARCHAR(255) NOT NULL,
    salario DECIMAL(10, 2) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear la tabla de sucursales
CREATE TABLE sucursales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    horario_apertura VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Datos de ejemplo realistas

-- Usuarios (un administrador y dos clientes)
-- Las contraseñas en texto plano son:
--   Administrador: AdminCamila2024!
--   Lucía Fernández: ClienteLuz#1
--   Marcos Delgado: ClienteMarcos#1
INSERT INTO usuarios (nombre, email, clave, rol) VALUES
('Camila Rivas', 'admin@camilatextiles.com', '$2y$12$uDAkRi3woqTwT3QC2dizwuIagONG3kbZ5YA2G4D0fRUv3KEeXe2LS', 'administrador'),
('Lucía Fernández', 'lucia.fernandez@example.com', '$2y$12$3jhMOzbJIBSUFRt1uAd97.sZ2QjCPeUvKLg5IevmWr.6XN2GiJGuK', 'cliente'),
('Marcos Delgado', 'marcos.delgado@example.com', '$2y$12$PRbyvWM4DMBUF7fVIM72d.At88famT7P4PQdmxWwXqLImlUHf5pL2', 'cliente');

-- Productos textiles con imágenes externas para el catálogo
INSERT INTO productos (nombre, descripcion, precio, imagen, visible) VALUES
('Poncho Andino de Alpaca', 'Poncho tejido a mano con fibras de alpaca de la sierra peruana. Ideal para climas fríos y noches de fogata.', 249.90, 'https://images.unsplash.com/photo-1503341455253-b2e723bb3dbb?auto=format&fit=crop&w=900&q=80', TRUE),
('Manta Decorativa Cusqueña', 'Manta multicolor inspirada en patrones tradicionales cusqueños, perfecta para sofás o camas.', 189.50, 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80', TRUE),
('Bolso de Yute Eco Chic', 'Bolso tote de yute reforzado con asas de cuero vegano, ideal para compras sostenibles.', 89.90, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80', TRUE),
('Camisa de Lino Premium', 'Camisa de lino orgánico con botones de coco, fresca y elegante para eventos de verano.', 139.00, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80', TRUE),
('Alfombra Artesanal Arequipeña', 'Alfombra tejida en telar artesanal con lana merino, tonos tierra para ambientes cálidos.', 329.00, 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=900&q=80', TRUE),
('Camino de Mesa Bordado', 'Camino de mesa bordado a mano con flores andinas, ideal para resaltar mesas de comedor.', 74.50, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=80', TRUE),
('Tela Morley Premium para Blusas', 'Tejido acanalado importado con alto rebote y suavidad, ideal para blusas ajustadas y básicos de moda.', 39.50, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80', TRUE),
('Tela Baby Rib Importada', 'Algodón peinado con elasticidad bidireccional que realza camisetas y bodies de alta rotación.', 34.80, 'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?auto=format&fit=crop&w=900&q=80', TRUE);

-- Inventario disponible por producto
INSERT INTO inventarios (id_producto, cantidad) VALUES
(1, 35),
(2, 28),
(3, 60),
(4, 42),
(5, 18),
(6, 55),
(7, 930),
(8, 900);

-- Colores disponibles para las telas técnicas
INSERT INTO producto_colores (id_producto, nombre, codigo_hex, imagen_muestra, descripcion) VALUES
(7, 'Crema vainilla', '#F3E5D8', 'https://images.unsplash.com/photo-1551232864-3f0890e580d4?auto=format&fit=crop&w=600&q=80', 'Matiz neutro que realza prendas minimalistas y colecciones resort.'),
(7, 'Negro grafito', '#1F1F1F', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=600&q=80', 'Clásico atemporal que resiste el uso continuo sin perder color.'),
(7, 'Verde olivo', '#556B2F', 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=600&q=80', 'Tono orgánico con matiz militar que funciona para colecciones cápsula y athleisure.'),
(7, 'Rosa empolvado', '#F6D1C1', 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=600&q=80', 'Color femenino con alto movimiento en blusas y tops para retail.'),
(8, 'Azul petróleo', '#1F3C5A', 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=600&q=80', 'Color corporate ideal para uniformes y líneas casual premium.'),
(8, 'Terracota desértica', '#C26841', 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=600&q=80', 'Matiz cálido que conecta con colecciones boho y urbanas.'),
(8, 'Blanco perla', '#F9F7F2', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80', 'Básico esencial para líneas de lencería y prendas de bebé.');

-- Presentaciones comerciales por producto
INSERT INTO producto_presentaciones (id_producto, tipo, metros_por_unidad, precio) VALUES
(7, 'rollo', 25.00, 890.00),
(7, 'metro', 1.00, 39.50),
(8, 'rollo', 30.00, 960.00),
(8, 'metro', 1.00, 34.80);

-- Existencias por variación (color + presentación)
INSERT INTO producto_existencias (id_producto, id_color, id_presentacion, stock) VALUES
(7, 1, 1, 6),
(7, 1, 2, 180),
(7, 2, 1, 5),
(7, 2, 2, 150),
(7, 3, 1, 3),
(7, 3, 2, 120),
(7, 4, 1, 2),
(7, 4, 2, 80),
(8, 5, 3, 4),
(8, 5, 4, 210),
(8, 6, 3, 5),
(8, 6, 4, 140),
(8, 7, 3, 4),
(8, 7, 4, 160);

-- Pedidos registrados por los clientes
INSERT INTO pedidos (id_usuario, id_producto, id_color, id_presentacion, cantidad, unidad, precio_unitario, total, estado) VALUES
(2, 1, NULL, NULL, 1, 'metro', 249.90, 249.90, 'confirmado'),
(2, 3, NULL, NULL, 2, 'metro', 89.90, 179.80, 'pendiente'),
(3, 2, NULL, NULL, 1, 'metro', 189.50, 189.50, 'completado'),
(3, 6, NULL, NULL, 3, 'metro', 74.50, 223.50, 'pendiente'),
(2, 7, 2, 1, 2, 'rollo', 890.00, 1780.00, 'pendiente'),
(3, 8, 7, 4, 40, 'metro', 34.80, 1392.00, 'confirmado');

-- Comentarios visibles en el detalle de productos
INSERT INTO comentarios (id_producto, id_usuario, comentario) VALUES
(1, 2, 'La textura del poncho es suave y abriga muchísimo. Llegó antes de lo esperado.'),
(3, 2, 'Muy resistente y con un diseño hermoso. Lo uso para todas mis compras.'),
(2, 3, 'Los colores son idénticos a las fotos y le dan vida a mi sala.'),
(6, 3, 'Ideal para reuniones familiares, todos preguntaron dónde lo compré.');

-- Equipo de la tienda
INSERT INTO empleados (nombre, puesto, salario) VALUES
('Valeria Nuñez', 'Gerente de tienda', 4200.00),
('Javier Morales', 'Especialista en logística', 3200.00),
('Anaïs Campos', 'Diseñadora textil', 3800.00);

-- Sucursales activas de la marca
INSERT INTO sucursales (nombre, direccion, telefono, horario_apertura) VALUES
('Showroom Miraflores', 'Av. Larco 1021, Miraflores, Lima', '+51 1 456 7890', 'Lunes a sábado de 10:00 a 21:00'),
('Taller Artesanal Arequipa', 'Jr. Misti 548, Arequipa', '+51 54 321 654', 'Lunes a viernes de 09:00 a 18:00'),
('Boutique Cusco', 'Calle Triunfo 347, Cusco', '+51 84 765 432', 'Todos los días de 10:00 a 20:00');
