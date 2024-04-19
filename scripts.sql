CREATE TABLE IF NOT EXISTS `control_reservas` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL DEFAULT '0',

  `total_estadia` float NOT NULL DEFAULT '0',

  `total` float NOT NULL DEFAULT '0',
  monto_cobrado float NOT NULL DEFAULT '0',
  monto_devoluciones float NOT NULL DEFAULT '0',
  monto_descuentos float NOT NULL DEFAULT '0',
  monto_extras float NOT NULL DEFAULT '0'

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO control_reservas ( id, numero,total_estadia,total)
SELECT id, numero,total_estadia,total
FROM reservas

update
    control_reservas,
    (select
        reserva_id, sum(reserva_cobros.monto_neto) as sumAttr
     from  reserva_cobros WHERE tipo != 'DESCUENTO'
     group by reserva_id) as a
set
    monto_cobrado = a.sumAttr
where
    control_reservas.id = a.reserva_id

update
    control_reservas,
    (select
        reserva_id, sum(reserva_cobros.monto_neto) as sumAttr
     from  reserva_cobros WHERE tipo = 'DESCUENTO'
     group by reserva_id) as a
set
    monto_descuentos = a.sumAttr
where
    control_reservas.id = a.reserva_id

update
    control_reservas,
    (select
        reserva_id, sum(reserva_extras.cantidad*reserva_extras.precio) as sumAttr
     from  reserva_extras
     group by reserva_id) as a
set
    monto_extras = a.sumAttr
where
    control_reservas.id = a.reserva_id

update
    control_reservas,
    (select
        reserva_id, sum(reserva_devoluciones.monto) as sumAttr
     from  reserva_devoluciones
     group by reserva_id) as a
set
    monto_devoluciones = a.sumAttr
where
    control_reservas.id = a.reserva_id

SELECT *, (total+monto_extras+monto_devoluciones-monto_descuentos) as monto_a_cobrar,monto_cobrado


FROM control_reservas
WHERE ROUND((total+monto_extras+monto_devoluciones-monto_descuentos),0)<ROUND(monto_cobrado,0)

