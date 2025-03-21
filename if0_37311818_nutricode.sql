-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql101.infinityfree.com
-- Tiempo de generación: 09-10-2024 a las 18:28:10
-- Versión del servidor: 10.6.19-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_37311818_nutricode`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `nombre`, `precio`, `imagen`, `stock`, `descripcion`) VALUES
(11, 'Torta Alianza Lima', '40.00', 'img/11.jpg', 3, 'torta con temÃ¡tica de alianza lima. Incluye cupcacke y figuras a u gusto'),
(12, 'Torta temÃ¡tica Baby Shower', '120.00', 'img/12.jpg', 1, 'Torta para temÃ¡tica de Baby Shower. Incluye Cakepops, Cupcackes y figuras a su gusto'),
(13, 'Torta Kuromi', '120.00', 'img/13.jpg', 5, 'Torta con diseÃ±o de Kuromi - Hello Kity. Incluye brownies, cupcackes, cackpops y trufas.'),
(15, 'Torta con temÃ¡tica de Barbie', '120.00', 'img/15.jpg', 1, 'Torta con temÃ¡tica de Barbie. totalmente personalizable. incluye cupcackes, cackepops, brownies y trufas.'),
(16, 'Torta temÃ¡tica de ZoolÃ³gico', '120.00', 'img/16.jpg', 4, 'Torta con temÃ¡tica de zoolÃ³gico. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable'),
(17, 'Torta temÃ¡tica de Mario', '120.00', 'img/17.jpg', 3, 'Torta con temÃ¡tica de la franquicia de Mario. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable'),
(18, 'Torta temÃ¡tica de Merlina', '120.00', 'img/18.jpg', 5, 'Torta con temÃ¡tica de Merlina. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable'),
(19, 'Torta personalizable del CumpleaÃ±ero', '80.00', 'img/19.jpg', 4, 'Torta con una versiÃ³n \"FunkPop\" del cumpleaÃ±ero'),
(20, 'Torta Spiderman', '120.00', 'img/20.jpg', 2, 'Torta con temÃ¡tica de Spiderman. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable'),
(21, 'Torta con temÃ¡tica de princesa Disney (Blancanieves))', '120.00', 'img/21.jpg', 3, 'Torta con temÃ¡tica de princesa Disney a su gusto. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable'),
(22, 'Bocaditos dulces con temÃ¡tica espacial', '60.00', 'img/22.jpg', 4, 'Bocaditos con temÃ¡tica especial. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable'),
(23, 'Torta TemÃ¡tica de Toy Story', '120.00', 'img/23.jpg', 2, 'Torta con temÃ¡tica de la pelÃ­cula Toy Story. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable'),
(24, 'Torta de Evento Aniversario', '120.00', 'img/24.jpg', 1, 'Torta para celebrar el aniversario con tu pareja o algÃºn recuerdo con tu ser querido. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable'),
(25, 'Torta Primera ComuniÃ³n', '120.00', 'img/25.jpg', 2, 'Torta con temÃ¡tica para eventos religiosos. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable'),
(26, 'Torta Dragon Ball', '120.00', 'img/26.jpg', 2, 'Torta con temÃ¡tica de Dragon ball. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable con cualquier personaje de la serie'),
(27, 'Torta Princesa Disney (Ariel))', '120.00', 'img/27.jpg', 3, 'Torta con temÃ¡tica de Princesa Disney. incluye cupcackes, cackepops, brownies y trufas. totalmente personalizable');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calculos_metabolismo_basal`
--

CREATE TABLE `calculos_metabolismo_basal` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `resultado` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `articulo_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `mensaje` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carritos`
--

