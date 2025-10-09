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

('Lucía Fernández', 'lucia.fernandez@example.com', '0$020y$12$IZg2ygBFt728hSrSVFfVEeyllnPpCrPHKle5pUs9mw5fgfOFTJ3x.', 'administrador'),
('Lucía Fernández', 'lucia.fernandez@example.com', '$2y$12$COytam4/33Irk1uUJ8UCLOnvtZHLxdWOmEa0RYgI7wd3e7j0TmsUW', 'cliente'),
('Marcos Delgado', 'marcos.delgado@example.com', '$2y$12$HhptmQQo1zLJDjUJeE2HVuzNh4h19ngGZE1ftgZ8Rd62X.qxq5XSy', 'cliente');

-- Productos textiles con imágenes externas para el catálogo
INSERT INTO productos (nombre, descripcion, precio, imagen, visible) VALUES
('Poncho Andino de Alpaca', 'Poncho tejido a mano con fibras de alpaca de la sierra peruana. Ideal para climas fríos y noches de fogata.', 249.90, 'https://images.unsplash.com/photo-1503341455253-b2e723bb3dbb?auto=format&fit=crop&w=900&q=80', TRUE),
('Manta Decorativa Cusqueña', 'Manta multicolor inspirada en patrones tradicionales cusqueños, perfecta para sofás o camas.', 189.50, 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80', TRUE),
('Bolso de Yute Eco Chic', 'Bolso tote de yute reforzado con asas de cuero vegano, ideal para compras sostenibles.', 89.90, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80', TRUE),
('Camisa de Lino Premium', 'Camisa de lino orgánico con botones de coco, fresca y elegante para eventos de verano.', 139.00, 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80', TRUE),
('Alfombra Artesanal Arequipeña', 'Alfombra tejida en telar artesanal con lana merino, tonos tierra para ambientes cálidos.', 329.00, 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=900&q=80', TRUE),
('Camino de Mesa Bordado', 'Camino de mesa bordado a mano con flores andinas, ideal para resaltar mesas de comedor.', 74.50, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=80', TRUE);

-- Inventario disponible por producto
INSERT INTO inventarios (id_producto, cantidad) VALUES
(1, 35),
(2, 28),
(3, 60),
(4, 42),
(5, 18),
(6, 55);

-- Pedidos registrados por los clientes
INSERT INTO pedidos (id_usuario, id_producto, cantidad, estado) VALUES
(2, 1, 1, 'confirmado'),
(2, 3, 2, 'pendiente'),
(3, 2, 1, 'completado'),
(3, 6, 3, 'pendiente');

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
