-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: control_parqueadero_opt
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clientes_mensuales`
--

DROP TABLE IF EXISTS `clientes_mensuales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_mensuales` (
  `idClientes_mensuales` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(60) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `estado` enum('Activo','Vencido') NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idClientes_mensuales`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `espacio`
--

DROP TABLE IF EXISTS `espacio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `espacio` (
  `idEspacio` varchar(10) NOT NULL,
  `numero_espacio` varchar(10) NOT NULL,
  `estado` enum('Libre','Ocupado','Reservado') NOT NULL DEFAULT 'Libre',
  PRIMARY KEY (`idEspacio`),
  UNIQUE KEY `numero_espacio_UNIQUE` (`numero_espacio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ingresos`
--

DROP TABLE IF EXISTS `ingresos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingresos` (
  `idIngresos` int NOT NULL AUTO_INCREMENT,
  `fecha_hora_ingreso` datetime NOT NULL,
  `fecha_hora_salida` datetime DEFAULT NULL,
  `total_pago` decimal(10,2) DEFAULT NULL,
  `tipo_cliente` enum('Ocasional','Mensual') NOT NULL DEFAULT 'Ocasional',
  `Vehiculo_idVehiculo` int NOT NULL,
  `Espacio_idEspacio` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idIngresos`),
  KEY `fk_Ingresos_Vehiculo_idx` (`Vehiculo_idVehiculo`),
  KEY `fk_Ingresos_Espacio_idx` (`Espacio_idEspacio`),
  CONSTRAINT `fk_Ingresos_Espacio` FOREIGN KEY (`Espacio_idEspacio`) REFERENCES `espacio` (`idEspacio`),
  CONSTRAINT `fk_Ingresos_Vehiculo` FOREIGN KEY (`Vehiculo_idVehiculo`) REFERENCES `vehiculo` (`idVehiculo`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tarifa`
--

DROP TABLE IF EXISTS `tarifa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tarifa` (
  `idTarifa` int NOT NULL AUTO_INCREMENT,
  `precio_hora` decimal(10,2) NOT NULL,
  `precio_dia` decimal(10,2) NOT NULL,
  `precio_noche` decimal(10,2) DEFAULT NULL,
  `precio_fin_semana` decimal(10,2) DEFAULT NULL,
  `precio_hora_pico` decimal(10,2) DEFAULT NULL,
  `precio_festivos` decimal(10,2) DEFAULT NULL,
  `precio_mensual` decimal(10,2) NOT NULL,
  `Tipo_vehiculo_idTipo_vehiculo` int NOT NULL,
  PRIMARY KEY (`idTarifa`),
  KEY `fk_Tarifa_Tipo_vehiculo_idx` (`Tipo_vehiculo_idTipo_vehiculo`),
  CONSTRAINT `fk_Tarifa_Tipo_vehiculo` FOREIGN KEY (`Tipo_vehiculo_idTipo_vehiculo`) REFERENCES `tipo_vehiculo` (`idTipo_vehiculo`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipo_vehiculo`
--

DROP TABLE IF EXISTS `tipo_vehiculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_vehiculo` (
  `idTipo_vehiculo` int NOT NULL AUTO_INCREMENT,
  `nombre_tipo` varchar(45) NOT NULL,
  PRIMARY KEY (`idTipo_vehiculo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehiculo`
--

DROP TABLE IF EXISTS `vehiculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehiculo` (
  `idVehiculo` int NOT NULL AUTO_INCREMENT,
  `placa` varchar(15) NOT NULL,
  `marca` varchar(45) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `Tipo_vehiculo_idTipo_vehiculo` int NOT NULL,
  `id_clientes` int DEFAULT NULL,
  `idclientes_mensuales` int DEFAULT NULL,
  PRIMARY KEY (`idVehiculo`),
  UNIQUE KEY `placa_UNIQUE` (`placa`),
  KEY `fk_Vehiculo_Tipo_vehiculo_idx` (`Tipo_vehiculo_idTipo_vehiculo`),
  KEY `fk_vehiculo_cliente` (`idclientes_mensuales`),
  CONSTRAINT `fk_vehiculo_cliente` FOREIGN KEY (`idclientes_mensuales`) REFERENCES `clientes_mensuales` (`idClientes_mensuales`),
  CONSTRAINT `fk_Vehiculo_Tipo_vehiculo` FOREIGN KEY (`Tipo_vehiculo_idTipo_vehiculo`) REFERENCES `tipo_vehiculo` (`idTipo_vehiculo`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-05 19:46:09
