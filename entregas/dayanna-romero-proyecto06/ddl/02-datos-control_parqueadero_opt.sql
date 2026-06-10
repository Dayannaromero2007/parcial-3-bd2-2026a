-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-06-2026 a las 23:23:16
-- Versión del servidor: 8.0.43
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `control_parqueadero_opt`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_mensuales`
--

CREATE TABLE `clientes_mensuales` (
  `idClientes_mensuales` int NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(60) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `estado` enum('Activo','Vencido') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `clientes_mensuales`
--

INSERT INTO `clientes_mensuales` (`idClientes_mensuales`, `nombre`, `telefono`, `correo`, `fecha_inicio`, `fecha_final`, `estado`) VALUES
(3, 'yiret', '3125679004', 'yiret.arenas@gmail.com', '2026-06-03', '2026-07-31', 'Activo'),
(4, 'valentina', '3045800688', 'valentinaarango067@gmail.com', '2026-06-03', '2026-07-03', 'Activo'),
(5, 'matias', '3078964325', 'matiasdelgado@gmail.com', '2026-06-04', '2026-06-04', 'Activo'),
(6, 'lina', '3058796158', 'linacamacho02@gmail.com', '2026-06-04', '2026-07-04', 'Activo'),
(7, 'alexandra', ' 3222945023', 'aleherqui75@gmail.com', '2026-06-05', '2026-07-05', 'Activo'),
(8, 'Juan Pérez', '3115432109', 'juan.perez@gmail.com', '2026-06-01', '2026-07-01', 'Activo'),
(9, 'María Gómez', '3209876543', 'camila.gomez@gmail.com', '2026-06-02', '2026-07-02', 'Activo'),
(10, 'Andrés Mendoza', '3152345678', 'andres.mendoza@gmail.com', '2026-06-03', '2026-07-03', 'Activo'),
(11, 'Diana Silva', '3187654321', 'diana.silva@gmail.com', '2026-06-05', '2026-07-05', 'Activo'),
(12, 'Santiago Olaya', '3001234567', 'santiago.olaya@gmail.com', '2026-06-05', '2026-07-05', 'Activo'),
(13, 'javier hernadez', '307369753', 'javierhernadez78@gmail.com', '2026-05-09', '2026-06-09', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacio`
--

