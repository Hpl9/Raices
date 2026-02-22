-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-02-2026 a las 17:28:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `raices`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_pedidos`
--

CREATE TABLE `estados_pedidos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_pedidos`
--

INSERT INTO `estados_pedidos` (`id`, `nombre`) VALUES
(5, 'cancelado'),
(4, 'completado'),
(3, 'enviado'),
(1, 'pendiente'),
(2, 'procesando');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `direccion_entrega` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_id` int(11) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_creacion` int(11) DEFAULT NULL,
  `usuario_modificacion` int(11) DEFAULT NULL,
  `tipo_entrega` enum('recogida','envio') NOT NULL DEFAULT 'recogida'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `total`, `direccion_entrega`, `fecha_creacion`, `estado_id`, `fecha_modificacion`, `usuario_creacion`, `usuario_modificacion`, `tipo_entrega`) VALUES
(14, 1, 6.80, NULL, '2026-02-21 16:30:46', 1, '2026-02-21 16:30:46', NULL, NULL, 'recogida'),
(15, 2, 15.40, NULL, '2026-02-21 18:22:20', 1, '2026-02-21 18:22:20', NULL, NULL, 'recogida'),
(16, 2, 9.00, NULL, '2026-02-21 18:22:30', 1, '2026-02-21 18:22:30', NULL, NULL, 'recogida'),
(17, 2, 14.20, NULL, '2026-02-21 20:38:38', 1, '2026-02-21 20:38:38', NULL, NULL, 'envio'),
(18, 4, 4.80, NULL, '2026-02-21 20:39:54', 1, '2026-02-21 20:39:54', NULL, NULL, 'recogida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalles`
--

