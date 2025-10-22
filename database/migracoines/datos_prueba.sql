USE camila_textil;

-- ======================================================
-- 👤 USUARIOS
-- ======================================================
INSERT INTO usuarios (nombre, email, clave, rol) VALUES
('Administrador', 'admin@camilatextil.com', 'admin123', 'administrador'),
('Lucía Fernández', 'lucia.fernandez@gmail.com', 'cliente123', 'cliente'),
('Carlos Pérez', 'carlos.perez@gmail.com', 'cliente456', 'cliente'),
('María Rojas', 'maria.rojas@hotmail.com', 'cliente789', 'cliente'),
('Pedro Morales', 'pedro.morales@outlook.com', 'cliente321', 'cliente'),
('Sofía Díaz', 'sofia.diaz@yahoo.com', 'cliente654', 'cliente');

-- ======================================================
-- 🧵 PRODUCTOS (ficha técnica)
-- ======================================================
INSERT INTO productos (nombre, descripcion, ancho_metros, composicion, tipo_tela, gramaje, elasticidad, precio_metro, precio_rollo, metros_por_rollo, imagen_principal)
VALUES
('Tela Morley Premium', 'Tela acanalada con alto rebote, ideal para bodys, blusas y ropa casual.', 1.60, '95% algodón, 5% spandex', 'Morley', 220.00, 'Bidireccional', 39.50, 920.00, 25, 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80'),
('Tela Rib Soft Stretch', 'Tejido suave con elasticidad media, perfecto para camisetas y tops.', 1.70, '97% algodón, 3% elastano', 'Rib', 180.00, 'Bidireccional', 38.00, 890.00, 25, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80'),
('Tela Lino Natural Europeo', 'Lino importado, con textura lavada y tacto fresco.', 1.55, '100% lino', 'Lino', 250.00, 'Sin elasticidad', 57.40, 1380.00, 30, 'https://images.unsplash.com/photo-1517677129300-07b130802f46?auto=format&fit=crop&w=900&q=80'),
('Tela Sarga Antifluido Premium', 'Tejido técnico repelente al agua, ideal para uniformes.', 1.50, '80% poliéster, 20% algodón', 'Sarga', 240.00, 'Sin elasticidad', 48.20, 1150.00, 28, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=80'),
('Tela Jersey DryFit Deportivo', 'Punto jersey respirable, ideal para ropa deportiva.', 1.80, '90% poliéster, 10% elastano', 'Jersey', 160.00, 'Bidireccional', 46.50, 1100.00, 30, 'https://images.unsplash.com/photo-1581089781785-603411fa81e5?auto=format&fit=crop&w=900&q=80'),
('Tela Popelina Ligera', 'Tela fina de algodón ideal para camisas, uniformes y batas.', 1.50, '100% algodón peinado', 'Popelina', 140.00, 'Sin elasticidad', 35.90, 875.00, 30, 'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?auto=format&fit=crop&w=900&q=80');

-- ======================================================
-- 🎨 COLORES (2 por producto)
-- ======================================================
INSERT INTO producto_colores (id_producto, nombre_color, codigo_color, codigo_hex, stock_metros, stock_rollos)
VALUES
-- Morley Premium
(1, 'Negro Grafito', 1, '#1F1F1F', 480, 20),
(1, 'Crema Vainilla', 2, '#F3E5D8', 350, 15),
-- Rib Soft Stretch
(2, 'Rosa Pálido', 3, '#F6D1C1', 400, 18),
(2, 'Azul Marino', 4, '#1F3C5A', 370, 15),
-- Lino Natural
(3, 'Beige Arena', 5, '#EED6AF', 420, 20),
(3, 'Gris Piedra', 6, '#D3D3D3', 300, 10),
-- Sarga Antifluido
(4, 'Verde Militar', 7, '#3C4F4B', 280, 12),
(4, 'Azul Royal', 8, '#2E5AAC', 310, 10),
-- Jersey DryFit
(5, 'Rojo Coral', 9, '#FF5E5B', 330, 14),
(5, 'Gris Titanio', 10, '#505050', 410, 16),
-- Popelina Ligera
(6, 'Blanco Puro', 11, '#FFFFFF', 600, 30),
(6, 'Celeste Claro', 12, '#B4D4EE', 460, 20);

-- ======================================================
-- 🧾 PEDIDOS (6 ejemplos)
-- ======================================================
INSERT INTO pedidos (id_usuario, id_producto, id_color, unidad, cantidad, precio_unitario, total, estado)
VALUES
(2, 1, 1, 'metro', 80, 39.50, 3160.00, 'pendiente'),
(3, 3, 5, 'rollo', 2, 1380.00, 2760.00, 'confirmado'),
(4, 2, 3, 'metro', 50, 38.00, 1900.00, 'pendiente'),
(5, 4, 7, 'rollo', 1, 1150.00, 1150.00, 'completado'),
(6, 5, 9, 'metro', 60, 46.50, 2790.00, 'confirmado'),
(2, 6, 11, 'metro', 90, 35.90, 3231.00, 'pendiente');

-- ======================================================
-- 💬 COMENTARIOS
-- ======================================================
INSERT INTO comentarios (id_producto, id_usuario, comentario)
VALUES
(1, 2, 'Excelente textura y rebote, muy cómoda para bodys.'),
(2, 3, 'Tela muy suave, mantiene su forma tras el lavado.'),
(3, 4, 'El lino es de excelente calidad, ideal para verano.'),
(4, 5, 'Buena resistencia y tacto profesional para uniformes.'),
(5, 6, 'Perfecta para ropa deportiva, transpirable y ligera.'),
(6, 2, 'Tela fresca y resistente, ideal para uniformes médicos.');

-- ======================================================
-- 👔 EMPLEADOS
-- ======================================================
INSERT INTO empleados (nombre, puesto, salario)
VALUES
('Valeria Núñez', 'Gerente General', 5200.00),
('Javier Morales', 'Encargado de Logística', 3100.00),
('Anaís Campos', 'Diseñadora Textil', 3800.00),
('Carla Arancibia', 'Vendedora Senior', 2500.00),
('Diego Rojas', 'Supervisor de Sucursal', 2700.00),
('Elena Gutiérrez', 'Asistente de Ventas', 2000.00);

-- ======================================================
-- 🏬 SUCURSALES
-- ======================================================
INSERT INTO sucursales (nombre, direccion, telefono, horario_apertura)
VALUES
('Sucursal Central Santa Cruz', 'Av. Irala 1100, Santa Cruz', '591-3-456789', 'Lunes a Sábado 09:00 - 18:00'),
('Sucursal La Paz', 'Av. Mariscal Santa Cruz 345, La Paz', '591-2-789654', 'Lunes a Viernes 10:00 - 17:00');
