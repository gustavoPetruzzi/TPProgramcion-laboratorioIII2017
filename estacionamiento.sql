-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 29, 2017 at 10:42 
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `estacionamiento`
--

-- --------------------------------------------------------

--
-- Table structure for table `autos`
--

CREATE TABLE `autos` (
  `patente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `color` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `marca` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id`, `usuario`, `pass`, `activo`) VALUES
(1, 'admin', 'admin', 0),
(2, 'Jose', '5678', 0);

-- --------------------------------------------------------

--
-- Table structure for table `loginempleados`
--

CREATE TABLE `loginempleados` (
  `idempleado` int(11) NOT NULL,
  `dia` date NOT NULL,
  `sesion` int(11) NOT NULL,
  `entrada` time NOT NULL,
  `salida` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `loginempleados`
--

INSERT INTO `loginempleados` (`idempleado`, `dia`, `sesion`, `entrada`, `salida`) VALUES
(1, '2017-05-23', 503, '03:35:10', '03:35:26');

-- --------------------------------------------------------

--
-- Table structure for table `lugares`
--

CREATE TABLE `lugares` (
  `numero` int(11) NOT NULL,
  `piso` int(11) NOT NULL,
  `patente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `reservado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operaciones`
--

CREATE TABLE `operaciones` (
  `idempleado` int(11) NOT NULL,
  `lugar` int(11) NOT NULL,
  `patente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `entrada` date NOT NULL,
  `salida` date NOT NULL,
  `precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `piso`
--

CREATE TABLE `piso` (
  `numero` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `piso`
--

INSERT INTO `piso` (`numero`, `cantidad`) VALUES
(1, 10),
(2, 10),
(3, 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