CREATE TABLE `espacio` (
  `idEspacio` varchar(10) NOT NULL,
  `numero_espacio` varchar(10) NOT NULL,
  `estado` enum('Libre','Ocupado','Reservado') NOT NULL DEFAULT 'Libre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `espacio`
--

INSERT INTO `espacio` (`idEspacio`, `numero_espacio`, `estado`) VALUES
('A1', 'A1', 'Ocupado'),
('A10', 'A10', 'Libre'),
('A2', 'A2', 'Libre'),
('A3', 'A3', 'Libre'),
('A4', 'A4', 'Libre'),
('A5', 'A5', 'Libre'),
('A6', 'A6', 'Libre'),
('A8', 'A8', 'Libre'),
('A9', 'A9', 'Libre'),
('B11', 'B11', 'Libre'),
('B12', 'B12', 'Libre'),
('B13', 'B13', 'Libre'),
('B14', 'B14', 'Libre'),
('B15', 'B15', 'Libre'),
('B16', 'B16', 'Libre'),
('B17', 'B17', 'Libre'),
('B18', 'B18', 'Libre'),
('B19', 'B19', 'Libre'),
('B20', 'B20', 'Libre'),
('C21', 'C21', 'Libre'),
('C22', 'C22', 'Libre'),
('C23', 'C23', 'Libre'),
('C24', 'C24', 'Libre'),
('C25', 'C25', 'Libre'),
('C26', 'C26', 'Libre'),
('C27', 'C27', 'Libre'),
('C28', 'C28', 'Libre'),
('C29', 'C29', 'Libre'),
('C30', 'C30', 'Libre'),
('D31', 'D31', 'Libre'),
('D32', 'D32', 'Libre'),
('D33', 'D33', 'Libre'),
('D34', 'D34', 'Libre'),
('D35', 'D35', 'Libre'),
('D36', 'D36', 'Libre'),
('D37', 'D37', 'Libre'),
('D38', 'D38', 'Libre'),
('D39', 'D39', 'Libre'),
('D40', 'D40', 'Libre'),
('E41', 'E41', 'Libre'),
('E42', 'E42', 'Libre'),
('E43', 'E43', 'Libre'),
('E44', 'E44', 'Libre'),
('E45', 'E45', 'Libre'),
('E46', 'E46', 'Libre'),
('E47', 'E47', 'Libre'),
('E48', 'E48', 'Libre'),
('E49', 'E49', 'Libre'),
('E50', 'E50', 'Libre'),
('F51', 'F51', 'Libre'),
('F52', 'F52', 'Libre'),
('F53', 'F53', 'Libre'),
('F54', 'F54', 'Libre'),
('F55', 'F55', 'Libre'),
('F56', 'F56', 'Libre'),
('F57', 'F57', 'Libre'),
('F58', 'F58', 'Libre'),
('F59', 'F59', 'Libre'),
('F60', 'F60', 'Libre'),
('G61', 'G61', 'Libre'),
('G62', 'G62', 'Libre'),
('G63', 'G63', 'Libre'),
('G64', 'G64', 'Libre'),
('G65', 'G65', 'Libre'),
('G66', 'G66', 'Libre'),
('G67', 'G67', 'Libre'),
('G68', 'G68', 'Libre'),
('G69', 'G69', 'Libre'),
('G70', 'G70', 'Libre'),
('H71', 'H71', 'Libre'),
('H72', 'H72', 'Libre'),
('H73', 'H73', 'Libre'),
('H74', 'H74', 'Libre'),
('H75', 'H75', 'Libre'),
('H76', 'H76', 'Libre'),
('H77', 'H77', 'Libre'),
('H78', 'H78', 'Libre'),
('H79', 'H79', 'Libre'),
('H80', 'H80', 'Libre'),
('I81', 'I81', 'Libre'),
('I82', 'I82', 'Libre'),
('I83', 'I83', 'Libre'),
('I84', 'I84', 'Libre'),
('I85', 'I85', 'Libre'),
('I86', 'I86', 'Libre'),
('I87', 'I87', 'Libre'),
('I88', 'I88', 'Libre'),
('I89', 'I89', 'Libre'),
('I90', 'I90', 'Libre'),
('J100', 'J100', 'Libre'),
('J91', 'J91', 'Libre'),
('J92', 'J92', 'Libre'),
('J93', 'J93', 'Libre'),
('J94', 'J94', 'Libre'),
('J95', 'J95', 'Libre'),
('J96', 'J96', 'Libre'),
('J97', 'J97', 'Libre'),
('J98', 'J98', 'Libre'),
('J99', 'J99', 'Libre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `idIngresos` int NOT NULL,
  `fecha_hora_ingreso` datetime NOT NULL,
  `fecha_hora_salida` datetime DEFAULT NULL,
  `total_pago` decimal(10,2) DEFAULT NULL,
  `tipo_cliente` enum('Ocasional','Mensual') NOT NULL DEFAULT 'Ocasional',
  `Vehiculo_idVehiculo` int NOT NULL,
  `Espacio_idEspacio` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`idIngresos`, `fecha_hora_ingreso`, `fecha_hora_salida`, `total_pago`, `tipo_cliente`, `Vehiculo_idVehiculo`, `Espacio_idEspacio`) VALUES
(1, '2026-06-02 20:37:08', '2026-06-03 01:07:33', 15000.00, 'Ocasional', 16, NULL),
(2, '2026-06-02 20:51:27', '2026-06-03 22:02:24', 30000.00, 'Ocasional', 21, NULL),
(3, '2026-06-02 20:54:22', '2026-06-03 22:03:01', 25000.00, 'Ocasional', 22, NULL),
(4, '2026-06-02 20:59:30', '2026-06-09 22:39:00', 15000.00, 'Mensual', 23, NULL),
(5, '2026-06-02 21:02:14', '2026-06-03 22:03:22', 50000.00, 'Ocasional', 24, 'G63'),
(6, '2026-06-02 21:39:49', '2026-06-04 17:16:35', 30000.00, 'Ocasional', 25, 'D34'),
(7, '2026-06-03 21:03:54', '2026-06-06 03:49:43', 50000.00, 'Ocasional', 29, 'D35'),
(8, '2026-06-03 21:13:25', '2026-06-06 03:49:50', 50000.00, 'Ocasional', 30, 'E45'),
(9, '2026-06-03 21:34:20', '2026-06-03 21:58:06', 1500.00, 'Ocasional', 31, 'A1'),
(10, '2026-06-04 01:42:19', NULL, NULL, 'Mensual', 32, 'H73'),
(11, '2026-06-04 01:42:56', NULL, NULL, 'Mensual', 33, 'B12'),
(12, '2026-06-05 03:38:11', NULL, NULL, 'Mensual', 35, 'A4'),
(13, '2026-06-06 01:22:53', NULL, NULL, 'Mensual', 36, 'A6'),
(88, '2026-06-06 19:19:30', '2026-06-06 19:47:26', 3000.00, 'Ocasional', 21, 'G61'),
(89, '2026-06-06 19:20:54', '2026-06-06 19:47:29', 2500.00, 'Ocasional', 22, 'B14'),
(90, '2026-06-06 19:22:34', '2026-06-09 14:22:17', 50000.00, 'Ocasional', 44, 'J93'),
(91, '2026-06-06 19:23:13', '2026-06-06 19:47:31', 8000.00, 'Ocasional', 24, 'D33'),
(92, '2026-06-06 19:24:26', '2026-06-06 19:48:55', 3000.00, 'Ocasional', 25, 'F55'),
(93, '2026-06-06 19:24:53', '2026-06-09 14:22:30', 50000.00, 'Ocasional', 29, 'C21'),
(94, '2026-06-06 19:25:44', '2026-06-06 19:48:58', 8000.00, 'Ocasional', 30, 'J96'),
(95, '2026-06-06 19:26:15', '2026-06-06 19:47:34', 1500.00, 'Ocasional', 31, 'B12'),
(98, '2026-06-06 19:35:02', '2026-06-06 12:45:56', 1500.00, 'Ocasional', 50, 'A5'),
(99, '2026-06-06 19:46:04', NULL, NULL, 'Mensual', 53, 'D35'),
(100, '2026-06-06 19:47:11', NULL, NULL, 'Mensual', 36, 'D36'),
(101, '2026-06-06 19:48:24', NULL, NULL, 'Mensual', 34, 'A2'),
(102, '2026-06-06 19:48:52', NULL, NULL, 'Mensual', 35, 'F51'),
(127, '2026-06-06 07:00:00', NULL, NULL, 'Mensual', 32, 'A1'),
(128, '2026-06-06 08:15:00', '2026-06-06 11:15:00', 6000.00, 'Ocasional', 21, 'B12'),
(129, '2026-06-06 09:30:00', '2026-06-06 12:30:00', 9000.00, 'Ocasional', 22, 'A4'),
(130, '2026-06-06 10:00:00', NULL, NULL, 'Mensual', 33, 'A6'),
(131, '2026-06-06 11:45:00', '2026-06-06 13:45:00', 4000.00, 'Ocasional', 23, 'B14'),
(132, '2026-06-06 14:00:00', '2026-06-06 16:00:00', 2000.00, 'Ocasional', 24, 'C21'),
(133, '2026-06-07 07:30:00', NULL, NULL, 'Mensual', 35, 'F55'),
(134, '2026-06-07 08:00:00', '2026-06-07 10:00:00', 4000.00, 'Ocasional', 25, 'G61'),
(135, '2026-06-07 09:15:00', '2026-06-07 14:15:00', 10000.00, 'Ocasional', 29, 'G63'),
(136, '2026-06-07 11:00:00', NULL, NULL, 'Mensual', 36, 'D34'),
(137, '2026-06-07 13:00:00', '2026-06-07 15:30:00', 5000.00, 'Ocasional', 30, 'D35'),
(138, '2026-06-07 15:00:00', '2026-06-07 17:00:00', 4000.00, 'Ocasional', 31, 'E45'),
(139, '2026-06-08 07:10:00', NULL, NULL, 'Mensual', 32, 'H73'),
(140, '2026-06-08 08:30:00', '2026-06-08 11:30:00', 6000.00, 'Ocasional', 21, 'J93'),
(141, '2026-06-08 09:45:00', '2026-06-09 12:44:48', 6000.00, 'Ocasional', 22, 'D33'),
(142, '2026-06-08 10:30:00', NULL, NULL, 'Mensual', 33, 'J96'),
(143, '2026-06-08 12:00:00', '2026-06-08 14:00:00', 4000.00, 'Ocasional', 23, 'A1'),
(144, '2026-06-08 14:15:00', '2026-06-08 18:15:00', 12000.00, 'Ocasional', 24, 'B12'),
(145, '2026-06-09 06:45:00', NULL, NULL, 'Mensual', 35, 'A4'),
(146, '2026-06-09 07:30:00', '2026-06-09 09:30:00', 4000.00, 'Ocasional', 25, 'A6'),
(147, '2026-06-09 08:15:00', '2026-06-09 11:15:00', 6000.00, 'Ocasional', 29, 'B14'),
(148, '2026-06-09 09:00:00', NULL, NULL, 'Mensual', 36, 'C21'),
(149, '2026-06-09 10:15:00', '2026-06-09 11:45:00', 4000.00, 'Ocasional', 30, 'F55'),
(150, '2026-06-09 11:30:00', '2026-06-09 12:30:00', 3000.00, 'Ocasional', 31, 'G61'),
(151, '2026-06-09 19:51:05', '2026-06-09 19:51:40', 2500.00, 'Ocasional', 57, 'E42'),
(152, '2026-06-09 19:51:58', '2026-06-09 19:52:32', 3000.00, 'Mensual', 58, 'I81'),
(153, '2026-06-09 19:53:58', NULL, NULL, 'Mensual', 58, 'G62');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifa`
--

CREATE TABLE `tarifa` (
  `idTarifa` int NOT NULL,
  `precio_hora` decimal(10,2) NOT NULL,
  `precio_dia` decimal(10,2) NOT NULL,
  `precio_noche` decimal(10,2) DEFAULT NULL,
  `precio_fin_semana` decimal(10,2) DEFAULT NULL,
  `precio_hora_pico` decimal(10,2) DEFAULT NULL,
  `precio_festivos` decimal(10,2) DEFAULT NULL,
  `precio_mensual` decimal(10,2) NOT NULL,
  `Tipo_vehiculo_idTipo_vehiculo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `tarifa`
--

INSERT INTO `tarifa` (`idTarifa`, `precio_hora`, `precio_dia`, `precio_noche`, `precio_fin_semana`, `precio_hora_pico`, `precio_festivos`, `precio_mensual`, `Tipo_vehiculo_idTipo_vehiculo`) VALUES
(1, 3000.00, 30000.00, 3500.00, 3500.00, 4000.00, 4000.00, 150000.00, 1),
(2, 2500.00, 25000.00, 3000.00, 3000.00, 3000.00, 3000.00, 120000.00, 2),
(3, 1000.00, 10000.00, 1200.00, 1200.00, 1200.00, 1200.00, 40000.00, 3),
(4, 1500.00, 15000.00, 2000.00, 2000.00, 2000.00, 2000.00, 80000.00, 4),
(5, 8000.00, 50000.00, 35000.00, 60000.00, 10000.00, 70000.00, 500000.00, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_vehiculo`
--

CREATE TABLE `tipo_vehiculo` (
  `idTipo_vehiculo` int NOT NULL,
  `nombre_tipo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `tipo_vehiculo`
--

INSERT INTO `tipo_vehiculo` (`idTipo_vehiculo`, `nombre_tipo`) VALUES
(1, 'Automóvil'),
(2, 'Automóvil eléctrico'),
(3, 'Bicicleta'),
(4, 'Moto'),
(5, 'Camión');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `idVehiculo` int NOT NULL,
  `placa` varchar(15) NOT NULL,
  `marca` varchar(45) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `Tipo_vehiculo_idTipo_vehiculo` int NOT NULL,
  `id_clientes` int DEFAULT NULL,
  `idclientes_mensuales` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `vehiculo`
--

INSERT INTO `vehiculo` (`idVehiculo`, `placa`, `marca`, `color`, `Tipo_vehiculo_idTipo_vehiculo`, `id_clientes`, `idclientes_mensuales`) VALUES
(12, 'acd147', 'toyota', 'roja', 1, NULL, NULL),
(16, 'cbd478', 'toyota', 'roja', 1, NULL, NULL),
(21, 'abc142', 'BYD', 'negro', 1, NULL, NULL),
(22, 'eac234', 'Teslas', 'blanco', 2, NULL, NULL),
(23, 'was785', 'Yamaha', 'roja', 4, NULL, NULL),
(24, 'fgt469', 'Yamaha', 'gris', 5, NULL, NULL),
(25, 'dec678', 'toyota', 'negro', 1, NULL, NULL),
(27, 'YSLM78', 'Toyota Tundra', 'negro', 1, NULL, 3),
(28, 'av29cg', 'BYD', 'negro', 4, NULL, 4),
(29, 'STK133', 'toyota', 'negro', 5, NULL, NULL),
(30, 'wqr679', 'toyota', 'negro', 5, NULL, NULL),
(31, 'QER302', 'Yamaha', 'blanco', 4, NULL, NULL),
(32, 'ASF235', 'Yamaha', 'gris', 2, NULL, NULL),
(33, 'QAS367', 'BYD', 'negro', 2, NULL, NULL),
(34, 'APW730', 'Honda', 'azul', 1, NULL, 5),
(35, 'WQP846', 'yamaha', 'gris', 4, NULL, 6),
(36, 'ASL75Q', 'Chevrolet', 'azul', 4, NULL, 7),
(37, 'JCP-123', 'Renault', 'Gris', 1, NULL, 8),
(38, 'MCG-456', 'Yamaha', 'Negro', 4, NULL, 9),
(39, 'AFM-789', 'Chevrolet', 'Rojo', 1, NULL, 10),
(40, 'DMS-321', 'BYD', 'Blanco', 2, NULL, 11),
(41, 'SOL-654', 'Honda', 'Azul', 4, NULL, 12),
(44, 'QPS245', 'Yamaha', 'roja', 5, NULL, NULL),
(50, 'BIC101', 'Trek', 'Verde neón', 3, NULL, NULL),
(53, 'YSLM78	', 'Toyota Tundra', 'negro', 1, NULL, NULL),
(57, 'CXW879', 'Yamaha', 'roja', 2, NULL, NULL),
(58, 'WZX972', 'BYD', 'gris', 1, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes_mensuales`
--
ALTER TABLE `clientes_mensuales`
  ADD PRIMARY KEY (`idClientes_mensuales`);

--
-- Indices de la tabla `espacio`
--
ALTER TABLE `espacio`
  ADD PRIMARY KEY (`idEspacio`),
  ADD UNIQUE KEY `numero_espacio_UNIQUE` (`numero_espacio`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`idIngresos`),
  ADD KEY `fk_Ingresos_Vehiculo_idx` (`Vehiculo_idVehiculo`),
  ADD KEY `fk_Ingresos_Espacio_idx` (`Espacio_idEspacio`);

--
-- Indices de la tabla `tarifa`
--
ALTER TABLE `tarifa`
  ADD PRIMARY KEY (`idTarifa`),
  ADD KEY `fk_Tarifa_Tipo_vehiculo_idx` (`Tipo_vehiculo_idTipo_vehiculo`);

--
-- Indices de la tabla `tipo_vehiculo`
--
ALTER TABLE `tipo_vehiculo`
  ADD PRIMARY KEY (`idTipo_vehiculo`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`idVehiculo`),
  ADD UNIQUE KEY `placa_UNIQUE` (`placa`),
  ADD KEY `fk_Vehiculo_Tipo_vehiculo_idx` (`Tipo_vehiculo_idTipo_vehiculo`),
  ADD KEY `fk_vehiculo_cliente` (`idclientes_mensuales`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes_mensuales`
--
ALTER TABLE `clientes_mensuales`
  MODIFY `idClientes_mensuales` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `idIngresos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT de la tabla `tarifa`
--
ALTER TABLE `tarifa`
  MODIFY `idTarifa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_vehiculo`
--
ALTER TABLE `tipo_vehiculo`
  MODIFY `idTipo_vehiculo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  MODIFY `idVehiculo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `fk_Ingresos_Espacio` FOREIGN KEY (`Espacio_idEspacio`) REFERENCES `espacio` (`idEspacio`),
  ADD CONSTRAINT `fk_Ingresos_Vehiculo` FOREIGN KEY (`Vehiculo_idVehiculo`) REFERENCES `vehiculo` (`idVehiculo`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `tarifa`
--
ALTER TABLE `tarifa`
  ADD CONSTRAINT `fk_Tarifa_Tipo_vehiculo` FOREIGN KEY (`Tipo_vehiculo_idTipo_vehiculo`) REFERENCES `tipo_vehiculo` (`idTipo_vehiculo`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD CONSTRAINT `fk_vehiculo_cliente` FOREIGN KEY (`idclientes_mensuales`) REFERENCES `clientes_mensuales` (`idClientes_mensuales`),
  ADD CONSTRAINT `fk_Vehiculo_Tipo_vehiculo` FOREIGN KEY (`Tipo_vehiculo_idTipo_vehiculo`) REFERENCES `tipo_vehiculo` (`idTipo_vehiculo`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
