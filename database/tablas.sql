-- tablas.sql

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
    color VARCHAR(120) NOT NULL, -- Paleta cromática que identifica la tela
    unidad_venta ENUM('metro', 'rollo') NOT NULL DEFAULT 'metro', -- Unidad comercial principal
    precio DECIMAL(10, 2) NOT NULL, -- Precio expresado en bolivianos (Bs)
    imagen VARCHAR(255) NOT NULL,  -- Usamos URL externa para la imagen
    visible BOOLEAN DEFAULT TRUE,  -- Define si el producto es visible en el catálogo
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear la tabla de inventarios
CREATE TABLE inventarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    cantidad DECIMAL(10, 2) NOT NULL, -- Control de stock expresado en la misma unidad de venta del producto
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

-- Crear la tabla de pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad DECIMAL(10, 2) NOT NULL,
    unidad_venta ENUM('metro', 'rollo') NOT NULL DEFAULT 'metro', -- Registra si el pedido fue reservado por metro lineal o rollo
    estado ENUM('pendiente', 'confirmado', 'completado', 'cancelado') NOT NULL DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_producto) REFERENCES productos(id)
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

-- Agregar datos iniciales de ejemplo (puedes agregar más si es necesario)
INSERT INTO usuarios (nombre, email, clave, rol) VALUES ('Administrador', 'admin@camilatextil.com', 'admin123', 'administrador');
