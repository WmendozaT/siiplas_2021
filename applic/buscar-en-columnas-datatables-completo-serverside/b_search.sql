-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-08-2021 a las 18:49:26
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `b_search`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_contacto`
--

CREATE TABLE `tbl_contacto` (
  `id` int(11) NOT NULL,
  `nombres` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_de_naci` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_contacto`
--

INSERT INTO `tbl_contacto` (`id`, `nombres`, `apellidos`, `direccion`, `email`, `telefono`, `fecha_de_naci`) VALUES
(1, 'Peter', 'Parker', '970 Princee St.\r\n\r\nPiqua, OH 45356', 'peter@parker.com', '4455664455', '1990-04-10'),
(2, 'Barry', 'Allen', '976 Livingston Lane, FL 33702', 'barry@allen.com', '2211335566', '1983-02-02'),
(3, 'Bruce', 'Banner', '69 Bridge Lane \r\nBrooklyn, NY 11201', 'bruce@banner.com', '7788995566', '1987-04-14'),
(4, 'Bruce', 'Wayne', '896 East Smith Store Dr, TX 77566', 'bruce@wayne.com', '8877887744', '1991-11-15'),
(5, 'Harvy', 'Dent', '35 Wakehurst Avenue \r\nNoblesville, IN 46060', 'harvy@dent.com', '9988774445', '1990-10-01'),
(6, 'Tony', 'Stark', '31 Edgewater Court \r\nMalden, MA 02148', 'tony@stark.com', '8899886655', '1984-10-05'),
(7, 'Nick', 'Fury', '70 WakePrin St.\r\n\r\nPiqua, OL 356444', 'nick@fury.com', '9966554488', '1980-01-25'),
(8, 'John', 'Mclane', '76 Kevins Lane \r\n\r\nSt. Petersburg, FN 33711', 'john@maclay.com', '7744114411', '2000-11-15'),
(9, 'Howard', 'Roark', '88 Golden Lane \r\n\r\nBrooklyn, LS 11204', 'howard@roark.com', '8745554413', '2011-11-15'),
(10, 'Peter', 'Keating', '86 Smith Road\r\n\r\nLake Jackson, TQ 77566', 'peter@keating.com', '9089094445', '2013-11-15');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_contacto`
--
ALTER TABLE `tbl_contacto`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_contacto`
--
ALTER TABLE `tbl_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
