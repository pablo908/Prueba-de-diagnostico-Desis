-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.6.2-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para formularioproducto
CREATE DATABASE IF NOT EXISTS `formularioproducto` /*!40100 DEFAULT CHARACTER SET utf8mb3 */;
USE `formularioproducto`;

-- Volcando estructura para tabla formularioproducto.bodega
CREATE TABLE IF NOT EXISTS `bodega` (
  `id_bodega` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_bodega`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla formularioproducto.bodega: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `bodega` DISABLE KEYS */;
INSERT INTO `bodega` (`id_bodega`, `nombre`) VALUES
	(1, 'Bodega Central'),
	(2, 'Bodega Norte'),
	(3, 'Bodega Sur'),
	(4, 'Bodega Oriente'),
	(5, 'Bodega Poniente');
/*!40000 ALTER TABLE `bodega` ENABLE KEYS */;

-- Volcando estructura para tabla formularioproducto.material
CREATE TABLE IF NOT EXISTS `material` (
  `id_material` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_material`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla formularioproducto.material: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
INSERT INTO `material` (`id_material`, `nombre`) VALUES
	(1, 'Plastico'),
	(2, 'Metal'),
	(3, 'Madera'),
	(4, 'Vidrio'),
	(5, 'Textil');
/*!40000 ALTER TABLE `material` ENABLE KEYS */;

-- Volcando estructura para tabla formularioproducto.monedas
CREATE TABLE IF NOT EXISTS `monedas` (
  `nombre` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla formularioproducto.monedas: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `monedas` DISABLE KEYS */;
INSERT INTO `monedas` (`nombre`) VALUES
	('USD'),
	('CLP'),
	('EUR');
/*!40000 ALTER TABLE `monedas` ENABLE KEYS */;

-- Volcando estructura para tabla formularioproducto.producto
CREATE TABLE IF NOT EXISTS `producto` (
  `codigo` varchar(15) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `id_bodega` int(11) DEFAULT NULL,
  `id_sucursal` int(11) DEFAULT NULL,
  `moneda` varchar(5) DEFAULT NULL,
  `precio` decimal(20,2) DEFAULT NULL,
  `descripcion` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_bodega` (`id_bodega`),
  KEY `id_sucursal` (`id_sucursal`),
  CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla formularioproducto.producto: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` (`codigo`, `nombre`, `id_bodega`, `id_sucursal`, `moneda`, `precio`, `descripcion`) VALUES
	('12365r', 'pablo pezoa', 1, 3, 'EUR', 10.00, 'ventana de aluminio'),
	('55555k', 'kkkkkkkkk', 2, 4, 'CLP', 1500.00, 'kkkkkkkkkkkkkkkkkkkkk'),
	('66666k', 'ppppppppppp', 5, 14, 'USD', 25.00, 'lllllllllllllllllllllllllllllllll'),
	('hhhhhhhhhhhh4', 'hhhhhhggg', 2, 4, 'USD', 50.00, 'ggggggggggggggggggggg'),
	('jjjjjj8', 'kkkkkkkkl', 1, 2, 'EUR', 6.50, 'pooooooooooo'),
	('lllllllll7', 'kkkkkkkkkkkkk', 1, 3, 'EUR', 3.50, 'kkkkkkkkkkkkkkkkk'),
	('mmmmmm32', 'mmmmmmmmm', 1, 3, 'CLP', 1500.00, 'nnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn');
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;

-- Volcando estructura para tabla formularioproducto.producto_material
CREATE TABLE IF NOT EXISTS `producto_material` (
  `codigo` varchar(50) NOT NULL,
  `id_material` int(11) NOT NULL,
  PRIMARY KEY (`codigo`,`id_material`),
  KEY `id_material` (`id_material`),
  CONSTRAINT `producto_material_ibfk_1` FOREIGN KEY (`codigo`) REFERENCES `producto` (`codigo`) ON DELETE CASCADE,
  CONSTRAINT `producto_material_ibfk_2` FOREIGN KEY (`id_material`) REFERENCES `material` (`id_material`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla formularioproducto.producto_material: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `producto_material` DISABLE KEYS */;
INSERT INTO `producto_material` (`codigo`, `id_material`) VALUES
	('12365r', 2),
	('12365r', 3),
	('12365r', 4),
	('55555k', 1),
	('55555k', 2),
	('66666k', 3),
	('66666k', 4),
	('66666k', 5),
	('hhhhhhhhhhhh4', 2),
	('hhhhhhhhhhhh4', 4),
	('jjjjjj8', 1),
	('jjjjjj8', 5),
	('lllllllll7', 2),
	('lllllllll7', 3),
	('mmmmmm32', 3),
	('mmmmmm32', 4),
	('mmmmmm32', 5);
/*!40000 ALTER TABLE `producto_material` ENABLE KEYS */;

-- Volcando estructura para tabla formularioproducto.sucursal
CREATE TABLE IF NOT EXISTS `sucursal` (
  `id_sucursal` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `id_bodega` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_sucursal`),
  KEY `id_bodega` (`id_bodega`),
  CONSTRAINT `sucursal_ibfk_1` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla formularioproducto.sucursal: ~15 rows (aproximadamente)
/*!40000 ALTER TABLE `sucursal` DISABLE KEYS */;
INSERT INTO `sucursal` (`id_sucursal`, `nombre`, `id_bodega`) VALUES
	(1, 'Sucursal Santiago Centro', 1),
	(2, 'Sucursal Providencia', 1),
	(3, 'Sucursal Ñuñoa', 1),
	(4, 'Sucursal Antofagasta', 2),
	(5, 'Sucursal Iquique', 2),
	(6, 'Sucursal Calama', 2),
	(7, 'Sucursal Concepción', 3),
	(8, 'Sucursal Temuco', 3),
	(9, 'Sucursal Valdivia', 3),
	(10, 'Sucursal Las Condes', 4),
	(11, 'Sucursal La Reina', 4),
	(12, 'Sucursal Peñalolén', 4),
	(13, 'Sucursal Maipú', 5),
	(14, 'Sucursal Pudahuel', 5),
	(15, 'Sucursal Quilicura', 5);
/*!40000 ALTER TABLE `sucursal` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
