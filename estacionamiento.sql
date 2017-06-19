-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 19-06-2017 a las 05:28:10
-- Versión del servidor: 10.1.22-MariaDB
-- Versión de PHP: 7.0.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `estacionamiento`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autos`
--

CREATE TABLE `autos` (
  `patente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `color` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `marca` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `autos`
--

INSERT INTO `autos` (`patente`, `color`, `marca`) VALUES
('DUD454', 'BLANCO', 'PEUGEOT'),
('BUD554', 'ROJO', 'RENAULT'),
('BUD555', 'ROJO', 'RENAULT'),
('BUD556', 'ROJO', 'RENAULT'),
('ASO111', 'rojo', 'renault'),
('fff111', 'blanco', 'chevrolet'),
('ADD401', 'verde', 'peugeot'),
('BUD111', 'blanco', 'renault'),
('FPI', 'azul', 'ford'),
('DDD333', 'rojo', 'renault'),
('DPI111', 'rojo', 'renault'),
('DPI444', 'rojo', 'renault');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `usuario`, `pass`, `activo`) VALUES
(1, 'admin', 'admin', 1),
(8, 'juan', '12345', 1),
(23, 'casto', '1111', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loginempleados`
--

CREATE TABLE `loginempleados` (
  `idempleado` int(11) NOT NULL,
  `dia` date NOT NULL,
  `entrada` time NOT NULL,
  `salida` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `loginempleados`
--

INSERT INTO `loginempleados` (`idempleado`, `dia`, `entrada`, `salida`) VALUES
(1, '2017-06-10', '09:38:24', '09:38:52'),
(1, '2017-06-10', '09:39:00', '09:39:44'),
(1, '2017-06-10', '09:39:47', '09:40:08'),
(1, '2017-06-10', '09:40:11', '09:40:27'),
(1, '2017-06-10', '09:40:31', '09:48:56'),
(1, '2017-06-10', '09:48:45', '09:48:56'),
(1, '2017-06-10', '09:49:00', '09:50:19'),
(1, '2017-06-10', '09:50:10', '09:50:19'),
(1, '2017-06-10', '09:50:25', '09:50:29'),
(1, '2017-06-10', '09:50:33', '09:54:23'),
(23, '2017-06-10', '09:54:35', '09:54:41'),
(1, '2017-06-10', '09:54:44', '09:55:29'),
(1, '2017-06-10', '09:55:26', '09:55:29'),
(1, '2017-06-10', '09:55:50', '09:55:53'),
(23, '2017-06-10', '09:56:06', '09:57:32'),
(1, '2017-06-10', '10:38:04', '21:05:32'),
(1, '2017-06-10', '21:03:35', '21:05:32'),
(1, '2017-06-10', '21:05:19', '21:05:32'),
(23, '2017-06-10', '21:05:43', '11:51:20'),
(1, '2017-06-11', '11:51:24', '12:11:53'),
(1, '2017-06-11', '12:11:56', '12:22:26'),
(1, '2017-06-11', '12:22:30', '12:25:26'),
(1, '2017-06-11', '12:25:30', '12:46:18'),
(1, '2017-06-11', '13:21:23', '19:28:00'),
(1, '2017-06-11', '19:27:13', '19:28:00'),
(1, '2017-06-11', '19:34:50', '19:35:44'),
(1, '2017-06-11', '19:35:48', '19:59:11'),
(1, '2017-06-11', '19:59:17', '19:59:56'),
(1, '2017-06-11', '20:00:01', '20:00:57'),
(1, '2017-06-11', '20:01:01', '20:01:29'),
(1, '2017-06-11', '20:01:34', '20:03:58'),
(1, '2017-06-11', '20:03:53', '20:03:58'),
(1, '2017-06-11', '20:08:30', '20:29:51'),
(1, '2017-06-11', '20:16:45', '20:29:51'),
(1, '2017-06-11', '20:29:54', '20:30:07'),
(1, '2017-06-11', '20:34:39', '20:49:30'),
(1, '2017-06-11', '20:49:16', '20:49:30'),
(1, '2017-06-11', '20:49:33', '20:50:24'),
(1, '2017-06-11', '21:11:19', '23:45:27'),
(1, '2017-06-11', '21:11:58', '23:45:27'),
(1, '2017-06-11', '21:12:25', '23:45:27'),
(1, '2017-06-11', '21:12:35', '23:45:27'),
(1, '2017-06-11', '21:14:21', '23:45:27'),
(1, '2017-06-11', '21:15:20', '23:45:27'),
(1, '2017-06-11', '21:15:36', '23:45:27'),
(1, '2017-06-11', '21:52:04', '23:45:27'),
(1, '2017-06-11', '21:52:19', '23:45:27'),
(1, '2017-06-11', '23:45:31', '23:45:36'),
(1, '2017-06-11', '23:45:42', '06:32:52'),
(1, '2017-06-11', '23:46:06', '06:32:52'),
(1, '2017-06-11', '23:49:46', '06:32:52'),
(1, '2017-06-12', '06:30:09', '06:32:52'),
(1, '2017-06-12', '06:33:02', '06:36:03'),
(1, '2017-06-12', '06:36:09', '06:54:37'),
(1, '2017-06-12', '06:54:40', '06:54:53'),
(1, '2017-06-12', '06:54:58', '06:56:21'),
(1, '2017-06-12', '06:56:26', '00:44:57'),
(1, '2017-06-12', '07:18:02', '00:44:57'),
(1, '2017-06-13', '23:37:19', '00:44:57'),
(1, '2017-06-13', '23:44:25', '00:44:57'),
(1, '2017-06-13', '23:45:57', '00:44:57'),
(1, '2017-06-13', '23:47:57', '00:44:57'),
(1, '2017-06-13', '23:50:07', '00:44:57'),
(1, '2017-06-14', '00:43:27', '00:44:57'),
(1, '2017-06-14', '00:45:01', '00:49:52'),
(1, '2017-06-14', '00:50:44', '00:58:34'),
(1, '2017-06-14', '00:55:34', '00:58:34'),
(1, '2017-06-14', '00:58:42', '01:02:58'),
(1, '2017-06-14', '01:03:12', '01:04:08'),
(1, '2017-06-14', '01:04:23', '01:04:26'),
(1, '2017-06-14', '01:10:39', '01:17:11'),
(1, '2017-06-14', '01:17:54', '02:11:15'),
(1, '2017-06-14', '02:11:23', '02:12:06'),
(1, '2017-06-14', '02:12:14', '00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugares`
--

CREATE TABLE `lugares` (
  `numero` int(11) NOT NULL,
  `piso` int(11) NOT NULL,
  `reservado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `lugares`
--

INSERT INTO `lugares` (`numero`, `piso`, `reservado`) VALUES
(1, 1, 1),
(2, 1, 0),
(3, 1, 0),
(4, 1, 0),
(5, 1, 0),
(6, 1, 0),
(7, 1, 0),
(8, 1, 0),
(9, 1, 0),
(10, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operaciones`
--

CREATE TABLE `operaciones` (
  `idempleado` int(11) NOT NULL,
  `lugar` int(11) NOT NULL,
  `patente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `entrada` datetime NOT NULL,
  `salida` datetime NOT NULL,
  `precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `operaciones`
--

INSERT INTO `operaciones` (`idempleado`, `lugar`, `patente`, `entrada`, `salida`, `precio`) VALUES
(1, 2, 'BUD554', '2017-06-10 21:05:28', '2017-06-11 19:35:49', 90),
(2, 3, 'PRUEBA', '2017-06-11 11:57:16', '2017-06-11 11:59:14', 0),
(1, 2, 'BUD554', '2017-06-11 13:21:27', '2017-06-11 19:35:49', 90),
(1, 4, 'ADD401', '2017-06-11 19:59:46', '2017-06-11 20:29:16', 0),
(1, 4, 'BUD554', '2017-06-11 20:30:04', '2017-06-11 20:34:41', 0),
(1, 2, 'BUD111', '2017-06-11 20:49:27', '2017-06-11 20:49:35', 10),
(1, 2, 'FPI', '2017-06-12 06:32:45', '2017-06-12 06:33:04', 10),
(1, 4, 'DDD333', '2017-06-12 06:33:33', '2017-06-12 06:33:38', 10),
(1, 5, 'DDD333', '2017-06-12 06:33:53', '2017-06-12 06:34:08', 10),
(1, 6, 'DDD333', '2017-06-12 06:35:57', '2017-06-12 06:36:01', 10),
(1, 7, 'DPI111', '2017-06-12 06:36:17', '2017-06-12 06:36:20', 10),
(1, 3, 'DPI111', '2017-06-12 06:54:44', '2017-06-12 06:54:51', 10),
(1, 2, 'DPI444', '2017-06-12 06:55:09', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `piso`
--

CREATE TABLE `piso` (
  `numero` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios`
--

CREATE TABLE `precios` (
  `hora` float NOT NULL,
  `media` float NOT NULL,
  `estadia` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `precios`
--

INSERT INTO `precios` (`hora`, `media`, `estadia`) VALUES
(10, 90, 170);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
