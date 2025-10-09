-- datos_prueba.sql

-- Insertar usuarios (clientes y administradores)
INSERT INTO usuarios (nombre, email, clave, rol) VALUES
('Administrador', 'admin@camilatextil.com', 'admin123', 'administrador'),
('Carlos Pérez', 'carlos.perez@gmail.com', 'cliente123', 'cliente'),
('Lucía Gómez', 'lucia.gomez@yahoo.com', 'cliente456', 'cliente'),
('Pedro Martínez', 'pedro.martinez@outlook.com', 'cliente789', 'cliente');

-- Insertar productos (productos disponibles para la venta)
INSERT INTO productos (nombre, descripcion, color, unidad_venta, precio, imagen, visible) VALUES
('Tela Algodón Peinado', 'Tejido plano 100% algodón, ideal para camisería y ropa casual.', 'Marfil', 'metro', 48.50, 'https://via.placeholder.com/150', TRUE),
('Tela Seda Natural', 'Satín de seda con caída fluida para vestidos de alta costura.', 'Perla', 'metro', 182.90, 'https://via.placeholder.com/150', TRUE),
('Tela Lana Merino', 'Paño de lana merino cardada, óptimo para abrigos y sacos ejecutivos.', 'Gris Oxford', 'rollo', 1240.00, 'https://via.placeholder.com/150', TRUE),
('Tela Lino Premium', 'Lino europeo de tacto fresco para colecciones primavera/verano.', 'Arena', 'metro', 66.75, 'https://via.placeholder.com/150', TRUE);

-- Insertar inventarios
INSERT INTO inventarios (id_producto, cantidad) VALUES
(1, 320.00),
(2, 180.00),
(3, 24.00),
(4, 410.00);

-- Insertar pedidos (pedidos realizados por los clientes)
INSERT INTO pedidos (id_usuario, id_producto, cantidad, unidad_venta, estado) VALUES
(2, 1, 45.00, 'metro', 'pendiente'),
(3, 2, 18.50, 'metro', 'confirmado'),
(4, 3, 3.00, 'rollo', 'completado'),
(2, 4, 120.00, 'metro', 'pendiente');

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
