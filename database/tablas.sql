-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2025 a las 00:18:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `camila_textil`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `estado` enum('sin leer','leído') DEFAULT 'sin leer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `nombre`, `email`, `mensaje`, `estado`) VALUES
(1, 'adsfafds', 'josealbertoninarocha@gmail.com', 'adsfadsf', 'sin leer'),
(2, 'José Alberto Nina Rocha', 'josealbertoninarocha@gmail.com', 'adsf', 'sin leer'),
(3, 'José Alberto Nina Rocha', 'josealbertoninarocha@gmail.com', 'asdffads', 'sin leer'),
(4, 'José Alberto Nina Rocha', 'josealbertoninarocha@gmail.com', 'rtyeyrte', 'sin leer'),
(9, 'evelyn', 'josealbertoninarocha@gmail.com', 'asdfadsf', 'leído');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `puesto` varchar(255) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `puesto`, `salario`, `fecha_creacion`) VALUES
(1, 'Valeria Núñez', 'Gerente General', 5200.00, '2025-10-09 15:38:29'),
(2, 'Javier Morales', 'Encargado de Logística', 3100.00, '2025-10-09 15:38:29'),
(3, 'Anaís Campos', 'Diseñadora Textil', 3800.00, '2025-10-09 15:38:29'),
(4, 'Carla Arancibia', 'Vendedora Senior', 2500.00, '2025-10-09 15:38:29'),
(5, 'Diego Rojas', 'Supervisor de Sucursal', 2700.00, '2025-10-09 15:38:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_color` int(11) NOT NULL,
  `unidad` enum('metro','rollo') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `estado` enum('pendiente','confirmado','completado','cancelado') DEFAULT 'pendiente',
  `motivo_cancelacion` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_expira` timestamp GENERATED ALWAYS AS (`fecha_creacion` + interval 48 hour) STORED,
  `colores_combinados` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_usuario`, `id_producto`, `id_color`, `unidad`, `cantidad`, `precio_unitario`, `total`, `estado`, `motivo_cancelacion`, `fecha_creacion`, `colores_combinados`) VALUES