################################### 08/02/2017 ###########################################
CREATE TABLE `canals` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`canal` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `id` (`id`),
	INDEX `canal` (`canal`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

CREATE TABLE `subcanals` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`subcanal` VARCHAR(250) NOT NULL,

	`canal_id` INT(11) NULL DEFAULT NULL,

	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

UPDATE `permiso_grupo` SET `nombre`='Canales' WHERE  `id`=38;
INSERT INTO `permiso_grupo` (`nombre`) VALUES ('Subcanales');

ALTER TABLE `reservas`
	ADD COLUMN `subcanal_id` INT(11) NULL DEFAULT NULL AFTER `estado`;

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (20, 'Grilla de reservas');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (10, 'Informe de ocupacion');

################################### 28/03/2017 ###########################################
INSERT INTO `permiso_grupo` (`nombre`) VALUES ('Reservas On Line');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (40, 'Operar');
ALTER TABLE `extra_subrubros`
	ADD COLUMN `descuento` TINYINT(1) NOT NULL DEFAULT '0' AFTER `subrubro`,
	ADD COLUMN `impacto` TINYINT(1) NOT NULL DEFAULT '0' AFTER `descuento`,
	ADD COLUMN `activo` TINYINT(1) NOT NULL DEFAULT '0' AFTER `impacto`;

CREATE TABLE `categoria_tarifa` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`categoria_id` INT(11) NOT NULL,
	`fecha` DATE NULL DEFAULT NULL,
	`importe` FLOAT NULL DEFAULT '0.00',
	PRIMARY KEY (`id`),
	INDEX `id` (`id`),
	INDEX `categoria_id` (`categoria_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;




CREATE TABLE `categoria_coheficiente` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`categoria_id` INT(11) NOT NULL,
	`dia` INT(11) NOT NULL,
	`coheficiente` FLOAT NULL DEFAULT '0.00',
	PRIMARY KEY (`id`),
	INDEX `id` (`id`),
	INDEX `categoria_id` (`categoria_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;




ALTER TABLE `categoria_coheficiente`
	ADD INDEX `dia` (`dia`),
	ADD UNIQUE INDEX `categoria_id_dia` (`categoria_id`, `dia`);


ALTER TABLE `categorias`
	ADD COLUMN `vehiculos` VARCHAR(255) NULL AFTER `categoria`,
	ADD COLUMN `activa` TINYINT(1) NOT NULL DEFAULT '0' AFTER `vehiculos`,
	ADD COLUMN `descuento` TINYINT(1) NOT NULL DEFAULT '0' AFTER `activa`;

CREATE TABLE `seguros` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`seguro` VARCHAR(255) NOT NULL,
	`importe` FLOAT NULL DEFAULT '0.00',
	PRIMARY KEY (`id`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;



CREATE TABLE `descuentos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`descuento` VARCHAR(255) NOT NULL,
	`coheficiente` FLOAT NULL DEFAULT '0.00',
	`activo` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

CREATE TABLE `hora_adicionals` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`hora` INT(11) NOT NULL,
	`coheficiente` FLOAT NULL DEFAULT '0.00',
	PRIMARY KEY (`id`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;


INSERT INTO `hora_adicionals` (`id`, `hora`, `coheficiente`) VALUES
	(4, 1, 0),
	(5, 2, 0.2),
	(6, 3, 0.2),
	(7, 4, 0.2),
	(8, 5, 1),
	(9, 6, 1),
	(10, 7, 1),
	(11, 8, 1),
	(12, 9, 1),
	(13, 10, 1),
	(14, 11, 1),
	(15, 12, 1),
	(16, 13, 1),
	(17, 14, 1),
	(18, 15, 1),
	(19, 16, 1),
	(20, 17, 1),
	(21, 18, 1),
	(22, 19, 1),
	(23, 20, 1),
	(24, 21, 1),
	(25, 22, 1),
	(26, 23, 1),
	(27, 24, 1);


CREATE TABLE `semana_dias` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`dia` VARCHAR(20) NOT NULL,

	PRIMARY KEY (`id`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

ALTER TABLE `semana_dias`
	ADD UNIQUE INDEX `dia` (`dia`);


INSERT INTO `semana_dias` (`id`, `dia`) VALUES
	(4, 'Jueves'),
	(1, 'Lunes'),
	(2, 'Martes'),
	(3, 'Miercoles'),
	(6, 'Sabado'),
	(5, 'Viernes');


CREATE TABLE `dia_horarios` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`semana_dia_id` INT(11) NOT NULL,
	`hora_inicio` TIME NOT NULL,
	`hora_fin` TIME NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `id` (`id`),
	INDEX `semana_dia_id` (`semana_dia_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;


INSERT INTO `dia_horarios` (`id`, `semana_dia_id`, `hora_inicio`, `hora_fin`) VALUES
	(5, 2, '09:00:00', '20:00:00'),
	(6, 1, '09:00:00', '20:00:00'),
	(7, 3, '09:00:00', '20:00:00'),
	(8, 4, '09:00:00', '20:00:00'),
	(9, 5, '09:00:00', '20:00:00'),
	(10, 6, '09:00:00', '13:00:00'),
	(11, 6, '16:00:00', '20:00:00');


CREATE TABLE `configuracion_reservas` (
	`id` VARCHAR(50) NOT NULL,
	`descripcion` VARCHAR(200) NOT NULL,
	`valor` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

INSERT INTO `configuracion_reservas` (`id`, `descripcion`, `valor`) VALUES
	('activar_sistema', 'Activar reservas On Line (Activado=1, Desactivado=0)', '0');
INSERT INTO `configuracion_reservas` (`id`, `descripcion`, `valor`) VALUES
	('hs_reservas', 'tiempo entre reservas', '3');

ALTER TABLE `unidads`
	ADD COLUMN `activa` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `reservas`
	CHANGE COLUMN `reservado_por` `reservado_por` INT(11) NULL AFTER `numero`;



ALTER TABLE `descuentos`
	ADD COLUMN `tarjeta` TINYINT(1) NOT NULL DEFAULT '0';

ALTER TABLE `descuentos`
	ADD COLUMN `parcial` FLOAT NULL DEFAULT '0.00';

ALTER TABLE `cobro_tarjeta_tipos`
	ADD COLUMN `nro_comercio` INT(11) NULL DEFAULT '0';

ALTER TABLE `clientes`
	ENGINE=InnoDB;

ALTER TABLE `reservas`
	ENGINE=InnoDB;

ALTER TABLE `reserva_descuentos`
	ENGINE=InnoDB;

ALTER TABLE `reserva_extras`
	CHANGE COLUMN `extra_id` `extra_id` INT(11) NULL AFTER `reserva_id`;

CREATE TABLE `marca_tarjetas` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`marca` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `id` (`id`),
	INDEX `marca` (`marca`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

ALTER TABLE `cobro_tarjeta_cuotas`
	ADD COLUMN `mascara_web` INT(11) NULL DEFAULT '0';

ALTER TABLE `cobro_tarjeta_tipos`
	ADD COLUMN `id_decidir` INT(11) NULL DEFAULT '0';

ALTER TABLE `cobro_tarjeta_tipos`
	ADD COLUMN `activa` TINYINT(1) NULL DEFAULT '0';

ALTER TABLE `cobro_tarjeta_cuotas`
	ADD COLUMN `activa` TINYINT(1) NULL DEFAULT '0';


##################################23/10/2017###########################################################

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (10, 'Informe de ocupacion/Días x Mes');


ALTER TABLE `descuentos`
	ADD COLUMN `activo_ingles` TINYINT(1) NULL DEFAULT '0' AFTER `parcial`,
	ADD COLUMN `activo_portugues` TINYINT(1) NULL DEFAULT '0' AFTER `activo_ingles`,
	ADD COLUMN `tarjeta_ingles` TINYINT(1) NULL DEFAULT '0' AFTER `activo_portugues`,
	ADD COLUMN `tarjeta_portugues` TINYINT(1) NULL DEFAULT '0' AFTER `tarjeta_ingles`;

ALTER TABLE `descuentos`
	ADD COLUMN `descuento_ingles` VARCHAR(255) NOT NULL AFTER `descuento`,
	ADD COLUMN `descuento_portugues` VARCHAR(255) NOT NULL AFTER `descuento_ingles`;

ALTER TABLE `extra_subrubros`
	ADD COLUMN `subrubro_ingles` VARCHAR(250) NULL AFTER `subrubro`,
	ADD COLUMN `subrubro_portugues` VARCHAR(250) NULL AFTER `subrubro_ingles`;

ALTER TABLE `categorias`
	ADD COLUMN `vehiculos_ingles` VARCHAR(255) NULL DEFAULT NULL AFTER `vehiculos`,
	ADD COLUMN `vehiculos_portugues` VARCHAR(255) NULL DEFAULT NULL AFTER `vehiculos_ingles`;

ALTER TABLE `seguros`
	ADD COLUMN `seguro_ingles` VARCHAR(255) NULL AFTER `seguro`,
	ADD COLUMN `seguro_portugues` VARCHAR(255) NULL AFTER `seguro_ingles`;

INSERT INTO `permiso_grupo` (`nombre`) VALUES ('Formulario DJ230 AREF');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('41', 'Descargar');

##################################30/10/2017###########################################################
ALTER TABLE `clientes`
	ADD COLUMN `sexo` TINYINT(1) NULL DEFAULT '0' AFTER `titular_conduce`;

ALTER TABLE `cobro_tarjetas`
	ADD COLUMN `lote_nuevo` VARCHAR(8) NULL AFTER `lote`;

ALTER TABLE `cobro_tarjetas`
	ADD COLUMN `fecha_pago` DATE NULL DEFAULT NULL AFTER `descuento_lote`;

##################################07/11/2017###########################################################
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (20, 'Check de disponibilidad');

##################################16/11/2017###########################################################
INSERT INTO `permiso_grupo` (`nombre`) VALUES ('Facturacion electronica');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('42', 'Descargar');

##################################28/11/2017###########################################################
CREATE TABLE `cobro_tarjeta_importacions` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`fecha` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
CREATE TABLE `cobro_tarjeta_importacion_items` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`cobro_tarjeta_importacion_id` INT(11) NOT NULL,
	`nro_liquidacion` VARCHAR(10) NULL DEFAULT NULL,
	`nro_comercio` VARCHAR(10) NULL DEFAULT '0',
	`lote` VARCHAR(8) NULL DEFAULT NULL,
	`fecha_compra` DATE NULL DEFAULT NULL,
	`fecha_pago` DATE NULL DEFAULT NULL,
	`importe` DECIMAL(10,2) NULL DEFAULT NULL,
	`exito` TINYINT(1) NULL DEFAULT NULL,
	`observaciones` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;

##################################30/11/2017###########################################################
CREATE TABLE `paises` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`iso` char(2) DEFAULT NULL,
`nombre` varchar(80) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `paises` VALUES(1, 'AF', 'Afganistán');
INSERT INTO `paises` VALUES(2, 'AX', 'Islas Gland');
INSERT INTO `paises` VALUES(3, 'AL', 'Albania');
INSERT INTO `paises` VALUES(4, 'DE', 'Alemania');
INSERT INTO `paises` VALUES(5, 'AD', 'Andorra');
INSERT INTO `paises` VALUES(6, 'AO', 'Angola');
INSERT INTO `paises` VALUES(7, 'AI', 'Anguilla');
INSERT INTO `paises` VALUES(8, 'AQ', 'Antártida');
INSERT INTO `paises` VALUES(9, 'AG', 'Antigua y Barbuda');
INSERT INTO `paises` VALUES(10, 'AN', 'Antillas Holandesas');
INSERT INTO `paises` VALUES(11, 'SA', 'Arabia Saudí');
INSERT INTO `paises` VALUES(12, 'DZ', 'Argelia');
INSERT INTO `paises` VALUES(13, 'AR', 'Argentina');
INSERT INTO `paises` VALUES(14, 'AM', 'Armenia');
INSERT INTO `paises` VALUES(15, 'AW', 'Aruba');
INSERT INTO `paises` VALUES(16, 'AU', 'Australia');
INSERT INTO `paises` VALUES(17, 'AT', 'Austria');
INSERT INTO `paises` VALUES(18, 'AZ', 'Azerbaiyán');
INSERT INTO `paises` VALUES(19, 'BS', 'Bahamas');
INSERT INTO `paises` VALUES(20, 'BH', 'Bahréin');
INSERT INTO `paises` VALUES(21, 'BD', 'Bangladesh');
INSERT INTO `paises` VALUES(22, 'BB', 'Barbados');
INSERT INTO `paises` VALUES(23, 'BY', 'Bielorrusia');
INSERT INTO `paises` VALUES(24, 'BE', 'Bélgica');
INSERT INTO `paises` VALUES(25, 'BZ', 'Belice');
INSERT INTO `paises` VALUES(26, 'BJ', 'Benin');
INSERT INTO `paises` VALUES(27, 'BM', 'Bermudas');
INSERT INTO `paises` VALUES(28, 'BT', 'Bhután');
INSERT INTO `paises` VALUES(29, 'BO', 'Bolivia');
INSERT INTO `paises` VALUES(30, 'BA', 'Bosnia y Herzegovina');
INSERT INTO `paises` VALUES(31, 'BW', 'Botsuana');
INSERT INTO `paises` VALUES(32, 'BV', 'Isla Bouvet');
INSERT INTO `paises` VALUES(33, 'BR', 'Brasil');
INSERT INTO `paises` VALUES(34, 'BN', 'Brunéi');
INSERT INTO `paises` VALUES(35, 'BG', 'Bulgaria');
INSERT INTO `paises` VALUES(36, 'BF', 'Burkina Faso');
INSERT INTO `paises` VALUES(37, 'BI', 'Burundi');
INSERT INTO `paises` VALUES(38, 'CV', 'Cabo Verde');
INSERT INTO `paises` VALUES(39, 'KY', 'Islas Caimán');
INSERT INTO `paises` VALUES(40, 'KH', 'Camboya');
INSERT INTO `paises` VALUES(41, 'CM', 'Camerún');
INSERT INTO `paises` VALUES(42, 'CA', 'Canadá');
INSERT INTO `paises` VALUES(43, 'CF', 'República Centroafricana');
INSERT INTO `paises` VALUES(44, 'TD', 'Chad');
INSERT INTO `paises` VALUES(45, 'CZ', 'República Checa');
INSERT INTO `paises` VALUES(46, 'CL', 'Chile');
INSERT INTO `paises` VALUES(47, 'CN', 'China');
INSERT INTO `paises` VALUES(48, 'CY', 'Chipre');
INSERT INTO `paises` VALUES(49, 'CX', 'Isla de Navidad');
INSERT INTO `paises` VALUES(50, 'VA', 'Ciudad del Vaticano');
INSERT INTO `paises` VALUES(51, 'CC', 'Islas Cocos');
INSERT INTO `paises` VALUES(52, 'CO', 'Colombia');
INSERT INTO `paises` VALUES(53, 'KM', 'Comoras');
INSERT INTO `paises` VALUES(54, 'CD', 'República Democrática del Congo');
INSERT INTO `paises` VALUES(55, 'CG', 'Congo');
INSERT INTO `paises` VALUES(56, 'CK', 'Islas Cook');
INSERT INTO `paises` VALUES(57, 'KP', 'Corea del Norte');
INSERT INTO `paises` VALUES(58, 'KR', 'Corea del Sur');
INSERT INTO `paises` VALUES(59, 'CI', 'Costa de Marfil');
INSERT INTO `paises` VALUES(60, 'CR', 'Costa Rica');
INSERT INTO `paises` VALUES(61, 'HR', 'Croacia');
INSERT INTO `paises` VALUES(62, 'CU', 'Cuba');
INSERT INTO `paises` VALUES(63, 'DK', 'Dinamarca');
INSERT INTO `paises` VALUES(64, 'DM', 'Dominica');
INSERT INTO `paises` VALUES(65, 'DO', 'República Dominicana');
INSERT INTO `paises` VALUES(66, 'EC', 'Ecuador');
INSERT INTO `paises` VALUES(67, 'EG', 'Egipto');
INSERT INTO `paises` VALUES(68, 'SV', 'El Salvador');
INSERT INTO `paises` VALUES(69, 'AE', 'Emiratos Árabes Unidos');
INSERT INTO `paises` VALUES(70, 'ER', 'Eritrea');
INSERT INTO `paises` VALUES(71, 'SK', 'Eslovaquia');
INSERT INTO `paises` VALUES(72, 'SI', 'Eslovenia');
INSERT INTO `paises` VALUES(73, 'ES', 'España');
INSERT INTO `paises` VALUES(74, 'UM', 'Islas ultramarinas de Estados Unidos');
INSERT INTO `paises` VALUES(75, 'US', 'Estados Unidos');
INSERT INTO `paises` VALUES(76, 'EE', 'Estonia');
INSERT INTO `paises` VALUES(77, 'ET', 'Etiopía');
INSERT INTO `paises` VALUES(78, 'FO', 'Islas Feroe');
INSERT INTO `paises` VALUES(79, 'PH', 'Filipinas');
INSERT INTO `paises` VALUES(80, 'FI', 'Finlandia');
INSERT INTO `paises` VALUES(81, 'FJ', 'Fiyi');
INSERT INTO `paises` VALUES(82, 'FR', 'Francia');
INSERT INTO `paises` VALUES(83, 'GA', 'Gabón');
INSERT INTO `paises` VALUES(84, 'GM', 'Gambia');
INSERT INTO `paises` VALUES(85, 'GE', 'Georgia');
INSERT INTO `paises` VALUES(86, 'GS', 'Islas Georgias del Sur y Sandwich del Sur');
INSERT INTO `paises` VALUES(87, 'GH', 'Ghana');
INSERT INTO `paises` VALUES(88, 'GI', 'Gibraltar');
INSERT INTO `paises` VALUES(89, 'GD', 'Granada');
INSERT INTO `paises` VALUES(90, 'GR', 'Grecia');
INSERT INTO `paises` VALUES(91, 'GL', 'Groenlandia');
INSERT INTO `paises` VALUES(92, 'GP', 'Guadalupe');
INSERT INTO `paises` VALUES(93, 'GU', 'Guam');
INSERT INTO `paises` VALUES(94, 'GT', 'Guatemala');
INSERT INTO `paises` VALUES(95, 'GF', 'Guayana Francesa');
INSERT INTO `paises` VALUES(96, 'GN', 'Guinea');
INSERT INTO `paises` VALUES(97, 'GQ', 'Guinea Ecuatorial');
INSERT INTO `paises` VALUES(98, 'GW', 'Guinea-Bissau');
INSERT INTO `paises` VALUES(99, 'GY', 'Guyana');
INSERT INTO `paises` VALUES(100, 'HT', 'Haití');
INSERT INTO `paises` VALUES(101, 'HM', 'Islas Heard y McDonald');
INSERT INTO `paises` VALUES(102, 'HN', 'Honduras');
INSERT INTO `paises` VALUES(103, 'HK', 'Hong Kong');
INSERT INTO `paises` VALUES(104, 'HU', 'Hungría');
INSERT INTO `paises` VALUES(105, 'IN', 'India');
INSERT INTO `paises` VALUES(106, 'ID', 'Indonesia');
INSERT INTO `paises` VALUES(107, 'IR', 'Irán');
INSERT INTO `paises` VALUES(108, 'IQ', 'Iraq');
INSERT INTO `paises` VALUES(109, 'IE', 'Irlanda');
INSERT INTO `paises` VALUES(110, 'IS', 'Islandia');
INSERT INTO `paises` VALUES(111, 'IL', 'Israel');
INSERT INTO `paises` VALUES(112, 'IT', 'Italia');
INSERT INTO `paises` VALUES(113, 'JM', 'Jamaica');
INSERT INTO `paises` VALUES(114, 'JP', 'Japón');
INSERT INTO `paises` VALUES(115, 'JO', 'Jordania');
INSERT INTO `paises` VALUES(116, 'KZ', 'Kazajstán');
INSERT INTO `paises` VALUES(117, 'KE', 'Kenia');
INSERT INTO `paises` VALUES(118, 'KG', 'Kirguistán');
INSERT INTO `paises` VALUES(119, 'KI', 'Kiribati');
INSERT INTO `paises` VALUES(120, 'KW', 'Kuwait');
INSERT INTO `paises` VALUES(121, 'LA', 'Laos');
INSERT INTO `paises` VALUES(122, 'LS', 'Lesotho');
INSERT INTO `paises` VALUES(123, 'LV', 'Letonia');
INSERT INTO `paises` VALUES(124, 'LB', 'Líbano');
INSERT INTO `paises` VALUES(125, 'LR', 'Liberia');
INSERT INTO `paises` VALUES(126, 'LY', 'Libia');
INSERT INTO `paises` VALUES(127, 'LI', 'Liechtenstein');
INSERT INTO `paises` VALUES(128, 'LT', 'Lituania');
INSERT INTO `paises` VALUES(129, 'LU', 'Luxemburgo');
INSERT INTO `paises` VALUES(130, 'MO', 'Macao');
INSERT INTO `paises` VALUES(131, 'MK', 'ARY Macedonia');
INSERT INTO `paises` VALUES(132, 'MG', 'Madagascar');
INSERT INTO `paises` VALUES(133, 'MY', 'Malasia');
INSERT INTO `paises` VALUES(134, 'MW', 'Malawi');
INSERT INTO `paises` VALUES(135, 'MV', 'Maldivas');
INSERT INTO `paises` VALUES(136, 'ML', 'Malí');
INSERT INTO `paises` VALUES(137, 'MT', 'Malta');
INSERT INTO `paises` VALUES(138, 'FK', 'Islas Malvinas');
INSERT INTO `paises` VALUES(139, 'MP', 'Islas Marianas del Norte');
INSERT INTO `paises` VALUES(140, 'MA', 'Marruecos');
INSERT INTO `paises` VALUES(141, 'MH', 'Islas Marshall');
INSERT INTO `paises` VALUES(142, 'MQ', 'Martinica');
INSERT INTO `paises` VALUES(143, 'MU', 'Mauricio');
INSERT INTO `paises` VALUES(144, 'MR', 'Mauritania');
INSERT INTO `paises` VALUES(145, 'YT', 'Mayotte');
INSERT INTO `paises` VALUES(146, 'MX', 'México');
INSERT INTO `paises` VALUES(147, 'FM', 'Micronesia');
INSERT INTO `paises` VALUES(148, 'MD', 'Moldavia');
INSERT INTO `paises` VALUES(149, 'MC', 'Mónaco');
INSERT INTO `paises` VALUES(150, 'MN', 'Mongolia');
INSERT INTO `paises` VALUES(151, 'MS', 'Montserrat');
INSERT INTO `paises` VALUES(152, 'MZ', 'Mozambique');
INSERT INTO `paises` VALUES(153, 'MM', 'Myanmar');
INSERT INTO `paises` VALUES(154, 'NA', 'Namibia');
INSERT INTO `paises` VALUES(155, 'NR', 'Nauru');
INSERT INTO `paises` VALUES(156, 'NP', 'Nepal');
INSERT INTO `paises` VALUES(157, 'NI', 'Nicaragua');
INSERT INTO `paises` VALUES(158, 'NE', 'Níger');
INSERT INTO `paises` VALUES(159, 'NG', 'Nigeria');
INSERT INTO `paises` VALUES(160, 'NU', 'Niue');
INSERT INTO `paises` VALUES(161, 'NF', 'Isla Norfolk');
INSERT INTO `paises` VALUES(162, 'NO', 'Noruega');
INSERT INTO `paises` VALUES(163, 'NC', 'Nueva Caledonia');
INSERT INTO `paises` VALUES(164, 'NZ', 'Nueva Zelanda');
INSERT INTO `paises` VALUES(165, 'OM', 'Omán');
INSERT INTO `paises` VALUES(166, 'NL', 'Países Bajos');
INSERT INTO `paises` VALUES(167, 'PK', 'Pakistán');
INSERT INTO `paises` VALUES(168, 'PW', 'Palau');
INSERT INTO `paises` VALUES(169, 'PS', 'Palestina');
INSERT INTO `paises` VALUES(170, 'PA', 'Panamá');
INSERT INTO `paises` VALUES(171, 'PG', 'Papúa Nueva Guinea');
INSERT INTO `paises` VALUES(172, 'PY', 'Paraguay');
INSERT INTO `paises` VALUES(173, 'PE', 'Perú');
INSERT INTO `paises` VALUES(174, 'PN', 'Islas Pitcairn');
INSERT INTO `paises` VALUES(175, 'PF', 'Polinesia Francesa');
INSERT INTO `paises` VALUES(176, 'PL', 'Polonia');
INSERT INTO `paises` VALUES(177, 'PT', 'Portugal');
INSERT INTO `paises` VALUES(178, 'PR', 'Puerto Rico');
INSERT INTO `paises` VALUES(179, 'QA', 'Qatar');
INSERT INTO `paises` VALUES(180, 'GB', 'Reino Unido');
INSERT INTO `paises` VALUES(181, 'RE', 'Reunión');
INSERT INTO `paises` VALUES(182, 'RW', 'Ruanda');
INSERT INTO `paises` VALUES(183, 'RO', 'Rumania');
INSERT INTO `paises` VALUES(184, 'RU', 'Rusia');
INSERT INTO `paises` VALUES(185, 'EH', 'Sahara Occidental');
INSERT INTO `paises` VALUES(186, 'SB', 'Islas Salomón');
INSERT INTO `paises` VALUES(187, 'WS', 'Samoa');
INSERT INTO `paises` VALUES(188, 'AS', 'Samoa Americana');
INSERT INTO `paises` VALUES(189, 'KN', 'San Cristóbal y Nevis');
INSERT INTO `paises` VALUES(190, 'SM', 'San Marino');
INSERT INTO `paises` VALUES(191, 'PM', 'San Pedro y Miquelón');
INSERT INTO `paises` VALUES(192, 'VC', 'San Vicente y las Granadinas');
INSERT INTO `paises` VALUES(193, 'SH', 'Santa Helena');
INSERT INTO `paises` VALUES(194, 'LC', 'Santa Lucía');
INSERT INTO `paises` VALUES(195, 'ST', 'Santo Tomé y Príncipe');
INSERT INTO `paises` VALUES(196, 'SN', 'Senegal');
INSERT INTO `paises` VALUES(197, 'CS', 'Serbia y Montenegro');
INSERT INTO `paises` VALUES(198, 'SC', 'Seychelles');
INSERT INTO `paises` VALUES(199, 'SL', 'Sierra Leona');
INSERT INTO `paises` VALUES(200, 'SG', 'Singapur');
INSERT INTO `paises` VALUES(201, 'SY', 'Siria');
INSERT INTO `paises` VALUES(202, 'SO', 'Somalia');
INSERT INTO `paises` VALUES(203, 'LK', 'Sri Lanka');
INSERT INTO `paises` VALUES(204, 'SZ', 'Suazilandia');
INSERT INTO `paises` VALUES(205, 'ZA', 'Sudáfrica');
INSERT INTO `paises` VALUES(206, 'SD', 'Sudán');
INSERT INTO `paises` VALUES(207, 'SE', 'Suecia');
INSERT INTO `paises` VALUES(208, 'CH', 'Suiza');
INSERT INTO `paises` VALUES(209, 'SR', 'Surinam');
INSERT INTO `paises` VALUES(210, 'SJ', 'Svalbard y Jan Mayen');
INSERT INTO `paises` VALUES(211, 'TH', 'Tailandia');
INSERT INTO `paises` VALUES(212, 'TW', 'Taiwán');
INSERT INTO `paises` VALUES(213, 'TZ', 'Tanzania');
INSERT INTO `paises` VALUES(214, 'TJ', 'Tayikistán');
INSERT INTO `paises` VALUES(215, 'IO', 'Territorio Británico del Océano Índico');
INSERT INTO `paises` VALUES(216, 'TF', 'Territorios Australes Franceses');
INSERT INTO `paises` VALUES(217, 'TL', 'Timor Oriental');
INSERT INTO `paises` VALUES(218, 'TG', 'Togo');
INSERT INTO `paises` VALUES(219, 'TK', 'Tokelau');
INSERT INTO `paises` VALUES(220, 'TO', 'Tonga');
INSERT INTO `paises` VALUES(221, 'TT', 'Trinidad y Tobago');
INSERT INTO `paises` VALUES(222, 'TN', 'Túnez');
INSERT INTO `paises` VALUES(223, 'TC', 'Islas Turcas y Caicos');
INSERT INTO `paises` VALUES(224, 'TM', 'Turkmenistán');
INSERT INTO `paises` VALUES(225, 'TR', 'Turquía');
INSERT INTO `paises` VALUES(226, 'TV', 'Tuvalu');
INSERT INTO `paises` VALUES(227, 'UA', 'Ucrania');
INSERT INTO `paises` VALUES(228, 'UG', 'Uganda');
INSERT INTO `paises` VALUES(229, 'UY', 'Uruguay');
INSERT INTO `paises` VALUES(230, 'UZ', 'Uzbekistán');
INSERT INTO `paises` VALUES(231, 'VU', 'Vanuatu');
INSERT INTO `paises` VALUES(232, 'VE', 'Venezuela');
INSERT INTO `paises` VALUES(233, 'VN', 'Vietnam');
INSERT INTO `paises` VALUES(234, 'VG', 'Islas Vírgenes Británicas');
INSERT INTO `paises` VALUES(235, 'VI', 'Islas Vírgenes de los Estados Unidos');
INSERT INTO `paises` VALUES(236, 'WF', 'Wallis y Futuna');
INSERT INTO `paises` VALUES(237, 'YE', 'Yemen');
INSERT INTO `paises` VALUES(238, 'DJ', 'Yibuti');
INSERT INTO `paises` VALUES(239, 'ZM', 'Zambia');
INSERT INTO `paises` VALUES(240, 'ZW', 'Zimbabue');

##################################01/12/2017###########################################################
ALTER TABLE `compra`
	CHANGE COLUMN `factura_nro` `factura_nro` VARCHAR(100) NULL AFTER `descripcion`;
ALTER TABLE `compra`
	CHANGE COLUMN `factura_tipo` `factura_tipo` VARCHAR(1) NULL AFTER `factura_nro`,
	CHANGE COLUMN `factura_orden` `factura_orden` VARCHAR(1) NULL AFTER `factura_tipo`;

ALTER TABLE `cheque_consumo`
	CHANGE COLUMN `vencido` `vencido` INT(11) NULL AFTER `debitado_por`;

##################################09/03/2018############################################################
ALTER TABLE `unidads`
	ADD COLUMN `orden` INT NULL AFTER `activa`;

##################################02/05/2018############################################################
CREATE TABLE `categoria_estadia` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`categoria_id` INT(11) NOT NULL,
	`fecha` DATE NULL DEFAULT NULL,
	`dias` INT(11),
	PRIMARY KEY (`id`),
	INDEX `id` (`id`),
	INDEX `categoria_id` (`categoria_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('40', 'Estadía Mínima');


##################################03/07/2018############################################################
ALTER TABLE `cobro_tarjeta_cuotas`
	CHANGE COLUMN `mascara_web` `mascara_web` VARCHAR(50) NULL DEFAULT '0' AFTER `interes`;

##################################06/07/2018############################################################
ALTER TABLE `empleado_hora_extra`
	CHANGE COLUMN `cantidad_aprobada` `cantidad_aprobada` FLOAT NULL AFTER `cantidad_solicitada`;
ALTER TABLE `empleado_hora_extra`
	CHANGE COLUMN `estado` `estado` INT(1) NULL AFTER `creado`;
ALTER TABLE `empleado_hora_extra`
	CHANGE COLUMN `aprobado_por` `aprobado_por` INT(11) NULL AFTER `estado`;
ALTER TABLE `empleado_hora_extra`
	CHANGE COLUMN `aprobado` `aprobado` DATE NULL AFTER `aprobado_por`;

#######################################Exportar Excel ####################################################

SELECT R.`retiro`, R.`hora_retiro`, R.`devolucion`, R.`hora_devolucion`, U.patente
FROM `reservas` R INNER JOIN unidads U ON R.unidad_id = U.id
WHERE (R.`retiro` >='2018-08-01' OR (R.`retiro` BETWEEN '2018-07-01' AND '2018-08-01' AND R.devolucion >= '2018-08-01'))
AND U.patente IN ('AC488VR','AB425OY','AA727XE','AA727XF','AA727XG','AC488VQ') AND (R.estado is null OR R.estado != 2)
ORDER BY U.patente

##################################29/07/2018############################################################
ALTER TABLE `clientes`
	CHANGE COLUMN `dni` `dni` VARCHAR(20) NULL COLLATE 'utf8_unicode_ci' AFTER `nombre_apellido`,
	CHANGE COLUMN `telefono` `telefono` VARCHAR(20) NULL COLLATE 'utf8_unicode_ci' AFTER `dni`,
	CHANGE COLUMN `celular` `celular` VARCHAR(20) NULL COLLATE 'utf8_unicode_ci' AFTER `telefono`,
	CHANGE COLUMN `email` `email` VARCHAR(250) NULL COLLATE 'utf8_unicode_ci' AFTER `localidad`;

ALTER TABLE `reservas`
	CHANGE COLUMN `lugar_retiro_id` `lugar_retiro_id` INT(11) NULL AFTER `retiro`,
	CHANGE COLUMN `pax_adultos` `pax_adultos` INT(1) NULL AFTER `hora_devolucion`,
	CHANGE COLUMN `pax_menores` `pax_menores` INT(1) NULL AFTER `pax_adultos`,
	CHANGE COLUMN `pax_bebes` `pax_bebes` INT(1) NULL AFTER `pax_menores`,
	CHANGE COLUMN `discover` `discover` TINYINT(1) NULL AFTER `pax_bebes`,
	CHANGE COLUMN `discover_plus` `discover_plus` TINYINT(1) NULL AFTER `discover`,
	CHANGE COLUMN `discover_advance` `discover_advance` TINYINT(1) NULL AFTER `discover_plus`,
	CHANGE COLUMN `total_estadia` `total_estadia` FLOAT NULL AFTER `discover_advance`,
	CHANGE COLUMN `creado` `creado` DATETIME NULL AFTER `total_estadia`,
	CHANGE COLUMN `actualizado` `actualizado` DATETIME NULL AFTER `creado`,
	CHANGE COLUMN `total` `total` FLOAT NULL AFTER `actualizado`,
	CHANGE COLUMN `comentarios` `comentarios` TEXT NULL COLLATE 'utf8_unicode_ci' AFTER `total`,
	CHANGE COLUMN `cargado_por` `cargado_por` INT(11) NULL AFTER `comentarios`;

##################################03/09/2018############################################################
CREATE TABLE `encuesta` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`reserva_id` INT(11) NOT NULL,
	`comentarios_buenos` TEXT NULL,
	`comentarios_malos` TEXT NULL,
	`password` VARCHAR(255) NULL DEFAULT NULL,
	`respondida` TINYINT(1) NOT NULL DEFAULT '0',
	`enviada` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=INNODB
AUTO_INCREMENT=1
;

CREATE TABLE `encuesta_respuestas` (
	`encuesta_id` INT(11) NOT NULL,
	`pregunta_id` VARCHAR(2) NOT NULL,
	`valor` VARCHAR(1) NOT NULL,
	`extra` VARCHAR(100) NOT NULL
)
COLLATE='latin1_swedish_ci'
ENGINE=INNODB
;

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (10, 'Informes de satisfacción');

ALTER TABLE `clientes`
	ADD COLUMN `email2` VARCHAR(255) NULL AFTER `email`;

UPDATE clientes SET email2 = email;

##################################17/09/2018############################################################
ALTER TABLE `seguros`
	ADD COLUMN `categoria_id` INT(11) NOT NULL DEFAULT '0' AFTER `id`;

INSERT INTO `seguros` ( `categoria_id`, `seguro`, `seguro_ingles`, `seguro_portugues`, `importe`, `orden`) VALUES
	(2, 'Discover Advance (sin franquicia por daÃ±os parciales)', 'Discover Advance (Covers all risk for partial damage)', 'Discover Advance', 300, 3),
	(2, 'Discover Plus (franquicia reducida por daÃ±os parciales)', 'Discover Plus (Reduced Partial Damage Waiver)', 'Discover Plus', 200, 2),
	(2, 'Discover (con franquicia por daÃ±os parciales)', 'Discover (PDW:Partial Damage Waiver)', 'Discover', 0, 1),
	(4, 'Discover Advance (sin franquicia por daÃ±os parciales)', 'Discover Advance (Covers all risk for partial damage)', 'Discover Advance', 300, 3),
	(4, 'Discover Plus (franquicia reducida por daÃ±os parciales)', 'Discover Plus (Reduced Partial Damage Waiver)', 'Discover Plus', 200, 2),
	(4, 'Discover (con franquicia por daÃ±os parciales)', 'Discover (PDW:Partial Damage Waiver)', 'Discover', 0, 1),
	(6, 'Discover Advance (sin franquicia por daÃ±os parciales)', 'Discover Advance (Covers all risk for partial damage)', 'Discover Advance', 300, 3),
	(6, 'Discover Plus (franquicia reducida por daÃ±os parciales)', 'Discover Plus (Reduced Partial Damage Waiver)', 'Discover Plus', 200, 2),
	(6, 'Discover (con franquicia por daÃ±os parciales)', 'Discover (PDW:Partial Damage Waiver)', 'Discover', 0, 1);

##################################30/10/2018############################################################
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (10, 'Modificar Puntajes de Informes de satisfacción');

##################################12/11/2018############################################################

ALTER TABLE `reservas`
	ADD COLUMN `voucher` INT(11) NOT NULL DEFAULT '0',
	ADD COLUMN `planilla` INT(11) NOT NULL DEFAULT '0';

##################################14/11/2018############################################################
ALTER TABLE `cuenta`
	ADD COLUMN `controla_facturacion` TINYINT NOT NULL DEFAULT '0' AFTER `visible_en_informe`,
	ADD COLUMN `CUIT` VARCHAR(50) NULL AFTER `controla_facturacion`;

CREATE TABLE `punto_ventas` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`cuit` VARCHAR(50) NULL,
	`numero` VARCHAR(50) NULL,
	`descripcion` VARCHAR(255)NULL,
	`direccion` VARCHAR(255)NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=INNODB
;
INSERT INTO `permiso_grupo` (`nombre`) VALUES ('Puntos de venta');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (43, 'Operar');

UPDATE `permiso_grupo` SET `nombre`='Carga de extras y facturas' WHERE  `id`=21;

ALTER TABLE `reserva_facturas`
	ADD COLUMN `punto_venta_id` INT(11) NULL DEFAULT '0' AFTER `id`;

CREATE TABLE `reserva_factura_importacions` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`fecha` DATETIME NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

CREATE TABLE `reserva_factura_importacion_items` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`reserva_factura_importacion_id` INT(11) NOT NULL,
	`nro` VARCHAR(50) NULL DEFAULT NULL,
	`tipo` VARCHAR(50) NULL DEFAULT '0',
	`fecha` DATE NULL DEFAULT NULL,
	`CAE` VARCHAR(50) NULL DEFAULT NULL,
	`nombre` VARCHAR(255) NULL DEFAULT NULL,
	`documento` VARCHAR(50) NULL DEFAULT NULL,
	`direccion` VARCHAR(255) NULL DEFAULT NULL,
	`moneda` VARCHAR(50) NULL DEFAULT NULL,
	`cotizacion` DECIMAL(10,2) NULL DEFAULT 0,
	`neto_gravado` DECIMAL(10,2) NULL DEFAULT 0,
	`base_21` DECIMAL(10,2) NULL DEFAULT 0,
	`iva_21` DECIMAL(10,2) NULL DEFAULT 0,
	`base_imponible` DECIMAL(10,2) NULL DEFAULT 0,
	`total_iva` DECIMAL(10,2) NULL DEFAULT 0,
	`total` DECIMAL(10,2) NULL DEFAULT 0,
	`exento` DECIMAL(10,2) NULL DEFAULT 0,
	`neto_no_gravado` DECIMAL(10,2) NULL DEFAULT 0,
	`base_0` DECIMAL(10,2) NULL DEFAULT 0,
	`iva_0` DECIMAL(10,2) NULL DEFAULT 0,
	base_2_5 DECIMAL(10,2) NULL DEFAULT 0,
	`iva_2_5` DECIMAL(10,2) NULL DEFAULT 0,
	`base_5` DECIMAL(10,2) NULL DEFAULT 0,
	`iva_5` DECIMAL(10,2) NULL DEFAULT 0,
	`base_10_5` DECIMAL(10,2) NULL DEFAULT 0,
	`iva_10_5` DECIMAL(10,2) NULL DEFAULT 0,
	`iva_19` DECIMAL(10,2) NULL DEFAULT 0,
	`base_27` DECIMAL(10,2) NULL DEFAULT 0,
	`iva_27` DECIMAL(10,2) NULL DEFAULT 0,
	`otros_tributos` DECIMAL(10,2) NULL DEFAULT 0,
	`provincia` VARCHAR(50) NULL DEFAULT '0',
	`condicion_iva` VARCHAR(50) NULL DEFAULT NULL,
	`exito` TINYINT(1) NULL DEFAULT NULL,
	`observaciones` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

CREATE TABLE `reserva_factura_procesada` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`reserva_id` INT(11) NOT NULL,
	`fecha` DATETIME NULL,
	`cliente` VARCHAR(255) NULL DEFAULT NULL,
	`dni` VARCHAR(50) NULL DEFAULT NULL,
	`total` DECIMAL(10,2) NULL DEFAULT 0,
	`neto` DECIMAL(10,2) NULL DEFAULT 0,
	`diferencia` DECIMAL(10,2) NULL DEFAULT 0,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

ALTER TABLE `voucher`
	ADD COLUMN `restricciones_en` TEXT NULL AFTER `politica_cancelacion`,
	ADD COLUMN `politica_cancelacion_en` TEXT NULL AFTER `restricciones_en`,
	ADD COLUMN `restricciones_po` TEXT NULL AFTER `politica_cancelacion_en`,
	ADD COLUMN `politica_cancelacion_po` TEXT NULL AFTER `restricciones_po`;

##################################07/12/2018############################################################
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (21, 'Editar y eliminar');

##################################10/12/2018############################################################
ALTER TABLE `categorias`
	ADD COLUMN `orden` INT NULL AFTER `descuento`;



##################################27/12/2018############################################################
CREATE TABLE `usuario_log` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`usuario_id` INT(11) NOT NULL,
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`nombre` VARCHAR(255) NULL DEFAULT NULL,
	`accion` VARCHAR(255) NULL DEFAULT NULL,
	`ip` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

INSERT INTO `permiso_grupo` (`nombre`) VALUES ('Centros de Costos');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('44', 'Operar');

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('10', 'Informe de Impuestos, tasas y cargas sociales');

CREATE TABLE `usuario_rubro` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`usuario_id` INT(11) NOT NULL,
	`rubro_id` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

UPDATE `permiso_grupo` SET `nombre`='Gastos y Compras' WHERE  `id`=7;
UPDATE `permiso_grupo` SET `nombre`='Impuestos, tasas y cargas sociales' WHERE  `id`=13;

UPDATE `discover`.`permiso` SET `nombre`='Administrador de gastos y compras' WHERE  `id`=100;

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (13, 'Administrador de impuestos, tasas y cargas sociales');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (10, 'Informe de Extras');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (10, 'Informe de Extras Adelantados');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (10, 'Informe de Extras No Adelantados');

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('8', 'Gastos y compras');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('8', 'Impuestos,tasas y Cargas sociales');

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (10, 'Auditoria de Usuarios');

##################################09/01/2019############################################################
UPDATE `permiso` SET `nombre`='Pagar meses atrasados (Sueldos y Horas Extras)' WHERE  `id`=102;

ALTER TABLE `compra` CHANGE `factura_nro` `factura_nro` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `compra` CHANGE `factura_tipo` `factura_tipo` VARCHAR(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `compra` CHANGE `factura_orden` `factura_orden` VARCHAR(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

UPDATE `configuracion` SET `descripcion`='Monto de gastos y compras que se aprueba automaticamente' WHERE  `id`='gasto_aprobado';
UPDATE `configuracion` SET `descripcion`='Monto de Impuestos,tasas y Cargas sociales que se aprueba automaticamente' WHERE  `id`='compra_aprobada';

##################################04/02/2019############################################################

INSERT INTO `ano` (`id`, `ano`) VALUES ('2019', '2019');
INSERT INTO `ano` (`id`, `ano`) VALUES ('2020', '2020');
INSERT INTO `ano` (`id`, `ano`) VALUES ('2021', '2021');
INSERT INTO `ano` (`id`, `ano`) VALUES ('2022', '2022');
INSERT INTO `ano` (`id`, `ano`) VALUES ('2023', '2023');
INSERT INTO `ano` (`id`, `ano`) VALUES ('2024', '2024');
INSERT INTO `ano` (`id`, `ano`) VALUES ('2025', '2025');
INSERT INTO `ano` (`id`, `ano`) VALUES ('2026', '2026');

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('13', 'Eliminar y anular pagos');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('7', 'Eliminar y anular pagos');


ALTER TABLE `reserva_facturas`
	ADD COLUMN `tipoDoc` INT(11) NULL DEFAULT '1' AFTER `punto_venta_id`;

##################################20/02/2019############################################################
ALTER TABLE `reservas`
	ADD COLUMN `vuelo` CHAR(10) NULL DEFAULT NULL AFTER `planilla`;

ALTER TABLE `reservas`
	ADD COLUMN `responsableRetiro` INT(11) NULL DEFAULT NULL AFTER `vuelo`,
	ADD COLUMN `responsableDevolucion` INT(11) NULL DEFAULT NULL AFTER `responsableRetiro`;

##################################27/02/2019############################################################
ALTER TABLE `rubro`
	ADD COLUMN `gastos` INT(1) NOT NULL DEFAULT '1' AFTER `rubro`,
	ADD COLUMN `impuestos` INT(1) NOT NULL DEFAULT '1' AFTER `gastos`,
	ADD COLUMN `activo` INT(1) NOT NULL DEFAULT '1' AFTER `impuestos`;

ALTER TABLE `subrubro`
	ADD COLUMN `activo` INT(1) NOT NULL DEFAULT '1';

##################################01/03/2019############################################################
ALTER TABLE `punto_ventas`
	ADD COLUMN `ivaVentas` TINYINT(1) NOT NULL DEFAULT '0' AFTER `direccion`;

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (10, 'Libro IVA ventas');
##################################11/03/2019############################################################
ALTER TABLE `cobro_cheques`
	ADD COLUMN `caja_acreditado` INT(11) NOT NULL AFTER `asociado_a_pagos_fecha`;

##################################24/04/2019############################################################

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (21, 'Permitir descuentos');

##################################06/05/2019############################################################
ALTER TABLE `cobro_tarjeta_tipos`
	ADD COLUMN `mostrar` TINYINT(1) NULL DEFAULT '0';
##################################06/08/2019###########################################################
ALTER TABLE `caja`
	ADD COLUMN `descubierto` INT(11) NOT NULL AFTER `visible_en_informe`,
	ADD COLUMN `sincronizacion` INT(11) NOT NULL AFTER `descubierto`,
	ADD COLUMN `dias_sincronizacion` INT(11) NOT NULL AFTER `sincronizacion`;


INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (16, 'Permitir mov. anteriores a la sincronizacion');

ALTER TABLE `reserva_cobros`
	ADD COLUMN `moneda_id` INT(11) NULL DEFAULT '1' AFTER `finalizado`,
	ADD COLUMN `monto_moneda` DECIMAL(10,2) NULL AFTER `moneda_id`,
	ADD COLUMN `cambio` DECIMAL(10,2) NULL AFTER `monto_moneda`;

CREATE TABLE `moneda` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`moneda` VARCHAR(100) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `id` (`id`),
	INDEX `moneda` (`moneda`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

INSERT INTO `moneda` (`moneda`) VALUES ('$');
INSERT INTO `moneda` (`moneda`) VALUES ('U$D');
INSERT INTO `moneda` (`moneda`) VALUES ('€');

ALTER TABLE `cobro_efectivos`
	ADD COLUMN `moneda_id` INT(11) NULL DEFAULT '1',
	ADD COLUMN `monto_moneda` DECIMAL(10,2) NULL AFTER `moneda_id`,
	ADD COLUMN `cambio` DECIMAL(10,2) NULL AFTER `monto_moneda`;

ALTER TABLE `caja_movimiento`
	ADD COLUMN `moneda_id` INT(11) NULL DEFAULT '1',
	ADD COLUMN `monto_moneda` DECIMAL(10,2) NULL AFTER `moneda_id`,
	ADD COLUMN `cambio` DECIMAL(10,2) NULL AFTER `monto_moneda`,
	ADD COLUMN `usados` DECIMAL(10,2) NULL DEFAULT '0' AFTER `cambio`;

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (16, 'Cambio de moneda extranjera');

##################################26/09/2019###########################################################
ALTER TABLE `proveedor`
	ADD COLUMN `razon` VARCHAR(250) NULL DEFAULT NULL AFTER `contacto`;

CREATE TABLE `plans` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`plan` VARCHAR(255) NOT NULL,
	`tipo` VARCHAR(255) NOT NULL,
	`monto` DECIMAL(10,2) NULL DEFAULT 0,
	`intereses` DECIMAL(10,2) NULL DEFAULT 0,
	`cuotas` INT(11) NOT NULL,
	`vencimiento1` DATE NULL,
	`vencimiento2` DATE NULL,
	`rubro_id` INT(11)  NULL,
	`subrubro_id` INT(11) NULL,
	`proveedor` VARCHAR(100) NULL,
	`user_id` INT(11)  NULL,

	PRIMARY KEY (`id`),
	INDEX `id` (`id`),
	INDEX `plan` (`plan`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

CREATE TABLE `cuota_plans` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,

	`plan_id` INT(11) NULL DEFAULT NULL,
	`vencimiento` DATE NULL,
	`monto` DECIMAL(10,2) NULL DEFAULT 0,
	`fecha_pago` DATE NULL,
	`estado` INT(11) NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;


INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (13, 'Planes de pagos');

ALTER TABLE `cuenta_a_pagar`
	ADD COLUMN `plan_id` INT(11) NULL;

ALTER TABLE `gasto`
	ADD COLUMN `plan_id` INT(11) NULL;

ALTER TABLE `compra`
	ADD COLUMN `plan_id` INT(11) NULL;



ALTER TABLE `gasto`
	ADD COLUMN `orden_pago` INT(11) NULL;

UPDATE cuenta_a_pagar
INNER JOIN gasto
			ON cuenta_a_pagar.operacion_id=gasto.id AND cuenta_a_pagar.operacion_tipo='gasto'
SET gasto.orden_pago = gasto.nro_orden
WHERE cuenta_a_pagar.estado = 1;

ALTER TABLE `compra`
	ADD COLUMN `orden_pago` INT(11) NULL;

UPDATE cuenta_a_pagar
INNER JOIN compra
			ON cuenta_a_pagar.operacion_id=compra.id AND cuenta_a_pagar.operacion_tipo='compra'
SET compra.orden_pago = compra.nro_orden
WHERE cuenta_a_pagar.estado = 1;

################################### 31/10/2019 ###########################################
ALTER TABLE `caja_movimiento`
	ALTER `fecha` DROP DEFAULT;
ALTER TABLE `caja_movimiento`
	CHANGE COLUMN `fecha` `fecha` DATETIME NOT NULL AFTER `monto`;

################################### 21/11/2019 ###########################################
CREATE TABLE `feriados` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`fecha` DATE NOT NULL,
	`abre` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `fecha` (`fecha`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `feriado_horarios` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`feriado_id` INT(11) NOT NULL,
	`hora_inicio` TIME NOT NULL,
	`hora_fin` TIME NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `id` (`id`),
	INDEX `feriado_id` (`feriado_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

##################################26/03/2020###########################################################
ALTER TABLE `punto_ventas`
	ADD COLUMN `alicuota` DECIMAL(10,2) NULL DEFAULT NULL AFTER `ivaVentas`;

##################################23/06/2020###########################################################
INSERT INTO `configuracion_reservas` (`id`, `descripcion`, `valor`) VALUES
	('dias_antes', 'dias de anticipacion', '2');

##################################30/06/2020###########################################################
ALTER TABLE `usuario`
	ADD COLUMN `dni` INT(11) NULL AFTER `espacio_trabajo_id`;

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (16, 'Transferencias intersucursal');



##################################28/07/2020###########################################################
ALTER TABLE `descuentos`
	ADD COLUMN `orden` INT(11) NULL AFTER `tarjeta_portugues`;

##################################02/07/2020###########################################################
INSERT INTO `permiso_grupo` (`nombre`) VALUES ('Carga y asignacion de chequeras');

ALTER TABLE `cuenta`
	ADD COLUMN `emite_cheques` TINYINT NOT NULL DEFAULT '0' AFTER `controla_facturacion`;

INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (4, 'Firma cheques');

CREATE TABLE `chequeras` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`cuenta_id` INT(11) NOT NULL,
	`numero` INT(11) NOT NULL,
	`tipo` VARCHAR(255) NOT NULL,
	`cantidad` INT(11) NOT NULL,
	`inicio` VARCHAR(255) NOT NULL,
	`final` VARCHAR(255) NOT NULL,
	`usuario_id` INT(11) NOT NULL,
	`estado` INT(11) NOT NULL,
	`ultimo` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `chequera_cheques` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`chequera_id` INT(11) NOT NULL,
	`numero` VARCHAR(255) NOT NULL,
	`estado` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `id` (`id`),
	INDEX `chequera_id` (`chequera_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;


ALTER TABLE `rel_pago_operacion`
	ADD INDEX `forma_pago_id` (`forma_pago_id`);

ALTER TABLE `cheque_consumo`
	ADD INDEX `cuenta_id` (`cuenta_id`);



################################### 25/07/2020 ###########################################
UPDATE `permiso` SET `nombre`='Agregar extraviados y anulados' WHERE  `id`=49;
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('14', 'Reemplazar');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES ('14', 'Debitar y anular débito');

ALTER TABLE `cheque_consumo`
	CHANGE COLUMN `concepto` `concepto` VARCHAR(255) NULL DEFAULT NULL AFTER `debitado`;


ALTER TABLE `cheque_consumo`
	ADD COLUMN `chequera_id` INT(11) NULL AFTER `vencido`;

################################### 16/09/2020 ###########################################
	ALTER TABLE `descuentos`
	ADD COLUMN `mercadopago` TINYINT(1) NULL DEFAULT '0' AFTER `tarjeta_portugues`,
	ADD COLUMN `mercadopago_ingles` TINYINT(1) NULL DEFAULT '0' AFTER `mercadopago`,
	ADD COLUMN `mercadopago_portugues` TINYINT(1) NULL DEFAULT '0' AFTER `mercadopago_ingles`;

	INSERT INTO `usuario` (id, `nombre`, `apellido`, `email`, `password`, `telefono`, `admin`) VALUES (2,'Usuario', 'WEB', 'UsuarioWEB', 'usuarioweb', '-', '0');

################################### 05/11/2020 ###########################################
ALTER TABLE `unidads`
	ADD COLUMN `km_ini` INT NULL AFTER `orden`,
	ADD COLUMN `km` INT NULL AFTER `km_ini`;

ALTER TABLE `reservas`
	ADD COLUMN `km_ini` INT NULL AFTER `unidad_id`,
	ADD COLUMN `km_fin` INT NULL AFTER `km_ini`;

	INSERT INTO `configuracion_reservas` (`id`, `descripcion`, `valor`) VALUES ('nro_reserva_inicial', 'Nro. de primer reserva a cargar Kms.', '0');

CREATE TABLE `alertas` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`tipo` ENUM('Flota','General') NOT NULL,
	`alerta` VARCHAR(255) NOT NULL,
	`corta` VARCHAR(255) NOT NULL,
	`fecha` DATE NOT NULL,
	`nivel` ENUM('Nivel 1','Nivel 2','Nivel 3') NOT NULL,
	`unidad` ENUM('KM','Tiempo','Reservas') NOT NULL,
	`magnitud` ENUM('Dia','Mes','Año')  NULL,
	`controla` INT(11) NULL,
	`segmento` ENUM('Intervalo','Umbral') NOT NULL,
	`inicio_num` INT(11) NULL,
	`inicio_fecha` DATE NULL,

	`fin_num` INT(11) NULL,
	`recordatorio` INT(11) NULL,
	descripcion VARCHAR(255)  NULL,

	PRIMARY KEY (`id`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

CREATE TABLE `unidad_alertas` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`unidad_id` INT(11) NULL DEFAULT NULL,
	`alerta_id` INT(11) NULL DEFAULT NULL,
	`inicio_num` INT(11) NULL,
	`inicio_fecha` DATE NULL,
	`fin_num` INT(11) NULL,
	`inactiva` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

CREATE TABLE `gestion_alertas` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`unidad_alerta_id` INT(11) NULL DEFAULT NULL,
	`alerta_id` INT(11) NULL DEFAULT NULL,
	`inicio_num` INT(11) NULL,
	`inicio_fecha` DATE NULL,
	`user_id` INT(11)  NULL,
	`gestion` ENUM('Posponer','Resolver')  NULL,
	`fecha_gestion` DATE NULL,
	`estado` ENUM('Pendiente','Resuelta','Vencida')  NULL,
	descripcion VARCHAR(255)  NULL,
	`fecha_resolucion` DATE NULL,
	`km_resolucion` INT(11) NULL,
	`reserva_resolucion` INT(11) NULL,
	`fecha_posposicion` DATE NULL,
	`km_posposicion` INT(11) NULL,
	`reserva_posposicion` INT(11) NULL,
	`reservas` INT(11) NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;



INSERT INTO `permiso_grupo` (`nombre`) VALUES ('Alertas');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (46, 'Gestion');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (46, 'Auditoria');


################################### 12/11/2020 ###########################################
ALTER TABLE `gasto`
	ADD COLUMN `quitar_egresos` INT(1) NOT NULL DEFAULT '0';

################################### 21/01/2021 ###########################################
UPDATE categoria_coheficiente SET dia = '8' WHERE dia = '8 o +';

################################### 28/01/2021 ###########################################
ALTER TABLE `plans`
	ADD COLUMN `ordenes` VARCHAR(255) NULL DEFAULT NULL AFTER `proveedor`;

UPDATE plans AS p
SET p.ordenes =
(SELECT GROUP_CONCAT(DISTINCT g.nro_orden)
 FROM gasto AS g
 WHERE g.plan_id = p.id AND g.plan_id IS NOT NULL
 GROUP BY g.plan_id)
 WHERE p.tipo = "Gastos y compras";

 UPDATE plans AS p
SET p.ordenes =
(SELECT GROUP_CONCAT(DISTINCT g.nro_orden)
 FROM compra AS g
 WHERE g.plan_id = p.id AND g.plan_id IS NOT NULL
 GROUP BY g.plan_id)
 WHERE p.tipo = "Impuestos, tasas y cargas sociales";

 UPDATE plans AS p
SET p.ordenes =
(SELECT GROUP_CONCAT(DISTINCT g.nro_orden)
 FROM compra AS g INNER JOIN cuenta_a_pagar AS CP ON g.id = CP.operacion_id AND CP.operacion_tipo = "compra"
 WHERE CP.plan_id = p.id AND CP.plan_id IS NOT NULL
 GROUP BY CP.plan_id)
 WHERE p.tipo = "Cuentas a pagar";

 UPDATE plans AS p
SET p.ordenes =
(SELECT GROUP_CONCAT(DISTINCT g.nro_orden)
 FROM gasto AS g INNER JOIN cuenta_a_pagar AS CP ON g.id = CP.operacion_id AND CP.operacion_tipo = "gasto"
 WHERE CP.plan_id = p.id AND CP.plan_id IS NOT NULL
 GROUP BY CP.plan_id)
 WHERE p.tipo = "Cuentas a pagar";

 ################################### 26/04/2021 ###########################################
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (10, 'Base de datos');

 ################################### 30/06/2021 ###########################################
ALTER TABLE `lugars`
	ADD COLUMN `activo` TINYINT(1) NOT NULL DEFAULT '1' AFTER `lugar_portugues`;

################################### 05/07/2021 ###########################################
CREATE TABLE `reserva_conductors` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`reserva_id` INT(11) NOT NULL,
	`nombre_apellido` VARCHAR(250) NOT NULL COLLATE 'utf8_unicode_ci',
	`dni` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`telefono` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`direccion` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`localidad` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`email` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`nro_licencia_de_conducir` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`vencimiento` DATE NULL DEFAULT NULL,
	`lugar_emision` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB;

################################### 15/07/2021 ###########################################

ALTER TABLE `clientes`
	ADD COLUMN `tipoDocumento` ENUM('DNI','Pasaporte') NULL DEFAULT 'DNI' AFTER `nombre_apellido`;

ALTER TABLE `clientes`
	ADD COLUMN `tipoTelefono` ENUM('Fijo','Celular') NULL DEFAULT 'Celular' AFTER `dni`;

ALTER TABLE `clientes`
	ADD COLUMN `codPais` VARCHAR(10) NULL DEFAULT NULL AFTER `tipoTelefono`,
	ADD COLUMN `codArea` VARCHAR(10) NULL DEFAULT NULL AFTER `codPais`;

CREATE TABLE `pais_telefono` (
	`nombre` VARCHAR(50) NULL DEFAULT NULL,
	`name` VARCHAR(50) NULL DEFAULT NULL,
	`nom` VARCHAR(50) NULL DEFAULT NULL,
	`iso2` VARCHAR(50) NULL DEFAULT NULL,
	`iso3` VARCHAR(50) NULL DEFAULT NULL,
	`phone_code` VARCHAR(50) NULL DEFAULT NULL
)
COLLATE='utf8_general_ci'
;

ALTER TABLE `paises`
	ADD COLUMN `prefijo` VARCHAR(10) NULL DEFAULT NULL AFTER `nombre`;

UPDATE paises INNER JOIN pais_telefono ON paises.iso = pais_telefono.iso2
SET paises.prefijo = `pais_telefono`.`phone_code`;

ALTER TABLE `reservas`
	ADD COLUMN `aerolinea` VARCHAR(50) NULL DEFAULT NULL AFTER `planilla`;

ALTER TABLE `clientes`
	ADD COLUMN `razon_social` VARCHAR(250) NULL DEFAULT NULL AFTER `cuit`,
	ADD COLUMN `tipoPersona` ENUM('Fisica','Juridica') NULL DEFAULT 'Fisica' AFTER `razon_social`;
ALTER TABLE `clientes`
	ADD COLUMN `titular_factura` TINYINT(1) NULL DEFAULT '0' AFTER `titular_conduce`;

CREATE TABLE `concepto_facturacions` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(255) NOT NULL,
	`activo` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

INSERT INTO `permiso_grupo` (`nombre`) VALUES ('Concepto de facturacion');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (43, 'Operar');

ALTER TABLE `reserva_cobros`
	ADD COLUMN `concepto_facturacion_id` INT(11) NULL AFTER `cambio`;

################################### 18/05/2022 ###########################################
CREATE TABLE `grilla_feriados` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(255) NOT NULL,
	`desde` DATE NULL DEFAULT NULL,
	`hasta` DATE NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `id` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;

INSERT INTO `permiso_grupo` (`nombre`) VALUES ('Carga de feriados en grilla');
INSERT INTO `permiso` (`permiso_grupo_id`, `nombre`) VALUES (48, 'Operar');

################################### 29/06/2022 ###########################################
ALTER TABLE `reserva_extras`
	ADD COLUMN `consumida` DATE NULL AFTER `extra_variable_id`;

##################################17/01/2023###########################################################
ALTER TABLE `extra_subrubros`
    ADD COLUMN `activo` TINYINT(1) NOT NULL DEFAULT 1 AFTER `subrubro`;

##################################03/08/2023###########################################################
ALTER TABLE `cobro_tarjeta_posnets`
    ADD COLUMN `activo` TINYINT(1) NOT NULL DEFAULT 1,
    ADD COLUMN `controla_facturacion` TINYINT(1) NOT NULL DEFAULT 1;

##################################03/04/2024###########################################################
CREATE TABLE `usuario_auditoria` (
                                     `id` INT(11) NOT NULL AUTO_INCREMENT,
                                     `usuario_id` INT(11) NOT NULL,
                                     `fecha` DATE NULL DEFAULT NULL,
                                     `logueo` TIMESTAMP NULL DEFAULT NULL,
                                     `last` TIMESTAMP NULL DEFAULT NULL,
                                     `segundos` float NOT NULL DEFAULT '0',
                                     `interaccion` VARCHAR(255) NULL DEFAULT NULL,
                                     `ip` VARCHAR(255) NULL DEFAULT NULL,
                                     PRIMARY KEY (`id`)
)
    COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

##################################18/04/2024###########################################################
ALTER TABLE `usuario`
    ADD COLUMN `activo` TINYINT(1) NOT NULL DEFAULT '1';