INSERT INTO `carritos` (`id`, `usuario_id`, `articulo_id`, `cantidad`, `subtotal`, `mensaje`) VALUES
(65, 49, 11, 9, '0.00', NULL),
(66, 49, 13, 15, '0.00', NULL),
(139, 52, 16, 1, '120.00', NULL),
(144, 40, 22, 1, '60.00', NULL),
(145, 40, 17, 1, '120.00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `username` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `es_admin` tinyint(1) DEFAULT 0,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `fecha_nacimiento`, `username`, `correo`, `password`, `es_admin`, `telefono`) VALUES
(15, 'Juan', 'Gaa', '1990-05-15', 'juanperez', 'juan@gmail.com', '$2y$10$oSNUOXvVi7vSkUZO51BGxO0fp/Jpyc0P4igZ9K.WHpRmfAl96Dvbu', 0, '916555123'),
(19, 'Laura', 'Diaz', '1987-07-05', 'lauradiaz', 'laura@gmail.com', '$2y$10$oHmDwmRHNAIOqDIMidwS/e5MIUvZ2Na/SIhq5geYqC6KQkCC25mkW', 0, '916555123'),
(20, 'Carlos', 'Ruiz', '1995-01-30', 'carlosruiz', 'carlos@gmail.com', '$2y$10$ltdxXDvMtHAVGQ0U3hIT2OJ9DafOJP.d4nG5TAbmF9XGbWS5Me9pi', 0, '916555123'),
(24, 'Mario', 'Torres', '1975-06-28', 'mariotorres', 'mario@gmail.com', '$2y$10$F8lVp6dNGd4ya7sLWiNUi.yPKAxjY7eOmN7oa6vnH7Jd1atVP/b6a', 0, '916555123'),
(39, 'ariam', 'pacheco', '2004-09-04', 'ariam', 'soonfo@gmail.com', '$2y$10$LQNvX472TseXgSIBqGNCOeNbsMl7vSWhJPbn3BwQNB1e6cdvNkg2i', 1, '916555123'),
(40, 'marcial', 'zegarra', '2021-09-12', 'ma21', 'marzedi@gmail.com', '$2y$10$6zgJbE9ZU1iywitR9js1z.HfhpWWArZMyxTjlh2L7EU/hZrua/xMW', 0, '916555123'),
(41, 'Jorge', 'Portocarrero', '2000-04-09', 'Droxon', 'jorge@gmail.com', '$2y$10$VRwNaV5bFOZT6jgsued4vORY1U.EhzUsmjlFV00c.IuTpqmuD94Qu', 1, '916555123'),
(42, 'Miguel', 'Laura', '2001-01-01', 'GatoWaza', 'miglaurato@uch.pe', '$2y$10$PcGgVygz0Y4giuzFrWPXOubo0TQyOcrj4xg96YrZ0NUaNrHUjQPsG', 0, '916555123'),
(49, 'Miguel Angel', 'Laura torres', '2005-01-01', 'asd', 'asd@gmail.com', '$2y$10$bowjIjZ/BdeBn5G/NPJZPOED/166.aC8.n6PazSN.cVsjSZG0Oxh6', 0, '916555123'),
(50, 'Marcial', 'Zegarra Diaz', '2024-09-12', 'ma23', 'marcial@gmail.com', '$2y$10$/oolLpoPGTFXDjJLHVpNPuvJZqJUTpyS6ioE99ZsR2hPSumApSWHC', 0, '916555123'),
(51, 'Admin', 'Admin', '2024-09-06', 'admin', 'admin@gmail.com', '$2y$10$DfMrVagi7ss25WuYqpefbOCafd3uISUYb/h0LIHVslhYXBTT8CPIu', 1, '916555123'),
(52, 'Cuellito ', 'Broaster', '2000-04-19', 'CB', 'CB@gmail.com', '$2y$10$hqrek8ae9gn/epXKCUb8huVOWm2QtOS.H0QRDJh4K4phGlDVl1k5K', 0, '916555123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `direccion` text DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `usuario_id`, `fecha_hora`, `monto_total`, `direccion`, `estado`) VALUES
(33, 50, '2024-09-23 14:26:40', '126.00', 'Comas 123', 'entregado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_articulo`
--

CREATE TABLE `venta_articulo` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `articulo_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `mensaje` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `venta_articulo`
--

INSERT INTO `venta_articulo` (`id`, `venta_id`, `articulo_id`, `cantidad`, `subtotal`, `mensaje`) VALUES
(60, 33, 12, 1, '2.29', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `calculos_metabolismo_basal`
--
ALTER TABLE `calculos_metabolismo_basal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `articulo_id` (`articulo_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `venta_articulo`
--
ALTER TABLE `venta_articulo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `articulo_id` (`articulo_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `calculos_metabolismo_basal`
--
ALTER TABLE `calculos_metabolismo_basal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `venta_articulo`
--
ALTER TABLE `venta_articulo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calculos_metabolismo_basal`
--
ALTER TABLE `calculos_metabolismo_basal`
  ADD CONSTRAINT `calculos_metabolismo_basal_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `carritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carritos_ibfk_2` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta_articulo`
--
ALTER TABLE `venta_articulo`
  ADD CONSTRAINT `venta_articulo_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_articulo_ibfk_2` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