(1, 2, 1, 1, 'metro', 80.00, 39.50, 3160.00, 'completado', NULL, '2025-10-09 15:38:29', ''),
(2, 3, 3, 5, 'rollo', 2.00, 1380.00, 2760.00, 'completado', NULL, '2025-10-09 15:38:29', ''),
(6, 2, 6, 11, 'metro', 90.00, 35.90, 3231.00, 'completado', NULL, '2025-10-09 15:38:29', ''),
(7, 2, 3, 6, 'rollo', 1.00, 1380.00, 1380.00, 'completado', NULL, '2025-10-09 16:48:40', ''),
(8, 2, 3, 6, 'rollo', 1.00, 1380.00, 1380.00, 'completado', NULL, '2025-10-09 16:50:44', ''),
(9, 2, 1, 1, 'rollo', 1.00, 920.00, 920.00, 'completado', NULL, '2025-10-09 16:59:07', ''),
(10, 2, 1, 1, 'rollo', 14.00, 920.00, 12880.00, 'completado', NULL, '2025-10-09 17:09:01', ''),
(11, 2, 1, 1, 'metro', 4.00, 39.50, 158.00, 'completado', NULL, '2025-10-09 17:09:49', ''),
(12, 2, 1, 1, 'metro', 1.00, 39.50, 39.50, 'completado', NULL, '2025-10-09 17:18:20', ''),
(13, 2, 1, 2, 'rollo', 5.00, 920.00, 4600.00, 'completado', NULL, '2025-10-09 17:24:57', ''),
(14, 2, 1, 2, 'metro', 1.00, 39.50, 39.50, 'completado', NULL, '2025-10-09 17:30:08', ''),
(15, 2, 1, 1, 'metro', 1.00, 39.50, 39.50, 'completado', NULL, '2025-10-09 17:30:08', ''),
(16, 2, 1, 2, 'metro', 1.00, 39.50, 39.50, 'completado', NULL, '2025-10-09 17:33:17', ''),
(17, 2, 1, 1, 'metro', 1.00, 39.50, 39.50, 'completado', NULL, '2025-10-09 17:33:17', ''),
(18, 2, 6, 12, 'metro', 5.00, 35.90, 179.50, 'completado', NULL, '2025-10-19 00:10:20', ''),
(19, 2, 6, 11, 'metro', 5.00, 35.90, 179.50, 'completado', NULL, '2025-10-19 00:10:20', ''),
(20, 2, 2, 4, 'rollo', 1.00, 890.00, 890.00, 'pendiente', NULL, '2025-10-19 01:08:07', ''),
(21, 2, 2, 3, 'rollo', 1.00, 890.00, 890.00, 'pendiente', NULL, '2025-10-19 01:08:07', ''),
(22, 2, 2, 4, 'metro', 2.00, 38.00, 76.00, 'pendiente', NULL, '2025-10-19 01:20:55', ''),
(23, 2, 2, 3, 'metro', 2.00, 38.00, 76.00, 'pendiente', NULL, '2025-10-19 01:20:55', ''),
(24, 2, 4, 8, 'metro', 3.00, 48.20, 144.60, 'completado', NULL, '2025-10-19 01:25:23', ''),
(25, 2, 4, 7, 'metro', 3.00, 48.20, 144.60, 'completado', NULL, '2025-10-19 01:25:23', ''),
(26, 2, 1, 15, 'metro', 1.00, 39.50, 39.50, 'confirmado', NULL, '2025-10-19 01:45:11', ''),
(27, 2, 1, 14, 'metro', 1.00, 39.50, 39.50, 'confirmado', NULL, '2025-10-19 01:45:11', ''),
(28, 2, 1, 2, 'metro', 1.00, 39.50, 39.50, 'confirmado', NULL, '2025-10-19 01:45:11', ''),
(29, 2, 1, 1, 'metro', 1.00, 39.50, 39.50, 'confirmado', NULL, '2025-10-19 01:45:11', ''),
(30, 2, 2, 4, 'metro', 23.00, 38.00, 874.00, 'pendiente', NULL, '2025-10-19 06:33:12', ''),
(31, 2, 2, 3, 'metro', 23.00, 38.00, 874.00, 'pendiente', NULL, '2025-10-19 06:33:12', ''),
(32, 2, 4, 8, 'metro', 10.00, 48.20, 482.00, 'completado', NULL, '2025-10-19 23:52:34', ''),
(33, 2, 4, 7, 'metro', 10.00, 48.20, 482.00, 'completado', NULL, '2025-10-19 23:52:34', ''),
(34, 2, 19, 17, 'rollo', 2.00, 1.00, 2.00, 'pendiente', NULL, '2025-10-20 18:22:19', ''),
(35, 2, 19, 16, 'rollo', 2.00, 1.00, 2.00, 'pendiente', NULL, '2025-10-20 18:22:19', ''),
(36, 2, 19, 17, 'metro', 4.00, 1.00, 4.00, 'cancelado', 'No hay stock', '2025-10-20 18:22:27', ''),
(37, 2, 19, 16, 'metro', 4.00, 1.00, 4.00, 'pendiente', NULL, '2025-10-20 18:22:27', ''),
(38, 2, 19, 17, 'metro', 7.00, 1.00, 7.00, 'pendiente', NULL, '2025-10-20 18:24:48', ''),
(39, 2, 19, 16, 'metro', 7.00, 1.00, 7.00, 'pendiente', NULL, '2025-10-20 18:24:48', ''),
(40, 2, 19, 17, 'rollo', 4.00, 1.00, 4.00, 'pendiente', NULL, '2025-10-20 18:33:05', ''),
(41, 2, 19, 16, 'rollo', 4.00, 1.00, 4.00, 'pendiente', NULL, '2025-10-20 18:33:05', ''),
(42, 2, 2, 4, 'rollo', 1.00, 890.00, 35.60, 'cancelado', NULL, '2025-10-20 18:33:23', ''),
(43, 2, 2, 3, 'rollo', 1.00, 890.00, 35.60, 'cancelado', NULL, '2025-10-20 18:33:23', ''),
(44, 2, 3, 6, 'rollo', 1.00, 1380.00, 46.00, 'pendiente', NULL, '2025-10-20 18:37:40', ''),
(45, 2, 3, 5, 'rollo', 1.00, 1380.00, 46.00, 'pendiente', NULL, '2025-10-20 18:37:40', ''),
(46, 2, 2, 4, 'metro', 3.00, 38.00, 114.00, 'cancelado', NULL, '2025-10-20 18:39:15', ''),
(47, 2, 2, 3, 'metro', 3.00, 38.00, 114.00, 'cancelado', NULL, '2025-10-20 18:39:15', ''),
(48, 2, 3, 6, 'rollo', 1.00, 1380.00, 46.00, 'pendiente', NULL, '2025-10-20 18:39:45', ''),
(49, 2, 3, 5, 'rollo', 1.00, 1380.00, 46.00, 'pendiente', NULL, '2025-10-20 18:39:45', ''),
(50, 2, 3, 6, 'metro', 6.00, 57.40, 344.40, 'pendiente', NULL, '2025-10-20 18:40:09', ''),
(51, 2, 3, 5, 'metro', 6.00, 57.40, 344.40, 'pendiente', NULL, '2025-10-20 18:40:09', ''),
(52, 2, 3, 6, 'rollo', 2.00, 1380.00, 92.00, 'pendiente', NULL, '2025-10-20 18:40:30', ''),
(53, 2, 3, 5, 'rollo', 2.00, 1380.00, 92.00, 'pendiente', NULL, '2025-10-20 18:40:30', ''),
(63, 2, 2, 4, 'metro', 1.00, 38.00, 38.00, 'cancelado', NULL, '2025-10-20 19:50:32', ''),
(64, 2, 2, 4, 'metro', 1.00, 38.00, 38.00, 'cancelado', NULL, '2025-10-20 19:50:36', ''),
(65, 2, 2, 3, 'metro', 1.00, 38.00, 38.00, 'cancelado', NULL, '2025-10-20 19:50:36', ''),
(66, 2, 6, 12, 'rollo', 1.00, 875.00, 875.00, 'cancelado', 'No hay stock', '2025-10-20 19:51:06', ''),
(67, 2, 6, 11, 'rollo', 1.00, 875.00, 875.00, 'cancelado', 'Error del cliente', '2025-10-20 19:51:06', ''),
(68, 2, 2, 4, 'rollo', 1.00, 22250.00, 22250.00, 'cancelado', NULL, '2025-10-20 19:58:13', ''),
(69, 2, 2, 3, 'rollo', 1.00, 22250.00, 22250.00, 'cancelado', NULL, '2025-10-20 19:58:13', ''),
(70, 2, 1, 15, 'rollo', 4.00, 750.00, 3000.00, 'cancelado', NULL, '2025-10-20 20:00:51', ''),
(71, 2, 1, 14, 'rollo', 4.00, 750.00, 3000.00, 'cancelado', NULL, '2025-10-20 20:00:51', ''),
(72, 2, 1, 15, 'rollo', 1.00, 750.00, 750.00, 'cancelado', NULL, '2025-10-20 20:03:42', ''),
(73, 2, 1, 14, 'rollo', 1.00, 750.00, 750.00, 'cancelado', NULL, '2025-10-20 20:03:42', ''),
(74, 2, 1, 2, 'rollo', 1.00, 750.00, 750.00, 'cancelado', NULL, '2025-10-20 20:03:42', ''),
(75, 2, 1, 1, 'rollo', 1.00, 750.00, 750.00, 'cancelado', NULL, '2025-10-20 20:03:42', ''),
(76, 2, 2, 4, 'rollo', 1.00, 800.00, 800.00, 'cancelado', NULL, '2025-10-20 20:03:54', ''),
(77, 2, 2, 3, 'rollo', 1.00, 800.00, 800.00, 'cancelado', NULL, '2025-10-20 20:03:54', ''),
(78, 2, 4, 8, 'metro', 1.00, 48.20, 48.20, 'cancelado', NULL, '2025-10-20 20:13:56', ''),
(79, 2, 4, 8, 'metro', 3.00, 48.20, 144.60, 'cancelado', NULL, '2025-10-20 20:14:00', ''),
(80, 2, 4, 7, 'metro', 3.00, 48.20, 144.60, 'cancelado', NULL, '2025-10-20 20:14:00', ''),
(81, 2, 1, 15, 'metro', 4.00, 39.50, 158.00, 'cancelado', NULL, '2025-10-20 20:14:18', ''),
(82, 2, 1, 14, 'metro', 4.00, 39.50, 158.00, 'cancelado', NULL, '2025-10-20 20:14:18', ''),
(83, 2, 1, 2, 'metro', 4.00, 39.50, 158.00, 'cancelado', NULL, '2025-10-20 20:14:18', ''),
(84, 2, 1, 1, 'metro', 4.00, 39.50, 158.00, 'cancelado', NULL, '2025-10-20 20:14:18', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalles`
--

CREATE TABLE `pedido_detalles` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_color` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `total` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ancho_metros` decimal(5,2) DEFAULT 1.60,
  `composicion` varchar(255) DEFAULT NULL,
  `tipo_tela` varchar(100) DEFAULT NULL,
  `elasticidad` varchar(100) DEFAULT NULL,
  `precio_metro` decimal(10,2) NOT NULL,
  `precio_rollo` decimal(10,2) NOT NULL,
  `metros_por_rollo` decimal(10,2) DEFAULT 25.00,
  `imagen_principal` varchar(255) NOT NULL,
  `visible` tinyint(1) DEFAULT 1,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `ancho_metros`, `composicion`, `tipo_tela`, `elasticidad`, `precio_metro`, `precio_rollo`, `metros_por_rollo`, `imagen_principal`, `visible`, `estado`, `fecha_creacion`) VALUES
(1, 'Tela Morley Premium', 'Tela acanalada con alto rebote, ideal para bodys, blusas y ropa casual.', 1.60, '95% algodón, 5% spandex', 'Morley', '', 39.50, 30.00, 25.00, 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80', 1, 'activo', '2025-10-09 15:38:29'),
(2, 'Tela Rib Soft Stretch', 'Tejido suave con elasticidad media, perfecto para camisetas y tops.', 1.70, '97% algodón, 3% elastano', 'Rib', '', 38.00, 32.00, 25.00, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80', 1, 'activo', '2025-10-09 15:38:29'),
(3, 'Tela Lino Natural Europeo', 'Lino importado, con textura lavada y tacto fresco.', 1.55, '100% lino', 'Lino', '', 57.40, 50.00, 30.00, 'https://images.unsplash.com/photo-1517677129300-07b130802f46?auto=format&fit=crop&w=900&q=80', 1, 'activo', '2025-10-09 15:38:29'),
(4, 'Tela Sarga Antifluido Premium', 'Tejido técnico repelente al agua, ideal para uniformes.', 1.50, '80% poliéster, 20% algodón', 'Sarga', '', 48.20, 42.00, 28.00, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=80', 1, 'activo', '2025-10-09 15:38:29'),
(5, 'Tela Jersey DryFit Deportivo', 'Punto jersey respirable, ideal para ropa deportiva.', 1.80, '90% poliéster, 10% elastano', 'Jersey', '', 46.50, 41.00, 30.00, 'https://images.unsplash.com/photo-1581089781785-603411fa81e5?auto=format&fit=crop&w=900&q=80', 1, 'activo', '2025-10-09 15:38:29'),
(6, 'Tela Popelina Ligera', 'Tela fina de algodón ideal para camisas, uniformes y batas.', 1.50, '100% algodón peinado', 'Popelina', '', 35.90, 30.00, 30.00, 'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?auto=format&fit=crop&w=900&q=80', 1, 'activo', '2025-10-09 15:38:29'),
(7, '234', '234', 1.60, '234', '234', '234', 234.00, 234.00, 234.00, 'C:\\xampp\\htdocs\\camila-textil\\controllers/../uploads/68f65cfb114f2_WhatsApp Image 2025-09-29 at 17.05.37.jpeg', 0, 'inactivo', '2025-10-20 16:02:03'),
(14, '7', '7', 1.60, '7', '7', '7', 7.00, 7.00, 7.00, 'C:\\xampp\\htdocs\\camila-textil\\controllers/../uploads/68f668a078f45_WhatsApp Image 2025-09-29 at 17.05.37.jpeg', 0, 'inactivo', '2025-10-20 16:51:44'),
(19, '121223', '13', 1.60, '1', '1', '', 1.00, 1.00, 1.00, 'C:\\xampp\\htdocs\\camila-textil\\controllers/../uploads/68f677efc00ab_cronograma camila textil.jpg', 0, 'inactivo', '2025-10-20 17:44:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_colores`
--

CREATE TABLE `producto_colores` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre_color` varchar(100) NOT NULL,
  `codigo_color` int(11) DEFAULT NULL,
  `codigo_hex` char(7) DEFAULT NULL,
  `stock_metros` decimal(10,2) DEFAULT 0.00,
  `stock_rollos` decimal(10,2) DEFAULT 0.00,
  `imagen_muestra` varchar(255) DEFAULT NULL,
  `estado` enum('disponible','agotado') DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_colores`
--

INSERT INTO `producto_colores` (`id`, `id_producto`, `nombre_color`, `codigo_color`, `codigo_hex`, `stock_metros`, `stock_rollos`, `imagen_muestra`, `estado`) VALUES
(1, 1, 'Negro Grafito', 1, '#1F1F1F', 480.00, 20.00, NULL, 'disponible'),
(2, 1, 'Crema Vainilla', 2, '#F3E5D8', 350.00, 15.00, NULL, 'disponible'),
(3, 2, 'Rosa Pálido', 3, '#F6D1C1', 400.00, 18.00, NULL, 'disponible'),
(4, 2, 'Azul Marino', 4, '#1F3C5A', 370.00, 15.00, NULL, 'disponible'),
(5, 3, 'Beige Arena', 5, '#EED6AF', 420.00, 20.00, NULL, 'disponible'),
(6, 3, 'Gris Piedra', 6, '#D3D3D3', 300.00, 10.00, NULL, 'disponible'),
(7, 4, 'Verde Militar', 7, '#3C4F4B', 280.00, 12.00, NULL, 'disponible'),
(8, 4, 'Azul Royal', 8, '#2E5AAC', 310.00, 10.00, NULL, 'disponible'),
(9, 5, 'Rojo Coral', 9, '#FF5E5B', 330.00, 14.00, NULL, 'disponible'),
(10, 5, 'Gris Titanio', 10, '#505050', 410.00, 16.00, NULL, 'disponible'),
(11, 6, 'Blanco Puro', 11, '#FFFFFF', 600.00, 30.00, NULL, 'disponible'),
(12, 6, 'Celeste Claro', 12, '#B4D4EE', 460.00, 20.00, NULL, 'disponible'),
(14, 1, 'Blanco', 3, '#ffffff', 82.00, 10.00, NULL, 'disponible'),
(15, 1, 'azul', 4, '#050dff', 100.00, 12.00, NULL, 'disponible'),
(16, 19, 'Blanco', 2, '#ffffff', 3.00, 3.00, NULL, 'disponible'),
(17, 19, 'negro', 1, '#000000', 11.00, 2.00, NULL, 'disponible'),
(19, 14, 'negro', 1, '#000000', 0.00, 0.00, NULL, 'disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `horario_apertura` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `visible` tinyint(1) DEFAULT 1,
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`id`, `nombre`, `direccion`, `telefono`, `horario_apertura`, `fecha_creacion`, `visible`, `latitud`, `longitud`) VALUES
(1, 'Sucursal Central', 'Av. 24 de Septiembre 123, Santa Cruz', '320-123456', 'Lunes a Viernes: 9:00 AM - 6:00 PM', '2025-10-19 11:46:28', 1, -17.7804011, -63.1830597),
(2, 'Sucursal Norte', 'Calle 10, Zona Norte, Santa Cruz', '320-654321', 'Lunes a Viernes: 9:00 AM - 5:00 PM', '2025-10-19 11:46:28', 1, -17.7993616, -63.1727600),
(3, 'Sucursal Sur', 'Av. El Trompillo 2500, Santa Cruz', '320-112233', 'Lunes a Viernes: 10:00 AM - 7:00 PM', '2025-10-19 11:46:28', 1, -17.7895000, -63.2020000),
(11, 'adsffdasadf', 'adsf', 'asdf', 'asdf', '2025-10-19 05:48:29', 1, -17.7947085, -63.1219482);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('cliente','administrador') DEFAULT 'cliente',
  `estado` enum('habilitado','bloqueado') DEFAULT 'habilitado',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `clave`, `rol`, `estado`, `fecha_creacion`) VALUES
