-- datos_prueba.sql

-- Insertar usuarios (clientes y administradores)
INSERT INTO usuarios (nombre, email, clave, rol) VALUES
('Administrador', 'admin@camilatextil.com', 'admin123', 'administrador'),
('Carlos Pérez', 'carlos.perez@gmail.com', 'cliente123', 'cliente'),
('Lucía Gómez', 'lucia.gomez@yahoo.com', 'cliente456', 'cliente'),
('Pedro Martínez', 'pedro.martinez@outlook.com', 'cliente789', 'cliente');

-- Insertar productos (productos disponibles para la venta)
INSERT INTO productos (nombre, descripcion, precio, imagen, visible) VALUES
('Tela Algodón', 'Tela de algodón de alta calidad para confección de ropa.', 25.50, 'https://via.placeholder.com/150', TRUE),
('Tela Seda', 'Tela de seda ideal para vestidos de lujo.', 75.00, 'https://via.placeholder.com/150', TRUE),
('Tela Lana', 'Tela gruesa de lana perfecta para chaquetas y abrigos.', 50.00, 'https://via.placeholder.com/150', TRUE),
('Tela Lino', 'Tela de lino, fresca y cómoda para ropa veraniega.', 40.00, 'https://via.placeholder.com/150', TRUE);

-- Insertar inventarios
INSERT INTO inventarios (id_producto, cantidad) VALUES
(1, 100),
(2, 50),
(3, 80),
(4, 150);

-- Insertar pedidos (pedidos realizados por los clientes)
INSERT INTO pedidos (id_usuario, id_producto, cantidad, estado) VALUES
(2, 1, 3, 'pendiente'),
(3, 2, 2, 'confirmado'),
(4, 3, 5, 'completado'),
(2, 4, 10, 'pendiente');

-- Insertar comentarios (comentarios realizados por los clientes sobre productos)
INSERT INTO comentarios (id_producto, id_usuario, comentario) VALUES
(1, 2, 'Excelente tela, muy cómoda para hacer camisetas.'),
(2, 3, 'La seda es de excelente calidad, muy suave al tacto.'),
(3, 4, 'La lana es gruesa y cálida, perfecta para invierno.'),
(4, 2, 'Muy ligera y fresca, ideal para el verano.');

-- Insertar empleados
INSERT INTO empleados (nombre, puesto, salario) VALUES
('Ana Rodríguez', 'Vendedora', 1200.00),
('Luis Fernández', 'Encargado de Inventario', 1500.00),
('Marta Gómez', 'Gerente', 2500.00);

-- Insertar sucursales
INSERT INTO sucursales (nombre, direccion, telefono, horario_apertura) VALUES
('Sucursal Santa Cruz 1', 'Av. Las Americas 123, Santa Cruz', '591-343-4567', 'Lunes a Viernes: 9:00 - 18:00'),
('Sucursal Santa Cruz 2', 'Calle 21 de Mayo, Santa Cruz', '591-343-7890', 'Lunes a Viernes: 10:00 - 17:00');