CREATE TABLE `pedido_detalles` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_detalles`
--

INSERT INTO `pedido_detalles` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(41, 14, 1, 1, 2.80),
(42, 14, 33, 1, 2.00),
(43, 14, 35, 1, 2.00),
(44, 15, 3, 1, 3.20),
(45, 15, 6, 1, 1.90),
(46, 15, 7, 1, 6.50),
(47, 15, 9, 1, 3.80),
(48, 16, 8, 1, 4.50),
(49, 16, 5, 1, 4.50),
(50, 17, 3, 1, 3.20),
(51, 17, 32, 1, 7.00),
(52, 17, 4, 1, 2.10),
(53, 17, 6, 1, 1.90),
(54, 18, 1, 1, 2.80),
(55, 18, 33, 1, 2.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `categoria` enum('fruta','verdura','regional') DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `unidad_medida` enum('kg','g','unidad') NOT NULL,
  `imagen_url` text DEFAULT NULL,
  `procedencia` varchar(100) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_creacion` int(11) DEFAULT NULL,
  `usuario_modificacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `categoria`, `descripcion`, `precio`, `stock`, `unidad_medida`, `imagen_url`, `procedencia`, `usuario_id`, `fecha_creacion`, `fecha_modificacion`, `usuario_creacion`, `usuario_modificacion`) VALUES
(1, 'Papas antiguas ', 'verdura', 'Papas antiguas majoreras, variedad colorada ', 2.80, 50, 'kg', 'http://localhost/Raices/public/frontend/cliente/assets/img/productos/papas-antiguas.png', 'Fuerteventura', 3, '2026-01-03 19:25:48', '2026-01-21 20:11:24', NULL, NULL),
(2, 'Calabacín ecológico', 'verdura', 'Calabacín de variedad verde lisa, cultivo ecológico.', 2.20, 40, 'kg', 'http://localhost/Raices/public/frontend/cliente/assets/img/productos/calabacines.png', 'Fuerteventura', 3, '2026-01-03 19:25:48', '2026-01-21 20:11:24', NULL, NULL),
(3, 'Tomate canario (ensalada)', 'verdura', 'Tomate de ensalada, variedad canaria tipo Híbrido Hash.', 3.20, 35, 'kg', 'http://localhost/Raices/public/frontend/cliente/assets/img/productos/tomate.png', 'Fuerteventura', 3, '2026-01-03 19:25:48', '2026-01-21 20:11:24', NULL, NULL),
(4, 'Plátano canario', 'fruta', 'Plátano de Canarias, variedad Cavendish.', 2.10, 60, 'kg', 'http://localhost/Raices/public/frontend/cliente/assets/img/productos/Platano.png', 'Gran Canaria', 3, '2026-01-03 19:25:48', '2026-02-21 18:39:11', NULL, NULL),
(5, 'Higos frescos', 'fruta', 'Higos frescos, variedad negra de temporada.', 4.50, 20, 'kg', 'http://localhost/Raices/public/frontend/cliente/assets/img/productos/higos.png', 'Fuerteventura', 3, '2026-01-03 19:25:48', '2026-01-21 20:11:24', NULL, NULL),
(6, 'Limón majorero', 'fruta', 'Limón majorero, variedad común.', 1.90, 45, 'kg', 'http://localhost/Raices/public/frontend/cliente/assets/img/productos/limones.png', 'Fuerteventura', 3, '2026-01-03 19:25:48', '2026-01-21 20:11:24', NULL, NULL),
(7, 'Queso majorero (cuña 250g)', 'regional', 'Queso Curado al pimentón, elaborado con leche de cabra.', 6.50, 25, 'unidad', 'http://localhost/Raices/public/frontend/cliente/assets/img/productos/queso-majorero-1.png', 'Fuerteventura', 4, '2026-01-03 19:25:48', '2026-01-21 20:11:24', NULL, NULL),
(8, 'Huevos camperos (docena)', 'regional', 'Huevos camperos tamaño M–L.', 4.50, 30, 'unidad', 'http://localhost/Raices/public/frontend/cliente/assets/img/productos/huevos.png', 'Fuerteventura', 4, '2026-01-03 19:25:48', '2026-02-08 18:40:26', NULL, NULL),
(9, 'Mojo picón casero (250ml)', 'regional', 'Mojo picón tradicional elaborado de forma artesanal', 3.80, 40, 'unidad', 'http://localhost/Raices/public/frontend/cliente/assets/img/productos/mojo-picon.png', 'Fuerteventura', 4, '2026-01-03 19:25:48', '2026-02-08 18:40:08', NULL, NULL),
(32, 'Aguacate', 'fruta', 'Cultivados en la finca de Villaverde', 7.00, 350, 'kg', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRlub5gLV2EATZIV0nZpsnhAJT8QHWJJdya0YdsdFaKHJY09bSWIQ6OGPMw3WITJ3oGVwwpW534ILPj5gPsP5L2xuDtFnwWATiBdsHep_JuJw&s=10', 'Fuerteventura', 3, '2026-02-08 18:24:49', '2026-02-21 18:39:39', NULL, NULL),
(33, 'Relinchón', 'verdura', 'cultivada en Tindaya', 2.00, 5, 'kg', 'data:image/jpeg;base64,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', 'Fuerteventura', 4, '2026-02-08 18:52:33', '2026-02-08 18:52:33', NULL, NULL),
(35, 'Lechuga francesa', 'verdura', 'Textura suave y su centro es crujiente', 2.00, 23, 'unidad', 'https://www.proplanta-sa.com.ar/wp-content/uploads/2021/02/lechuga-francesa-2.jpg', 'Fuerteventura', 3, '2026-02-08 19:28:40', '2026-02-08 19:28:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `apellido` varchar(120) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contrasena_hash` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` enum('cliente','admin') DEFAULT 'cliente',
  `direccion` varchar(255) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `poblacion` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ultimo_inicio_sesion` timestamp NULL DEFAULT NULL,
  `usuario_creacion` int(11) DEFAULT NULL,
  `usuario_modificacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `contrasena_hash`, `telefono`, `rol`, `direccion`, `codigo_postal`, `poblacion`, `municipio`, `fecha_creacion`, `fecha_modificacion`, `ultimo_inicio_sesion`, `usuario_creacion`, `usuario_modificacion`) VALUES
(1, 'Luis', 'Santos', 'cliente1@mail.com', '$2y$10$NbCut86eMaoiJ9WIoOmDjemKu9YVtP9vt0rvBivnjJUd5rZsQQJAe', '600111001', 'cliente', 'Calle Juan Ismael 10', '35600', 'Puerto del Rosario', 'Puerto del Rosario', '2026-01-03 18:51:17', '2026-01-21 20:10:45', NULL, NULL, NULL),
(2, 'Daniel', 'Pérez', 'cliente2@mail.com', '$2y$10$NxpiaQOZDlOA6dIFCFFUG.UyRCbLDG72xcuceElzPHwqYhFByZGlW', '600111002', 'cliente', 'Calle Primero de Mayo 5', '35660', 'Corralejo', 'La Oliva', '2026-01-03 18:51:17', '2026-01-21 20:10:45', NULL, NULL, NULL),
(3, 'Javi', 'Rodríguez', 'admin1@mail.com', '$2y$10$jbuknIVgw3ab..hTK2ul6eycwSkR2tMGGvQnPrQ4ME1RgBQsKOqvu', '600111003', 'admin', 'Calle Bocaina 22', '35650', 'El Cotillo', 'La Oliva', '2026-01-03 18:51:17', '2026-01-21 20:10:45', NULL, NULL, NULL),
(4, 'Irene', 'Morales', 'admin2@mail.com', '$2y$10$HkazhdptPBhAdnggEzaUXO5l8cPW8V3G/2w7.x4gdwhahrreTa3zi', '600111004', 'admin', 'Calle Majanicho 7', '35640', 'Lajares', 'La Oliva', '2026-01-03 18:51:17', '2026-01-21 20:10:45', NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estados_pedidos`
--
ALTER TABLE `estados_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_pedidos_estado` (`estado_id`);

--
-- Indices de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estados_pedidos`
--
ALTER TABLE `estados_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_estado` FOREIGN KEY (`estado_id`) REFERENCES `estados_pedidos` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD CONSTRAINT `pedido_detalles_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedido_detalles_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