(1, 'Administrador', 'admin@camilatextil.com', '$2y$10$.nmnPwlg9bTdGo2arUQBeOAH5Zy4HlxbTdvXst1HGEGJFQxdGXJQS', 'administrador', 'habilitado', '2025-10-09 15:38:29'),
(2, 'Lucía Fernández', 'lucia.fernandez@gmail.com', '$2y$10$bhWxtq7y7bXZDRM/kNbkC.sd8HARM49VQwXkNmHa8Ih4lTLw0HAnq', 'cliente', 'habilitado', '2025-10-09 15:38:29'),
(3, 'Carlos Pérez', 'carlos.perez@gmail.com', 'cliente456', 'cliente', 'habilitado', '2025-10-09 15:38:29'),
(7, 'jose', 'jose@gmail.com', '$2y$10$qRg49tthCde2TU.Zwj7uIuG64BonjVZcBc.W3IlY/v5.AYIjGoiG2', 'administrador', 'habilitado', '2025-10-19 06:40:33'),
(9, 'José Alberto Nina Rocha', 'jose@camilatextil.com', '$2y$10$z9f.2PkintundU44DeqEe.gTvYh9z7rVSAFvalfGtJCawTFYs0cv.', 'administrador', 'habilitado', '2025-10-20 14:49:36');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_fk_usuario` (`id_usuario`),
  ADD KEY `pedidos_fk_producto` (`id_producto`),
  ADD KEY `pedidos_fk_color` (`id_color`);

--
-- Indices de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_color` (`id_color`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto_colores`
--
ALTER TABLE `producto_colores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `producto_colores`
--
ALTER TABLE `producto_colores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_fk_color` FOREIGN KEY (`id_color`) REFERENCES `producto_colores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedidos_fk_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedidos_fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD CONSTRAINT `pedido_detalles_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_detalles_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `producto_colores` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
