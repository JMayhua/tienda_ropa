-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-03-2025 a las 23:20:39
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
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detalles_pedido`
--

INSERT INTO `detalles_pedido` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 2, 1, 49.99),
(2, 2, 3, 2, 129.99),
(3, 3, 10, 1, 29.99),
(4, 4, 2, 1, 49.99),
(5, 5, 1, 1, 19.99),
(6, 6, 3, 1, 129.99),
(7, 6, 4, 1, 39.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','completado','cancelado') DEFAULT 'pendiente',
  `comprobante` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `fecha`, `total`, `estado`, `comprobante`) VALUES
(1, 1, '2025-03-13 22:16:21', 49.99, 'pendiente', '67d34b2578890_qr_23.png'),
(2, 1, '2025-03-13 23:25:55', 259.98, 'pendiente', '67d35b736a062_logo.png'),
(3, 1, '2025-03-13 23:30:31', 29.99, 'pendiente', '67d35c8732f76_qr_21.png'),
(4, 1, '2025-03-16 02:26:11', 49.99, 'pendiente', '67d628b3d2122_qr_24.png'),
(5, 1, '2025-03-16 02:50:04', 19.99, 'pendiente', '67d62e4c203c7_logo.png'),
(6, 1, '2025-03-16 02:52:50', 169.98, 'pendiente', '67d62ef2b8409_20221026_224021_100150.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `talla` varchar(10) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `descuento` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `talla`, `color`, `imagen`, `stock`, `descuento`) VALUES
(1, 'Camiseta Básica Blanca', 'Camiseta de algodón suave y cómoda, ideal para uso diario.', 19.99, 'Camisetas', 'M', 'Blanco', '../publico/recursos/imagenes/1.jpg', 50, 0),
(2, 'Jeans Slim Fit Azul', 'Jeans ajustados y modernos, perfectos para un look casual.', 49.99, 'Pantalones', '32', 'Azul', '../publico/recursos/imagenes/2.jpg', 30, 0),
(3, 'Chaqueta de Cuero Negro', 'Chaqueta elegante y resistente, ideal para ocasiones especiales.', 129.99, 'Chaquetas', 'L', 'Negro', '../publico/recursos/imagenes/3.jpg', 10, 0),
(4, 'Vestido Floral Veraniego', 'Vestido ligero y fresco, perfecto para el verano.', 39.99, 'Vestidos', 'S', 'Multicolor', '../publico/recursos/imagenes/4.jpg', 25, 0),
(5, 'Zapatos Deportivos Negros', 'Zapatos cómodos y resistentes, ideales para correr o entrenar.', 79.99, 'Zapatos', '42', 'Negro', '../publico/recursos/imagenes/5.jpg', 20, 0),
(6, 'Gorra de Béisbol Roja', 'Gorra clásica con diseño moderno, ideal para días soleados.', 24.99, 'Accesorios', 'Única', 'Rojo', '../publico/recursos/imagenes/6.jpg', 40, 0),
(7, 'Sudadera con Capucha Gris', 'Sudadera cómoda y abrigada, perfecta para días fríos.', 69.99, 'Sudaderas', 'XL', 'Gris', '../publico/recursos/imagenes/7.jpg', 18, 0),
(8, 'Bolso de Mano Marrón', 'Bolso elegante y espacioso, ideal para llevar tus pertenencias.', 59.99, 'Bolsos', 'Única', 'Marrón', '../publico/recursos/imagenes/8.jpg', 15, 0),
(9, 'Reloj de Pulsera Plateado', 'Reloj elegante y duradero, perfecto para cualquier ocasión.', 99.99, 'Accesorios', 'Única', 'Plateado', '../publico/recursos/imagenes/9.jpg', 12, 0),
(10, 'Bufanda de Lana Azul', 'Bufanda suave y cálida, ideal para el invierno.', 29.99, 'Accesorios', 'Única', 'Azul', '../publico/recursos/imagenes/descarga.jpg', 35, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('cliente','admin') DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'jose', 'jose@mayhua', '$2y$10$Qty.Jw62ifydA7/c60JIpeZM.OGWkvZsseNORs.EfTfaeYB2gPp1C', 'cliente'),
(2, 'adolfo', 'admin@mayhua', '$2y$10$9aF89OhtI9srp/EwgFm5x.6Dyd7LtxYpNj70EQjvGptZlg2gaMqRG', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
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
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `detalles_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
