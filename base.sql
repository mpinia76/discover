-- --------------------------------------------------------
-- Host:                         163.10.35.34
-- Versión del servidor:         5.6.14 - MySQL Community Server (GPL)
-- SO del servidor:              Win32
-- HeidiSQL Versión:             9.2.0.4947
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando estructura para tabla discover.ano
CREATE TABLE IF NOT EXISTS `ano` (
  `id` int(11) NOT NULL,
  `ano` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.ano: 9 rows
/*!40000 ALTER TABLE `ano` DISABLE KEYS */;
INSERT INTO `ano` (`id`, `ano`) VALUES
	(2010, 2010),
	(2011, 2011),
	(2012, 2012),
	(2013, 2013),
	(2014, 2014),
	(2015, 2015),
	(2016, 2016),
	(2017, 2017),
	(2018, 2018);
/*!40000 ALTER TABLE `ano` ENABLE KEYS */;


-- Volcando estructura para tabla discover.banco
CREATE TABLE IF NOT EXISTS `banco` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banco` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `banco` (`banco`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.banco: 0 rows
/*!40000 ALTER TABLE `banco` DISABLE KEYS */;
/*!40000 ALTER TABLE `banco` ENABLE KEYS */;


-- Volcando estructura para tabla discover.caja
CREATE TABLE IF NOT EXISTS `caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caja` varchar(50) NOT NULL,
  `visible_en_informe` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.caja: 0 rows
/*!40000 ALTER TABLE `caja` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja` ENABLE KEYS */;


-- Volcando estructura para tabla discover.caja_movimiento
CREATE TABLE IF NOT EXISTS `caja_movimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caja_id` int(11) NOT NULL,
  `origen` varchar(100) NOT NULL,
  `registro_id` int(11) NOT NULL,
  `monto` float NOT NULL,
  `fecha` date NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `caja_id` (`caja_id`),
  KEY `registro_id` (`registro_id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `origen` (`origen`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.caja_movimiento: 0 rows
/*!40000 ALTER TABLE `caja_movimiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja_movimiento` ENABLE KEYS */;


-- Volcando estructura para tabla discover.caja_sincronizada
CREATE TABLE IF NOT EXISTS `caja_sincronizada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caja_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `monto` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `caja_id` (`caja_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.caja_sincronizada: 0 rows
/*!40000 ALTER TABLE `caja_sincronizada` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja_sincronizada` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cheque_consumo
CREATE TABLE IF NOT EXISTS `cheque_consumo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` bigint(20) NOT NULL,
  `titular` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `fecha_debitado` date NOT NULL,
  `monto` float NOT NULL,
  `interes` float NOT NULL,
  `descuento` float NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  `debitado` int(1) NOT NULL DEFAULT '0',
  `concepto` varchar(150) NOT NULL,
  `creado_por` int(11) NOT NULL,
  `debitado_por` int(11) NOT NULL,
  `vencido` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cheque_consumo: 0 rows
/*!40000 ALTER TABLE `cheque_consumo` DISABLE KEYS */;
/*!40000 ALTER TABLE `cheque_consumo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_apellido` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `dni` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `celular` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `localidad` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `nacimiento` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profesion` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nro_licencia_de_conducir` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vencimiento` date DEFAULT NULL,
  `ad_dni` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ad_telefono` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ad_email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ad_nombre_apellido` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razones_eligio` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iva` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuit` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.clientes: 4 rows
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` (`id`, `nombre_apellido`, `dni`, `telefono`, `celular`, `direccion`, `localidad`, `email`, `nacimiento`, `profesion`, `nro_licencia_de_conducir`, `vencimiento`, `ad_dni`, `ad_telefono`, `ad_email`, `ad_nombre_apellido`, `razones_eligio`, `iva`, `cuit`) VALUES
	(1, 'SSS', '22312', '2322', '222', '', '', 'mmmm@mm.com', '', '', '', NULL, '', '', '', '', NULL, '', ''),
	(2, 'adasdsa', '333', '3443234', '4423324', '', '', 'marcosp@ffff.com', '', '', '', NULL, '', '', '', '', NULL, '', ''),
	(3, 'adasdsa', '333', '3443234', '4423324', '', '', 'marcosp@ffff.com', '', '', '', NULL, '', '', '', '', NULL, '', ''),
	(4, 'adasdsa', '333', '3443234', '4423324', '', '', 'marcosp@ffff.com', '', '', '', NULL, '', '', '', '', NULL, '', '');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cobro_cheques
CREATE TABLE IF NOT EXISTS `cobro_cheques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(100) NOT NULL,
  `banco` varchar(100) NOT NULL,
  `librado_por` varchar(100) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `fecha_cobro` date NOT NULL,
  `cuit` varchar(100) NOT NULL,
  `a_la_orden_de` varchar(100) NOT NULL,
  `reserva_cobro_id` int(11) NOT NULL,
  `interes` decimal(10,2) NOT NULL,
  `monto_neto` decimal(10,2) NOT NULL,
  `acreditado` int(11) NOT NULL,
  `fecha_acreditado` date NOT NULL,
  `acreditado_por` int(11) NOT NULL,
  `asociado_a_pagos` int(11) NOT NULL,
  `cuenta_acreditado` int(11) NOT NULL,
  `asociado_a_pagos_fecha` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cobro_cheques: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `cobro_cheques` DISABLE KEYS */;
/*!40000 ALTER TABLE `cobro_cheques` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cobro_efectivos
CREATE TABLE IF NOT EXISTS `cobro_efectivos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caja_id` int(11) NOT NULL,
  `monto_neto` decimal(10,2) DEFAULT NULL,
  `reserva_cobro_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `caja_id` (`caja_id`),
  KEY `reserva_cobro_id` (`reserva_cobro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cobro_efectivos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `cobro_efectivos` DISABLE KEYS */;
/*!40000 ALTER TABLE `cobro_efectivos` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cobro_tarjetas
CREATE TABLE IF NOT EXISTS `cobro_tarjetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cobro_tarjeta_tipo_id` int(11) NOT NULL,
  `tarjeta_numero` int(11) NOT NULL,
  `cuotas` int(11) NOT NULL,
  `monto_neto` decimal(10,2) NOT NULL,
  `interes` decimal(10,2) NOT NULL,
  `reserva_cobro_id` int(11) NOT NULL,
  `lote` varchar(10) NOT NULL,
  `autorizacion` varchar(25) NOT NULL,
  `cupon` varchar(25) NOT NULL,
  `titular` varchar(150) NOT NULL,
  `dni` varchar(10) NOT NULL,
  `domicilio` varchar(250) NOT NULL,
  `nacimiento` varchar(10) NOT NULL,
  `cobro_tarjeta_lote_id` int(11) NOT NULL,
  `descuento_lote` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cobro_tarjetas: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `cobro_tarjetas` DISABLE KEYS */;
/*!40000 ALTER TABLE `cobro_tarjetas` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cobro_tarjeta_lotes
CREATE TABLE IF NOT EXISTS `cobro_tarjeta_lotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cobro_tarjeta_tipo_id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `fecha_cierre` date NOT NULL,
  `fecha_acreditacion` date NOT NULL,
  `cerrado_por` int(11) NOT NULL,
  `acreditado_por` int(11) NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `descuentos` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cobro_tarjeta_lotes: 0 rows
/*!40000 ALTER TABLE `cobro_tarjeta_lotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `cobro_tarjeta_lotes` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cobro_tarjeta_posnets
CREATE TABLE IF NOT EXISTS `cobro_tarjeta_posnets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posnet` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cobro_tarjeta_posnets: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `cobro_tarjeta_posnets` DISABLE KEYS */;
/*!40000 ALTER TABLE `cobro_tarjeta_posnets` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cobro_tarjeta_tipos
CREATE TABLE IF NOT EXISTS `cobro_tarjeta_tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cobro_tarjeta_posnet_id` int(11) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cobro_tarjeta_tipos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `cobro_tarjeta_tipos` DISABLE KEYS */;
/*!40000 ALTER TABLE `cobro_tarjeta_tipos` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cobro_transferencias
CREATE TABLE IF NOT EXISTS `cobro_transferencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta_id` int(11) NOT NULL,
  `quien_transfiere` varchar(100) NOT NULL,
  `numero_operacion` varchar(100) NOT NULL,
  `reserva_cobro_id` int(11) NOT NULL,
  `interes` decimal(10,2) NOT NULL,
  `monto_neto` decimal(10,2) NOT NULL,
  `acreditado` int(11) NOT NULL,
  `fecha_acreditado` date NOT NULL,
  `acreditado_por` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cobro_transferencias: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `cobro_transferencias` DISABLE KEYS */;
/*!40000 ALTER TABLE `cobro_transferencias` ENABLE KEYS */;


-- Volcando estructura para tabla discover.compra
CREATE TABLE IF NOT EXISTS `compra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nro_orden` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `rubro_id` int(11) NOT NULL,
  `subrubro_id` int(11) NOT NULL,
  `proveedor` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `factura_nro` varchar(100) NOT NULL,
  `factura_tipo` varchar(1) NOT NULL,
  `factura_orden` varchar(1) NOT NULL,
  `monto` float NOT NULL,
  `user_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remito_nro` varchar(100) NOT NULL,
  `recibo_nro` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.compra: 0 rows
/*!40000 ALTER TABLE `compra` DISABLE KEYS */;
/*!40000 ALTER TABLE `compra` ENABLE KEYS */;


-- Volcando estructura para tabla discover.configuracion
CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` varchar(50) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `valor` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.configuracion: 0 rows
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;


-- Volcando estructura para tabla discover.control_reservas
CREATE TABLE IF NOT EXISTS `control_reservas` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL DEFAULT '0',
  `total_estadia` float NOT NULL,
  `total` float NOT NULL,
  `monto_cobrado` float NOT NULL,
  `monto_devoluciones` float NOT NULL,
  `monto_extras` float NOT NULL,
  `monto_descuentos` float NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.control_reservas: 0 rows
/*!40000 ALTER TABLE `control_reservas` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_reservas` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cuenta
CREATE TABLE IF NOT EXISTS `cuenta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banco_id` int(11) NOT NULL,
  `cuenta_tipo_id` int(11) NOT NULL,
  `numero` varchar(100) NOT NULL,
  `saldo` float NOT NULL,
  `sucursal` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `visible_en_informe` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `banco_id` (`banco_id`),
  KEY `cuenta_tipo_id` (`cuenta_tipo_id`),
  KEY `sucursal` (`sucursal`),
  KEY `nombre` (`nombre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cuenta: 0 rows
/*!40000 ALTER TABLE `cuenta` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuenta` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cuenta_a_pagar
CREATE TABLE IF NOT EXISTS `cuenta_a_pagar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operacion_tipo` varchar(10) NOT NULL,
  `operacion_id` int(11) NOT NULL,
  `monto` float NOT NULL,
  `fecha_pago` date NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `operacion_tipo` (`operacion_tipo`),
  KEY `operacion_id` (`operacion_id`),
  KEY `fecha_pago` (`fecha_pago`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cuenta_a_pagar: 0 rows
/*!40000 ALTER TABLE `cuenta_a_pagar` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuenta_a_pagar` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cuenta_movimiento
CREATE TABLE IF NOT EXISTS `cuenta_movimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta_id` int(11) NOT NULL,
  `origen` varchar(100) NOT NULL,
  `registro_id` int(11) NOT NULL,
  `monto` float NOT NULL,
  `fecha` date NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cuenta_movimiento: 0 rows
/*!40000 ALTER TABLE `cuenta_movimiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuenta_movimiento` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cuenta_sincronizada
CREATE TABLE IF NOT EXISTS `cuenta_sincronizada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `monto` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fecha` (`fecha`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.cuenta_sincronizada: 0 rows
/*!40000 ALTER TABLE `cuenta_sincronizada` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuenta_sincronizada` ENABLE KEYS */;


-- Volcando estructura para tabla discover.cuenta_tipo
CREATE TABLE IF NOT EXISTS `cuenta_tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta_tipo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `cuenta_tipo` (`cuenta_tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.cuenta_tipo: 3 rows
/*!40000 ALTER TABLE `cuenta_tipo` DISABLE KEYS */;
INSERT INTO `cuenta_tipo` (`id`, `cuenta_tipo`) VALUES
	(1, 'Cuenta corriente'),
	(2, 'Caja de ahorros'),
	(3, 'Caja de seguridad');
/*!40000 ALTER TABLE `cuenta_tipo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.documento
CREATE TABLE IF NOT EXISTS `documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `path` (`path`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.documento: 0 rows
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;


-- Volcando estructura para tabla discover.efectivo_consumo
CREATE TABLE IF NOT EXISTS `efectivo_consumo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caja_id` int(11) NOT NULL,
  `monto` float NOT NULL,
  `interes` float NOT NULL,
  `descuento` float NOT NULL,
  `operacion_tipo` varchar(10) NOT NULL,
  `operacion_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `caja_id` (`caja_id`),
  KEY `operacion_id` (`operacion_id`),
  KEY `fecha` (`fecha`)
) ENGINE=MyISAM AUTO_INCREMENT=8892 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.efectivo_consumo: 0 rows
/*!40000 ALTER TABLE `efectivo_consumo` DISABLE KEYS */;
/*!40000 ALTER TABLE `efectivo_consumo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.empleado
CREATE TABLE IF NOT EXISTS `empleado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  `apellido` varchar(250) NOT NULL,
  `telefono_fijo` varchar(250) NOT NULL,
  `telefono_cel` varchar(250) NOT NULL,
  `domicilio_dni` varchar(250) NOT NULL,
  `domicilio_reside` varchar(250) NOT NULL,
  `localidad` varchar(250) NOT NULL,
  `provincia` varchar(250) NOT NULL,
  `nacimiento` date NOT NULL,
  `estudios` varchar(250) NOT NULL,
  `cant_hijos` int(2) NOT NULL,
  `estado_civil` varchar(25) NOT NULL,
  `dni` int(20) NOT NULL,
  `cuil` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `foto` varchar(50) NOT NULL,
  `fecha_alta` date NOT NULL,
  `inicio_actividades` date NOT NULL,
  `nro_legajo` varchar(100) NOT NULL,
  `creado_por` int(11) NOT NULL,
  `fecha_baja` date DEFAULT NULL,
  `baja_por` int(11) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `estado_civil` (`estado_civil`),
  FULLTEXT KEY `estado_civil_2` (`estado_civil`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.empleado: 1 rows
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
INSERT INTO `empleado` (`id`, `nombre`, `apellido`, `telefono_fijo`, `telefono_cel`, `domicilio_dni`, `domicilio_reside`, `localidad`, `provincia`, `nacimiento`, `estudios`, `cant_hijos`, `estado_civil`, `dni`, `cuil`, `email`, `foto`, `fecha_alta`, `inicio_actividades`, `nro_legajo`, `creado_por`, `fecha_baja`, `baja_por`, `estado`) VALUES
	(1, 'Marcos', 'Piñero', '4236350', '570-1741', 'Balcarce 621', '115 1588', 'La Plata', 'Buenos Aires', '1976-01-13', 'Universitarios', 2, 'Casado', 25174805, '20-25174805-6', 'marcos.pinero1976@gmail.com', '', '2016-09-23', '2016-09-23', '1', 29, NULL, NULL, 1);
/*!40000 ALTER TABLE `empleado` ENABLE KEYS */;


-- Volcando estructura para tabla discover.empleado_adelanto
CREATE TABLE IF NOT EXISTS `empleado_adelanto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleado_id` int(11) NOT NULL,
  `monto` float NOT NULL,
  `comentarios` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `creado` date NOT NULL,
  `creado_por` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `recibo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `empleado_id` (`empleado_id`),
  KEY `creado_por` (`creado_por`),
  KEY `mes` (`mes`),
  KEY `ano` (`ano`),
  KEY `creado` (`creado`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.empleado_adelanto: 0 rows
/*!40000 ALTER TABLE `empleado_adelanto` DISABLE KEYS */;
/*!40000 ALTER TABLE `empleado_adelanto` ENABLE KEYS */;


-- Volcando estructura para tabla discover.empleado_historico
CREATE TABLE IF NOT EXISTS `empleado_historico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleado_id` int(11) NOT NULL,
  `alta` date NOT NULL,
  `baja` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `empleado_id` (`empleado_id`),
  KEY `alta` (`alta`),
  KEY `baja` (`baja`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.empleado_historico: 3 rows
/*!40000 ALTER TABLE `empleado_historico` DISABLE KEYS */;
INSERT INTO `empleado_historico` (`id`, `empleado_id`, `alta`, `baja`) VALUES
	(1, 0, '2016-09-23', NULL),
	(2, 0, '2016-09-23', NULL),
	(3, 1, '2016-09-23', NULL);
/*!40000 ALTER TABLE `empleado_historico` ENABLE KEYS */;


-- Volcando estructura para tabla discover.empleado_hora_extra
CREATE TABLE IF NOT EXISTS `empleado_hora_extra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleado_id` int(11) NOT NULL,
  `hora_extra_id` int(11) NOT NULL,
  `cantidad_solicitada` float NOT NULL,
  `cantidad_aprobada` float NOT NULL,
  `mes` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `creado_por` int(11) NOT NULL,
  `creado` date NOT NULL,
  `estado` int(1) NOT NULL,
  `aprobado_por` int(11) NOT NULL,
  `aprobado` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `empleado_id` (`empleado_id`),
  KEY `hora_extra_id` (`hora_extra_id`),
  KEY `mes` (`mes`),
  KEY `ano` (`ano`),
  KEY `creado_por` (`creado_por`),
  KEY `creado` (`creado`),
  KEY `estado` (`estado`),
  KEY `aprobado_por` (`aprobado_por`),
  KEY `aprobado` (`aprobado`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.empleado_hora_extra: 0 rows
/*!40000 ALTER TABLE `empleado_hora_extra` DISABLE KEYS */;
/*!40000 ALTER TABLE `empleado_hora_extra` ENABLE KEYS */;


-- Volcando estructura para tabla discover.empleado_pago
CREATE TABLE IF NOT EXISTS `empleado_pago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleado_id` int(11) NOT NULL,
  `monto` float NOT NULL,
  `mes` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `recibo` int(11) DEFAULT NULL,
  `abonado_por` int(11) NOT NULL,
  `abonado` date NOT NULL,
  `descuentos` decimal(10,2) DEFAULT NULL,
  `motivo_descuentos` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `empleado_id` (`empleado_id`),
  KEY `mes` (`mes`),
  KEY `ano` (`ano`),
  KEY `abonado_por` (`abonado_por`),
  KEY `abonado` (`abonado`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.empleado_pago: 0 rows
/*!40000 ALTER TABLE `empleado_pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `empleado_pago` ENABLE KEYS */;


-- Volcando estructura para tabla discover.empleado_sueldo
CREATE TABLE IF NOT EXISTS `empleado_sueldo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creado` date NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `sueldo` decimal(10,2) NOT NULL,
  `viaticos` decimal(10,2) NOT NULL,
  `asignaciones` decimal(10,2) NOT NULL,
  `presentismo` decimal(10,2) NOT NULL,
  `aguinaldo` decimal(10,2) NOT NULL,
  `creado_por` int(11) NOT NULL,
  `sueldo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `creado` (`creado`),
  KEY `empleado_id` (`empleado_id`),
  KEY `ano` (`ano`),
  KEY `mes` (`mes`),
  KEY `creado_por` (`creado_por`),
  KEY `sueldo_id` (`sueldo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.empleado_sueldo: 0 rows
/*!40000 ALTER TABLE `empleado_sueldo` DISABLE KEYS */;
/*!40000 ALTER TABLE `empleado_sueldo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.empleado_sueldo_0001
CREATE TABLE IF NOT EXISTS `empleado_sueldo_0001` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sueldo_id` int(11) NOT NULL,
  `categoria` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `calificacion` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `seccion` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sueldo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `empleado_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.empleado_sueldo_0001: 0 rows
/*!40000 ALTER TABLE `empleado_sueldo_0001` DISABLE KEYS */;
/*!40000 ALTER TABLE `empleado_sueldo_0001` ENABLE KEYS */;


-- Volcando estructura para tabla discover.empleado_trabajo
CREATE TABLE IF NOT EXISTS `empleado_trabajo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `horas_0001` float NOT NULL,
  `horas_0002` float NOT NULL,
  `duracion_jornada` float NOT NULL,
  `espacio_trabajo_id` int(11) NOT NULL,
  `sector_1_id` int(11) NOT NULL,
  `sector_2_id` int(11) DEFAULT NULL,
  `porcentaje_sector_1` float NOT NULL,
  `porcentaje_sector_2` float DEFAULT NULL,
  `creado_por` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fecha` (`fecha`),
  KEY `empleado_id` (`empleado_id`),
  KEY `espacio_trabajo_id` (`espacio_trabajo_id`),
  KEY `sector_1_id` (`sector_1_id`),
  KEY `sector_2_id` (`sector_2_id`),
  KEY `creado_por` (`creado_por`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.empleado_trabajo: 1 rows
/*!40000 ALTER TABLE `empleado_trabajo` DISABLE KEYS */;
INSERT INTO `empleado_trabajo` (`id`, `fecha`, `empleado_id`, `horas_0001`, `horas_0002`, `duracion_jornada`, `espacio_trabajo_id`, `sector_1_id`, `sector_2_id`, `porcentaje_sector_1`, `porcentaje_sector_2`, `creado_por`) VALUES
	(2, '2016-09-23', 1, 10, 0, 8, 1, 1, NULL, 100, 0, 29);
/*!40000 ALTER TABLE `empleado_trabajo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.espacio_trabajo
CREATE TABLE IF NOT EXISTS `espacio_trabajo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `espacio` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `espacio` (`espacio`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.espacio_trabajo: 1 rows
/*!40000 ALTER TABLE `espacio_trabajo` DISABLE KEYS */;
INSERT INTO `espacio_trabajo` (`id`, `espacio`) VALUES
	(1, 'Oficina');
/*!40000 ALTER TABLE `espacio_trabajo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.extras
CREATE TABLE IF NOT EXISTS `extras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extra_rubro_id` int(11) NOT NULL,
  `extra_subrubro_id` int(11) NOT NULL,
  `detalle` varchar(240) NOT NULL,
  `tarifa` float NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `extra_variable_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `extra_rubro_id` (`extra_rubro_id`),
  KEY `extra_subrubro_id` (`extra_subrubro_id`),
  KEY `detalle` (`detalle`),
  KEY `activo` (`activo`),
  KEY `extra_variable_id` (`extra_variable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.extras: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `extras` DISABLE KEYS */;
/*!40000 ALTER TABLE `extras` ENABLE KEYS */;


-- Volcando estructura para tabla discover.extra_rubros
CREATE TABLE IF NOT EXISTS `extra_rubros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubro` varchar(250) NOT NULL,
  `extra_variables` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rubro` (`rubro`),
  KEY `extra_variables` (`extra_variables`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.extra_rubros: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `extra_rubros` DISABLE KEYS */;
/*!40000 ALTER TABLE `extra_rubros` ENABLE KEYS */;


-- Volcando estructura para tabla discover.extra_subrubros
CREATE TABLE IF NOT EXISTS `extra_subrubros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extra_rubro_id` int(11) NOT NULL,
  `subrubro` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `extra_rubro_id` (`extra_rubro_id`),
  KEY `subrubro` (`subrubro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.extra_subrubros: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `extra_subrubros` DISABLE KEYS */;
/*!40000 ALTER TABLE `extra_subrubros` ENABLE KEYS */;


-- Volcando estructura para tabla discover.extra_variables
CREATE TABLE IF NOT EXISTS `extra_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extra_rubro_id` int(11) NOT NULL,
  `detalle` varchar(240) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `extra_rubro_id` (`extra_rubro_id`),
  KEY `detalle` (`detalle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.extra_variables: 0 rows
/*!40000 ALTER TABLE `extra_variables` DISABLE KEYS */;
/*!40000 ALTER TABLE `extra_variables` ENABLE KEYS */;


-- Volcando estructura para tabla discover.forma_pago
CREATE TABLE IF NOT EXISTS `forma_pago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forma_pago` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `forma_pago` (`forma_pago`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.forma_pago: 0 rows
/*!40000 ALTER TABLE `forma_pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `forma_pago` ENABLE KEYS */;


-- Volcando estructura para tabla discover.gasto
CREATE TABLE IF NOT EXISTS `gasto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nro_orden` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `rubro_id` int(11) NOT NULL,
  `subrubro_id` int(11) NOT NULL,
  `proveedor` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `factura_nro` varchar(100) NOT NULL,
  `factura_tipo` varchar(1) NOT NULL,
  `factura_orden` varchar(1) NOT NULL,
  `monto` float NOT NULL,
  `user_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remito_nro` varchar(100) NOT NULL,
  `recibo_nro` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nro_orden` (`nro_orden`),
  KEY `fecha` (`fecha`),
  KEY `fecha_vencimiento` (`fecha_vencimiento`),
  KEY `rubro_id` (`rubro_id`),
  KEY `subrubro_id` (`subrubro_id`),
  KEY `proveedor` (`proveedor`),
  KEY `factura_nro` (`factura_nro`),
  KEY `factura_tipo` (`factura_tipo`),
  KEY `factura_orden` (`factura_orden`),
  KEY `user_id` (`user_id`),
  KEY `estado` (`estado`),
  KEY `created` (`created`),
  KEY `remito_nro` (`remito_nro`),
  KEY `recibo_nro` (`recibo_nro`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.gasto: 0 rows
/*!40000 ALTER TABLE `gasto` DISABLE KEYS */;
/*!40000 ALTER TABLE `gasto` ENABLE KEYS */;


-- Volcando estructura para tabla discover.lugars
CREATE TABLE IF NOT EXISTS `lugars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lugar` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.lugars: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `lugars` DISABLE KEYS */;
INSERT INTO `lugars` (`id`, `lugar`) VALUES
	(1, 'La Plata'),
	(2, 'Rauch');
/*!40000 ALTER TABLE `lugars` ENABLE KEYS */;


-- Volcando estructura para tabla discover.mes
CREATE TABLE IF NOT EXISTS `mes` (
  `id` int(11) NOT NULL,
  `mes` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  KEY `mes` (`mes`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.mes: 12 rows
/*!40000 ALTER TABLE `mes` DISABLE KEYS */;
INSERT INTO `mes` (`id`, `mes`) VALUES
	(1, 'Enero'),
	(2, 'Febrero'),
	(3, 'Marzo'),
	(4, 'Abril'),
	(5, 'Mayo'),
	(6, 'Junio'),
	(7, 'Julio'),
	(8, 'Agosto'),
	(9, 'Septiembre'),
	(10, 'Octubre'),
	(11, 'Noviembre'),
	(12, 'Diciembre');
/*!40000 ALTER TABLE `mes` ENABLE KEYS */;


-- Volcando estructura para tabla discover.motivo
CREATE TABLE IF NOT EXISTS `motivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `motivo_grupo_id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `motivo_grupo_id` (`motivo_grupo_id`),
  KEY `nombre` (`nombre`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.motivo: 0 rows
/*!40000 ALTER TABLE `motivo` DISABLE KEYS */;
/*!40000 ALTER TABLE `motivo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.motivo_grupo
CREATE TABLE IF NOT EXISTS `motivo_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grupo` (`grupo`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.motivo_grupo: 3 rows
/*!40000 ALTER TABLE `motivo_grupo` DISABLE KEYS */;
INSERT INTO `motivo_grupo` (`id`, `grupo`) VALUES
	(1, 'Movimientos de caja'),
	(2, 'Movimientos de cuentas'),
	(3, 'Movimientos de tarjetas');
/*!40000 ALTER TABLE `motivo_grupo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.permiso
CREATE TABLE IF NOT EXISTS `permiso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permiso_grupo_id` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `permiso_grupo_id` (`permiso_grupo_id`),
  KEY `nombre` (`nombre`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.permiso: 86 rows
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
INSERT INTO `permiso` (`id`, `permiso_grupo_id`, `nombre`) VALUES
	(1, 1, 'Agregar'),
	(2, 1, 'Editar'),
	(3, 1, 'Eliminar'),
	(4, 2, 'Agregar'),
	(5, 2, 'Editar'),
	(6, 2, 'Eliminar'),
	(7, 3, 'Agregar'),
	(8, 3, 'Editar'),
	(9, 3, 'Eliminar'),
	(10, 4, 'Agregar'),
	(11, 4, 'Editar'),
	(51, 4, 'Agregar movimientos'),
	(13, 6, 'Agregar'),
	(14, 6, 'Editar'),
	(64, 6, 'Oficina'),
	(16, 5, 'Agregar'),
	(17, 5, 'Editar'),
	(18, 5, 'Eliminar'),
	(19, 7, 'Agregar'),
	(20, 7, 'Aprobar'),
	(21, 7, 'Consultar y abonar'),
	(23, 8, 'Consultar y abonar'),
	(24, 9, 'Consultar'),
	(25, 10, 'Consultar'),
	(26, 11, 'Montos de aprobacion'),
	(27, 12, 'Agregar'),
	(28, 12, 'Editar'),
	(29, 12, 'Eliminar'),
	(30, 13, 'Agregar'),
	(31, 13, 'Aprobar'),
	(32, 13, 'Consultar y abonar'),
	(39, 7, 'Editar monto'),
	(34, 7, 'Editar'),
	(35, 13, 'Editar'),
	(36, 14, 'Confirmar debito'),
	(37, 14, 'Editar'),
	(38, 15, 'Confirmar debito'),
	(40, 13, 'Editar monto'),
	(41, 4, 'Motivo de ajuste personalizado'),
	(42, 16, 'Motivo de ajuste personalizado'),
	(43, 16, 'Consultar'),
	(44, 16, 'Agregar'),
	(45, 16, 'Transferencias'),
	(46, 16, 'Depositar en cuenta'),
	(47, 16, 'Sincronizar'),
	(48, 14, 'Consultar'),
	(49, 14, 'Agregar manualmente'),
	(50, 15, 'Consultar'),
	(52, 4, 'Hacer transferencia'),
	(53, 4, 'Hacer extraccion'),
	(54, 4, 'Sincronizar'),
	(55, 16, 'Agregar movimientos'),
	(56, 18, 'Agregar'),
	(57, 18, 'Editar'),
	(58, 18, 'Eliminar'),
	(59, 15, 'Editar'),
	(60, 6, 'Consultar Ficha'),
	(61, 0, 'Agregar Hora Extra'),
	(62, 19, 'Consultar'),
	(63, 19, 'Pagar'),
	(65, 6, 'Hotel'),
	(66, 19, 'Oficina'),
	(67, 19, 'Hotel'),
	(68, 11, 'Eliminar ordenes'),
	(69, 6, 'Dar de baja'),
	(70, 6, 'Otorgar adelanto'),
	(71, 6, 'Asignar horas extras'),
	(90, 28, 'Operar'),
	(84, 22, 'Operar'),
	(89, 27, 'Operar'),
	(88, 26, 'Operar'),
	(87, 25, 'Operar'),
	(86, 24, 'Operar'),
	(85, 23, 'Operar'),
	(83, 21, 'Operar'),
	(91, 29, 'Operar'),
	(92, 30, 'Operar'),
	(93, 31, 'Operar'),
	(94, 32, 'Operar'),
	(95, 33, 'Operar'),
	(96, 34, 'Operar'),
	(82, 20, 'Operar'),
	(97, 11, 'Carga de documentacion en sistema'),
	(100, 7, 'Administrador de gastos'),
	(101, 20, 'Borrado de cobros'),
	(102, 19, 'Pagar meses atrasados');
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;


-- Volcando estructura para tabla discover.permiso_grupo
CREATE TABLE IF NOT EXISTS `permiso_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nombre` (`nombre`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.permiso_grupo: 33 rows
/*!40000 ALTER TABLE `permiso_grupo` DISABLE KEYS */;
INSERT INTO `permiso_grupo` (`id`, `nombre`) VALUES
	(1, 'Usuarios'),
	(2, 'Rubros'),
	(3, 'Subrubros'),
	(4, 'Cuentas'),
	(5, 'Tarjetas'),
	(6, 'Empleados'),
	(7, 'Gastos'),
	(8, 'Cuentas a pagar'),
	(9, 'Informe economico'),
	(10, 'Informe financiero'),
	(11, 'Configuracion'),
	(12, 'Proveedores'),
	(13, 'Compras'),
	(14, 'Cheques a debitar'),
	(15, 'Transferencias a debitar'),
	(16, 'Cajas'),
	(18, 'Sectores'),
	(19, 'Sueldos'),
	(20, 'Ventas'),
	(21, 'Ventas restringido'),
	(22, 'Cheques de terceros a acreditar'),
	(23, 'Transferencias a acreditar'),
	(24, 'Transacciones con tarjeta'),
	(25, 'Cierre y acreditacion de lote'),
	(26, 'Resumen de tarjetas corporativas'),
	(27, 'Conceptos de ajuste'),
	(28, 'Apartamentos y capacidad'),
	(29, 'Rubros de Extras'),
	(30, 'Subrubro de Extras'),
	(31, 'Menu de Extras y Valorizacion'),
	(32, 'Terminales de cobro con tarjeta'),
	(33, 'Marcas de tarjetas'),
	(34, 'Cuotas y coheficientes');
/*!40000 ALTER TABLE `permiso_grupo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.proveedor
CREATE TABLE IF NOT EXISTS `proveedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `rubro_id` int(11) NOT NULL,
  `cliente_nro` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `cuit` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `contacto` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nombre` (`nombre`),
  KEY `rubro_id` (`rubro_id`),
  KEY `cliente_nro` (`cliente_nro`),
  KEY `cuit` (`cuit`),
  KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.proveedor: 0 rows
/*!40000 ALTER TABLE `proveedor` DISABLE KEYS */;
/*!40000 ALTER TABLE `proveedor` ENABLE KEYS */;


-- Volcando estructura para tabla discover.recibos
CREATE TABLE IF NOT EXISTS `recibos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nro` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla discover.recibos: 0 rows
/*!40000 ALTER TABLE `recibos` DISABLE KEYS */;
/*!40000 ALTER TABLE `recibos` ENABLE KEYS */;


-- Volcando estructura para tabla discover.rel_pago_operacion
CREATE TABLE IF NOT EXISTS `rel_pago_operacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forma_pago` varchar(100) NOT NULL,
  `forma_pago_id` int(11) NOT NULL,
  `operacion_tipo` varchar(100) NOT NULL,
  `operacion_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `forma_pago` (`forma_pago`),
  KEY `operacion_tipo` (`operacion_tipo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.rel_pago_operacion: 0 rows
/*!40000 ALTER TABLE `rel_pago_operacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_pago_operacion` ENABLE KEYS */;


-- Volcando estructura para tabla discover.reservas
CREATE TABLE IF NOT EXISTS `reservas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL DEFAULT '0',
  `reservado_por` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `unidad_id` int(11) NOT NULL,
  `retiro` date NOT NULL,
  `lugar_retiro_id` int(11) NOT NULL,
  `hora_retiro` time NOT NULL,
  `devolucion` date DEFAULT NULL,
  `lugar_devolucion_id` int(11) DEFAULT NULL,
  `hora_devolucion` time DEFAULT NULL,
  `pax_adultos` int(1) NOT NULL,
  `pax_menores` int(1) NOT NULL,
  `pax_bebes` int(1) NOT NULL,
  `discover` tinyint(1) NOT NULL,
  `discover_plus` tinyint(1) NOT NULL,
  `discover_advance` tinyint(1) NOT NULL,
  `total_estadia` float NOT NULL,
  `creado` datetime NOT NULL,
  `actualizado` datetime NOT NULL,
  `total` float NOT NULL,
  `comentarios` text COLLATE utf8_unicode_ci NOT NULL,
  `cargado_por` int(11) NOT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `apartamento_id` (`unidad_id`),
  KEY `cargado_por` (`cargado_por`),
  KEY `estado` (`estado`),
  KEY `check_in` (`retiro`),
  KEY `check_out` (`lugar_retiro_id`),
  KEY `creado` (`creado`),
  KEY `actualizado` (`actualizado`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.reservas: 1 rows
/*!40000 ALTER TABLE `reservas` DISABLE KEYS */;
INSERT INTO `reservas` (`id`, `numero`, `reservado_por`, `cliente_id`, `unidad_id`, `retiro`, `lugar_retiro_id`, `hora_retiro`, `devolucion`, `lugar_devolucion_id`, `hora_devolucion`, `pax_adultos`, `pax_menores`, `pax_bebes`, `discover`, `discover_plus`, `discover_advance`, `total_estadia`, `creado`, `actualizado`, `total`, `comentarios`, `cargado_por`, `estado`) VALUES
	(1, 1, 1, 4, 3, '2016-09-24', 1, '02:15:00', NULL, NULL, NULL, 1, 0, 0, 0, 0, 0, 200, '2016-09-24 00:00:00', '2016-09-24 14:15:16', 200, '', 29, NULL);
/*!40000 ALTER TABLE `reservas` ENABLE KEYS */;


-- Volcando estructura para tabla discover.reserva_cobros
CREATE TABLE IF NOT EXISTS `reserva_cobros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reserva_id` int(11) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `monto_neto` decimal(10,2) DEFAULT NULL,
  `monto_cobrado` decimal(10,2) NOT NULL,
  `monto_pendiente` decimal(10,2) NOT NULL,
  `finalizado` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `reserva_id` (`reserva_id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `tipo` (`tipo`),
  KEY `finalizado` (`finalizado`),
  KEY `fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.reserva_cobros: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `reserva_cobros` DISABLE KEYS */;
/*!40000 ALTER TABLE `reserva_cobros` ENABLE KEYS */;


-- Volcando estructura para tabla discover.reserva_descuentos
CREATE TABLE IF NOT EXISTS `reserva_descuentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `motivo` varchar(240) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `reserva_cobro_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `motivo` (`motivo`),
  KEY `reserva_cobro_id` (`reserva_cobro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.reserva_descuentos: 0 rows
/*!40000 ALTER TABLE `reserva_descuentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `reserva_descuentos` ENABLE KEYS */;


-- Volcando estructura para tabla discover.reserva_devoluciones
CREATE TABLE IF NOT EXISTS `reserva_devoluciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reserva_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `forma_pago` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `motivo` varchar(240) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `reserva_id` (`reserva_id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `forma_pago` (`forma_pago`),
  KEY `motivo` (`motivo`),
  KEY `fecha` (`fecha`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.reserva_devoluciones: 0 rows
/*!40000 ALTER TABLE `reserva_devoluciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `reserva_devoluciones` ENABLE KEYS */;


-- Volcando estructura para tabla discover.reserva_extras
CREATE TABLE IF NOT EXISTS `reserva_extras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cantidad` int(11) NOT NULL DEFAULT '0',
  `reserva_id` int(11) NOT NULL,
  `extra_id` int(11) NOT NULL,
  `agregada` date NOT NULL,
  `adelantada` tinyint(4) NOT NULL DEFAULT '1',
  `precio` decimal(10,2) NOT NULL,
  `extra_variable_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cantidad` (`cantidad`),
  KEY `reserva_id` (`reserva_id`),
  KEY `extra_id` (`extra_id`),
  KEY `agregada` (`agregada`),
  KEY `extra_variable_id` (`extra_variable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.reserva_extras: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `reserva_extras` DISABLE KEYS */;
/*!40000 ALTER TABLE `reserva_extras` ENABLE KEYS */;


-- Volcando estructura para tabla discover.reserva_facturas
CREATE TABLE IF NOT EXISTS `reserva_facturas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL,
  `titular` varchar(150) NOT NULL,
  `fecha_emision` date NOT NULL,
  `numero` varchar(150) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `reserva_id` int(11) NOT NULL,
  `agregada_por` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo` (`tipo`),
  KEY `titular` (`titular`),
  KEY `fecha_emision` (`fecha_emision`),
  KEY `numero` (`numero`),
  KEY `reserva_id` (`reserva_id`),
  KEY `agregada_por` (`agregada_por`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.reserva_facturas: 0 rows
/*!40000 ALTER TABLE `reserva_facturas` DISABLE KEYS */;
/*!40000 ALTER TABLE `reserva_facturas` ENABLE KEYS */;


-- Volcando estructura para tabla discover.rubro
CREATE TABLE IF NOT EXISTS `rubro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubro` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rubro` (`rubro`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.rubro: 0 rows
/*!40000 ALTER TABLE `rubro` DISABLE KEYS */;
/*!40000 ALTER TABLE `rubro` ENABLE KEYS */;


-- Volcando estructura para tabla discover.sector
CREATE TABLE IF NOT EXISTS `sector` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sector` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `hora_extra_activa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sector` (`sector`),
  KEY `hora_extra_activa` (`hora_extra_activa`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.sector: 1 rows
/*!40000 ALTER TABLE `sector` DISABLE KEYS */;
INSERT INTO `sector` (`id`, `sector`, `hora_extra_activa`) VALUES
	(1, 'Reservas', 1);
/*!40000 ALTER TABLE `sector` ENABLE KEYS */;


-- Volcando estructura para tabla discover.sector_horas_extras
CREATE TABLE IF NOT EXISTS `sector_horas_extras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creado` date NOT NULL,
  `sector_id` int(11) NOT NULL,
  `valor` float NOT NULL,
  `creado_por` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `creado` (`creado`),
  KEY `sector_id` (`sector_id`),
  KEY `creado_por` (`creado_por`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.sector_horas_extras: 1 rows
/*!40000 ALTER TABLE `sector_horas_extras` DISABLE KEYS */;
INSERT INTO `sector_horas_extras` (`id`, `creado`, `sector_id`, `valor`, `creado_por`) VALUES
	(1, '2016-09-23', 1, 15, 29);
/*!40000 ALTER TABLE `sector_horas_extras` ENABLE KEYS */;


-- Volcando estructura para tabla discover.subrubro
CREATE TABLE IF NOT EXISTS `subrubro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubro_id` int(11) NOT NULL,
  `subrubro` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rubro_id` (`rubro_id`),
  KEY `subrubro` (`subrubro`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.subrubro: 0 rows
/*!40000 ALTER TABLE `subrubro` DISABLE KEYS */;
/*!40000 ALTER TABLE `subrubro` ENABLE KEYS */;


-- Volcando estructura para tabla discover.tarjeta
CREATE TABLE IF NOT EXISTS `tarjeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banco_id` int(11) NOT NULL,
  `tarjeta_marca_id` int(11) NOT NULL,
  `titular` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `banco_id` (`banco_id`),
  KEY `tarjeta_marca_id` (`tarjeta_marca_id`),
  KEY `titular` (`titular`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.tarjeta: 0 rows
/*!40000 ALTER TABLE `tarjeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `tarjeta` ENABLE KEYS */;


-- Volcando estructura para tabla discover.tarjeta_consumo
CREATE TABLE IF NOT EXISTS `tarjeta_consumo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tarjeta_id` int(11) NOT NULL,
  `operacion_tipo` varchar(10) NOT NULL,
  `operacion_id` int(11) NOT NULL,
  `monto` float NOT NULL,
  `interes` float NOT NULL,
  `descuento` float NOT NULL,
  `cuotas` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `comprobante_nro` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tarjeta_id` (`tarjeta_id`),
  KEY `operacion_tipo` (`operacion_tipo`),
  KEY `operacion_id` (`operacion_id`),
  KEY `fecha` (`fecha`),
  KEY `comprobante_nro` (`comprobante_nro`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.tarjeta_consumo: 0 rows
/*!40000 ALTER TABLE `tarjeta_consumo` DISABLE KEYS */;
/*!40000 ALTER TABLE `tarjeta_consumo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.tarjeta_consumo_cuota
CREATE TABLE IF NOT EXISTS `tarjeta_consumo_cuota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `tarjeta_consumo_id` int(11) NOT NULL,
  `nro_cuota` int(11) NOT NULL,
  `monto` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fecha` (`fecha`),
  KEY `tarjeta_consumo_id` (`tarjeta_consumo_id`),
  KEY `nro_cuota` (`nro_cuota`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.tarjeta_consumo_cuota: 0 rows
/*!40000 ALTER TABLE `tarjeta_consumo_cuota` DISABLE KEYS */;
/*!40000 ALTER TABLE `tarjeta_consumo_cuota` ENABLE KEYS */;


-- Volcando estructura para tabla discover.tarjeta_marca
CREATE TABLE IF NOT EXISTS `tarjeta_marca` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `marca` (`marca`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.tarjeta_marca: 0 rows
/*!40000 ALTER TABLE `tarjeta_marca` DISABLE KEYS */;
/*!40000 ALTER TABLE `tarjeta_marca` ENABLE KEYS */;


-- Volcando estructura para tabla discover.tarjeta_movimiento
CREATE TABLE IF NOT EXISTS `tarjeta_movimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `tarjeta_resumen_id` int(11) NOT NULL,
  `detalle` varchar(150) NOT NULL,
  `monto` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fecha` (`fecha`),
  KEY `tarjeta_resumen_id` (`tarjeta_resumen_id`),
  KEY `detalle` (`detalle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.tarjeta_movimiento: 0 rows
/*!40000 ALTER TABLE `tarjeta_movimiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `tarjeta_movimiento` ENABLE KEYS */;


-- Volcando estructura para tabla discover.tarjeta_resumen
CREATE TABLE IF NOT EXISTS `tarjeta_resumen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `inicio` date NOT NULL,
  `fin` date NOT NULL,
  `tarjeta_id` int(11) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '0',
  `vencimiento` date NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto` float NOT NULL,
  `ano` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `tarjeta_id` (`tarjeta_id`),
  KEY `inicio` (`inicio`),
  KEY `vencimiento` (`vencimiento`),
  KEY `fecha_pago` (`fecha_pago`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.tarjeta_resumen: 0 rows
/*!40000 ALTER TABLE `tarjeta_resumen` DISABLE KEYS */;
/*!40000 ALTER TABLE `tarjeta_resumen` ENABLE KEYS */;


-- Volcando estructura para tabla discover.transferencia_consumo
CREATE TABLE IF NOT EXISTS `transferencia_consumo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta_id` int(11) NOT NULL,
  `cuenta_destino` text NOT NULL,
  `monto` float NOT NULL,
  `interes` float NOT NULL,
  `descuento` float NOT NULL,
  `operacion_tipo` varchar(10) NOT NULL,
  `operacion_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `debitado` int(1) NOT NULL,
  `fecha_debitada` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cuenta_id` (`cuenta_id`),
  KEY `operacion_tipo` (`operacion_tipo`),
  KEY `operacion_id` (`operacion_id`),
  KEY `fecha` (`fecha`),
  KEY `debitado` (`debitado`),
  KEY `fecha_debitada` (`fecha_debitada`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.transferencia_consumo: 0 rows
/*!40000 ALTER TABLE `transferencia_consumo` DISABLE KEYS */;
/*!40000 ALTER TABLE `transferencia_consumo` ENABLE KEYS */;


-- Volcando estructura para tabla discover.unidads
CREATE TABLE IF NOT EXISTS `unidads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` varchar(250) NOT NULL,
  `modelo` varchar(250) NOT NULL,
  `patente` varchar(10) DEFAULT NULL,
  `habilitacion` date DEFAULT NULL,
  `periodo` varchar(50) DEFAULT NULL,
  `baja` date DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.unidads: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `unidads` DISABLE KEYS */;
INSERT INTO `unidads` (`id`, `marca`, `modelo`, `patente`, `habilitacion`, `periodo`, `baja`, `estado`) VALUES
	(3, 'Chevrolet', 'Meriva', 'JTC102', '2016-09-23', '', '2016-09-30', 1),
	(4, 'Chevrolet', 'Corsa', 'JTC102', '2016-09-23', '-', '2016-10-13', 0);
/*!40000 ALTER TABLE `unidads` ENABLE KEYS */;


-- Volcando estructura para tabla discover.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(12) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `admin` int(1) NOT NULL,
  `espacio_trabajo_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_2` (`email`),
  KEY `admin` (`admin`),
  KEY `espacio_trabajo_id` (`espacio_trabajo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.usuario: 2 rows
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `email`, `password`, `telefono`, `admin`, `espacio_trabajo_id`) VALUES
	(1, 'Administrador', 'General', 'admin', 'PampaOnline', '', 1, 1),
	(29, 'Martin', 'Minervini', 'minervinim@villagedelaspampas.com.ar', 'jeepdefe', '0221-154540475', 1, 1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;


-- Volcando estructura para tabla discover.usuario_caja
CREATE TABLE IF NOT EXISTS `usuario_caja` (
  `usuario_id` int(11) NOT NULL,
  `caja_id` int(11) NOT NULL,
  KEY `usuario_id` (`usuario_id`),
  KEY `caja_id` (`caja_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.usuario_caja: 0 rows
/*!40000 ALTER TABLE `usuario_caja` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_caja` ENABLE KEYS */;


-- Volcando estructura para tabla discover.usuario_cuenta
CREATE TABLE IF NOT EXISTS `usuario_cuenta` (
  `usuario_id` int(11) NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  KEY `usuario_id` (`usuario_id`),
  KEY `cuenta_id` (`cuenta_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla discover.usuario_cuenta: 0 rows
/*!40000 ALTER TABLE `usuario_cuenta` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_cuenta` ENABLE KEYS */;


-- Volcando estructura para tabla discover.usuario_permiso
CREATE TABLE IF NOT EXISTS `usuario_permiso` (
  `usuario_id` int(11) NOT NULL,
  `permiso_id` int(11) NOT NULL,
  PRIMARY KEY (`usuario_id`,`permiso_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.usuario_permiso: 0 rows
/*!40000 ALTER TABLE `usuario_permiso` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_permiso` ENABLE KEYS */;


-- Volcando estructura para tabla discover.voucher
CREATE TABLE IF NOT EXISTS `voucher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restricciones` text NOT NULL,
  `politica_cancelacion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla discover.voucher: 1 rows
/*!40000 ALTER TABLE `voucher` DISABLE KEYS */;
INSERT INTO `voucher` (`id`, `restricciones`, `politica_cancelacion`) VALUES
	(1, '', '');
/*!40000 ALTER TABLE `voucher` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
