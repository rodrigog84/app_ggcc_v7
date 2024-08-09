INSERT INTO `gc_menu` (`name`, `img`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('Remuneraciones', 'fa-users', 1, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/parametros_generales', 'Parametros Generales', 8, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (100, 4);

CREATE TABLE `gc_parametros_generales` (
	`uf` INT(11) NOT NULL DEFAULT '0',
	`sueldominimo` INT(11) NOT NULL DEFAULT '0',
	`csimples` INT(11) NOT NULL DEFAULT '0',
	`cinvalidas` INT(11) NOT NULL DEFAULT '0',
	`cmaternales` INT(11) NOT NULL DEFAULT '0'
)
ENGINE=InnoDB
;


UPDATE `gc_app` SET `function`='remuneraciones/parametros_generales' WHERE  `id`=100;
INSERT INTO `gc_parametros_generales` (`sueldominimo`) VALUES (241000);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_parametros_generales', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (101, 4);

ALTER TABLE `gc_parametros_generales`
	CHANGE COLUMN `uf` `uf` DOUBLE NOT NULL DEFAULT '0' FIRST;


INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/personal', 'Mantenci&oacute;n de Personal', 8, 1, 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (102, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/add_trabajador', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (103, 1);
/***********************************************************************************/
CREATE TABLE `gc_estado_civil` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NULL DEFAULT NULL,
	`activo` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
AUTO_INCREMENT=5
;


INSERT INTO `gc_estado_civil` (`id`, `nombre`, `activo`, `updated_at`) VALUES (1, 'Soltero', 1, '2015-11-05 23:06:34');
INSERT INTO `gc_estado_civil` (`id`, `nombre`, `activo`, `updated_at`) VALUES (2, 'Casado', 1, '2015-11-05 23:06:51');
INSERT INTO `gc_estado_civil` (`id`, `nombre`, `activo`, `updated_at`) VALUES (3, 'Viudo', 1, '2015-11-05 23:07:00');
INSERT INTO `gc_estado_civil` (`id`, `nombre`, `activo`, `updated_at`) VALUES (4, 'Divorciado', 1, '2015-11-05 23:07:05');



CREATE TABLE `gc_cargos` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NULL DEFAULT NULL,
	`idcomunidad` INT(11) UNSIGNED NULL DEFAULT NULL,
	`idpadre` INT(11) UNSIGNED NULL DEFAULT NULL,
	`activo` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_gc_cargos_idcomunidad_gc_comunidad_id` (`idcomunidad`),
	CONSTRAINT `fk_gc_cargos_idcomunidad_gc_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
COMMENT='Tener cuidado con id, que se aplican directo sobre el código'
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
AUTO_INCREMENT=60
;



INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (1, 'Seguridad', NULL, NULL, 1, '0000-00-00 00:00:00');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (2, 'Guardia de Seguridad', NULL, 1, 1, '2015-11-05 23:34:43');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (3, 'Jefe de Porteria', NULL, 1, 1, '2015-11-05 23:35:09');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (4, 'Portero', NULL, 1, 1, '2015-11-05 23:35:21');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (5, 'Guardia Acceso Playa', NULL, 1, 1, '2015-11-05 23:35:21');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (6, 'Supervisor', NULL, 1, 1, '2015-11-05 23:35:57');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (7, 'Jefe de Turno', NULL, 1, 1, '2015-11-05 23:36:08');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (8, 'Salvavidas Playa', NULL, 1, 1, '2015-11-05 23:36:23');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (9, 'Recepcionista', NULL, 1, 1, '2015-11-05 23:36:41');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (10, 'Operador Sala de Control', NULL, 1, 1, '2015-11-05 23:37:05');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (11, 'Centinela', NULL, 1, 1, '2015-11-05 23:37:14');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (12, 'Asistente de Pasillo', NULL, 1, 1, '2015-11-05 23:37:27');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (13, 'Aseo', NULL, NULL, 1, '2015-11-05 23:37:40');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (14, 'Aseador(a)', NULL, 13, 1, '2015-11-05 23:37:55');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (15, 'Auxiliar', NULL, 13, 1, '2015-11-05 23:38:06');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (16, 'Mantencion', NULL, 13, 1, '2015-11-05 23:38:15');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (17, 'Concerjeria', NULL, NULL, 1, '2015-11-05 23:38:36');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (18, 'Concerje', NULL, 17, 1, '2015-11-05 23:38:47');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (19, 'Nochero', NULL, 17, 1, '2015-11-05 23:38:57');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (20, 'Primer Concerje', NULL, 17, 1, '2015-11-05 23:39:08');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (21, 'Jefe de Turno', NULL, 17, 1, '2015-11-05 23:39:22');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (22, 'Auxiliar de Servicios', NULL, 17, 1, '2015-11-05 23:39:55');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (23, 'Reemplazante', NULL, 17, 1, '2015-11-05 23:39:55');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (24, 'Mantencion y Ayudante de Concerje', NULL, 17, 1, '2015-11-05 23:40:49');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (25, 'Capataz de Condominio', NULL, 17, 1, '2015-11-05 23:41:01');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (26, 'Jardineria', NULL, NULL, 1, '2015-11-05 23:41:17');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (27, 'Jardinero', NULL, 26, 1, '2015-11-05 23:41:33');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (28, 'Laborales Agricolas', NULL, 26, 1, '2015-11-05 23:41:48');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (29, 'Construccion en Jardineria', NULL, 26, 1, '2015-11-05 23:42:06');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (30, 'Capataz', NULL, 26, 1, '2015-11-05 23:42:17');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (31, 'Tractorista', NULL, 26, 1, '2015-11-05 23:42:30');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (32, 'Administracion', NULL, NULL, 1, '2015-11-05 23:42:53');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (33, 'Administrador', NULL, 32, 1, '2015-11-05 23:43:05');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (34, 'Mayordomo', NULL, 32, 1, '2015-11-05 23:43:17');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (35, 'Secretaria(o)', NULL, 32, 1, '2015-11-05 23:43:31');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (36, 'Supervisor', NULL, 32, 1, '2015-11-05 23:44:08');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (37, 'Recepcionista', NULL, 32, 1, '2015-11-05 23:44:28');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (38, 'Contador', NULL, 32, 1, '2015-11-05 23:44:42');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (39, 'Asistente Contable', NULL, 32, 1, '2015-11-05 23:44:56');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (40, 'Bodeguero', NULL, 32, 1, '2015-11-05 23:45:05');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (41, 'Encargado Adquisiciones', NULL, 32, 1, '2015-11-05 23:45:27');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (42, 'Gerente', NULL, 32, 1, '2015-11-05 23:45:44');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (43, 'Asistente Administrativo', NULL, 32, 1, '2015-11-05 23:45:58');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (44, 'Jefe de Edificio', NULL, 32, 1, '2015-11-05 23:46:12');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (45, 'Supervisor de Mantenciones', NULL, 32, 1, '2015-11-05 23:46:25');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (46, 'Jefe de Operaciones', NULL, 32, 1, '2015-11-05 23:46:41');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (47, 'Personal', NULL, 32, 1, '2015-11-05 23:46:50');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (48, 'Otros', NULL, NULL, 1, '2015-11-05 23:47:16');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (49, 'Maestro', NULL, 48, 1, '2015-11-05 23:47:34');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (50, 'Mantencion General', NULL, 48, 1, '2015-11-05 23:47:47');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (51, 'Supervisor Club Playa', NULL, 48, 1, '2015-11-05 23:48:03');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (52, 'Jefe Mantencion Condominio', NULL, 48, 1, '2015-11-05 23:48:23');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (53, 'Supervisor Laguna', NULL, 48, 1, '2015-11-05 23:48:35');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (54, 'Cuidador de Piscina', NULL, 48, 1, '2015-11-05 23:48:54');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (55, 'Encargado Planta de Agua', NULL, 48, 1, '2015-11-05 23:49:06');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (56, 'Aguatero', NULL, 48, 1, '2015-11-05 23:49:25');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (57, 'Hornero Jefe de Personal', NULL, 48, 1, '2015-11-05 23:49:38');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (58, 'Operaciones Junior', NULL, 48, 1, '2015-11-05 23:49:52');
INSERT INTO `gc_cargos` (`id`, `nombre`, `idcomunidad`, `idpadre`, `activo`, `updated_at`) VALUES (59, 'Guardia-Concerje', NULL, 48, 1, '2015-11-05 23:50:06');


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/validate_sueldo_minimo', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (104, 1);


/*********************************************************************************/

CREATE TABLE `gc_personal` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(11) UNSIGNED NOT NULL,
	`rut` INT(11) UNSIGNED NOT NULL,
	`dv` CHAR(1) NOT NULL,
	`nombre` VARCHAR(100) NOT NULL,
	`apaterno` VARCHAR(100) NOT NULL,
	`amaterno` VARCHAR(100) NOT NULL,
	`fecnacimiento` DATE NOT NULL,
	`sexo` ENUM('M','F') NOT NULL,
	`idecivil` INT(11) UNSIGNED NOT NULL,
	`nacionalidad` ENUM('C','E') NOT NULL,
	`direccion` VARCHAR(200) NOT NULL,
	`idregion` SMALLINT(6) NOT NULL,
	`idcomuna` SMALLINT(6) NOT NULL,
	`fono` VARCHAR(15) NOT NULL,
	`email` VARCHAR(50) NOT NULL,
	`fecingreso` DATE NOT NULL,
	`idcargo` INT(11) UNSIGNED NOT NULL,
	`tipocontrato` ENUM('F','I') NOT NULL,
	`parttime` TINYINT(4) NOT NULL,
	`diastrabajo` INT(11) NOT NULL,
	`horasdiarias` INT(11) NOT NULL,
	`horassemanales` INT(11) NOT NULL,
	`sueldobase` INT(11) NOT NULL,
	`cargassimples` INT(11) NOT NULL,
	`cargasinvalidas` INT(11) NOT NULL,
	`cargasmaternales` INT(11) NOT NULL,
	`cargasretroactivas` INT(11) NOT NULL,
	`movilizacion` INT(11) NOT NULL,
	`colacion` INT(11) NOT NULL,
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_personal_idecivil_estado_civil_id` (`idecivil`),
	INDEX `fk_personal_idcargo_cargo_id` (`idcargo`),
	CONSTRAINT `fk_personal_idcargo_cargo_id` FOREIGN KEY (`idcargo`) REFERENCES `gc_cargos` (`id`),
	CONSTRAINT `fk_personal_idecivil_estado_civil_id` FOREIGN KEY (`idecivil`) REFERENCES `gc_estado_civil` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;



INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_trabajador', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (105, 1);


/**********************************************************/
CREATE TABLE `gc_bonos_personal` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idpersonal` INT(10) UNSIGNED NOT NULL,
	`descripcion` VARCHAR(50) NOT NULL,
	`monto` INT(10) UNSIGNED NOT NULL,
	`fecha` DATE NOT NULL,
	`proporcional` TINYINT(4) NOT NULL,
	`imponible` TINYINT(4) NOT NULL,
	`fijo` TINYINT(4) NOT NULL,
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_bonos_personal_idpersonal_personal_id` (`idpersonal`),
	CONSTRAINT `fk_bonos_personal_idpersonal_personal_id` FOREIGN KEY (`idpersonal`) REFERENCES `gc_personal` (`id`)
)
ENGINE=InnoDB
;


/***********************************************************************/

CREATE TABLE `gc_afp` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL,
	`porc` DOUBLE NOT NULL,
	`exregimen` TINYINT(4) NOT NULL DEFAULT '0',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

ALTER TABLE `gc_afp`
	ADD COLUMN `active` TINYINT(4) NOT NULL DEFAULT '1' AFTER `exregimen`;

/*********************************************************************************/
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_personal_afp', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (106, 1);
ALTER TABLE `gc_personal`
	ADD COLUMN `idafp` INT(11) UNSIGNED NOT NULL AFTER `colacion`;

ALTER TABLE `gc_personal`
	ADD CONSTRAINT `fk_personal_idafp_afp_id` FOREIGN KEY (`idafp`) REFERENCES `gc_afp` (`id`);
ALTER TABLE `gc_personal`
	ADD COLUMN `adicafp` DOUBLE NOT NULL AFTER `idafp`;
ALTER TABLE `gc_personal`
	ADD COLUMN `tipoahorrovol` ENUM('pesos','porcentaje') NOT NULL AFTER `adicafp`;
ALTER TABLE `gc_personal`
	ADD COLUMN `ahorrovol` DOUBLE NOT NULL AFTER `tipoahorrovol`;

ALTER TABLE `gc_personal`
	ADD COLUMN `tipocotapv` ENUM('pesos','uf','porcentaje') NOT NULL AFTER `ahorrovol`;			
ALTER TABLE `gc_personal`
	ADD COLUMN `cotapv` DOUBLE NOT NULL AFTER `tipocotapv`;

ALTER TABLE `gc_personal`
	ALTER `idafp` DROP DEFAULT;
ALTER TABLE `gc_personal`
	CHANGE COLUMN `idafp` `idafp` INT(11) UNSIGNED NULL AFTER `colacion`;
ALTER TABLE `gc_personal`
	CHANGE COLUMN `adicafp` `adicafp` DOUBLE NULL DEFAULT NULL AFTER `idafp`;
ALTER TABLE `gc_personal`
	CHANGE COLUMN `tipoahorrovol` `tipoahorrovol` ENUM('pesos','porcentaje') NULL DEFAULT NULL AFTER `adicafp`;
ALTER TABLE `gc_personal`
	CHANGE COLUMN `ahorrovol` `ahorrovol` DOUBLE NULL DEFAULT NULL AFTER `tipoahorrovol`;

	/*****************EN SERVIDOR***********/	
ALTER TABLE `gc_personal`
	CHANGE COLUMN `tipocotapv` `tipocotapv` ENUM('pesos','uf','porcentaje') NULL DEFAULT NULL AFTER `ahorrovol`;				
ALTER TABLE `gc_personal`
	CHANGE COLUMN `cotapv` `cotapv` DOUBLE NULL DEFAULT NULL AFTER `tipocotapv`;

CREATE TABLE `gc_isapre` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL,
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


ALTER TABLE `gc_personal`
	ADD COLUMN `idisapre` INT UNSIGNED NULL DEFAULT NULL AFTER `cotapv`;

ALTER TABLE `gc_personal`
	ADD CONSTRAINT `fk_personal_idisapre_isapre_id` FOREIGN KEY (`idisapre`) REFERENCES `gc_isapre` (`id`);
ALTER TABLE `gc_personal`
	ADD COLUMN `valorpactado` DOUBLE UNSIGNED NULL DEFAULT NULL AFTER `idisapre`;
			
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_salud', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');			
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (107, 1);


/*************************************************************************************/
CREATE TABLE `gc_cajas_compensacion` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL,
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


CREATE TABLE `gc_mutual_seguridad` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL,
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_otros', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (108, 1);


ALTER TABLE `gc_comunidad`
	ADD COLUMN `idcaja` INT(11) NULL AFTER `fondoreserva`;

ALTER TABLE `gc_comunidad`
	ALTER `idcaja` DROP DEFAULT;
ALTER TABLE `gc_comunidad`
	CHANGE COLUMN `idcaja` `idcaja` INT(11) NULL AFTER `fondoreserva`,
	ADD COLUMN `idmutual` INT(11) NULL AFTER `idcaja`;

ALTER TABLE `gc_comunidad`
	CHANGE COLUMN `idcaja` `idcaja` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `fondoreserva`,
	ADD CONSTRAINT `fk_comunidad_idcaja_cajas_compensacion_id` FOREIGN KEY (`idcaja`) REFERENCES `gc_cajas_compensacion` (`id`);	
ALTER TABLE `gc_comunidad`
	CHANGE COLUMN `idmutual` `idmutual` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `idcaja`;

ALTER TABLE `gc_comunidad`
	ADD CONSTRAINT `fk_comunidad_idmutual_mutual_seguridad_id` FOREIGN KEY (`idmutual`) REFERENCES `gc_mutual_seguridad` (`id`);	


INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/asistencia', 'Asistencia', 8, 1, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (109, 1);



/***********************************************************************/
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_asistencia', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (110, 1);

CREATE TABLE `gc_remuneracion` (
	`idpersonal` INT(10) UNSIGNED NOT NULL,
	`idperiodo` INT(10) UNSIGNED NOT NULL,
	`diastrabajo` INT(11) NULL DEFAULT NULL,
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`idpersonal`, `idperiodo`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


CREATE TABLE `gc_periodo_remuneracion` (
	`idperiodo` INT(11) UNSIGNED NOT NULL,
	`idcomunidad` INT(11) UNSIGNED NOT NULL,
	`cierre` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`idperiodo`, `idcomunidad`),
	INDEX `fk_periodo_estado_idcomunidad_comunidad_id` (`idcomunidad`),
	CONSTRAINT `gc_periodo_remuneracion_ibfk_1` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`),
	CONSTRAINT `gc_periodo_remuneracion_ibfk_2` FOREIGN KEY (`idperiodo`) REFERENCES `gc_periodo` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/horas_descuentos', 'Horas de descuento', 8, 1, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (111, 1);


ALTER TABLE `gc_remuneracion`
	ADD COLUMN `horasdescuento` INT(11) NULL DEFAULT NULL AFTER `diastrabajo`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `montodescuento` INT(11) NULL DEFAULT NULL AFTER `horasdescuento`;	

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_horas_descuentos', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (112, 1);
/******************************************/

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `tiporecargo` INT(11) NULL DEFAULT NULL AFTER `montodescuento`,
	ADD COLUMN `horasextras` INT(11) NULL DEFAULT NULL AFTER `tiporecargo`,
	ADD COLUMN `montohorasextras` INT(11) NULL DEFAULT NULL AFTER `horasextras`;

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/horas_extraordinarias', 'Horas Extraordinarias', 8, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (113, 1);

ALTER TABLE `gc_remuneracion`
	CHANGE COLUMN `horasextras` `horasextras50` INT(11) NULL DEFAULT NULL AFTER `tiporecargo`,
	CHANGE COLUMN `montohorasextras` `montohorasextras50` INT(11) NULL DEFAULT NULL AFTER `horasextras50`;

ALTER TABLE `gc_remuneracion`
	DROP COLUMN `tiporecargo`;

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `horasextras100` INT(11) NULL DEFAULT NULL AFTER `montohorasextras50`,
	ADD COLUMN `montohorasextras100` INT(11) NULL DEFAULT NULL AFTER `horasextras100`;



INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_horas_extraordinarias', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');			
INSERT INTO `gc_role` (`appid`, `levelid`, `created_at`, `updated_at`) VALUES (114, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/anticipos', 'Anticipos', 8, 1, 1, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (115, 1);

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `anticipo` INT(11) NULL DEFAULT NULL AFTER `montohorasextras100`,
	ADD COLUMN `aguinaldo` INT(11) NULL DEFAULT NULL AFTER `anticipo`;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_anticipos', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (116, 1);


/**************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/calculo_remuneraciones', 'Calculo Remuneraciones', 8, 1, 1, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (117, 1);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_calculo_remuneraciones', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (118, 1);

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `sueldobase` INT(10) UNSIGNED NOT NULL AFTER `idperiodo`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `gratificacion` INT(11) NULL DEFAULT NULL AFTER `aguinaldo`;	
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `movilizacion` INT(10) UNSIGNED NOT NULL AFTER `sueldobase`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `totalhaberes` INT(11) NULL DEFAULT NULL AFTER `gratificacion`;		
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `sueldoimponible` INT(11) NULL DEFAULT NULL AFTER `totalhaberes`;	
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `cotizacionobligatoria` INT(11) NULL DEFAULT NULL AFTER `sueldoimponible`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `comisionafp` INT(11) NULL DEFAULT NULL AFTER `cotizacionobligatoria`;	
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `adicafp` INT(11) NULL DEFAULT NULL AFTER `comisionafp`;			
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `cotizacionsalud` INT(11) NULL DEFAULT NULL AFTER `adicafp`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `fonasa` INT(11) NULL DEFAULT NULL AFTER `cotizacionsalud`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `inp` INT(11) NULL DEFAULT NULL AFTER `fonasa`;			
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `basetributaria` INT(11) NULL DEFAULT NULL AFTER `inp`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `porcadicafp` DOUBLE NULL DEFAULT NULL AFTER `cotizacionobligatoria`;		
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `porccomafp` DOUBLE NULL DEFAULT NULL AFTER `cotizacionobligatoria`;	
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `ufperiodo` INT(10) UNSIGNED NOT NULL AFTER `idperiodo`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `ufcotsalud` DOUBLE NULL DEFAULT NULL AFTER `inp`;
ALTER TABLE `gc_remuneracion`
	CHANGE COLUMN `ufcotsalud` `valorpactado` DOUBLE NULL DEFAULT NULL AFTER `inp`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `valorhorasextras50` INT(11) NULL DEFAULT NULL AFTER `montodescuento`;				
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `valorhorasextras100` INT(11) NULL DEFAULT NULL AFTER `montohorasextras50`;	


update gc_tipo_deuda_detalle set idcomunidad = null where idcomunidad = 4 and idtipodeuda = 1;

#INSERT INTO `gc_tipo_deuda_detalle` (`id`, `idtipodeuda`, `nombre`, `idpadre`, `activo`) VALUES (52,1, 'Sueldos', 27, 1);	

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/periodos', 'Detalle Remuneraciones', 8, 1, 1, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (119, 1);

ALTER TABLE `gc_personal`
	ADD CONSTRAINT `fk_personal_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/ver_remuneraciones_periodo', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (120, 1);


ALTER TABLE `gc_personal`
	CHANGE COLUMN `sueldobase` `sueldobase` BIGINT NOT NULL AFTER `horassemanales`;
ALTER TABLE `gc_remuneracion`
	CHANGE COLUMN `sueldobase` `sueldobase` BIGINT UNSIGNED NOT NULL AFTER `ufperiodo`;

ALTER TABLE `gc_personal`
	ADD COLUMN `segcesantia` TINYINT(4) NOT NULL AFTER `parttime`;

ALTER TABLE `gc_personal`
	ADD COLUMN `tipogratificacion` ENUM('SG','TL','MF') NOT NULL AFTER `sueldobase`;

ALTER TABLE `gc_personal`
	ADD COLUMN `gratificacion` BIGINT(20) NOT NULL AFTER `tipogratificacion`;		

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `segcesantia` INT(11) NULL DEFAULT NULL AFTER `adicafp`;

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `tipogratificacion` ENUM('SG','TL','MF') NULL DEFAULT NULL AFTER `aguinaldo`;	

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `adicisapre` INT(11) NULL DEFAULT NULL AFTER `valorpactado`;	

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `tipoahorrovol` ENUM('pesos','porcentaje') NULL DEFAULT NULL AFTER `adicisapre`;

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `ahorrovol` DOUBLE NULL DEFAULT NULL AFTER `tipoahorrovol`;		
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `montoahorrovol` INT NULL DEFAULT NULL AFTER `ahorrovol`;	

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `tipocotapv` ENUM('pesos','uf','porcentaje') NULL DEFAULT NULL AFTER `montoahorrovol`;

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `cotapv` DOUBLE NULL DEFAULT NULL AFTER `tipocotapv`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `montocotapv` INT NULL DEFAULT NULL AFTER `cotapv`;			
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `valorhora` INT(11) NULL DEFAULT NULL AFTER `horasdescuento`;	
ALTER TABLE `gc_remuneracion`
	CHANGE COLUMN `valorhora` `valorhora` INT(11) NULL DEFAULT NULL AFTER `diastrabajo`;	

CREATE TABLE `gc_tabla_impuesto` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`desde` INT(11) NULL DEFAULT NULL,
	`hasta` INT(11) NULL DEFAULT NULL,
	`factor` DOUBLE NULL DEFAULT NULL,
	`rebaja` INT(11) NULL DEFAULT NULL,
	`tasa_maxima` DOUBLE NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=9
;
INSERT INTO `gc_tabla_impuesto` (`id`, `desde`, `hasta`, `factor`, `rebaja`, `tasa_maxima`) VALUES (1, 0, 587237, 0, 0, 0);
INSERT INTO `gc_tabla_impuesto` (`id`, `desde`, `hasta`, `factor`, `rebaja`, `tasa_maxima`) VALUES (2, 587238, 1304970, 0.04, 23489.46, 2.2);
INSERT INTO `gc_tabla_impuesto` (`id`, `desde`, `hasta`, `factor`, `rebaja`, `tasa_maxima`) VALUES (3, 1304971, 2174950, 0.08, 75688.26, 4.52);
INSERT INTO `gc_tabla_impuesto` (`id`, `desde`, `hasta`, `factor`, `rebaja`, `tasa_maxima`) VALUES (4, 2174951, 3044930, 0.135, 195311.51, 7.09);
INSERT INTO `gc_tabla_impuesto` (`id`, `desde`, `hasta`, `factor`, `rebaja`, `tasa_maxima`) VALUES (5, 3044931, 3914910, 0.23, 484579.86, 10.62);
INSERT INTO `gc_tabla_impuesto` (`id`, `desde`, `hasta`, `factor`, `rebaja`, `tasa_maxima`) VALUES (6, 3914911, 5219880, 0.304, 774282.2, 15.57);
INSERT INTO `gc_tabla_impuesto` (`id`, `desde`, `hasta`, `factor`, `rebaja`, `tasa_maxima`) VALUES (7, 5219881, 6524850, 0.355, 1040496.08, 19.55);
INSERT INTO `gc_tabla_impuesto` (`id`, `desde`, `hasta`, `factor`, `rebaja`, `tasa_maxima`) VALUES (8, 6524850, 999999999, 0.4, 1334114.33, 19.55);



ALTER TABLE `gc_remuneracion`
	ADD COLUMN `impuesto` INT(11) NULL DEFAULT NULL AFTER `adicisapre`;	

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `bonosimponibles` INT(10) UNSIGNED NOT NULL AFTER `movilizacion`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `bonosnoimponibles` INT(10) UNSIGNED NOT NULL AFTER `bonosimponibles`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
	DROP PRIMARY KEY,
	ADD PRIMARY KEY (`id`),
	ADD CONSTRAINT `fk_remuneracion_idpersonal_personal_id` FOREIGN KEY (`idpersonal`) REFERENCES `gc_personal` (`id`);			

ALTER TABLE `gc_remuneracion`
	ADD CONSTRAINT `fk_remuneracion_idperiodo_periodo_id` FOREIGN KEY (`idperiodo`) REFERENCES `gc_periodo` (`id`);	

CREATE TABLE `gc_bonos_remuneracion` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idremuneracion` INT(10) UNSIGNED NOT NULL,
	`descripcion` VARCHAR(50) NULL DEFAULT NULL,
	`imponible` TINYINT(4) NULL DEFAULT NULL,
	`monto` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `fk_bonos_remuneracion_idremuneracion_remuneracion_id` (`idremuneracion`),
	CONSTRAINT `fk_bonos_remuneracion_idremuneracion_remuneracion_id` FOREIGN KEY (`idremuneracion`) REFERENCES `gc_remuneracion` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/liquidacion', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`, `created_at`, `updated_at`) VALUES (121, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `sueldoliquido` INT(11) NULL DEFAULT NULL AFTER `basetributaria`;	

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `pdf_content` TEXT NULL DEFAULT NULL AFTER `sueldoliquido`;

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `colacion` INT(10) UNSIGNED NOT NULL AFTER `movilizacion`;	

ALTER TABLE `gc_personal`
	ADD COLUMN `asigfamiliar` BIGINT(20) NOT NULL AFTER `gratificacion`;	

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `asigfamiliar` INT(11) NULL DEFAULT NULL AFTER `gratificacion`;	

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `seginvalidez` INT(11) NULL DEFAULT NULL AFTER `sueldoliquido`;	

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `aportesegcesantia` INT(11) NULL DEFAULT NULL AFTER `seginvalidez`;

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `aportepatronal` INT(11) NULL DEFAULT NULL AFTER `aportesegcesantia`;		

ALTER TABLE `gc_comunidad`
	ADD COLUMN `porcmutual` DOUBLE UNSIGNED NULL DEFAULT NULL AFTER `idmutual`;


ALTER TABLE `gc_periodo_estado`
	ADD COLUMN `pdf_content` TEXT NULL DEFAULT NULL AFTER `publica`;	

#INSERT INTO `gc_tipo_deuda_detalle` (`id`, `idtipodeuda`, `nombre`, `idpadre`, `activo`) VALUES (52,1, 'Sueldos', 27, 1);

#INSERT INTO `gc_tipo_deuda_detalle` (`id`, `idtipodeuda`, `nombre`, `idpadre`, `activo`, `updated_at`) VALUES (53,1, 'Anticipo', 27, 1, '0000-00-00 00:00:00');

#INSERT INTO `gc_tipo_deuda_detalle` (`id`, `idtipodeuda`, `nombre`, `idpadre`, `activo`, `updated_at`) VALUES (54,1, 'Aguinaldo', 27, 1, '0000-00-00 00:00:00');

ALTER TABLE `gc_tipo_documento_tributario`
	ADD COLUMN `active` TINYINT NULL DEFAULT NULL AFTER `nombre`;

update gc_tipo_documento_tributario set active = 1;
INSERT INTO `gc_tipo_documento_tributario` (`nombre`, `active`) VALUES ('Remuneraciones', 0);	

INSERT INTO `gc_tipo_documento_tributario` (`nombre`, `active`) VALUES ('Anticipos', 0);

INSERT INTO `gc_tipo_documento_tributario` (`nombre`, `active`) VALUES ('Aguinaldos', 0);
INSERT INTO `gc_tipo_documento_tributario` (`nombre`, `active`) VALUES ('Prevision', 0);

#INSERT INTO `gc_tipo_deuda_detalle` (`id`,`idtipodeuda`, `nombre`, `idpadre`, `activo`, `updated_at`) VALUES (55,1, 'Pagos Previsionales', 27, 1, '0000-00-00 00:00:00');
UPDATE `gc_tipo_deuda_detalle` SET `nombre`='Leyes Sociales' WHERE  `id`=55;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/liquidaciones', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (122, 1);

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/ver_parametros', 'Parametros', 8, 1, 1, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (123, 1);

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/impuesto_unico', 'Impuesto &Uacute;nico', 8, 1, 1, 9, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (124, 4);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_impuesto_unico', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (125, 4);

UPDATE `gc_app` SET `orden`=9 WHERE  `id`=123;
UPDATE `gc_app` SET `orden`=10 WHERE  `id`=124;


INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/afp', 'AFP', 8, 1, 1, 11, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (126, 4);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/add_afp', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (127, 4);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_afp', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (128, 4);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/delete_afp', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (129, 4);

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/isapres', 'Isapres', 8, 1, 1, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (130, 4);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/add_isapre', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (131, 4);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_isapre', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (132, 4);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/delete_isapre', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (133, 4);

ALTER TABLE `gc_remuneracion`
	CHANGE COLUMN `adicisapre` `adicisapre` INT(11) NULL DEFAULT NULL AFTER `valorpactado`,
	ADD COLUMN `cotadicisapre` INT(11) NULL DEFAULT NULL AFTER `adicisapre`,
	ADD COLUMN `adicsalud` INT(11) NULL DEFAULT NULL AFTER `cotadicisapre`;

UPDATE `gc_app` SET `menuid`=5 WHERE  `id`=119;	
UPDATE `gc_app` SET `name`='Parametros Remuneraciones', `menuid`=5 WHERE  `id`=123;

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/admin_cargos', 'Cargos Personal', 1, 1, 1, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (134, 1);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/add_cargos', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (135, 1);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/submit_cargos', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (136, 1);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/delete_cargos', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (137, 1);


CREATE TABLE `gc_log_personal` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idpersonal` INT(11) UNSIGNED NULL DEFAULT NULL,
	`observacion` TEXT NULL,
	`active` TINYINT(4) NULL DEFAULT NULL,
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_log_personal_idpersonal_personal_id` (`idpersonal`),
	CONSTRAINT `fk_log_personal_idpersonal_personal_id` FOREIGN KEY (`idpersonal`) REFERENCES `gc_personal` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


ALTER TABLE `gc_remuneracion`
	ADD COLUMN `active` TINYINT NULL AFTER `pdf_content`;
ALTER TABLE `gc_remuneracion`
	CHANGE COLUMN `active` `active` TINYINT(4) NULL DEFAULT '0' AFTER `pdf_content`;	


/*****************************/

	ALTER TABLE `gc_bonos_personal`
	COMMENT='son los bonos registrados';

ALTER TABLE `gc_bonos_remuneracion`
	COMMENT='son los bonos que aplican en una remuneracion';	



	/******************************************************/
CREATE TABLE IF NOT EXISTS `gc_tabla_asig_familiar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desde` int(11) DEFAULT NULL,
  `hasta` int(11) DEFAULT NULL,
  `monto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

INSERT INTO `gc_tabla_asig_familiar` (`id`, `desde`, `hasta`, `monto`) VALUES
	(1, 0, 262326, 10269),
	(2, 262327, 383156, 6302),
	(3, 383157, 597593, 1992),
	(4, 597594, 999999999, 0);

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/asignacion_familiar', 'Asignacion Familiar', 8, 1, 1, 11, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
UPDATE `gc_app` SET `orden`=12 WHERE  `id`=126;
UPDATE `gc_app` SET `orden`=13 WHERE  `id`=130;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (138, 4);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_asignacion_familiar', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (139, 4);


/******************************/

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `sueldonoimponible` INT(11) NULL DEFAULT NULL AFTER `sueldoimponible`;

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `totalleyessociales` INT(11) NULL DEFAULT NULL AFTER `montocotapv`,
	ADD COLUMN `otrosdescuentos` INT(11) NULL DEFAULT NULL AFTER `totalleyessociales`;	


/*********************************************************************/
ALTER TABLE `gc_remuneracion`
	CHANGE COLUMN `horasextras50` `horasextras50` DOUBLE NULL DEFAULT NULL AFTER `valorhorasextras50`,
	CHANGE COLUMN `horasextras100` `horasextras100` DOUBLE NULL DEFAULT NULL AFTER `valorhorasextras100`;

INSERT INTO `gc_afp` (`nombre`, `porc`, `exregimen`, `active`) VALUES ('Pensionado', 0, 2, 1);
INSERT INTO `gc_afp` (`nombre`, `porc`, `exregimen`, `active`) VALUES ('No Cotiza', 0, 2, 1);


/***********************************************************************/
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `montocargaretroactiva` INT(11) NULL DEFAULT NULL AFTER `gratificacion`;
ALTER TABLE `gc_remuneracion`
	ADD COLUMN `cargasretroactivas` INT(11) NULL DEFAULT NULL AFTER `gratificacion`;	


/**********************************************/
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (50, 3);	

/************************************************/
ALTER TABLE `gc_periodo_remuneracion`
	ADD COLUMN `aprueba` DATETIME NULL DEFAULT NULL AFTER `cierre`;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/aprueba_remuneraciones', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (140, 1);


ALTER TABLE `gc_cuenta`
	ADD COLUMN `idperiodoremuneracion` INT NULL DEFAULT NULL AFTER `descripcion`;

ALTER TABLE `gc_cuenta`
	CHANGE COLUMN `idperiodoremuneracion` `idperiodoremuneracion` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `descripcion`,
	ADD CONSTRAINT `fk_cuenta_idperiodoremuneracion_periodo_remuneracion_idperiodo` FOREIGN KEY (`idperiodoremuneracion`) REFERENCES `gc_periodo` (`id`);	

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/rechaza_remuneraciones', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (141, 1);	


/***************************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/descuentos', 'Descuentos/Prestamos', 8, 1, 1, 14, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (142, 1);

UPDATE `gc_app` SET `orden`=14 WHERE  `id`=117;
UPDATE `gc_app` SET `orden`=7 WHERE  `id`=142;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/add_descuento', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (143, 1);


CREATE TABLE `gc_tipo_descuento` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL,
	`tipo` ENUM('P','D') NOT NULL COMMENT 'P: Prestamo. D: Descuento',
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;

INSERT INTO `gc_tipo_descuento` (`id`, `nombre`, `tipo`, `active`, `created_at`, `updated_at`) VALUES (1, 'Comunidad', 'P', 1, '2016-02-28 22:21:39', '2016-02-28 22:21:40');
INSERT INTO `gc_tipo_descuento` (`id`, `nombre`, `tipo`, `active`, `created_at`, `updated_at`) VALUES (2, 'Caja de Compensación', 'P', 1, '2016-02-28 22:21:39', '2016-02-28 22:22:23');
INSERT INTO `gc_tipo_descuento` (`id`, `nombre`, `tipo`, `active`, `created_at`, `updated_at`) VALUES (3, 'Otros Descuentos', 'D', 1, '2016-02-28 22:21:39', '2016-02-28 22:22:23');



CREATE TABLE `gc_descuentos_personal` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`tipodescuento` INT(11) UNSIGNED NULL DEFAULT NULL,
	`idpersonal` INT(11) UNSIGNED NOT NULL,
	`idperiodo` INT(11) UNSIGNED NOT NULL,
	`monto` INT(11) NULL DEFAULT NULL,
	`descripcion` VARCHAR(100) NULL DEFAULT NULL,
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_descuentos_personal_tipodescuento_tipo_descuento_id` (`tipodescuento`),
	CONSTRAINT `fk_descuentos_personal_tipodescuento_tipo_descuento_id` FOREIGN KEY (`tipodescuento`) REFERENCES `gc_tipo_descuento` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_descuento', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (144, 1);


ALTER TABLE `gc_remuneracion`
	ADD COLUMN `descuentos` INT(11) NULL DEFAULT NULL AFTER `aguinaldo`,
	ADD COLUMN `prestamos` INT(11) NULL DEFAULT NULL AFTER `descuentos`;

/**************************************************************/
INSERT INTO `gc_tipo_deuda_detalle` (`idtipodeuda`, `nombre`, `idpadre`, `activo`) VALUES (1, 'Descuento', 27, 1);
INSERT INTO `gc_tipo_documento_tributario` (`id`, `nombre`, `active`, `updated_at`) VALUES (12, 'Descuentos', 0, '2016-02-29 14:08:43');


/*******************************************************************/
ALTER TABLE `gc_personal`
	ADD COLUMN `fecafc` DATE NOT NULL AFTER `segcesantia`;
ALTER TABLE `gc_personal`
	CHANGE COLUMN `fecafc` `fecafc` DATE NULL AFTER `segcesantia`;	
update gc_personal set fecafc = fecingreso where segcesantia = 1 and fecafc = '0000-00-00';


/**************************************************************************/

ALTER TABLE `gc_propiedad`
	ADD COLUMN `direccion` VARCHAR(100) NULL DEFAULT NULL AFTER `numero`;

		
/****************************************************************************/
ALTER TABLE `gc_cartola_pagos`
	ADD COLUMN `idlistado` INT(11) NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `gc_cartola_pagos`
	CHANGE COLUMN `idlistado` `idlistado` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `id`;

CREATE TABLE `gc_listado_pagos` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`monto` INT(11) NOT NULL DEFAULT '0',
	`fechapago` DATE NOT NULL,
	`idformapago` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`cheque` INT(11) UNSIGNED NULL DEFAULT NULL,
	`pdf_content` TEXT NULL,
	`activo` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_cartola_pagos_idformapago_forma_pago_id` (`idformapago`),
	CONSTRAINT `gc_listado_pagos_ibfk_3` FOREIGN KEY (`idformapago`) REFERENCES `gc_forma_pago` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


insert into gc_listado_pagos (monto, fechapago, idformapago, cheque, created_at) 
select sum(monto), fechapago, idformapago,cheque, created_at from gc_cartola_pagos
group by created_at;


update gc_cartola_pagos cp
inner join gc_listado_pagos lp on cp.created_at = lp.created_at
set cp.idlistado = lp.id;

ALTER TABLE `gc_cartola_pagos`
	ADD CONSTRAINT `fk_cartola_pagos_idlistado_listado_pagos_id` FOREIGN KEY (`idlistado`) REFERENCES `gc_listado_pagos` (`id`);	

ALTER TABLE `gc_cartola_propiedad`
	ADD COLUMN `adicional` INT(11) NOT NULL DEFAULT '0' COMMENT 'en caso de pagar un monto mayor a la deuda, aqui se guarda el monto adicional' AFTER `monto`;	




/*********************************************************************************/

ALTER TABLE `gc_listado_pagos`
	ADD COLUMN `paguesea` VARCHAR(200) NULL DEFAULT NULL AFTER `cheque`;

ALTER TABLE `gc_cartola_pagos`
	ADD COLUMN `updated_at` DATETIME NOT NULL AFTER `created_at`;	
ALTER TABLE `gc_cartola_pagos`
	CHANGE COLUMN `updated_at` `updated_at` DATETIME NULL AFTER `created_at`;	
ALTER TABLE `gc_cartola_caja`
	ADD COLUMN `protesto` TINYINT NOT NULL DEFAULT '0' AFTER `fechaconciliacion`;	

/*************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/download_egreso', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (145, 1);

update gc_listado_pagos set paguesea = '' where paguesea is null;

	/************************************************/


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/previred', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (146, 1);
ALTER TABLE `gc_periodo_remuneracion`
	ADD COLUMN `previred_content` TEXT NULL DEFAULT NULL AFTER `aprueba`;
ALTER TABLE `gc_afp`
	ADD COLUMN `codprevired` CHAR(2) NOT NULL DEFAULT '00' AFTER `exregimen`;
UPDATE `gc_afp` SET `codprevired`='5' WHERE  `id`=1;
UPDATE `gc_afp` SET `codprevired`='8' WHERE  `id`=2;
UPDATE `gc_afp` SET `codprevired`='33' WHERE  `id`=3;
UPDATE `gc_afp` SET `codprevired`='3' WHERE  `id`=4;
UPDATE `gc_afp` SET `codprevired`='34' WHERE  `id`=5;
UPDATE `gc_afp` SET `codprevired`='29' WHERE  `id`=6;


ALTER TABLE `gc_afp`
	CHANGE COLUMN `codprevired` `codprevired` INT NOT NULL DEFAULT '00' AFTER `exregimen`;



/******************************************************************************/

ALTER TABLE `gc_tabla_asig_familiar`
	ADD COLUMN `tramo` CHAR(1) NOT NULL AFTER `id`;
UPDATE `gc_tabla_asig_familiar` SET `tramo`='A' WHERE  `id`=1;
UPDATE `gc_tabla_asig_familiar` SET `tramo`='B' WHERE  `id`=2;
UPDATE `gc_tabla_asig_familiar` SET `tramo`='C' WHERE  `id`=3;
UPDATE `gc_tabla_asig_familiar` SET `tramo`='D' WHERE  `id`=4;


ALTER TABLE `gc_personal`
	ADD COLUMN `idasigfamiliar` INT(11) NULL AFTER `cargasretroactivas`;

UPDATE gc_personal set idasigfamiliar = null
ALTER TABLE `gc_personal`
	ADD CONSTRAINT `fk_personal_idasigfamiliar_asig_familiar_id` FOREIGN KEY (`idasigfamiliar`) REFERENCES `gc_tabla_asig_familiar` (`id`);		

/***************************************************************/

UPDATE `gc_afp` SET `active`=0 WHERE  `id`=7;
UPDATE `gc_afp` SET `nombre`='No Cotiza (Pensionado)' WHERE  `id`=8;


/**************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/delete_cuenta', 5, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (147, 1);



/***************************************************************/
UPDATE `gc_app` SET `name`='Editar Cuentas' WHERE  `id`=74;

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('accounts/editar_individual', 'Editar Cuentas Individuales', 4, 1, 1, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (148, 1);
UPDATE `gc_app` SET `orden`=7 WHERE  `id`=148;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/delete_cuenta_individual', 5, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (149, 1);



/********************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('comunity/reversar_ggcc', 2, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (150, 1);

/**********************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/desautoriza_cuenta', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (151, 1);

/******************************************************************************/
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (119, 2);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (120, 2);
UPDATE `gc_app` SET `menuid`=5 WHERE  `id`=120;
UPDATE `gc_app` SET `menuid`=5 WHERE  `id`=121;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (121, 2);
UPDATE `gc_app` SET `menuid`=5 WHERE  `id`=122;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (122, 2);
UPDATE `gc_app` SET `menuid`=5 WHERE  `id`=146;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (146, 2);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (123, 2);


/*****************************************************************************/
ALTER TABLE `gc_cartola_propiedad`
	ADD COLUMN `updated_at` DATETIME NOT NULL AFTER `created_at`;


/*******************************************************************************/

UPDATE `gc_app` SET `orden`=8 WHERE  `id`=148;	
UPDATE `gc_app` SET `orden`=7 WHERE  `id`=74;
UPDATE `gc_app` SET `orden`=6 WHERE  `id`=17;
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('accounts/add_ingreso_comunidad', 'Ingresos Comunidad', 4, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (152, 1);

INSERT INTO `gc_tipo_deuda` (`id`, `nombre`) VALUES (13, 'Ingresos Comunidad');
INSERT INTO `gc_tipo_deuda_detalle` (`idtipodeuda`, `nombre`, `activo`) VALUES (13, 'Arriendo Espacios Comunes', 1);
INSERT INTO `gc_tipo_deuda_detalle` (`idtipodeuda`, `nombre`, `activo`, `updated_at`) VALUES (13, 'Ingresos Caja de Compensación', 1, '0000-00-00 00:00:00');
INSERT INTO `gc_tipo_deuda_detalle` (`idtipodeuda`, `nombre`, `activo`, `updated_at`) VALUES (13, 'Otros Ingresos', 1, '0000-00-00 00:00:00');

CREATE TABLE `gc_ingresos` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(11) UNSIGNED NOT NULL,
	`idggcc` INT(11) NULL DEFAULT NULL,
	`tipoingreso` ENUM('cc','fr') NOT NULL COMMENT 'cc: cuenta corriente, fr: fondo reserva',
	`idproveedor` INT(11) UNSIGNED NULL DEFAULT NULL,
	`idtipodoctrib` INT(11) UNSIGNED NULL DEFAULT NULL,
	`nrodocumento` INT(11) UNSIGNED NULL DEFAULT NULL,
	`fecdocumento` DATE NULL DEFAULT NULL,
	`idtipodeudadetalle` INT(11) UNSIGNED NULL DEFAULT NULL,
	`monto` INT(11) NOT NULL DEFAULT '0',
	`fecvencimiento` DATE NULL DEFAULT NULL,
	`descripcion` VARCHAR(100) NULL DEFAULT NULL,
	`nombrearchivo` VARCHAR(100) NULL DEFAULT NULL,
	`nombrerealarchivo` VARCHAR(100) NULL DEFAULT NULL,
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_deuda_ggcc_comunidad_id` (`idggcc`),
	INDEX `fk_cuenta_idproveedor_proveedor_id` (`idproveedor`),
	INDEX `fk_deuda_idtipodeudadetalle_tipo_deuda_detalle_id` (`idtipodeudadetalle`),
	INDEX `fk_cuenta_idtipodoctrib_tipo_documento_tributario_id` (`idtipodoctrib`),
	INDEX `fk_cuenta_idcomunidad_comunidad_id` (`idcomunidad`),
	CONSTRAINT `gc_ingresos_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`),
	CONSTRAINT `gc_ingresos_idggcc_ggcc_comunidad_id` FOREIGN KEY (`idggcc`) REFERENCES `gc_ggcc_comunidad` (`id`),
	CONSTRAINT `gc_ingresos_idproveedor_proveedor_id` FOREIGN KEY (`idproveedor`) REFERENCES `gc_proveedor` (`id`),
	CONSTRAINT `gc_ingresos_idtipodeudadetalle_deuda_detalle_id` FOREIGN KEY (`idtipodeudadetalle`) REFERENCES `gc_tipo_deuda_detalle` (`id`),
	CONSTRAINT `gc_ingresos_idtipodoctrib_tipo_doc_tributario_id` FOREIGN KEY (`idtipodoctrib`) REFERENCES `gc_tipo_documento_tributario` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


ALTER TABLE `gc_cartola_caja`
	ADD COLUMN `idingreso` INT(11) NULL DEFAULT NULL AFTER `idabono`;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/validate_ingreso', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (153, 1);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/submit_ingreso', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (154, 1);

ALTER TABLE `gc_cartola_caja`
	CHANGE COLUMN `idingreso` `idingreso` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `idabono`,
	ADD CONSTRAINT `fk_cartola_caja_idingreso_ingresos_id` FOREIGN KEY (`idingreso`) REFERENCES `gc_ingresos` (`id`);


ALTER TABLE `gc_cartola_fondo_reserva`
	ADD COLUMN `idingreso` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `idcuenta`,
	ADD CONSTRAINT `gc_cartola_fondo_reserva_ingreso_id` FOREIGN KEY (`idingreso`) REFERENCES `gc_ingresos` (`id`);	


/*************************************************************************/
update gc_remuneracion set pdf_content = null where idpersonal = 19;
update gc_remuneracion set pdf_content = null where idpersonal = 20;	

/********************************************************************/

ALTER TABLE `gc_cartola_propiedad`
	CHANGE COLUMN `idperiodo` `idperiodo` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `idpropiedad`;	
INSERT INTO `gc_cartola_propiedad` (`idpropiedad`, `idperiodo`, `fechapago`, `idformapago`, `idbanco`, `ruttitular`, `fechadeposito`, `monto`, `created_at`, `updated_at`) VALUES (1244, NULL, '2016-03-28', 1, 6, 0, '0000-00-00', 300000, '2016-03-07 18:09:32', '0000-00-00 00:00:00');	
INSERT INTO `gc_cartola_caja` (`idcomunidad`, `idabono`, `glosa`, `monto`, `saldo`, `fechapago`, `created_at`, `updated_at`) VALUES (17, 377, 'Abono GC de Propiedad # I08.  ', 300000, 385324, '2016-03-28', '2016-03-07 12:09:31', '2016-03-14 02:00:44');

update gc_propiedad set 
														saldo = saldo - 300000,
														saldo_publicado = saldo_publicado - 300000
														where id = 1244;

update gc_comunidad set 
														caja = caja + 300000
														where id = 17;														


/***********************************************************************************/
#CIGNA????
#Fundaci�n Ltda.
#Linksalud
#Normedica
#Promepart
#Vida Plena

ALTER TABLE `gc_isapre`
	ADD COLUMN `codprevired` INT(11) NOT NULL DEFAULT '1' AFTER `active`;
ALTER TABLE `gc_isapre`
	CHANGE COLUMN `codprevired` `codprevired` INT(11) NOT NULL AFTER `active`;
UPDATE `gc_isapre` SET `codprevired`=7 WHERE  `id`=1;	
UPDATE `gc_isapre` SET `codprevired`=9 WHERE  `id`=3;
UPDATE `gc_isapre` SET `codprevired`=4 WHERE  `id`=5;
UPDATE `gc_isapre` SET `codprevired`=2 WHERE  `id`=6;
UPDATE `gc_isapre` SET `codprevired`=5 WHERE  `id`=7;
UPDATE `gc_isapre` SET `codprevired`=25 WHERE  `id`=8;
UPDATE `gc_isapre` SET `codprevired`=10 WHERE  `id`=9;
UPDATE `gc_isapre` SET `codprevired`=11 WHERE  `id`=11;
UPDATE `gc_isapre` SET `codprevired`=17 WHERE  `id`=12;
UPDATE `gc_isapre` SET `codprevired`=20 WHERE  `id`=13;
UPDATE `gc_isapre` SET `codprevired`=21 WHERE  `id`=14;
UPDATE `gc_isapre` SET `codprevired`=3 WHERE  `id`=15;
UPDATE `gc_isapre` SET `codprevired`=12 WHERE  `id`=16;




/************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('reports/ver_ingreso', 5, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (155, 1);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (155, 2);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (155, 3);


/**********************************************************************************************/

INSERT INTO `gc_tipo_documento_tributario` (`id`, `nombre`, `active`) VALUES (13, 'Otro Documento', 1);

/***************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/conciliar_movimiento', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (156, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/submit_conciliacion_movimiento', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (157, 1);


/********************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/ver_descuento', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (158, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/edit_descuento', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (159, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_edit_descuento', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (160, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/delete_descuento', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (161, 1);


/*********************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('payments/comprobantes', 2, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (162, 1);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('payments/comprobante_detalle_ggcc', 2, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (163, 1);


/**************************************************************************************************/

INSERT INTO `gc_banco` (`id`, `nombre`, `activo`, `updated_at`) VALUES (21, 'Depósito Otro Banco', 1, '2016-04-07 17:28:10');


/*****************************************************************************************************/

#1.- en conciliación, para pagos de cuenta, poner igual que en el comprobante, si existe "Paguese a" poner ese nombre, sino el detalle

CREATE TABLE `gc_listado_abonos` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idpropiedad` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`monto` INT(11) NOT NULL DEFAULT '0',
	`fechapago` DATE NOT NULL,
	`idformapago` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`idbanco` INT(11) UNSIGNED NULL DEFAULT NULL,
	`cheque` INT(11) UNSIGNED NULL DEFAULT NULL,
	`ruttitular` INT(11) UNSIGNED NULL DEFAULT NULL,
	`dvtitular` CHAR(1) NULL DEFAULT NULL,
	`fechadeposito` DATE NOT NULL,
	`observacion` TEXT NOT NULL,
	`nombrearchivo` VARCHAR(100) NOT NULL,
	`nombrerealarchivo` VARCHAR(100) NOT NULL,
	`activo` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_cartola_pagos_idformapago_forma_pago_id` (`idformapago`),
	INDEX `gc_listado_abonos_idbanco_banco_id` (`idbanco`),
	INDEX `fk_listado_abonos_idpropiedad_propiedad_id` (`idpropiedad`),
	CONSTRAINT `fk_listado_abonos_idpropiedad_propiedad_id` FOREIGN KEY (`idpropiedad`) REFERENCES `gc_propiedad` (`id`),
	CONSTRAINT `gc_listado_abonos_ibfk_1` FOREIGN KEY (`idformapago`) REFERENCES `gc_forma_pago` (`id`),
	CONSTRAINT `gc_listado_abonos_idbanco_banco_id` FOREIGN KEY (`idbanco`) REFERENCES `gc_banco` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


ALTER TABLE `gc_cartola_propiedad`
	ADD COLUMN `idlistado` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;


insert into gc_listado_abonos (idpropiedad,monto,fechapago, idformapago, idbanco, cheque, ruttitular, dvtitular, fechadeposito, created_at) 
select idpropiedad, sum(monto), fechapago, idformapago, idbanco, cheque, ruttitular, dvtitular, fechadeposito,  created_at from gc_cartola_propiedad 
where monto > 0 and activo = 1
group by created_at;

#insert into gc_listado_abonos (monto,fechapago, idformapago, idbanco, cheque, ruttitular, dvtitular, fechadeposito, created_at) 
#select sum(monto), fechapago, idformapago, idbanco, cheque, ruttitular, dvtitular, fechadeposito,  created_at from gc_cartola_propiedad 
#where monto > 0 and activo = 1
#group by created_at;

update gc_cartola_propiedad cp
inner join gc_listado_abonos la on cp.created_at = la.created_at
set cp.idlistado = la.id;	

update gc_listado_abonos la
inner join gc_cartola_propiedad cp on la.id = cp.idlistado
set la.monto = la.monto + cp.adicional
where cp.adicional > 0


update gc_cartola_propiedad set idlistado = 1
where idlistado = 0;


ALTER TABLE `gc_cartola_propiedad`
	ADD CONSTRAINT `fk_cartola_propiedad_idlistado_listado_abonos_id` FOREIGN KEY (`idlistado`) REFERENCES `gc_listado_abonos` (`id`);


update gc_listado_abonos la
inner join gc_cartola_propiedad cp on la.id = cp.idlistado
set la.idpropiedad = cp.idpropiedad


/******** AUN NO *******/
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('payments/conciliacion', 'Conciliaci&oacute;n', 7, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (164, 1);

ALTER TABLE `gc_listado_pagos`
	ADD COLUMN `fechaconciliacion` DATE NULL AFTER `pdf_content`;

#actualiza fecha de conciliacion
update gc_listado_pagos lp 
inner join gc_cartola_pagos cp on lp.id = cp.idlistado
inner join gc_cartola_caja c on cp.id = c.idpago
set lp.fechaconciliacion = c.fechaconciliacion


#actualiza estado activo
update gc_listado_pagos lp
inner join gc_cartola_pagos cp on lp.id = cp.idlistado
inner join gc_cartola_caja c on cp.id = c.idpago
set lp.activo = c.activo


ALTER TABLE `gc_listado_abonos`
	ADD COLUMN `fechaconciliacion` DATE NOT NULL AFTER `nombrerealarchivo`;
ALTER TABLE `gc_listado_abonos`
	ALTER `fechaconciliacion` DROP DEFAULT;
ALTER TABLE `gc_listado_abonos`
	CHANGE COLUMN `fechaconciliacion` `fechaconciliacion` DATE NULL AFTER `nombrerealarchivo`;


update gc_listado_abonos la 
inner join gc_cartola_propiedad cp on la.id = cp.idlistado
inner join gc_cartola_caja c on cp.id = c.idabono
set la.fechaconciliacion = c.fechaconciliacion


update gc_listado_abonos la 
inner join gc_cartola_propiedad cp on la.id = cp.idlistado
inner join gc_cartola_caja c on cp.id = c.idabono
set la.activo = c.activo	

ALTER TABLE `gc_listado_pagos`
	ADD COLUMN `observacion` TEXT NULL DEFAULT NULL AFTER `fechaconciliacion`;

ALTER TABLE `gc_listado_pagos`
	ADD COLUMN `protesto` TINYINT(4) NULL AFTER `observacion`;

ALTER TABLE `gc_listado_abonos`
	ADD COLUMN `protesto` TINYINT(4) NULL DEFAULT NULL AFTER `fechaconciliacion`;


ALTER TABLE `gc_listado_abonos`
	CHANGE COLUMN `protesto` `idprotesto` TINYINT(4) NULL DEFAULT NULL AFTER `fechaconciliacion`;

ALTER TABLE `gc_listado_abonos`
	CHANGE COLUMN `idprotesto` `idprotesto` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `fechaconciliacion`,
	ADD CONSTRAINT `fk_listado_abonos_idprotesto_listado_abonos_id` FOREIGN KEY (`idprotesto`) REFERENCES `gc_listado_abonos` (`id`);	

ALTER TABLE `gc_listado_abonos`
	ADD COLUMN `protesto` TINYINT(4) NULL AFTER `fechaconciliacion`;

ALTER TABLE `gc_listado_pagos`
	ADD COLUMN `idprotesto` INT(11) NULL DEFAULT NULL AFTER `protesto`;

ALTER TABLE `gc_listado_pagos`
	CHANGE COLUMN `idprotesto` `idprotesto` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `protesto`,
	ADD CONSTRAINT `fk_listado_pagos_idprotesto_listado_pagos_id` FOREIGN KEY (`idprotesto`) REFERENCES `gc_listado_pagos` (`id`);	


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('payments/ver_conciliacion_movimiento', 7, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (165, 1);

UPDATE `gc_app` SET `valid`=0 WHERE  `id`=41;

## PRUEBAS
select * from gc_listado_pagos where id = 190;
select cc.* from gc_cartola_caja cc 
inner join gc_cartola_pagos cp on cc.idpago = cp.id
where cp.idlistado = 190;

select * from gc_cartola_pagos where idlistado = 190;
select c.* from gc_cuenta c
inner join gc_cartola_pagos cp on c.id = cp.idcuenta
where cp.idlistado = 190;

select gc.* from gc_cuenta c
inner join gc_cartola_pagos cp on c.id = cp.idcuenta
inner join gc_ggcc_comunidad gc on c.idggcc = gc.id
where cp.idlistado = 190;

select * from gc_comunidad where id = 17;
select * from gc_cartola_fondo_reserva;		


/*****************************************************************/
UPDATE `gc_app` SET `valid`=1 WHERE  `id`=41;
DELETE FROM `gc_role` WHERE  `id`=55;

/*************************************************************************/

ALTER TABLE `gc_comunidad`
	ADD COLUMN `cajainicial` INT(11) NULL DEFAULT '0' AFTER `saldo`,
	ADD COLUMN `fondoreservainicial` INT(11) NULL DEFAULT '0' AFTER `caja`;
update gc_comunidad set cajainicial = cajainicial + 25620822, caja = caja + 25620822 where id = 17;	

/********************************************************************************/

UPDATE `gc_tipo_deuda_detalle` SET `idcomunidad`=NULL WHERE  `id`=69;	

/*********************************************************************************/
update gc_comunidad set cajainicial = cajainicial - 17856683, caja = caja - 17856683 where id = 17;	

/***********************************************************************************/

#add_cuenta es la que se ve bien

ALTER TABLE `gc_deuda_propiedad`
	ADD COLUMN `idcuenta` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `idperiodo`,
	ADD CONSTRAINT `fk_deuda_propiedad_idcuenta_cuenta_id` FOREIGN KEY (`idcuenta`) REFERENCES `gc_cuenta` (`id`);

ALTER TABLE `gc_lectura_servicio`
	ADD COLUMN `idcuenta` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `idtipodeudadetalle`,
	ADD CONSTRAINT `fk_lectura_servicio_idcuenta_cuenta_id` FOREIGN KEY (`idcuenta`) REFERENCES `gc_cuenta` (`id`);	

/**************************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('accounts/editar_lectura_individual', 'Editar Lecturas Individuales', 4, 1, 1, 9, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (166, 1);

ALTER TABLE `gc_cuenta`
	ADD COLUMN `idperiodo` INT UNSIGNED NULL DEFAULT NULL AFTER `formapago`,
	ADD CONSTRAINT `fk_cuenta_idperiodo_periodo_id` FOREIGN KEY (`idperiodo`) REFERENCES `gc_periodo` (`id`);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('reports/ver_detalle_lectura', 5, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (167, 1);


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('reports/ver_lectura_individual', 5, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (168, 1);

/********************************************************************************************/

CREATE TABLE `gc_access_log` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`userid` INT(11) UNSIGNED NOT NULL,
	`action` VARCHAR(30) NOT NULL,
	`detail` TEXT NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_access_log_userid_users_id` (`userid`),
	CONSTRAINT `fk_access_log_userid_users_id` FOREIGN KEY (`userid`) REFERENCES `gc_users` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


truncate gc_access_log;

ALTER TABLE `gc_access_log`
	ADD COLUMN `action` VARCHAR(30) NOT NULL AFTER `userid`,
	ADD COLUMN `detail` TEXT NOT NULL AFTER `action`;

ALTER TABLE `gc_access_log`
	CHANGE COLUMN `detail` `detail` TEXT NULL AFTER `action`;

ALTER TABLE `gc_access_log`
	CHANGE COLUMN `action` `action` VARCHAR(30) NULL AFTER `userid`;		

/******************************************************************************************/

ALTER TABLE `gc_access_log`
	ADD COLUMN `ipaddress` VARCHAR(30) NULL AFTER `detail`;


/********************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/delete_cobro_individual', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (169, 1);

/**************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/edit_cobro_individual', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (170, 1);
ALTER TABLE `gc_detalle_lectura_servicio`
	ADD COLUMN `valor_ant` DOUBLE NOT NULL DEFAULT '0' AFTER `idpropiedad`;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/submit_edit_cobro_individual', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (171, 1);


/************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('comunity/reenvio_ggcc_mail', 2, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (172, 1);


/*************************************************************************************************/

CREATE TABLE `gc_tipo_cuenta_contable` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`codigo` VARCHAR(30) NULL DEFAULT NULL,
	`nombre` VARCHAR(100) NULL DEFAULT NULL,
	`tipo` ENUM('ACTIVO','PASIVO','PATRIMONIO') NULL DEFAULT NULL,
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB
;	

INSERT INTO `gc_tipo_cuenta_contable` (`codigo`, `nombre`, `tipo`, `created_at`) VALUES ('1.10', 'Activo Circulante', 'ACTIVO', '2016-05-10 18:56:17');
INSERT INTO `gc_tipo_cuenta_contable` (`codigo`, `nombre`, `tipo`, `created_at`) VALUES ('1.20', 'Activo Fijo', 'ACTIVO', '2016-05-10 18:56:47');
INSERT INTO `gc_tipo_cuenta_contable` (`codigo`, `nombre`, `tipo`, `created_at`) VALUES ('2.10', 'Pasivo Circulante', 'PASIVO', '2016-05-10 19:17:06');
INSERT INTO `gc_tipo_cuenta_contable` (`codigo`, `nombre`, `tipo`, `created_at`) VALUES ('3', 'Patrimonio', 'PATRIMONIO', '2016-05-10 19:25:09');



CREATE TABLE `gc_plan_cuentas` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`codigo` VARCHAR(30) NULL DEFAULT '0',
	`nombre` VARCHAR(100) NULL DEFAULT '0',
	`idtipo` INT(10) UNSIGNED NULL DEFAULT '0',
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_plan_cuentas_idtipo_tipo_cuenta_id` (`idtipo`),
	CONSTRAINT `fk_plan_cuentas_idtipo_tipo_cuenta_id` FOREIGN KEY (`idtipo`) REFERENCES `gc_tipo_cuenta_contable` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('1.10.10', 'Banco', 1, '2016-05-10 18:59:13');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('1.10.20', 'Documentos en Cartera', 1, '2016-05-10 19:00:34');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('1.10.30', 'Gastos Comunes por Cobrar Mes', 1, '2016-05-10 19:14:00');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('1.10.40', 'Multas por Cobrar Mes', 1, '2016-05-10 19:14:29');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('1.10.50', 'Gastos Comunes por Cobrar Morosos', 1, '2016-05-10 19:14:44');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('1.10.70', 'Fondo Fijo', 1, '2016-05-10 19:15:12');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('1.10.80', 'Documentos por Rendir ', 1, '2016-05-10 19:15:30');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('1.20.10', 'Bienes y equipos', 2, '2016-05-10 19:26:02');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('1.20.90', 'Depreciación acumulada', 2, '2016-05-10 19:26:34');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('2.10.10', 'Pagos Anticipados Propietarios', 3, '2016-05-10 19:26:55');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('2.10.20', 'Provisiones', 3, '2016-05-10 19:27:08');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('2.10.30', 'Cuentas por pagar', 3, '2016-05-10 19:27:25');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('3.10.10', 'Fondo Operacional', 4, '2016-05-10 19:27:43');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('3.10.20', 'Fondos  de Reserva', 4, '2016-05-10 19:27:59');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('3.10.30', 'Fondos  de Reserva - Otros', 4, '2016-05-10 19:28:25');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('3.10.40', 'Fondos de Reserva - Multas', 4, '2016-05-10 19:28:45');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('3.10.50', 'Excedentes  Acumulados', 4, '2016-05-10 19:28:57');

INSERT INTO `gc_menu` (`name`, `img`, `valid`, `orden`) VALUES ('Contabilidad', 'fa-book', 1, 8);
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('contabilidad/saldo_inicial', 'Saldos Iniciales', 9, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (173, 1);


CREATE TABLE `gc_comunidad_cuenta_inic` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`idcuentacontable` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`valor` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`created_at` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_cuenta_inic_idcomunidad_comunidad_id` (`idcomunidad`),
	INDEX `fk_cuenta_inic_idcuentacontable_plan_cuentas_id` (`idcuentacontable`),
	CONSTRAINT `fk_cuenta_inic_idcuentacontable_plan_cuentas_id` FOREIGN KEY (`idcuentacontable`) REFERENCES `gc_plan_cuentas` (`id`),
	CONSTRAINT `fk_cuenta_inic_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
ENGINE=InnoDB
;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/submit_saldo_inicial', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (174, 1);


CREATE TABLE `gc_periodo_balance` (
	`idperiodo` INT(11) UNSIGNED NOT NULL,
	`idcomunidad` INT(11) UNSIGNED NOT NULL,
	`corte` DATE NULL DEFAULT NULL,
	`calculo` DATETIME NULL DEFAULT NULL,
	`aprueba` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`idperiodo`, `idcomunidad`),
	INDEX `fk_periodo_estado_idcomunidad_comunidad_id` (`idcomunidad`),
	CONSTRAINT `gc_periodo_balance_ibfk_1` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`),
	CONSTRAINT `gc_periodo_balance_ibfk_2` FOREIGN KEY (`idperiodo`) REFERENCES `gc_periodo` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;



ALTER TABLE `gc_plan_cuentas`
	ADD COLUMN `edita` TINYINT UNSIGNED NULL DEFAULT '0' AFTER `idtipo`;

UPDATE `gc_plan_cuentas` SET `edita`=1 WHERE  `id`=6;	

##################
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('contabilidad/generar_balance', 'Generar Balance', 9, 1, 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (175, 1);


CREATE TABLE `gc_detalle_balance` (
	`id` INT(10) UNSIGNED NOT NULL,
	`idcomunidad` INT(10) UNSIGNED NULL DEFAULT NULL,
	`idperiodo` INT(10) UNSIGNED NULL DEFAULT NULL,
	`idcuentacontable` INT(10) UNSIGNED NULL DEFAULT NULL,
	`tipo` ENUM('DEBE','HABER') NULL DEFAULT NULL,
	`valor` INT(11) NULL DEFAULT NULL,
	`descripcion` VARCHAR(100) NULL DEFAULT NULL,
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_detalle_balance_idcomunidad_comunidad_id` (`idcomunidad`),
	INDEX `fk_detalle_balance_idperiodo_periodo_id` (`idperiodo`),
	INDEX `fk_detalle_balance_idcuentacontable_plan_cuentas_id` (`idcuentacontable`),
	CONSTRAINT `fk_detalle_balance_idcuentacontable_plan_cuentas_id` FOREIGN KEY (`idcuentacontable`) REFERENCES `gc_plan_cuentas` (`id`),
	CONSTRAINT `fk_detalle_balance_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`),
	CONSTRAINT `fk_detalle_balance_idperiodo_periodo_id` FOREIGN KEY (`idperiodo`) REFERENCES `gc_periodo` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/submit_generar_balance', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (176, 1);

ALTER TABLE `gc_detalle_balance`
	CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST;

ALTER TABLE `gc_cuenta`
	ADD COLUMN `fecautoriza` DATETIME NULL DEFAULT NULL AFTER `idggcc`;

ALTER TABLE `gc_ingresos`
	ADD COLUMN `fecautoriza` DATETIME NULL DEFAULT NULL AFTER `idggcc`;		

update gc_ggcc_propiedad gp
inner join gc_propiedad p on gp.idpropiedad = p.id
set gp.pdf_content = null
where p.idcomunidad = 18;	


/****************************************************/
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/ver_balance', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (177, 1);


ALTER TABLE `gc_cuenta`
	CHANGE COLUMN `formapago` `formapago` ENUM('gc','fr','ci','sc','af') NOT NULL COMMENT 'gc: gasto comun, fr: fondo reserva, ci: cobro individual, sc: sin cobro, af: activo fijo' AFTER `fecautoriza`;
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('contabilidad/activo_fijo', 'Activo Fijo', 9, 1, 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
UPDATE `gc_app` SET `orden`=3 WHERE  `id`=175;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (178, 1);

ALTER TABLE `gc_cuenta`
	ADD COLUMN `vidautil` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `idperiodoremuneracion`;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/put_vida_util', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (179, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/submit_vida_util', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (180, 1);

CREATE TABLE `gc_activo_fijo_balance` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(10) UNSIGNED NULL DEFAULT NULL,
	`idperiodo` INT(10) UNSIGNED NULL DEFAULT NULL,
	`idcuenta` INT(10) UNSIGNED NULL DEFAULT NULL,
	`monto` INT(11) NULL DEFAULT NULL,
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_detalle_balance_idcomunidad_comunidad_id` (`idcomunidad`),
	INDEX `fk_detalle_balance_idperiodo_periodo_id` (`idperiodo`),
	INDEX `fk_detalle_balance_idcuentacontable_plan_cuentas_id` (`idcuenta`),
	CONSTRAINT `gc_af_balance_idcuenta_cuenta_id` FOREIGN KEY (`idcuenta`) REFERENCES `gc_cuenta` (`id`),
	CONSTRAINT `gc_activo_fijo_balance_ibfk_1` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`),
	CONSTRAINT `gc_activo_fijo_balance_ibfk_3` FOREIGN KEY (`idperiodo`) REFERENCES `gc_periodo` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;

ALTER TABLE `gc_cuenta`
	ADD COLUMN `vidautilresidual` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `vidautil`;

/*************************************************************************/

ALTER TABLE `gc_cuenta`
	ADD COLUMN `baja` TINYINT UNSIGNED NULL DEFAULT '0' AFTER `vidautilresidual`;


/**************************************************************************/
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('contabilidad/ingresos_no_contabilizados', 'Ingresos No Contabilizados', 9, 1, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
UPDATE `gc_app` SET `orden`=4 WHERE  `id`=175;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (181, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/add_ingreso_no_contabilizado', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (182, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/submit_ingreso_no_contabilizado', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (183, 1);

CREATE TABLE `gc_ingresos_no_contabilizados` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`fechaingreso` DATE NOT NULL,
	`monto` INT(11) NOT NULL,
	`descripcion` VARCHAR(250) NULL DEFAULT NULL,
	`activo` TINYINT(4) NULL DEFAULT '1',
	`fechaelimina` DATETIME NULL DEFAULT NULL,
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_ing_no_cont_idcomunidad_comunidad_id` (`idcomunidad`),
	CONSTRAINT `fk_ing_no_cont_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
ENGINE=InnoDB
;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/delete_ingreso_no_contabilizado', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (184, 1);


/********************************************************************/

UPDATE `gc_afp` SET `nombre`='No Cotiza' WHERE  `id`=8;
ALTER TABLE `gc_personal`
	ADD COLUMN `pensionado` TINYINT(4) NOT NULL DEFAULT '0' AFTER `colacion`;
UPDATE `gc_periodo_remuneracion` SET `aprueba`=NULL WHERE  `idperiodo`=15 AND `idcomunidad`=18;

/*************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('accounts/editar_ingresos', 'Editar Ingresos', 4, 1, 1, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (185, 1);

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/edit_ingreso', 5, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (186, 1);

ALTER TABLE `gc_deuda_propiedad`
	ADD COLUMN `interes` DOUBLE NULL AFTER `descripcion`;

update gc_deuda_propiedad dp
inner join gc_propiedad p on dp.idpropiedad = p.id
set interes = 3.12
where dp.idperiodo = 15 and dp.idtipodeudadetalle = 12
and p.idcomunidad = 17;

update gc_ggcc_propiedad set pdf_content = null  where idperiodo = 15;


/******************************************************************************/	

ALTER TABLE `gc_ggcc_comunidad`
	ADD COLUMN `tipo_fr` ENUM('pesos','porcentaje') NULL DEFAULT NULL AFTER `idperiodo`,
	ADD COLUMN `porcentaje` DOUBLE NULL DEFAULT NULL AFTER `tipo_fr`;

/******************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/delete_ingreso', 5, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (187, 1);

ALTER TABLE `gc_cartola_caja`
	ADD COLUMN `exingreso` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `idingreso`;
	
/***********************************************************************************************************/

CREATE TABLE `gc_asiento_contable` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(10) UNSIGNED NOT NULL,
	`idperiodo` INT(10) UNSIGNED NOT NULL,
	`glosa` TEXT NOT NULL,
	`fecmovimiento` DATE NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_asiento_contable_idcomunidad_comunidad_id` (`idcomunidad`),
	INDEX `fk_asiento_contable_idperiodo_periodo_id` (`idperiodo`),
	CONSTRAINT `fk_asiento_contable_idperiodo_periodo_id` FOREIGN KEY (`idperiodo`) REFERENCES `gc_periodo` (`id`),
	CONSTRAINT `fk_asiento_contable_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `gc_detalle_asiento_contable` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idasiento` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`idcuentacontable` INT(11) UNSIGNED NULL DEFAULT NULL,
	`tipo` ENUM('DEBE','HABER') NULL DEFAULT NULL,
	`valor` INT(11) NULL DEFAULT '0',
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_detalle_asiento_contable_idasiento_asiento_contable_id` (`idasiento`),
	INDEX `fk_detalle_asiento_contable_idcuentacontable_plan_cuentas_id` (`idcuentacontable`),
	CONSTRAINT `fk_detalle_asiento_contable_idasiento_asiento_contable_id` FOREIGN KEY (`idasiento`) REFERENCES `gc_asiento_contable` (`id`),
	CONSTRAINT `fk_detalle_asiento_contable_idcuentacontable_plan_cuentas_id` FOREIGN KEY (`idcuentacontable`) REFERENCES `gc_plan_cuentas` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;	


/*************************************************************************/

UPDATE `gc_app` SET `name`='Ingresos No Identificados' WHERE  `id`=181;

/******************************************************/
ALTER TABLE `gc_cuenta`
	ADD COLUMN `unidadmedida` ENUM('m3','kw','unidad') NULL DEFAULT NULL AFTER `idtipodeudadetalle`;

/*******************************************************/

ALTER TABLE `gc_cuenta`
	ADD COLUMN `montounidad` INT NOT NULL DEFAULT '0' AFTER `unidadmedida`;



/*********************************************************************************************/

ALTER TABLE `gc_parametros_generales`
	ADD COLUMN `tasasis` DOUBLE NOT NULL DEFAULT '0' AFTER `cmaternales`;

UPDATE `gc_parametros_generales` SET `tasasis`=1.15;

/***********************************************************************************/

ALTER TABLE `gc_cuenta`
	ADD COLUMN `active` TINYINT NOT NULL DEFAULT '1' AFTER `nombrerealarchivo`;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/desactiva_cuenta', 5, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (188, 1);


UPDATE `gc_periodo_estado` SET `pdf_content`=NULL WHERE  `idperiodo`=15 AND `idcomunidad`=19;


/*************************************************************************************/

ALTER TABLE `gc_ingresos`
	ADD COLUMN `habilitagasto` TINYINT NULL DEFAULT '0' AFTER `descripcion`;
ALTER TABLE `gc_ingresos`
	CHANGE COLUMN `habilitagasto` `habilitagasto` TINYINT(4) NOT NULL DEFAULT '0' AFTER `descripcion`;


/************************************************************************************/
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_personal_apv', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (189, 1);

/**********************************************************************************************/

-- Volcando estructura para tabla ggcc_prod.gc_apv
CREATE TABLE IF NOT EXISTS `gc_apv` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `codprevired` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

INSERT INTO `gc_apv` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES
	(1, 'Cuprum', 3, 1, '0000-00-00 00:00:00', '2016-07-06 20:27:55'),
	(2, 'Habitat', 5, 1, '0000-00-00 00:00:00', '2016-07-06 20:28:10'),
	(3, 'Provida', 8, 1, '0000-00-00 00:00:00', '2016-07-06 20:28:21'),
	(4, 'Planvital', 29, 1, '0000-00-00 00:00:00', '2016-07-06 20:28:31'),
	(5, 'Capital', 33, 1, '0000-00-00 00:00:00', '2016-07-06 20:28:37'),
	(6, 'Modelo', 34, 1, '0000-00-00 00:00:00', '2016-07-06 20:28:46'),
	(7, 'ABN AMRO (CHILE) SEGUROS DE VIDA S.A.', 100, 1, '0000-00-00 00:00:00', '2016-07-06 20:29:00'),
	(8, 'AGF ALLIANZ CHILE COMPAÑIA DE SEGUROS VIDA S.A', 101, 1, '0000-00-00 00:00:00', '2016-07-06 20:29:13'),
	(9, 'SANTANDER SEGUROS DE VIDA S.A.', 102, 1, '0000-00-00 00:00:00', '2016-07-06 20:29:23'),
	(10, 'BCI SEGUROS VIDA S.A.', 103, 1, '0000-00-00 00:00:00', '2016-07-06 20:29:31'),
	(11, 'BANCHILE SEGUROS DE VIDA S.A.', 104, 1, '0000-00-00 00:00:00', '2016-07-06 20:29:40'),
	(12, 'BBVA SEGUROS DE VIDA S.A.', 105, 1, '0000-00-00 00:00:00', '2016-07-06 20:29:54'),
	(13, 'BICE VIDA COMPAÑIA DE SEGUROS S.A.', 106, 1, '0000-00-00 00:00:00', '2016-07-06 20:30:04'),
	(14, 'CHILENA CONSOLIDADA SEGUROS DE VIDA S.A.', 107, 1, '0000-00-00 00:00:00', '2016-07-06 20:30:19'),
	(15, 'CIGNA COMPAÑIA DE SEGUROS DE VIDA S.A.', 108, 1, '0000-00-00 00:00:00', '2016-07-06 20:30:28'),
	(16, 'CN LIFE, COMPAÑIA DE SEGUROS DE VIDA S.A.', 109, 1, '0000-00-00 00:00:00', '2016-07-06 20:30:35'),
	(17, 'COMPAÑIA DE SEGUROS DE VIDA CARDIF S.A.', 110, 1, '0000-00-00 00:00:00', '2016-07-06 20:30:52'),
	(18, 'CIA DE SEG. DE VIDA CONSORCIO NACIONAL DE SEG S.A.', 111, 1, '0000-00-00 00:00:00', '2016-07-06 20:31:02'),
	(19, 'COMPAÑIA DE SEGUROS DE VIDA HUELEN S.A.', 113, 1, '0000-00-00 00:00:00', '2016-07-06 20:31:14'),
	(20, 'COMPAÑIA DE SEGUROS DE VIDA VITALIS S.A.', 115, 1, '0000-00-00 00:00:00', '2016-07-06 20:31:33'),
	(21, 'COMPAÑIA DE SEGUROS CORPVIDA S.A.', 116, 1, '0000-00-00 00:00:00', '2016-07-06 20:31:46'),
	(22, 'EUROAMERICA SEGUROS DE VIDA S.A.', 117, 1, '0000-00-00 00:00:00', '2016-07-06 20:31:54'),
	(23, 'SEGUROS DE VIDA SURA S.A.', 118, 1, '0000-00-00 00:00:00', '2016-07-06 20:32:12'),
	(24, 'METLIFE CHILE SEGUROS DE VIDA S.A.', 121, 1, '0000-00-00 00:00:00', '2016-07-06 20:32:26'),
	(25, 'MAPFRE COMPAÑIA DE SEGUROS DE VIDA DE CHILE S.A.', 123, 1, '0000-00-00 00:00:00', '2016-07-06 20:32:36'),
	(26, 'MUTUAL DE SEGUROS DE CHILE', 125, 1, '0000-00-00 00:00:00', '2016-07-06 20:32:47'),
	(27, 'MUTUALIDAD DE CARABINEROS', 126, 1, '0000-00-00 00:00:00', '2016-07-06 20:33:01'),
	(28, 'MUTUALIDAD DEL EJERCITO Y AVIACION', 127, 1, '0000-00-00 00:00:00', '2016-07-06 20:33:15'),
	(29, 'OHIO NATIONAL SEGUROS DE VIDA S.A.', 128, 1, '0000-00-00 00:00:00', '2016-07-06 20:33:36'),
	(30, 'PRINCIPAL COMPAÑIA DE SEGUROS DE VIDA CHILE S.A.', 129, 1, '0000-00-00 00:00:00', '2016-07-06 20:33:44'),
	(31, 'RENTA NACIONAL COMPAÑIA DE SEGUROS DE VIDA S.A.', 130, 1, '0000-00-00 00:00:00', '2016-07-06 20:33:52'),
	(32, 'SEGUROS DE VIDA SECURITY PREVISION S.A.', 131, 1, '0000-00-00 00:00:00', '2016-07-06 20:34:01'),
	(33, 'COMPAÑIA DE SEGUROS GENERALES PENTA-SECURITY S.A.', 134, 1, '0000-00-00 00:00:00', '2016-07-06 20:34:16'),
	(34, 'PENTA VIDA COMPAÑIA DE SEGUROS DE VIDA S.A.', 135, 1, '0000-00-00 00:00:00', '2016-07-06 20:34:28'),
	(35, 'ACE SEGUROS S.A.', 136, 1, '0000-00-00 00:00:00', '2016-07-06 20:34:43'),
	(36, 'BANDESARROLLO ADMINISTRADORA GENERAL DE FONDOS S.A.', 201, 1, '0000-00-00 00:00:00', '2016-07-06 20:34:55'),
	(37, 'BBVA ASSET MANAGEMENT ADMINISTRADORA GENERAL DE FONDOS S.A.', 203, 1, '0000-00-00 00:00:00', '2016-07-06 20:35:03'),
	(38, 'BCI ASSET MANAGEMENT ADMINISTRADORA GENERAL DE FONDOS S.A.', 204, 1, '0000-00-00 00:00:00', '2016-07-06 20:35:11'),
	(39, 'BICE INVERSIONES ADMINISTRADORA GENERAL DE FONDOS S.A.', 205, 1, '0000-00-00 00:00:00', '2016-07-06 20:35:21'),
	(40, 'BTG PACTUAL CHILE SA AGF', 208, 1, '0000-00-00 00:00:00', '2016-07-06 20:35:31'),
	(41, 'CORPBANCA ADMINISTRADORA GENERAL DE FONDOS S.A.', 210, 1, '0000-00-00 00:00:00', '2016-07-06 20:35:38'),
	(42, 'LARRAIN VIAL S.A. CORREDORA DE BOLSA', 213, 1, '0000-00-00 00:00:00', '2016-07-06 20:35:47'),
	(43, 'PRINCIPAL ADMINISTRADORA GENERAL DE FONDOS S.A.', 214, 1, '0000-00-00 00:00:00', '2016-07-06 20:35:55'),
	(44, 'SANTANDER ASSET MANAGEMENT S.A. ADM. GENERAL DE FONDOS', 215, 1, '0000-00-00 00:00:00', '2016-07-06 20:36:07'),
	(45, 'SCOTIA SUD AMERICANO ADMINISTRADORA DE FONDOS MUTUOS S.A.', 217, 1, '0000-00-00 00:00:00', '2016-07-06 20:36:17'),
	(46, 'ADMINISTRADORA GENERAL DE FONDOS SECURITY S.A.', 218, 1, '0000-00-00 00:00:00', '2016-07-06 20:36:27'),
	(47, 'CRUZ DEL SUR CORREDORA DE BOLSA S.A.', 219, 1, '0000-00-00 00:00:00', '2016-07-06 20:36:37'),
	(48, 'BANCHILE CORREDORES DE BOLSA S.A.', 222, 1, '0000-00-00 00:00:00', '2016-07-06 20:36:44'),
	(49, 'ZURICH ADMINISTRADORA GENERAL DE FONDOS S.A.', 224, 1, '0000-00-00 00:00:00', '2016-07-06 20:36:57'),
	(50, 'ITAU CHILE ADMINISTRADORA GENERAL DE FONDOS S.A.', 225, 1, '0000-00-00 00:00:00', '2016-07-06 20:37:07'),
	(51, 'PENTA ADMINISTRADORA GENERAL DE FONDOS S.A.', 226, 1, '0000-00-00 00:00:00', '2016-07-06 20:37:15'),
	(52, 'CORREDORES DE BOLSA SURA S.A.', 227, 1, '0000-00-00 00:00:00', '2016-07-06 20:37:22'),
	(53, 'BTG PACTUAL CHILE SA CORREDORES DE BOLSA', 228, 1, '0000-00-00 00:00:00', '2016-07-06 20:37:29'),
	(54, 'BANCOESTADO S.A. ADMINISTRADORA GENERAL DE FONDO', 229, 1, '0000-00-00 00:00:00', '2016-07-06 20:37:36'),
	(55, 'SCOTIA SUD AMERICANO CORREDORES DE BOLSA S.A.', 231, 1, '0000-00-00 00:00:00', '2016-07-06 20:37:43'),
	(56, 'BICE INVERSIONES CORREDORES DE BOLSA S.A.', 232, 1, '0000-00-00 00:00:00', '2016-07-06 20:38:01'),
	(57, 'EUROAMERICA ADMINISTRADORA GENERAL DE FONDOS S.A.', 600, 1, '0000-00-00 00:00:00', '2016-07-06 20:38:12'),
	(58, 'LARRAIN VIAL ADMINISTRADORA GENERAL DE FONDOS S.A.', 601, 1, '0000-00-00 00:00:00', '2016-07-06 20:38:23'),
	(59, 'BBVA Banco Bhif', 303, 1, '0000-00-00 00:00:00', '2016-07-06 20:38:36'),
	(60, 'Banco Ripley', 319, 1, '0000-00-00 00:00:00', '2016-07-06 20:38:42'),
	(61, 'Banco ScotiaBank', 320, 1, '0000-00-00 00:00:00', '2016-07-06 20:38:50'),
	(62, 'Banco Santander Santiago', 321, 1, '0000-00-00 00:00:00', '2016-07-06 20:38:58'),
	(63, 'CAJA DE COMPENSACION LOS ANDES', 400, 1, '0000-00-00 00:00:00', '2016-07-06 20:39:08');


ALTER TABLE `gc_personal`
	ADD COLUMN `instapv` INT NULL DEFAULT NULL AFTER `ahorrovol`;
ALTER TABLE `gc_personal`
	CHANGE COLUMN `instapv` `instapv` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `ahorrovol`,
	ADD CONSTRAINT `fk_personal_apv_apv_id` FOREIGN KEY (`instapv`) REFERENCES `gc_apv` (`id`);	
ALTER TABLE `gc_personal`
	ADD COLUMN `formapagoapv` TINYINT NULL DEFAULT NULL AFTER `cotapv`;	
ALTER TABLE `gc_personal`
	ADD COLUMN `nrocontratoapv` INT(11) NULL DEFAULT NULL AFTER `instapv`;
ALTER TABLE `gc_personal`
	ADD COLUMN `depconvapv` INT(11) NULL DEFAULT '0' AFTER `formapagoapv`;		


/****************************************************************************************************/

ALTER TABLE `gc_mutual_seguridad`
	ADD COLUMN `codprevired` INT(11) NOT NULL DEFAULT '0' AFTER `nombre`;	
ALTER TABLE `gc_mutual_seguridad`
	ADD COLUMN `codprevired` INT(11) NOT NULL DEFAULT '0' AFTER `nombre`;
UPDATE `gc_mutual_seguridad` SET `active`=0 WHERE  `id`=3;		
UPDATE `gc_mutual_seguridad` SET `active`=0 WHERE  `id`=4;
UPDATE `gc_mutual_seguridad` SET `active`=0 WHERE  `id`=6;
UPDATE `gc_mutual_seguridad` SET `active`=0 WHERE  `id`=8;
UPDATE `gc_mutual_seguridad` SET `codprevired`=1 WHERE  `id`=2;
UPDATE `gc_mutual_seguridad` SET `codprevired`=2 WHERE  `id`=7;
UPDATE `gc_mutual_seguridad` SET `codprevired`=3 WHERE  `id`=5;
ALTER TABLE `gc_cajas_compensacion`
	ADD COLUMN `codprevired` INT(11) NOT NULL DEFAULT '0' AFTER `nombre`;
UPDATE `gc_cajas_compensacion` SET `codprevired`=1 WHERE  `id`=3;	
UPDATE `gc_cajas_compensacion` SET `codprevired`=2 WHERE  `id`=2;
UPDATE `gc_cajas_compensacion` SET `codprevired`=3 WHERE  `id`=4;
UPDATE `gc_cajas_compensacion` SET `codprevired`=5 WHERE  `id`=5;
UPDATE `gc_cajas_compensacion` SET `codprevired`=6 WHERE  `id`=1;

/*************************************************************************************************************/


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('payments/download_ingreso', 2, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (190, 1);

ALTER TABLE `gc_listado_abonos`
	ADD COLUMN `pdf_content` TEXT NULL DEFAULT NULL AFTER `idprotesto`;


/***********************************************************************************************************/

DELETE FROM `gc_role` WHERE  `id`=209;	

/************************************************************************************************************/

INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (190, 2);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (145, 2);


/******************************************************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('reports/saldo_propiedades', 'Saldos por Propiedad', 5, 1, 1, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (191, 2);
UPDATE `gc_app` SET `function`='payments/abonar_ggcc' WHERE  `id`=191;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (9, 2);

/****************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/upfile', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (192, 1);

/*************************************************************************************/

ALTER TABLE `gc_listado_abonos`
	ADD COLUMN `folio` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `gc_comunidad`
	ADD COLUMN `maxfolio` INT UNSIGNED NULL DEFAULT '0' AFTER `porcmutual`;
update gc_comunidad c
inner join (select p.idcomunidad, count(a.id) as abonos from gc_listado_abonos a
inner join gc_propiedad p on a.idpropiedad = p.id
group by p.idcomunidad) as tmp
on c.id = tmp.idcomunidad
set c.maxfolio = tmp.abonos;		



/*******************************************************************************************/

ALTER TABLE `gc_comunidad`
	CHANGE COLUMN `maxfolio` `maxfolioabono` INT(10) UNSIGNED NULL DEFAULT '0' AFTER `porcmutual`;
ALTER TABLE `gc_comunidad`
	ADD COLUMN `maxfoliopago` INT(10) UNSIGNED NULL DEFAULT '0' AFTER `maxfolioabono`;	
ALTER TABLE `gc_listado_pagos`
	ADD COLUMN `folio` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;
update gc_comunidad c
inner join (select idcomunidad, count(id) as pagos from 
(select lp.id, c.idcomunidad from gc_listado_pagos lp
inner join gc_cartola_pagos cp on lp.id = cp.idlistado
inner join gc_cuenta c on cp.idcuenta = c.id
group by lp.id)
 as tmp
group by idcomunidad) as tmp2
on c.id = tmp2.idcomunidad
set c.maxfoliopago = tmp2.pagos;			

/****************************************************************************************************/

update gc_listado_abonos a3
inner join gc_propiedad p3 on a3.idpropiedad = p3.id
inner join 
(select 
(select count(a2.id) from gc_listado_abonos a2 
inner join gc_propiedad p2 on a2.idpropiedad = p2.id
where p2.idcomunidad = 17 and  a2.id < a.id ) + 1 as folio_new, 
a.*  from gc_listado_abonos a
inner join gc_propiedad p on a.idpropiedad = p.id
where p.idcomunidad = 17 and a.folio = 0
order by id) as tmp on a3.id = tmp.id
set a3.folio = tmp.folio_new
where a3.folio = 0 and p3.idcomunidad = 17;


update gc_listado_abonos a3
inner join gc_propiedad p3 on a3.idpropiedad = p3.id
inner join 
(select 
(select count(a2.id) from gc_listado_abonos a2 
inner join gc_propiedad p2 on a2.idpropiedad = p2.id
where p2.idcomunidad = 18 and  a2.id < a.id ) + 1 as folio_new, 
a.*  from gc_listado_abonos a
inner join gc_propiedad p on a.idpropiedad = p.id
where p.idcomunidad = 18 and a.folio = 0
order by id) as tmp on a3.id = tmp.id
set a3.folio = tmp.folio_new
where a3.folio = 0 and p3.idcomunidad = 18;


update gc_listado_abonos a3
inner join gc_propiedad p3 on a3.idpropiedad = p3.id
inner join 
(select 
(select count(a2.id) from gc_listado_abonos a2 
inner join gc_propiedad p2 on a2.idpropiedad = p2.id
where p2.idcomunidad = 19 and  a2.id < a.id ) + 1 as folio_new, 
a.*  from gc_listado_abonos a
inner join gc_propiedad p on a.idpropiedad = p.id
where p.idcomunidad = 19 and a.folio = 0
order by id) as tmp on a3.id = tmp.id
set a3.folio = tmp.folio_new
where a3.folio = 0 and p3.idcomunidad = 19


update gc_listado_abonos a3
inner join gc_propiedad p3 on a3.idpropiedad = p3.id
inner join 
(select 
(select count(a2.id) from gc_listado_abonos a2 
inner join gc_propiedad p2 on a2.idpropiedad = p2.id
where p2.idcomunidad = 2 and  a2.id < a.id ) + 1 as folio_new, 
a.*  from gc_listado_abonos a
inner join gc_propiedad p on a.idpropiedad = p.id
where p.idcomunidad = 2 and a.folio = 0
order by id) as tmp on a3.id = tmp.id
set a3.folio = tmp.folio_new
where a3.folio = 0 and p3.idcomunidad = 2


update gc_listado_pagos lp3
inner join gc_cartola_pagos cp3 on lp3.id = cp3.idlistado
inner join gc_cuenta c3 on cp3.idcuenta = c3.id
inner join 
(select 
(select count(distinct lp2.id) from gc_listado_pagos lp2 
inner join gc_cartola_pagos cp2 on lp2.id = cp2.idlistado
inner join gc_cuenta c2 on cp2.idcuenta = c2.id
where c2.idcomunidad = 17 and  lp2.id < lp.id ) + 1 as folio_new, 
lp.* from gc_listado_pagos lp
inner join gc_cartola_pagos cp on lp.id = cp.idlistado
inner join gc_cuenta c on cp.idcuenta = c.id
where c.idcomunidad = 17
group by lp.id) as tmp on lp3.id = tmp.id
set lp3.folio = tmp.folio_new
where lp3.folio = 0 and c3.idcomunidad = 17



update gc_listado_pagos lp3
inner join gc_cartola_pagos cp3 on lp3.id = cp3.idlistado
inner join gc_cuenta c3 on cp3.idcuenta = c3.id
inner join 
(select 
(select count(distinct lp2.id) from gc_listado_pagos lp2 
inner join gc_cartola_pagos cp2 on lp2.id = cp2.idlistado
inner join gc_cuenta c2 on cp2.idcuenta = c2.id
where c2.idcomunidad = 18 and  lp2.id < lp.id ) + 1 as folio_new, 
lp.* from gc_listado_pagos lp
inner join gc_cartola_pagos cp on lp.id = cp.idlistado
inner join gc_cuenta c on cp.idcuenta = c.id
where c.idcomunidad = 18
group by lp.id) as tmp on lp3.id = tmp.id
set lp3.folio = tmp.folio_new
where lp3.folio = 0 and c3.idcomunidad = 18



update gc_listado_pagos lp3
inner join gc_cartola_pagos cp3 on lp3.id = cp3.idlistado
inner join gc_cuenta c3 on cp3.idcuenta = c3.id
inner join 
(select 
(select count(distinct lp2.id) from gc_listado_pagos lp2 
inner join gc_cartola_pagos cp2 on lp2.id = cp2.idlistado
inner join gc_cuenta c2 on cp2.idcuenta = c2.id
where c2.idcomunidad = 19 and  lp2.id < lp.id ) + 1 as folio_new, 
lp.* from gc_listado_pagos lp
inner join gc_cartola_pagos cp on lp.id = cp.idlistado
inner join gc_cuenta c on cp.idcuenta = c.id
where c.idcomunidad = 19
group by lp.id) as tmp on lp3.id = tmp.id
set lp3.folio = tmp.folio_new
where lp3.folio = 0 and c3.idcomunidad = 19



update gc_listado_pagos lp3
inner join gc_cartola_pagos cp3 on lp3.id = cp3.idlistado
inner join gc_cuenta c3 on cp3.idcuenta = c3.id
inner join 
(select 
(select count(distinct lp2.id) from gc_listado_pagos lp2 
inner join gc_cartola_pagos cp2 on lp2.id = cp2.idlistado
inner join gc_cuenta c2 on cp2.idcuenta = c2.id
where c2.idcomunidad = 2 and  lp2.id < lp.id ) + 1 as folio_new, 
lp.* from gc_listado_pagos lp
inner join gc_cartola_pagos cp on lp.id = cp.idlistado
inner join gc_cuenta c on cp.idcuenta = c.id
where c.idcomunidad = 2
group by lp.id) as tmp on lp3.id = tmp.id
set lp3.folio = tmp.folio_new
where lp3.folio = 0 and c3.idcomunidad = 2


/****************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/delete_cuenta_individual_massive', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (193, 1);


/*****************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/delete_adm_esp_comunes_massive', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (194, 1);

/******************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/desautoriza_cuenta_masivo', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (195, 1);



/*********************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('reports/mensual_data', 'Reportes Mensuales', 5, 1, 1, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
UPDATE `gc_app` SET `orden`=9 WHERE  `id`=119;
UPDATE `gc_app` SET `orden`=10 WHERE  `id`=123;
UPDATE `gc_app` SET `orden`=11 WHERE  `id`=191;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (196, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('reports/export_detalle_lectura', 5, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (197, 1);



UPDATE `gc_mutual_seguridad` SET `nombre`='Sin Mutual (ISL)' WHERE  `id`=1;

/****************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('reports/export_mensual_data', 5, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (198, 1);


/*************************************************************************/

ALTER TABLE `gc_periodo_estado`
	ADD COLUMN `tipo_interes` ENUM('cm','cd') NOT NULL COMMENT 'cm: capitalización mensual, cd: capitalización diaria' AFTER `idcomunidad`;
ALTER TABLE `gc_periodo_estado`
	CHANGE COLUMN `tipo_interes` `tipo_interes` ENUM('cm','cd') NOT NULL DEFAULT 'cm' COMMENT 'cm: capitalización mensual, cd: capitalización diaria' AFTER `idcomunidad`;	
ALTER TABLE `gc_periodo_estado`
	ADD COLUMN `interes_diario` DOUBLE UNSIGNED NOT NULL DEFAULT '0' AFTER `interes`;


/********************************************************************************/

ALTER TABLE `gc_ingresos`
	CHANGE COLUMN `tipoingreso` `tipoingreso` ENUM('cc','fr','na') NOT NULL COMMENT 'cc: cuenta corriente, fr: fondo reserva, na: no afecta banco' AFTER `fecautoriza`;


/***********************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/rechaza_balance', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (199, 1);


/*************************************************************************************/
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('1.10.60', 'Depósitos a Plazo o Fondos Mutuos', 1, '2016-09-20 12:29:10');
UPDATE `gc_plan_cuentas` SET `nombre`='Intereses por Cobrar Mes' WHERE  `id`=4;
INSERT INTO `gc_plan_cuentas` (`nombre`, `idtipo`) VALUES ('Otras Multas', 1);
INSERT INTO `gc_plan_cuentas` (`nombre`, `idtipo`) VALUES ('Otros Cobros', 1);
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('3.10.40', 'Fondos de Reserva - Intereses', 4, '2016-05-10 19:28:45');
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('3.10.40', 'Fondos de Reserva - Otros Cobros', 4, '2016-05-10 19:28:45');
UPDATE `gc_plan_cuentas` SET `codigo`='1.10.05' WHERE  `id`=1;
UPDATE `gc_plan_cuentas` SET `codigo`='1.10.10' WHERE  `id`=2;
UPDATE `gc_plan_cuentas` SET `codigo`='1.10.15' WHERE  `id`=3;
UPDATE `gc_plan_cuentas` SET `codigo`='1.10.20' WHERE  `id`=4;
UPDATE `gc_plan_cuentas` SET `codigo`='1.10.25' WHERE  `id`=19;
UPDATE `gc_plan_cuentas` SET `codigo`='1.10.30' WHERE  `id`=20;
UPDATE `gc_plan_cuentas` SET `codigo`='1.10.35' WHERE  `id`=18;
UPDATE `gc_plan_cuentas` SET `codigo`='1.10.40' WHERE  `id`=5;
UPDATE `gc_plan_cuentas` SET `codigo`='1.10.45' WHERE  `id`=6;
UPDATE `gc_plan_cuentas` SET `codigo`='1.10.50' WHERE  `id`=7;
UPDATE `gc_plan_cuentas` SET `codigo`='3.10.50' WHERE  `id`=21;
UPDATE `gc_plan_cuentas` SET `codigo`='3.10.60' WHERE  `id`=22;
UPDATE `gc_plan_cuentas` SET `codigo`='3.10.70' WHERE  `id`=17;


/*************************************************************************************/

ALTER TABLE `gc_plan_cuentas`
	ADD COLUMN `manual` TINYINT(3) UNSIGNED NULL DEFAULT '0' AFTER `edita`;
UPDATE `gc_plan_cuentas` SET `manual`=1 WHERE  `id`=6;	
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/set_cuenta_balance', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (200, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/submit_cuenta_balance', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (201, 1);
UPDATE `gc_plan_cuentas` SET `manual`=1 WHERE  `id`=11;
UPDATE `gc_plan_cuentas` SET `manual`=1 WHERE  `id`=13;
UPDATE `gc_plan_cuentas` SET `manual`=1 WHERE  `id`=15;
UPDATE `gc_plan_cuentas` SET `manual`=1 WHERE  `id`=18;
/*******************************************************************************/

ALTER TABLE `gc_cuenta`
	ADD COLUMN `fecdesactiva` DATETIME NOT NULL AFTER `active`;
ALTER TABLE `gc_cuenta`
	CHANGE COLUMN `fecdesactiva` `fecdesactiva` DATETIME NULL AFTER `active`;	
ALTER TABLE `gc_cuenta`
	ADD COLUMN `depreciacion` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `vidautilresidual`;
update gc_cuenta set depreciacion = monto/vidautil where formapago = 'af';
ALTER TABLE `gc_cuenta`
	ADD COLUMN `depacum` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `depreciacion`;
ALTER TABLE `gc_cuenta`
	ADD COLUMN `vuresidualprevia` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `vidautilresidual`;
update gc_cuenta set vuresidualprevia = vidautil where formapago = 'af';				
update gc_cuenta set depacum = depreciacion*(vidautil-vidautilresidual) where formapago = 'af';
ALTER TABLE `gc_cuenta`
	ADD COLUMN `valorresidual` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `depacum`;
UPDATE `gc_cuenta` SET `depacum`=5903151 WHERE  `id`=1766;		
update gc_cuenta set valorresidual = (monto - depacum) where formapago = 'af' and depreciacion is not null;
INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`) VALUES ('3.10.80', 'Depreciación Activo Fijo', 4);


/****************************************************************************************/

CREATE TABLE `gc_comunidad_cuenta_saldo` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`idcuentacontable` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`valor` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`created_at` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_cuenta_inic_idcomunidad_comunidad_id` (`idcomunidad`),
	INDEX `fk_cuenta_inic_idcuentacontable_plan_cuentas_id` (`idcuentacontable`),
	CONSTRAINT `gc_comunidad_cuenta_saldo_ibfk_1` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`),
	CONSTRAINT `gc_comunidad_cuenta_saldo_ibfk_2` FOREIGN KEY (`idcuentacontable`) REFERENCES `gc_plan_cuentas` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


insert into gc_comunidad_cuenta_saldo
select * from gc_comunidad_cuenta_inic;


/*************************************************************************************************/

INSERT INTO `gc_plan_cuentas` (`codigo`, `nombre`, `idtipo`, `created_at`) VALUES ('2.10.40', 'Ingresos No Identificados', 3, '2016-09-26 14:05:15');
INSERT INTO `gc_tipo_documento_tributario` (`id`, `nombre`) VALUES (14, 'Impuestos');
UPDATE `gc_tipo_documento_tributario` SET `active`=0 WHERE  `id`=14;
INSERT INTO `gc_tipo_deuda_detalle` (`idtipodeuda`, `nombre`, `idpadre`, `activo`, `updated_at`) VALUES (1, 'Impuestos', 27, 1, '0000-00-00 00:00:00');


/************************************************************************************************/

ALTER TABLE `gc_comunidad_cuenta_saldo`
	CHANGE COLUMN `valor` `valor` INT(10) NOT NULL DEFAULT '0' AFTER `idcuentacontable`;
ALTER TABLE `gc_comunidad_cuenta_inic`
	CHANGE COLUMN `valor` `valor` INT(10) NOT NULL DEFAULT '0' AFTER `idcuentacontable`;


/***************************************************************************************************/

UPDATE `gc_cuenta` SET `idtipodoctrib`=4, `monto`=5742, `saldo`=5742 WHERE  `id`=3473;
	


/**************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/ver_cuenta_balance', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (202, 1);

/************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('contabilidad/acepta_balance', 9, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (203, 1);
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('contabilidad/detalle_balances', 'Detalle Balances', 9, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (204, 1);
UPDATE `gc_app` SET `name`='Balances Aprobados' WHERE  `id`=204;
UPDATE `gc_app` SET `function`='contabilidad/balances_aprobados' WHERE  `id`=204;


/****************************************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('contabilidad/saldo_actual', 'Saldos Actuales', 9, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (205, 1);
UPDATE `gc_app` SET `orden`=2 WHERE  `id`=205;
UPDATE `gc_app` SET `orden`=3 WHERE  `id`=178;
UPDATE `gc_app` SET `orden`=4 WHERE  `id`=181;
UPDATE `gc_app` SET `orden`=5 WHERE  `id`=175;
UPDATE `gc_app` SET `orden`=6 WHERE  `id`=204;


/**************************************************************************************************/
UPDATE `gc_app` SET `name`='Cuentas Condominio', `orden`='1' WHERE  `id`=74;
UPDATE `gc_app` SET `name`='', `visible`='0', `orden`=NULL WHERE  `id`=12;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/add_otros_cargos', '4', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('206', '1');
UPDATE `gc_app` SET `function`='accounts/editar_otros_cargos' WHERE  `id`=13;


/*************************************************************************************************/

INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (196, 3);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (197, 3);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (198, 3);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (167, 3);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (168, 3);


INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (196, 2);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (197, 2);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (198, 2);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (167, 2);
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (168, 2);

/******************************************************************************************************/

UPDATE `gc_app` SET `name`='Cuentas Individuales', `orden`=3 WHERE  `id`=148;
UPDATE `gc_app` SET `name`='', `visible`=0, `orden`=NULL WHERE  `id`=14;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/add_adm_esp_comunes', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (207, 1);
UPDATE `gc_app` SET `function`='accounts/editar_adm_esp_comunes' WHERE  `id`=15;


/*******************************************************************************************************/

UPDATE `gc_app` SET `name`='Ingresos Comunidad', `orden`=5 WHERE  `id`=185;
UPDATE `gc_app` SET `name`='', `visible`=0, `orden`=NULL WHERE  `id`=152;

/********************************************************************************************************/

UPDATE `gc_app` SET `visible`=0, `orden`=NULL WHERE  `id`=166;
DELETE FROM `gc_role` WHERE  `id`=226;

/*****************************************************************************************************/

DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=142;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=143;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=144;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=145;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=146;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=147;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=148;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=149;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=150;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=151;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=152;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=153;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=156;
DELETE FROM `gc_cartola_fondo_reserva` WHERE  `id`=157;

UPDATE `gc_cartola_fondo_reserva` SET `saldo`='41510552' WHERE  `id`=175;
UPDATE `gc_cartola_fondo_reserva` SET `saldo`='42640856' WHERE  `id`=210;
UPDATE `gc_comunidad` SET `fondoreserva`='42640856' WHERE  `id`=17;


/*******************************************************************/

INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('63', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('90', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('64', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('65', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('89', '1');
ALTER TABLE `gc_propiedad`
	ADD COLUMN `saldoinicial` INT(11) NOT NULL DEFAULT '0' AFTER `saldo_publicado`;

/**********************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('payments/reenviar_comprobante', '2', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('208', '1');


/********************************************************************************/

INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('190', '3');

/*********************************************************************************/

ALTER TABLE `gc_parametros_generales`
	ADD COLUMN `topeimponible` DOUBLE NOT NULL DEFAULT '0' AFTER `tasasis`;


/**********************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('reports/export_saldos_propiedad', '5', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('209', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('209', '2');

/************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/edit_cuenta_individual', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (210, 1);


/****************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/traspasa_anticipos', '8', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('211', '1');
ALTER TABLE `gc_periodo_remuneracion`
	ADD COLUMN `anticipo` DATETIME NULL DEFAULT NULL COMMENT 'indica si se traspasa anticipo a cuentas' AFTER `idcomunidad`;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/reversa_anticipos', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (212, 1);

/**************************************************************************************/

ALTER TABLE `gc_listado_abonos`
	COLLATE='utf8_spanish_ci',
	CONVERT TO CHARSET utf8;
	
	
ALTER TABLE `gc_listado_pagos`
	COLLATE='utf8_spanish_ci',
	CONVERT TO CHARSET utf8;	
	
ALTER TABLE `gc_cartola_caja`
	COLLATE='utf8_spanish_ci',
	CONVERT TO CHARSET utf8;

/**********************************************************************************************/

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `aguinaldobruto` INT(11) NULL DEFAULT NULL AFTER `aguinaldo`;

		
/***********************************************************************************************/

ALTER TABLE `gc_listado_pagos`
	ADD COLUMN `idcomunidad` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;
update gc_listado_pagos lp
							inner join gc_cartola_pagos cp on lp.id = cp.idlistado
							inner join gc_cartola_caja c on cp.id = c.idpago
							set lp.idcomunidad = c.idcomunidad;
update gc_listado_pagos set idcomunidad = 1 where idcomunidad = 0;
ALTER TABLE `gc_listado_pagos`
	ADD CONSTRAINT `fk_listado_pagos_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`);

ALTER TABLE `gc_listado_abonos`
	ADD COLUMN `idcomunidad` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;

update gc_listado_abonos lp
inner join gc_cartola_propiedad cp on lp.id = cp.idlistado
inner join gc_cartola_caja c on cp.id = c.idabono
set lp.idcomunidad = c.idcomunidad;

update gc_listado_abonos set idcomunidad = 1 where idcomunidad = 0;
ALTER TABLE `gc_listado_abonos`
	ADD CONSTRAINT `fk_listado_abonos_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`);	


/****************************************************************************************/

ALTER TABLE `gc_comunidad`
	ADD COLUMN `fecinicio` DATE NULL DEFAULT '0' AFTER `maxfoliopago`,
	ADD COLUMN `idperiodoinicio` INT NULL DEFAULT '0' AFTER `fecinicio`;

ALTER TABLE `gc_comunidad`
	CHANGE COLUMN `idperiodoinicio` `idperiodoinicio` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `fecinicio`;	
update gc_comunidad set idperiodoinicio = 1;
update gc_comunidad set fecinicio = '2015-04-01';	



/******************************************************************************************/

ALTER TABLE `gc_lectura_servicio`
	ADD COLUMN `nuevomedidor` ENUM('Y','N') NULL DEFAULT 'N' AFTER `idcuenta`;


/*******************************************************************************************/


CREATE TABLE `gc_feriado` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`fecha` DATE NOT NULL,
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
;


INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/feriados', 'Feriados', '8', '1', '1', '14', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('213', '4');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/add_feriado', '8', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('214', '4');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_feriado', '8', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('215', '4');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/delete_feriado', '8', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('216', '4');
ALTER TABLE `gc_feriado`
	ADD UNIQUE INDEX `fecha` (`fecha`);

/********************************************************************************************/

ALTER TABLE `gc_personal`
	ADD COLUMN `fecinicvacaciones` DATE NOT NULL AFTER `idcargo`;
ALTER TABLE `gc_personal`
	ADD COLUMN `saldoinicvacaciones` INT NOT NULL AFTER `fecinicvacaciones`;	

ALTER TABLE `gc_personal`
	ALTER `saldoinicvacaciones` DROP DEFAULT;
ALTER TABLE `gc_personal`
	CHANGE COLUMN `saldoinicvacaciones` `saldoinicvacaciones` DOUBLE NOT NULL AFTER `fecinicvacaciones`;	


/**********************************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/vacaciones', 'Vacaciones', '8', '1', '1', '8', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('217', '1');
update gc_personal set fecinicvacaciones = fecingreso where fecinicvacaciones = '0000-00-00';
ALTER TABLE `gc_personal`
	ADD COLUMN `diasvactomados` INT NOT NULL AFTER `saldoinicvacaciones`;
ALTER TABLE `gc_personal`
	CHANGE COLUMN `diasvactomados` `diasvactomados` INT(11) NOT NULL DEFAULT '0' AFTER `saldoinicvacaciones`;	
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/solicita_vacaciones', '8', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('218', '1');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_solicita_vacaciones', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (219, 1);

CREATE TABLE `gc_cartola_vacaciones` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idpersonal` INT(10) UNSIGNED NOT NULL,
	`fecinicio` DATE NOT NULL,
	`fecfin` DATE NOT NULL,
	`dias` INT(11) NOT NULL,
	`comentarios` TEXT NOT NULL,
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`update_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_cartola_vacaciones_idpersonal_personal_id` (`idpersonal`),
	CONSTRAINT `fk_cartola_vacaciones_idpersonal_personal_id` FOREIGN KEY (`idpersonal`) REFERENCES `gc_personal` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

ALTER TABLE `gc_personal`
	CHANGE COLUMN `saldoinicvacaciones` `saldoinicvacaciones` DOUBLE NOT NULL DEFAULT '0' AFTER `fecinicvacaciones`;

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/cartola_vacaciones', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (220, 1);
UPDATE `gc_app` SET `orden`=15 WHERE  `id`=217;
UPDATE `gc_app` SET `name`='M&oacute;dulo Vacaciones' WHERE  `id`=217;


/**************************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/delete_vacaciones', '8', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('221', '1');


/***************************************************************************************************************/

ALTER TABLE `gc_personal`
	ADD COLUMN `diasprogresivos` INT NOT NULL DEFAULT '0' AFTER `saldoinicvacaciones`;

CREATE TABLE `gc_dias_progresivos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idpersonal` INT(11) UNSIGNED NOT NULL,
	`anno` INT(11) NULL DEFAULT NULL,
	`acumulado` INT(11) NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`created_at` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `fk_dias_progresivos_idpersonal_gc_personal_id` (`idpersonal`),
	CONSTRAINT `fk_dias_progresivos_idpersonal_gc_personal_id` FOREIGN KEY (`idpersonal`) REFERENCES `gc_personal` (`id`)
)
ENGINE=InnoDB
;


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/add_dia_progresivo', '8', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('222', '1');


/***/
ALTER TABLE `gc_dias_progresivos`
	CHANGE COLUMN `anno` `fechacumple` DATE NULL DEFAULT NULL AFTER `idpersonal`;
ALTER TABLE `gc_personal`
	ADD COLUMN `diasprogtomados` INT(11) NOT NULL DEFAULT '0' AFTER `diasvactomados`;	

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_dia_progresivo', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (223, 1);

/***/
ALTER TABLE `gc_personal`
	ADD COLUMN `fecultdiaprogresivo` DATE NULL DEFAULT NULL AFTER `diasprogresivos`;

################################################################
/************************************************************************************************/

UPDATE `gc_app` SET `name`='Vacaciones' WHERE  `id`=217;	
ALTER TABLE `gc_personal`
	ADD COLUMN `saldoinicvacprog` DOUBLE NOT NULL DEFAULT '0' AFTER `saldoinicvacaciones`;


/****************************************************************************************************/

ALTER TABLE `gc_dias_progresivos`
	CHANGE COLUMN `fechacumple` `fechainicio` DATE NULL DEFAULT NULL AFTER `idpersonal`,
	CHANGE COLUMN `acumulado` `dias` INT(11) NULL DEFAULT NULL AFTER `fechainicio`;	

ALTER TABLE `gc_personal`
	DROP COLUMN `fecultdiaprogresivo`;	
/***************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/delete_dias_progresivos', '8', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('224', '1');

ALTER TABLE `gc_dias_progresivos`
	ADD COLUMN `active` TINYINT NOT NULL DEFAULT '1' AFTER `dias`;

ALTER TABLE `gc_dias_progresivos`
	CHANGE COLUMN `fechainicio` `fechainicio` YEAR NULL DEFAULT NULL AFTER `idpersonal`;	

/***************************************************************************************/


UPDATE `gc_app` SET `menuid`=8, `orden`=16 WHERE  `id`=119;	

######################################### MOVIMIENTOS ##################################
/*************************************************************************************/

CREATE TABLE `gc_movimientos_personal` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL,
	`codprevired` INT(11) NOT NULL DEFAULT '0',
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


INSERT INTO `gc_movimientos_personal` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES (1, 'Contratación a plazo indefinido', 1, 1, '2017-02-15 22:44:42', '2017-02-15 22:44:46');
INSERT INTO `gc_movimientos_personal` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES (2, 'Retiro', 2, 1, '2017-02-15 22:44:42', '2017-02-15 22:48:02');
INSERT INTO `gc_movimientos_personal` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES (3, 'Subsidios', 3, 1, '2017-02-15 22:44:42', '2017-02-15 22:48:02');
INSERT INTO `gc_movimientos_personal` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES (4, 'Permiso Sin Goce de Sueldos', 4, 1, '2017-02-15 22:44:42', '2017-02-15 22:48:03');
INSERT INTO `gc_movimientos_personal` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES (5, 'Incorporación en el Lugar de Trabajo', 5, 1, '2017-02-15 22:44:42', '2017-02-15 22:48:03');
INSERT INTO `gc_movimientos_personal` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES (6, 'Accidentes del Trabajo ', 6, 1, '2017-02-15 22:44:42', '2017-02-15 22:48:04');
INSERT INTO `gc_movimientos_personal` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES (7, 'Contratación a plazo fijo', 7, 1, '2017-02-15 22:44:42', '2017-02-15 22:48:05');
INSERT INTO `gc_movimientos_personal` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES (8, 'Cambio Contrato plazo fijo a plazo indefinido', 8, 1, '2017-02-15 22:44:42', '2017-02-15 22:48:05');
INSERT INTO `gc_movimientos_personal` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES (9, 'Otros Movimientos (Ausentismos)', 11, 1, '2017-02-15 22:44:42', '2017-02-15 22:48:06');
INSERT INTO `gc_movimientos_personal` (`id`, `nombre`, `codprevired`, `active`, `created_at`, `updated_at`) VALUES (10, 'Reliquidación, Premio, Bono', 12, 1, '2017-02-15 22:44:42', '2017-02-15 22:48:07');

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/movimientos_personal', 'Movimientos del Personal', '8', '1', '1', '15', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('225', '1');

UPDATE `gc_app` SET `orden`='8' WHERE  `id`=225;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/add_movimiento_personal', '8', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('226', '1');


CREATE TABLE `gc_lista_movimiento_personal` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idpersonal` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`idmovimiento` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`comentario` TEXT NOT NULL,
	`fecmovimiento` DATE NOT NULL,
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `fk_lista_mov_personal_idpersonal_gc_personal_id` (`idpersonal`),
	INDEX `fk_lista_mov_personal_idmovimiento_gc_movimientos_personal_id` (`idmovimiento`),
	CONSTRAINT `fk_lista_mov_personal_idmovimiento_gc_movimientos_personal_id` FOREIGN KEY (`idmovimiento`) REFERENCES `gc_movimientos_personal` (`id`),
	CONSTRAINT `fk_lista_mov_personal_idpersonal_gc_personal_id` FOREIGN KEY (`idpersonal`) REFERENCES `gc_personal` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/submit_movimiento_personal', '8', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('227', '1');

/*****************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/ver_movimiento_personal', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (228, 1);


/*******************************************************************************************************/


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/delete_movimiento_personal', 8, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (229, 1);



################################ FIN ##############################

/***************************************************************************/

ALTER TABLE `gc_movimientos_personal`
	ADD COLUMN `rango` TINYINT NOT NULL DEFAULT '0' AFTER `codprevired`;
UPDATE `gc_movimientos_personal` SET `nombre`='Licencias Médicas' WHERE  `id`=3;	
UPDATE `gc_movimientos_personal` SET `rango`=1 WHERE  `id`=3;
UPDATE `gc_movimientos_personal` SET `rango`=1 WHERE  `id`=4;
UPDATE `gc_movimientos_personal` SET `rango`=1 WHERE  `id`=6;
UPDATE `gc_movimientos_personal` SET `rango`=1 WHERE  `id`=9;

ALTER TABLE `gc_lista_movimiento_personal`
	ADD COLUMN `fechastamovimiento` DATE NOT NULL AFTER `fecmovimiento`;



/**********************************************************************************/

ALTER TABLE `gc_tipo_deuda_detalle`
	ADD COLUMN `monto` INT(11) NOT NULL DEFAULT '0' AFTER `idpadre`;

CREATE TABLE `gc_um_espacio_comun` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NULL DEFAULT NULL,
	`activo` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


INSERT INTO `gc_um_espacio_comun` (`id`, `nombre`, `activo`, `updated_at`) VALUES (1, 'Minutos', 1, '2017-03-21 16:56:49');
INSERT INTO `gc_um_espacio_comun` (`id`, `nombre`, `activo`, `updated_at`) VALUES (2, 'Horas', 1, '2017-03-21 16:57:09');

ALTER TABLE `gc_tipo_deuda_detalle`
	ADD COLUMN `um_espcomun` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `idpadre`,
	ADD CONSTRAINT `fk_tipo_deuda_detalle_um_espcomun_umespacio_comun_id` FOREIGN KEY (`um_espcomun`) REFERENCES `gc_um_espacio_comun` (`id`);

ALTER TABLE `gc_tipo_deuda_detalle`
	CHANGE COLUMN `um_espcomun` `idumespcomun` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `idpadre`;	

/***********************************************************************************************************************/

ALTER TABLE `gc_um_espacio_comun`
	ADD COLUMN `idcomunidad` INT NULL DEFAULT NULL AFTER `nombre`;	
ALTER TABLE `gc_um_espacio_comun`
	CHANGE COLUMN `idcomunidad` `idcomunidad` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `nombre`,
	ADD CONSTRAINT `fk_um_espacio_comun_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`);	
ALTER TABLE `gc_deuda_propiedad`
	ADD COLUMN `idumespcomun` INT NULL AFTER `fechadeuda`;
ALTER TABLE `gc_deuda_propiedad`
	CHANGE COLUMN `idumespcomun` `idumespcomun` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `fechadeuda`,
	ADD CONSTRAINT `fk_deuda_propiedad_idumespcomun_unidadmedida_id` FOREIGN KEY (`idumespcomun`) REFERENCES `gc_um_espacio_comun` (`id`);
ALTER TABLE `gc_deuda_propiedad`
	ADD COLUMN `montoum` INT(11) NOT NULL DEFAULT '0' AFTER `idumespcomun`;			

ALTER TABLE `gc_deuda_propiedad`
	ADD COLUMN `cantidad` DOUBLE NOT NULL DEFAULT '0' AFTER `montoum`;
ALTER TABLE `gc_deuda_propiedad`
	CHANGE COLUMN `cantidad` `cantidadum` DOUBLE NOT NULL DEFAULT '0' AFTER `montoum`;		

/****************************************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/admin_esp_comunes', 'Unid. Medidas Esp Comunes', 1, 1, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
UPDATE `gc_app` SET `function`='admins/admin_um_esp_comunes' WHERE  `id`=230;
UPDATE `gc_app` SET `orden`=11 WHERE  `id`=230;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (230, 1);
UPDATE `gc_app` SET `name`='Unidad cobro - Esp Comunes' WHERE  `id`=230;
UPDATE `gc_app` SET `orden`=4 WHERE  `id`=230;
update gc_app set orden = orden + 1 where menuid = 1 and orden is not null and orden > 4 
UPDATE `gc_app` SET `orden`=5 WHERE  `id`=34;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/add_um_esp_comunes', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (231, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/submit_um_esp_comunes', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (232, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/delete_um_esp_comunes', 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (233, 1);

/*************************************************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('reports/reporte_egresos', 'Reporte Egresos', 5, 1, 1, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
UPDATE `gc_app` SET `orden`=9 WHERE  `id`=196;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (234, 1);
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('reports/export_egresos', '5', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('235', '1');

/****************************************************************************************************************/


update gc_app set orden = orden + 1 where menuid = 4 and orden is not null and orden >= 2;
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('accounts/honorarios_condominio', 'Honorarios Condominio', '4', '1', '1', '2', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('236', '1');
INSERT INTO `gc_tipo_documento_tributario` (`nombre`, `active`) VALUES ('Boleta Honorarios', '1');
UPDATE `gc_tipo_documento_tributario` SET `active`='0' WHERE  `id`=15;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/add_honorarios_condominio', '4', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('237', '1');

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/submit_honorarios_condominio', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (238, 1);
ALTER TABLE `gc_cuenta`
	ADD COLUMN `retencion` ENUM('cr','sr') NULL DEFAULT NULL AFTER `nrodocumento`;

ALTER TABLE `gc_cuenta`
	ADD COLUMN `retencionidctaasoc` INT UNSIGNED NULL DEFAULT NULL AFTER `retencion`;	
ALTER TABLE `gc_cuenta`
	CHANGE COLUMN `retencionidctaasoc` `retencionidctaasoc` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `retencion`;
ALTER TABLE `gc_cuenta`
	ADD CONSTRAINT `fk_cuenta_retencionidctaasoc_cuenta_id` FOREIGN KEY (`retencionidctaasoc`) REFERENCES `gc_cuenta` (`id`);		
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/delete_honorarios_condominio', '4', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('239', '1');

/**********************************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/edit_honorarios_condominio', 4, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (240, 1);

/****************************************************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/decjurada_honorarios', 'Declaraci&oacute;n Jurada Honorarios', 8, 1, 1, 17, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (241, 1);


/***************************************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('remuneraciones/decjurada_rentas', 'Declaraci&oacute;n Jurada Rentas', 8, 1, 1, 18, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (242, 1);


/*********************************************************************************/

UPDATE `gc_app` SET `name`='Estado Deuda/Abonos' WHERE  `id`=3;

/***************************************************************************************/

ALTER TABLE `gc_users`
	ADD COLUMN `inicpass` VARCHAR(250) NULL DEFAULT NULL AFTER `photo`;
#PROCESO
# 1.- SE CREA USUARIO CON INICPASS.  ESTO INDICA QUE NO SE HA ENVIADO MAIL (LISTO)
# 2.- SE AGREGA ICONO EN LISTADO DE COMUNIDADES, INDICANDO QUE EXISTEN USUARIOS SIN ENVIAR MAIL
# 3.- AL PRESIONAR BOTÓN, SE ENVIA MAIL, Y SE BORRA INICPASS



/***************************************************************************************/

ALTER TABLE `gc_cuenta`
	ADD COLUMN `cuotas` ENUM('sc','cc') NOT NULL DEFAULT 'sc' AFTER `montounidad`;
ALTER TABLE `gc_cuenta`
	ADD COLUMN `numcuotas` INT NOT NULL DEFAULT '0' AFTER `cuotas`;
ALTER TABLE `gc_cuenta`
	ADD COLUMN `totalcuenta` INT(11) NOT NULL DEFAULT '0' AFTER `numcuotas`;		
#CREAR UNA TABLA APARTE CON CUENTAS EN CUOTAS (1 REGISTRO POR TOTAL DE LA CUENTA) Y RELACIONAR CON TABLA CUENTAS


CREATE TABLE `gc_cuenta_cuotas` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(11) UNSIGNED NOT NULL,
	`formapago` ENUM('gc','fr','ci','sc','af') NOT NULL DEFAULT 'gc' COMMENT 'gc: gasto comun, fr: fondo reserva, ci: cobro individual, sc: sin cobro, af: activo fijo',
	`idproveedor` INT(11) UNSIGNED NULL DEFAULT NULL,
	`idtipodoctrib` INT(11) UNSIGNED NULL DEFAULT NULL,
	`nrodocumento` INT(11) UNSIGNED NULL DEFAULT NULL,
	`fecdocumento` DATE NULL DEFAULT NULL,
	`idtipodeudadetalle` INT(11) UNSIGNED NULL DEFAULT NULL,
	`numcuotas` INT(11) NOT NULL DEFAULT '0',
	`monto` INT(11) NOT NULL DEFAULT '0',
	`fecvencimiento` DATE NULL DEFAULT NULL,
	`descripcion` VARCHAR(100) NULL DEFAULT NULL,
	`nombrearchivo` VARCHAR(100) NULL DEFAULT NULL,
	`nombrerealarchivo` VARCHAR(100) NULL DEFAULT NULL,
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_cuenta_idproveedor_proveedor_id` (`idproveedor`),
	INDEX `fk_deuda_idtipodeudadetalle_tipo_deuda_detalle_id` (`idtipodeudadetalle`),
	INDEX `fk_cuenta_idtipodoctrib_tipo_documento_tributario_id` (`idtipodoctrib`),
	INDEX `fk_cuenta_idcomunidad_comunidad_id` (`idcomunidad`),
	CONSTRAINT `gc_cuenta_cuotas_ibfk_1` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`),
	CONSTRAINT `gc_cuenta_cuotas_ibfk_4` FOREIGN KEY (`idproveedor`) REFERENCES `gc_proveedor` (`id`),
	CONSTRAINT `gc_cuenta_cuotas_ibfk_5` FOREIGN KEY (`idtipodoctrib`) REFERENCES `gc_tipo_documento_tributario` (`id`),
	CONSTRAINT `gc_cuenta_cuotas_ibfk_8` FOREIGN KEY (`idtipodeudadetalle`) REFERENCES `gc_tipo_deuda_detalle` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


ALTER TABLE `gc_cuenta`
	ADD COLUMN `idcuentacuotas` INT UNSIGNED NULL DEFAULT NULL AFTER `cuotas`;

ALTER TABLE `gc_cuenta`
	ADD CONSTRAINT `fk_cuenta_idcuentacuotas_cuenta_cuotas_id` FOREIGN KEY (`idcuentacuotas`) REFERENCES `gc_cuenta_cuotas` (`id`);



/******************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/delete_cuenta_cuotas', '5', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('242', '1');


/***********************************************************************************************************/

INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('67', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('68', '1');		
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('88', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('69', '1');


/******************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/envio_masivo_mails', '1', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('243', '4');


/*********************************************************************************************************/

ALTER TABLE `gc_cuenta`
	CHANGE COLUMN `descripcion` `descripcion` VARCHAR(150) NULL DEFAULT NULL AFTER `fecvencimiento`;

/***************************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/edit_cuenta_cuotas', '5', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('244', '1');


/***********************************************************************************************************************/
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/validate_cuenta_cuotas', '4', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('accounts/submit_cuenta_cuotas', '4', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('245', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('246', '1');


/***********************************************************************************************************************/

INSERT INTO `gc_menu` (`name`, `img`, `valid`, `orden`) VALUES ('Comunidad', 'fa-home', '1', '2');
UPDATE `gc_menu` SET `orden`='3' WHERE  `id`=1;
UPDATE `gc_menu` SET `orden`='4' WHERE  `id`=4;
UPDATE `gc_menu` SET `orden`='5' WHERE  `id`=2;
UPDATE `gc_menu` SET `orden`='6' WHERE  `id`=7;
UPDATE `gc_menu` SET `orden`='7' WHERE  `id`=5;
UPDATE `gc_menu` SET `orden`='8' WHERE  `id`=8;
UPDATE `gc_menu` SET `orden`='9' WHERE  `id`=9;
UPDATE `gc_app` SET `menuid`='10' WHERE  `id`=1;
UPDATE `gc_app` SET `menuid`='10' WHERE  `id`=63;
UPDATE `gc_app` SET `menuid`='10' WHERE  `id`=67;

/*****************************************************************************************************************/


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('remuneraciones/libro', '5', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('247', '1');

/********************************************************************************************************************************/


ALTER TABLE `gc_comunidad`
	ADD COLUMN `fecvencimiento` DATE NOT NULL AFTER `active`;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('96', '1');
	
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/confirma_carga_propiedades', '1', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('248', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('248', '4');


/**********************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/envio_masivo_mails_usuarios', '10', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('249', '1');


/***********************************************************************************/


update gc_comunidad set fecvencimiento = '2017-12-31';


/****************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/pay_account', '1', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('250', '4');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/submit_pay_account', '1', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('251', '4');


CREATE TABLE `gc_log_pagos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(11) UNSIGNED NOT NULL,
	`numpagos` INT(11) NOT NULL,
	`fecvencimientoactual` DATE NOT NULL,
	`fecvencimientonuevo` DATE NOT NULL,
	`fechapago` DATE NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_log_pagos_idcomunidad_comunidad_id` (`idcomunidad`),
	CONSTRAINT `fk_log_pagos_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


/************************************************************************************************/

ALTER TABLE `gc_comunidad`
	ADD COLUMN `fecaviso` DATE NOT NULL AFTER `fecvencimiento`;


ALTER TABLE `gc_comunidad`
	CHANGE COLUMN `created_at` `created_at` DATETIME NOT NULL AFTER `fecaviso`;


ALTER TABLE `gc_comunidad`
	ALTER `created_at` DROP DEFAULT;
ALTER TABLE `gc_comunidad`
	CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;	


/****************************************************************************************************/

ALTER TABLE `gc_comunidad`
	ADD COLUMN `logo` VARCHAR(50) NOT NULL AFTER `fecaviso`;


/********************************************************************************************************/


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/ver_comprobante_muestra', '1', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('252', '1');
ALTER TABLE `gc_comunidad`
	ADD COLUMN `obscomprobante` TEXT NOT NULL AFTER `logo`;


/***************************************************************************************************************/


ALTER TABLE `gc_remuneracion`
	ADD COLUMN `sueldoimponibleimposiciones` INT(11) NULL DEFAULT NULL AFTER `sueldonoimponible`;


update gc_remuneracion set sueldoimponibleimposiciones = sueldoimponible where sueldoimponibleimposiciones is null;


/******************************************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('payments/webpay', '2', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('253', '1');


INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('payments/pay_webpay', '2', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('254', '1');

ALTER TABLE `gc_comunidad`
	ADD COLUMN `montocuotaoferta` INT NOT NULL DEFAULT '0' AFTER `obscomprobante`;



CREATE TABLE `gc_tabla_cobro` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`desde` INT(11) NOT NULL DEFAULT '0',
	`hasta` INT(11) NOT NULL DEFAULT '0',
	`valor` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM


INSERT INTO `gc_tabla_cobro` (`desde`, `hasta`, `valor`) VALUES (0, 50, 40000);
INSERT INTO `gc_tabla_cobro` (`desde`, `hasta`, `valor`) VALUES (51, 100, 50000);
INSERT INTO `gc_tabla_cobro` (`desde`, `hasta`, `valor`) VALUES (101, 150, 60000);
INSERT INTO `gc_tabla_cobro` (`desde`, `hasta`, `valor`) VALUES (151, 200, 65000);
INSERT INTO `gc_tabla_cobro` (`desde`, `hasta`, `valor`) VALUES (201, 9999999, 70000);
ALTER TABLE `gc_log_pagos`
	ADD COLUMN `montopago` INT NOT NULL AFTER `fechapago`,
	ADD COLUMN `tokentranskbank` VARCHAR(100) NOT NULL AFTER `montopago`,
	ADD COLUMN `aceptacionpago` INT NOT NULL AFTER `tokentranskbank`;

ALTER TABLE `gc_log_pagos`
	CHANGE COLUMN `montopago` `montopago` INT(11) NOT NULL AFTER `fechapago`,
	CHANGE COLUMN `tokentranskbank` `tokentranskbank` VARCHAR(100) NULL AFTER `montopago`;

ALTER TABLE `gc_log_pagos`
	CHANGE COLUMN `montopago` `montopago` INT(11) NOT NULL DEFAULT '0' AFTER `fechapago`;

ALTER TABLE `gc_log_pagos`
	ALTER `aceptacionpago` DROP DEFAULT;
ALTER TABLE `gc_log_pagos`
	CHANGE COLUMN `aceptacionpago` `aceptacionpago` DATETIME NULL AFTER `tokentranskbank`;	





/******************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `orden`, `created_at`) VALUES ('remuneraciones/reversar_aprobacion_remuneraciones', '8', '0', '1', '', '2018-01-30 00:41:57');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('255', '1');


/*****************************************************************************************/

ALTER TABLE `gc_comunidad`
	ADD COLUMN `firma` VARCHAR(50) NOT NULL AFTER `logo`;


/******************************************************************************************/


INSERT INTO `gc_tipo_documento_tributario` (`id`, `nombre`, `active`) VALUES ('16', 'Factura Electrónica', '1');
INSERT INTO `gc_tipo_documento_tributario` (`id`, `nombre`, `active`) VALUES ('17', 'Boleta Electrónica', '1');
INSERT INTO `gc_tipo_documento_tributario` (`id`, `nombre`, `active`) VALUES ('18', 'Nota de Crédito Electrónica', '1');
INSERT INTO `gc_tipo_documento_tributario` (`nombre`, `active`) VALUES ('Nota de Débito Electrónica', '1');


/*****************************************************************************************************/
CREATE TABLE `gc_email_propiedad` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idpropiedad` INT(11) UNSIGNED NULL DEFAULT NULL,
	`email` VARCHAR(50) NULL DEFAULT NULL,
	`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_email_propiedad_idpropiedad_propiedad_id` (`idpropiedad`),
	CONSTRAINT `fk_email_propiedad_idpropiedad_propiedad_id` FOREIGN KEY (`idpropiedad`) REFERENCES `gc_propiedad` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

/*****************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`) VALUES ('reports/reenviar_comprobante', '5', '0', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('257', '1');


ALTER TABLE `gc_parametros_generales`
	ADD COLUMN `topeimponibleips` DOUBLE NOT NULL DEFAULT '0' AFTER `topeimponible`,
	ADD COLUMN `topeimponibleafc` DOUBLE NOT NULL DEFAULT '0' AFTER `topeimponibleips`;


/*******************************************************/
DELETE FROM `gc_role` WHERE  `id`=344;	

ALTER TABLE `gc_remuneracion`
	ADD COLUMN `sueldoimponibleafc` INT(11) NULL DEFAULT NULL AFTER `sueldoimponibleimposiciones`,
	ADD COLUMN `sueldoimponibleips` INT(11) NULL DEFAULT NULL AFTER `sueldoimponibleafc`;	


/******************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/comunicados', 'Comunicados', '10', '1', '1', '9', '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('258', '1');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/add_comunicado', '10', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('259', '1');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/submit_comunicados', '10', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('260', '1');


CREATE TABLE `gc_comunicados` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(10) UNSIGNED NOT NULL,
	`titulo` VARCHAR(500) NOT NULL,
	`txt_comunicado` TEXT NOT NULL,
	`estado` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_comunicado_idcomunidad_comunidad_id` (`idcomunidad`),
	CONSTRAINT `fk_comunicado_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
ENGINE=InnoDB
;



ALTER TABLE `gc_comunicados`
	ADD COLUMN `fec_marca_envio` DATETIME NULL DEFAULT NULL AFTER `updated_at`,
	ADD COLUMN `fec_envio` DATETIME NULL DEFAULT NULL AFTER `fec_marca_envio`;
ALTER TABLE `gc_comunicados`
	ADD COLUMN `delay_envio_min` INT NOT NULL AFTER `updated_at`;	

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/send_comunicado', '10', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');	
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('261', '1');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/anular_envio_comunicado', '10', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('262', '1');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/delete_comunicado', '10', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('263', '1');
alter table gc_comunicados add active int default 1;


/*******************************************************************/

INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('77', '1');
UPDATE `gc_app` SET `orden`='11' WHERE  `id`=258;
UPDATE `gc_app` SET `orden`='10' WHERE  `id`=67;
UPDATE `gc_app` SET `menuid`='10', `orden`='8' WHERE  `id`=77;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/carga_bodegas', '1', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('264', '1');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/confirma_carga_bodegas', '1', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('265', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('80', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('78', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('79', '1');
UPDATE `gc_app` SET `menuid`='10', `orden`='9' WHERE  `id`=81;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('81', '1');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/carga_estacionamientos', '1', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('266', '1');
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/confirma_carga_estacionamientos', '1', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('267', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('84', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('82', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('83', '1');


/*************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/ver_envio_comunicado', '10', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('268', '1');




/******************************************************************************/

SELECT * FROM `folios_caf` WHERE updated_at >= '2018-05-23 23:00:00'
ALTER TABLE `gc_periodo_estado`
	ADD COLUMN `delay_envio_min` INT NOT NULL AFTER `interes_diario`;	

	ALTER TABLE `gc_periodo_estado`
	ADD COLUMN `envia` DATETIME NULL DEFAULT NULL AFTER `publica`;
update gc_periodo_estado set envia = publica;		



/******************************************************************************/


CREATE TABLE `gc_log_avisos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(10) UNSIGNED NULL DEFAULT NULL,
	`fecaviso` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `fk_gc_comunidad_id_log_pagos_idcomunidad` (`idcomunidad`),
	CONSTRAINT `fk_gc_comunidad_id_log_pagos_idcomunidad` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
ENGINE=InnoDB
;

/******************************************************************************/

INSERT INTO `gc_forma_pago` (`id`, `nombre`, `activo`) VALUES ('5', 'Tarjeta de Débito', '1');
INSERT INTO `gc_forma_pago` (`nombre`, `activo`) VALUES ('Tarjeta de Crédito', '1');

ALTER TABLE `gc_forma_pago`
	ADD COLUMN `abono` INT(11) NULL DEFAULT '1' AFTER `nombre`,
	ADD COLUMN `pago` INT(11) NULL DEFAULT '1' AFTER `abono`;

update gc_forma_pago set abono = 1, pago = 1;

INSERT INTO `gc_forma_pago` (`nombre`, `abono`, `activo`) VALUES ('Cargo en Cuenta Corriente (PAC)', '0', '1');	
INSERT INTO `gc_forma_pago` (`nombre`, `abono`, `activo`) VALUES ('Pago por Nómina', '0', '1');
UPDATE `gc_forma_pago` SET `pago`='0' WHERE  `id`=5;
UPDATE `gc_forma_pago` SET `pago`='0' WHERE  `id`=6;




/******************************************************************************************************/


CREATE TABLE `gc_clasifica_tipo_cuenta` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NULL DEFAULT NULL,
	`idcomunidad` INT(10) UNSIGNED NOT NULL,
	`updated_at` DATETIME NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `fk_cla_tipo_cuenta_idcomunidad_comunidad_id` (`idcomunidad`),
	CONSTRAINT `fk_cla_tipo_cuenta_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;

INSERT INTO `gc_clasifica_tipo_cuenta` (`nombre`, `idcomunidad`, `updated_at`) VALUES ('Gasto Común Ordinario', 19, '2018-06-24 19:53:36');
INSERT INTO `gc_clasifica_tipo_cuenta` (`nombre`, `idcomunidad`, `updated_at`) VALUES ('Gasto Común Extraordinario', 19, '2018-06-24 19:54:05');
INSERT INTO `gc_clasifica_tipo_cuenta` (`nombre`, `idcomunidad`, `updated_at`) VALUES ('Ingresos/Rebajas de  Gastos', 19, '2018-06-24 19:54:31');



ALTER TABLE `gc_tipo_deuda_detalle`
	ADD COLUMN `id_clasif_cuenta` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `idumespcomun`;

ALTER TABLE `gc_tipo_deuda_detalle`
	ADD CONSTRAINT `fk_tipo_deuda_detalle_id_clasif_cuenta_clasif_id` FOREIGN KEY (`idclasifcuenta`) REFERENCES `gc_clasifica_tipo_cuenta` (`id`);


ALTER TABLE `gc_tipo_deuda_detalle`
	CHANGE COLUMN `id_clasif_cuenta` `idclasifcuenta` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `idumespcomun`;



/**********************************************************************************/


CREATE TABLE `gc_tipo_deuda_clasif_comunidad` (
	`idtipodeuda` INT(11) UNSIGNED NOT NULL,
	`idclasif` INT(11) UNSIGNED NOT NULL,
	`idcomunidad` INT(11) UNSIGNED NOT NULL,
	INDEX `gc_deuda_clasif_idclasif_clasif_id` (`idclasif`),
	INDEX `gc_deuda_clasif_idcomunidad_comunidad_id` (`idcomunidad`),
	CONSTRAINT `gc_deuda_clasif_idclasif_clasif_id` FOREIGN KEY (`idclasif`) REFERENCES `gc_clasifica_tipo_cuenta` (`id`),
	CONSTRAINT `gc_deuda_clasif_idcomunidad_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


insert into gc_tipo_deuda_clasif_comunidad
select id, idclasifcuenta, idcomunidad from gc_tipo_deuda_detalle where idclasifcuenta is not null
		


/**************************************************************************************/


alter table gc_log_pagos add envia_comprobante int default 0 after aceptacionpago;
update gc_log_pagos set envia_comprobante = 1;
alter table gc_log_pagos add fec_envio datetime after envia_comprobante;


/*************************************************************************************/

update gc_tabla_cobro set valor = 27500;


/********************************************************************************************/

UPDATE `gc_menu` SET `name`='Informaci&oacute;n/Reportes', `orden`='10' WHERE  `id`=5;


/**************************************************************************************/

ALTER TABLE `gc_personal`
	ADD COLUMN `fecfiniquito` DATE NOT NULL AFTER `fecingreso`;

ALTER TABLE `gc_personal`
	CHANGE COLUMN `fecfiniquito` `fecfiniquito` DATE NULL AFTER `fecingreso`;

update gc_personal set fecfiniquito = null

ALTER TABLE `gc_log_personal`
	ADD COLUMN `fecfiniquito` DATE NULL AFTER `observacion`;


/***************************************************************************************************/

alter table gc_deuda_propiedad add enviacorreo int default 0 after monto;
update gc_deuda_propiedad set enviacorreo = 0;


/**********************************************************************************************/

INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`) VALUES ('admins/accion_mora', 'Acci&oacute;n Mora', '10', '1', '1', '12');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('269', '1');

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`) VALUES ('admins/submit_accion_mora', '10', '0', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('270', '1');

ALTER TABLE `gc_comunidad`
	ADD COLUMN `mes_aldia` INT(11) NOT NULL DEFAULT '-1' AFTER `montocuotaoferta`,
	ADD COLUMN `mes_moroso` INT(11) NOT NULL DEFAULT '-1' AFTER `mes_aldia`,
	ADD COLUMN `mes_corteluz` INT(11) NOT NULL DEFAULT '-1' AFTER `mes_moroso`,
	ADD COLUMN `mes_prejudicial` INT(11) NOT NULL DEFAULT '-1' AFTER `mes_corteluz`,
	ADD COLUMN `mes_judicial` INT(11) NOT NULL DEFAULT '-1' AFTER `mes_prejudicial`;


/******************************************************************************/

ALTER TABLE `gc_comunidad`
	ADD COLUMN `codigo_comercio` INT(11) NOT NULL DEFAULT '0' AFTER `mes_judicial`;	

update 	gc_comunidad
set		codigo_comercio = 0;

UPDATE `gc_comunidad` SET `codigo_comercio`='597020000541' WHERE  `id`=20;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`) VALUES ('payments/add_abono_webpay', '2', '0', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('271', '3');
ALTER TABLE `gc_comunidad`
	ADD COLUMN `enviroment` VARCHAR(50) NOT NULL AFTER `codigo_comercio`;
ALTER TABLE `gc_comunidad`
	ADD COLUMN `private_key` TEXT NOT NULL AFTER `enviroment`;
ALTER TABLE `gc_comunidad`
	ADD COLUMN `public_cert` TEXT NOT NULL AFTER `private_key`;
ALTER TABLE `gc_comunidad`
	ADD COLUMN `webpay_cert` TEXT NOT NULL AFTER `public_cert`;
UPDATE `ggcc_prod`.`gc_comunidad` SET `enviroment`='INTEGRACION' WHERE  `id`=17;
UPDATE `ggcc_prod`.`gc_comunidad` SET `private_key`='-----BEGIN RSA PRIVATE KEY-----\r\nMIIEpQIBAAKCAQEA0ClVcH8RC1u+KpCPUnzYSIcmyXI87REsBkQzaA1QJe4w/B7g\r\n6KvKV9DaqfnNhMvd9/ypmGf0RDQPhlBbGlzymKz1xh0lQBD+9MZrg8Ju8/d1k0pI\r\nb1QLQDnhRgR2T14ngXpP4PIQKtq7DsdHBybFU5vvAKVqdHvImZFzqexbZjXWxxhT\r\n+/sGcD4Vs673fc6B+Xj2UrKF7QyV5pMDq0HCCLTMmafWAmNrHyl6imQM+bqC12gn\r\nEEAEkrJiSO6P/21m9iDJs5KQanpJby0aGW8mocYRHDMHZjtTiIP0+JAJgL9KsH+r\r\nXdk2bT7aere7TzOK/bEwhkYEXnMMt/65vV6AfwIDAQABAoIBAHnIlOn6DTi99eXl\r\nKVSzIb5dA747jZWMxFruL70ifM+UKSh30FGPoBP8ZtGnCiw1ManSMk6uEuSMKMEF\r\n5iboVi4okqnTh2WSC/ec1m4BpPQqxKjlfrdTTjnHIxrZpXYNucMwkeci93569ZFR\r\n2SY/8pZV1mBkZoG7ocLmq+qwE1EaBEL/sXMvuF/h08nJ71I4zcclpB8kN0yFrBCW\r\n7scqOwTLiob2mmU2bFHOyyjTkGOlEsBQxhtVwVEt/0AFH/ucmMTP0vrKOA0HkhxM\r\noeR4k2z0qwTzZKXuEZtsau8a/9B3S3YcgoSOhRP/VdY1WL5hWDHeK8q1Nfq2eETX\r\njnQ4zjECgYEA7z2/biWe9nDyYDZM7SfHy1xF5Q3ocmv14NhTbt8iDlz2LsZ2JcPn\r\nEMV++m88F3PYdFUOp4Zuw+eLJSrBqfuPYrTVNH0v/HdTqTS70R2YZCFb9g0ryaHV\r\nTRwYovu/oQMV4LBSzrwdtCrcfUZDtqMYmmZfEkdjCWCEpEi36nlG0JMCgYEA3r49\r\no+soFIpDqLMei1tF+Ah/rm8oY5f4Wc82kmSgoPFCWnQEIW36i/GRaoQYsBp4loue\r\nvyPuW+BzoZpVcJDuBmHY3UOLKr4ZldOn2KIj6sCQZ1mNKo5WuZ4YFeL5uyp9Hvio\r\nTCPGeXghG0uIk4emSwolJVSbKSRi6SPsiANff+UCgYEAvNMRmlAbLQtsYb+565xw\r\nNvO3PthBVL4dLL/Q6js21/tLWxPNAHWklDosxGCzHxeSCg9wJ40VM4425rjebdld\r\nDF0Jwgnkq/FKmMxESQKA2tbxjDxNCTGv9tJsJ4dnch/LTrIcSYt0LlV9/WpN24LS\r\n0lpmQzkQ07/YMQosDuZ1m/0CgYEAu9oHlEHTmJcO/qypmu/ML6XDQPKARpY5Hkzy\r\ngj4ZdgJianSjsynUfsepUwK663I3twdjR2JfON8vxd+qJPgltf45bknziYWvgDtz\r\nt/Duh6IFZxQQSQ6oN30MZRD6eo4X3dHp5eTaE0Fr8mAefAWQCoMw1q3m+ai1PlhM\r\nuFzX4r0CgYEArx4TAq+Z4crVCdABBzAZ7GvvAXdxvBo0AhD9IddSWVTCza972wta\r\n5J2rrS/ye9Tfu5j2IbTHaLDz14mwMXr1S4L39UX/NifLc93KHie/yjycCuu4uqNo\r\nMtdweTnQt73lN2cnYedRUhw9UTfPzYu7jdXCUAyAD4IEjFQrswk2x04=\r\n-----END RSA PRIVATE KEY-----' WHERE  `id`=17;
UPDATE `ggcc_prod`.`gc_comunidad` SET `public_cert`='-----BEGIN CERTIFICATE-----\r\nMIIDujCCAqICCQCZ42cY33KRTzANBgkqhkiG9w0BAQsFADCBnjELMAkGA1UEBhMC\r\nQ0wxETAPBgNVBAgMCFNhbnRpYWdvMRIwEAYDVQQKDAlUcmFuc2JhbmsxETAPBgNV\r\nBAcMCFNhbnRpYWdvMRUwEwYDVQQDDAw1OTcwMjAwMDA1NDExFzAVBgNVBAsMDkNh\r\nbmFsZXNSZW1vdG9zMSUwIwYJKoZIhvcNAQkBFhZpbnRlZ3JhZG9yZXNAdmFyaW9z\r\nLmNsMB4XDTE2MDYyMjIxMDkyN1oXDTI0MDYyMDIxMDkyN1owgZ4xCzAJBgNVBAYT\r\nAkNMMREwDwYDVQQIDAhTYW50aWFnbzESMBAGA1UECgwJVHJhbnNiYW5rMREwDwYD\r\nVQQHDAhTYW50aWFnbzEVMBMGA1UEAwwMNTk3MDIwMDAwNTQxMRcwFQYDVQQLDA5D\r\nYW5hbGVzUmVtb3RvczElMCMGCSqGSIb3DQEJARYWaW50ZWdyYWRvcmVzQHZhcmlv\r\ncy5jbDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBANApVXB/EQtbviqQ\r\nj1J82EiHJslyPO0RLAZEM2gNUCXuMPwe4OirylfQ2qn5zYTL3ff8qZhn9EQ0D4ZQ\r\nWxpc8pis9cYdJUAQ/vTGa4PCbvP3dZNKSG9UC0A54UYEdk9eJ4F6T+DyECrauw7H\r\nRwcmxVOb7wClanR7yJmRc6nsW2Y11scYU/v7BnA+FbOu933Ogfl49lKyhe0MleaT\r\nA6tBwgi0zJmn1gJjax8peopkDPm6gtdoJxBABJKyYkjuj/9tZvYgybOSkGp6SW8t\r\nGhlvJqHGERwzB2Y7U4iD9PiQCYC/SrB/q13ZNm0+2nq3u08ziv2xMIZGBF5zDLf+\r\nub1egH8CAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAdgNpIS2NZFx5PoYwJZf8faze\r\nNmKQg73seDGuP8d8w/CZf1Py/gsJFNbh4CEySWZRCzlOKxzmtPTmyPdyhObjMA8E\r\nAdps9DtgiN2ITSF1HUFmhMjI5V7U2L9LyEdpUaieYyPBfxiicdWz2YULVuOYDJHR\r\nn05jlj/EjYa5bLKs/yggYiqMkZdIX8NiLL6ZTERIvBa6azDKs6yDsCsnE1M5tzQI\r\nVVEkZtEfil6E1tz8v3yLZapLt+8jmPq1RCSx3Zh4fUkxBTpUW/9SWUNEXbKK7bB3\r\nzfB3kGE55K5nxHKfQlrqdHLcIo+vdShATwYnmhUkGxUnM9qoCDlB8lYu3rFi9w==\r\n-----END CERTIFICATE-----' WHERE  `id`=17;
UPDATE `ggcc_prod`.`gc_comunidad` SET `webpay_cert`='-----BEGIN CERTIFICATE-----\r\nMIIDKTCCAhECBFZl7uIwDQYJKoZIhvcNAQEFBQAwWTELMAkGA1UEBhMCQ0wxDjAMBgNVBAgMBUNo\r\naWxlMREwDwYDVQQHDAhTYW50aWFnbzEMMAoGA1UECgwDa2R1MQwwCgYDVQQLDANrZHUxCzAJBgNV\r\nBAMMAjEwMB4XDTE1MTIwNzIwNDEwNloXDTE4MDkwMjIwNDEwNlowWTELMAkGA1UEBhMCQ0wxDjAM\r\nBgNVBAgMBUNoaWxlMREwDwYDVQQHDAhTYW50aWFnbzEMMAoGA1UECgwDa2R1MQwwCgYDVQQLDANr\r\nZHUxCzAJBgNVBAMMAjEwMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAizJUWTDC7nfP\r\n3jmZpWXFdG9oKyBrU0Bdl6fKif9a1GrwevThsU5Dq3wiRfYvomStNjFDYFXOs9pRIxqX2AWDybjA\r\nX/+bdDTVbM+xXllA9stJY8s7hxAvwwO7IEuOmYDpmLKP7J+4KkNH7yxsKZyLL9trG3iSjV6Y6SO5\r\nEEhUsdxoJFAow/h7qizJW0kOaWRcljf7kpqJAL3AadIuqV+hlf+Ts/64aMsfSJJA6xdbdp9ddgVF\r\noqUl1M8vpmd4glxlSrYmEkbYwdI9uF2d6bAeaneBPJFZr6KQqlbbrVyeJZqmMlEPy0qPco1TIxrd\r\nEHlXgIFJLyyMRAyjX9i4l70xjwIDAQABMA0GCSqGSIb3DQEBBQUAA4IBAQBn3tUPS6e2USgMrPKp\r\nsxU4OTfW64+mfD6QrVeBOh81f6aGHa67sMJn8FE/cG6jrUmX/FP1/Cpbpvkm5UUlFKpgaFfHv+Kg\r\nCpEvgcRIv/OeIi6Jbuu3NrPdGPwzYkzlOQnmgio5RGb6GSs+OQ0mUWZ9J1+YtdZc+xTga0x7nsCT\r\n5xNcUXsZKhyjoKhXtxJm3eyB3ysLNyuL/RHy/EyNEWiUhvt1SIePnW+Y4/cjQWYwNqSqMzTSW9TP\r\n2QR2bX/W2H6ktRcLsgBK9mq7lE36p3q6c9DtZJE+xfA4NGCYWM9hd8pbusnoNO7AFxJZOuuvLZI7\r\nJvD7YLhPvCYKry7N6x3l\r\n-----END CERTIFICATE-----' WHERE  `id`=17;			
ALTER TABLE `gc_comunidad`
	CHANGE COLUMN `codigo_comercio` `codigo_comercio` BIGINT NOT NULL DEFAULT '0' AFTER `mes_judicial`;
INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('payments/webpay_prop', '2', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('272', '3');

CREATE TABLE `gc_log_trans_abonos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idpropiedad` INT(11) NOT NULL DEFAULT '0',
	`montopago` BIGINT(20) NOT NULL DEFAULT '0',
	`periodo` INT(11) NULL DEFAULT '0',
	`pagototal` VARCHAR(10) NULL DEFAULT NULL,
	`tokentranskbank` VARCHAR(100) NOT NULL,
	`aceptacionpago` DATETIME NOT NULL,
	`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
/*******************************************************************************/

INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('258', '3');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('268', '3');


/*******************************************************/

UPDATE `gc_menu` SET `name`='Egresos Comunidad' WHERE  `id`=4;
INSERT INTO `gasto_ggcc`.`gc_menu` (`id`, `name`, `img`, `valid`, `orden`) VALUES ('11', 'Configuraci&oacute;n Comunidad', 'fa-wrench', '1', '11');
UPDATE `gc_app` SET `menuid`='11' WHERE  `id`=40;
UPDATE `gc_app` SET `menuid`='11' WHERE  `id`=273;
UPDATE `gc_app` SET `menuid`='11' WHERE  `id`=77;
UPDATE `gc_app` SET `menuid`='11' WHERE  `id`=81;
UPDATE `gc_app` SET `menuid`='1' WHERE  `id`=277;
UPDATE `gc_app` SET `menuid`='1' WHERE  `id`=282;
UPDATE `gc_app` SET `menuid`='11' WHERE  `id`=67;
UPDATE `gc_app` SET `menuid`='11' WHERE  `id`=269;
UPDATE `gc_app` SET `menuid`='4' WHERE  `id`=26;
UPDATE `gc_app` SET `menuid`='4' WHERE  `id`=29;
UPDATE `gc_app` SET `menuid`='11' WHERE  `id`=230;
UPDATE `gc_app` SET `menuid`='4' WHERE  `id`=34;
UPDATE `gc_app` SET `menuid`='11' WHERE  `id`=134;
UPDATE `gc_app` SET `menuid`='5' WHERE  `id`=63;
UPDATE `gc_app` SET `menuid`='5' WHERE  `id`=40;
UPDATE `gc_app` SET `menuid`='11' WHERE  `id`=63;
UPDATE `gc_app` SET `menuid`='11' WHERE  `id`=29;


/* #1634496361: Access violation at address 0000001000000000 in module 'heidisql.exe'. Execution of address 0000001000000000 Message CharCode:13 Msg:256 */
UPDATE `gc_menu` SET `orden`='5' WHERE  `id`=8;
UPDATE `gc_menu` SET `orden`='6' WHERE  `id`=2;
UPDATE `gc_menu` SET `orden`='7' WHERE  `id`=7;
UPDATE `gc_menu` SET `orden`='8' WHERE  `id`=5;
UPDATE `gc_menu` SET `orden`='9' WHERE  `id`=11;
UPDATE `gc_menu` SET `orden`='10' WHERE  `id`=9;


UPDATE `gc_app` SET `orden`='1' WHERE  `id`=26;
UPDATE `gc_app` SET `orden`='2' WHERE  `id`=34;
UPDATE `gc_app` SET `orden`='3' WHERE  `id`=74;
UPDATE `gc_app` SET `orden`='4' WHERE  `id`=236;
UPDATE `gc_app` SET `orden`='5' WHERE  `id`=13;
UPDATE `gc_app` SET `orden`='6' WHERE  `id`=148;
UPDATE `gc_app` SET `orden`='7' WHERE  `id`=15;
UPDATE `gc_app` SET `orden`='8' WHERE  `id`=185;
UPDATE `gc_app` SET `orden`='9' WHERE  `id`=17;




UPDATE `gc_app` SET `orden`='1' WHERE  `id`=29;
UPDATE `gc_app` SET `orden`='2' WHERE  `id`=230;
UPDATE `gc_app` SET `orden`='3' WHERE  `id`=63;
UPDATE `gc_app` SET `orden`='4' WHERE  `id`=77;
UPDATE `gc_app` SET `orden`='5' WHERE  `id`=273;
UPDATE `gc_app` SET `orden`='6' WHERE  `id`=81;
UPDATE `gc_app` SET `orden`='7' WHERE  `id`=67;
UPDATE `gc_app` SET `orden`='8' WHERE  `id`=134;
UPDATE `gc_app` SET `orden`='9' WHERE  `id`=269;


UPDATE `gc_app` SET `orden`='1' WHERE  `id`=63;
UPDATE `gc_app` SET `orden`='2' WHERE  `id`=77;
UPDATE `gc_app` SET `orden`='3' WHERE  `id`=81;
UPDATE `gc_app` SET `orden`='4' WHERE  `id`=273;
UPDATE `gc_app` SET `orden`='5' WHERE  `id`=67;
UPDATE `gc_app` SET `orden`='6' WHERE  `id`=29;
UPDATE `gc_app` SET `orden`='7' WHERE  `id`=230;


/***********************************************************/
INSERT INTO `gc_region` (`idregion`, `nombre`) VALUES ('16', 'Ñuble');
ALTER TABLE `gc_afp`
	ADD COLUMN `codlre` INT(11) NOT NULL DEFAULT '0' AFTER `codprevired`;
UPDATE `gc_afp` SET `codlre`='6' WHERE  `id`=2;
UPDATE `gc_afp` SET `codlre`='13' WHERE  `id`=4;
UPDATE `gc_afp` SET `codlre`='11' WHERE  `id`=6;
UPDATE `gc_afp` SET `codlre`='14' WHERE  `id`=1;
UPDATE `gc_afp` SET `codlre`='19' WHERE  `id`=10;
UPDATE `gc_afp` SET `codlre`='31' WHERE  `id`=3;
UPDATE `gc_afp` SET `codlre`='103' WHERE  `id`=5;
UPDATE `gc_afp` SET `codlre`='100' WHERE  `id`=8;
UPDATE `gc_afp` SET `codlre`='100' WHERE  `id`=9;

ALTER TABLE `gc_isapre`
	ADD COLUMN `codlre` INT(11) NOT NULL AFTER `codprevired`;

UPDATE `gc_isapre` SET `codlre`='102' WHERE  `id`=1;
UPDATE `gc_isapre` SET `codlre`='3' WHERE  `id`=2;
UPDATE `gc_isapre` SET `codlre`='1' WHERE  `id`=7;
UPDATE `gc_isapre` SET `codlre`='4' WHERE  `id`=5;
UPDATE `gc_isapre` SET `codlre`='9' WHERE  `id`=6;
UPDATE `gc_isapre` SET `codlre`='12' WHERE  `id`=15;
UPDATE `gc_isapre` SET `codlre`='37' WHERE  `id`=3;
UPDATE `gc_isapre` SET `codlre`='38' WHERE  `id`=8;
UPDATE `gc_isapre` SET `codlre`='39' WHERE  `id`=11;
UPDATE `gc_isapre` SET `codlre`='40' WHERE  `id`=16;
UPDATE `gc_isapre` SET `codlre`='41' WHERE  `id`=13;
UPDATE `gc_isapre` SET `codlre`='42' WHERE  `id`=14;
UPDATE `gc_isapre` SET `codlre`='43' WHERE  `id`=12;

ALTER TABLE `gc_cajas_compensacion`
	ADD COLUMN `codlre` INT(11) NOT NULL DEFAULT '0' AFTER `codprevired`;

UPDATE `gc_cajas_compensacion` SET `codlre`='4' WHERE  `id`=1;
UPDATE `gc_cajas_compensacion` SET `codlre`='2' WHERE  `id`=2;
UPDATE `gc_cajas_compensacion` SET `codlre`='1' WHERE  `id`=3;
UPDATE `gc_cajas_compensacion` SET `codlre`='3' WHERE  `id`=4;





alter table rem_afp add codlre int


/*****************************************************************************/

ALTER TABLE `gc_personal`
	ADD COLUMN `indmesaviso` BIGINT NULL DEFAULT NULL AFTER `fecfiniquito`,
	ADD COLUMN `indannoservicio` BIGINT NULL DEFAULT NULL AFTER `indmesaviso`,
	ADD COLUMN `indferiadolegal` BIGINT NULL DEFAULT NULL AFTER `indannoservicio`,
	ADD COLUMN `indvoluntaria` BIGINT NULL DEFAULT NULL AFTER `indferiadolegal`,
	ADD COLUMN `indtotal` BIGINT NULL DEFAULT NULL AFTER `indvoluntaria`;

/**********************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`, `visible`, `valid`) VALUES ('remuneraciones/get_datos_finiquito', '5', '0', '1');
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('302', '1');


/************************************************************************************/

CREATE TABLE `gc_causal_finiquito` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`motivo` VARCHAR(250) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`articulo` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`activo` TINYINT(4) NULL DEFAULT '1',
	`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;



INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Acuerdo entre las partes de ponerle término', 'Art. 159 Inciso 1', 1, '2022-06-03 12:48:55', '2022-06-03 12:59:34');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Renuncia del trabajador', 'Art. 159 Inciso 2', 1, '2022-06-03 12:49:02', '2022-06-03 12:59:38');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Muerte del trabajador', 'Art. 159 Inciso 3', 1, '2022-06-03 12:49:15', '2022-06-03 12:59:42');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Vencimiento del plazo del contrato', 'Art. 159 Inciso 4', 1, '2022-06-03 12:52:04', '2022-06-03 12:59:46');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Término del trabajo acordado', 'Art. 159 Inciso 5', 1, '2022-06-03 12:52:14', '2022-06-03 12:59:49');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Caso fortuito o fuerza mayor', 'Art. 159 Inciso 6', 1, '2022-06-03 12:52:25', '2022-06-03 12:59:54');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Conductas indebidas y graves del trabajador', 'Art. 160 Inciso 1', 1, '2022-06-03 12:57:07', '2022-06-03 12:59:57');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Realizar actividades prohibidas en el contrato de trabajo', 'Art. 160 Inciso 2', 1, '2022-06-03 12:57:36', '2022-06-03 13:00:00');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('No presentarse el trabajador a sus labores sin causa justificada ', 'Art. 160 Inciso 3', 1, '2022-06-03 12:57:56', '2022-06-03 13:00:03');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Abandono del trabajo', 'Art. 160 Inciso 4', 1, '2022-06-03 12:58:12', '2022-06-03 13:00:06');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Actos, omisiones o imprudencias temerarias que afecten a la seguridad o al funcionamiento del establecimiento', 'Art. 160 Inciso 5', 1, '2022-06-03 12:58:33', '2022-06-03 13:00:09');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('El perjuicio material causado intencionalmente en las instalaciones, maquinarias, herramientas, útiles de trabajo, productos o mercaderías', 'Art. 160 Inciso 6', 1, '2022-06-03 12:58:45', '2022-06-03 13:00:12');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Incumplimiento grave de las obligaciones que impone el contrato', 'Art. 160 Inciso 7', 1, '2022-06-03 12:58:56', '2022-06-03 13:00:15');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Necesidades de la empresa, establecimiento o servicio', 'Art. 161', 1, '2022-06-03 12:59:07', '2022-06-03 13:00:20');
INSERT INTO `gc_causal_finiquito` (`motivo`, `articulo`, `activo`, `created_at`, `updated_at`) VALUES ('Por haber sido sometido el empleador, mediante resolución judicial, a un procedimiento concursal de liquidación de sus bienes', 'Artículo 163 bis', 1, '2022-06-03 12:59:24', '2022-06-03 13:00:25');


ALTER TABLE `gc_personal`
	ADD COLUMN `causalfiniquito` INT NULL DEFAULT NULL AFTER `fecfiniquito`;


/****************************************************************************************/

INSERT INTO `gc_app` (`function`, `menuid`) VALUES ('payments/pagoonline', '2');
UPDATE `gc_app` SET `visible`='0', `valid`='1' WHERE  `id`=303;
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES ('303', '1');
ALTER TABLE `gc_log_pagos`
	ADD COLUMN `tokentgc` VARCHAR(100) NULL DEFAULT NULL AFTER `tokentranskbank`;

/************************************************************************************/


ALTER TABLE `gc_log_pagos`
	ADD COLUMN `paymentinfo` VARCHAR(500) NULL DEFAULT NULL AFTER `fec_envio`;

/*******************************************************************************************/


CREATE TABLE `gc_log_envio_mail` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(100) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`messageid` VARCHAR(100) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`idcomunidad` INT(11) NULL DEFAULT NULL,
	`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;




/***************************************************************************************/

CREATE TABLE `gc_archivos_comunicado` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idcomunicado` INT(11) NOT NULL DEFAULT '0',
	`nomarchivo` VARCHAR(250) NOT NULL DEFAULT '' COLLATE 'utf8mb4_general_ci',
	`nomtemparchivo` VARCHAR(250) NOT NULL DEFAULT '' COLLATE 'utf8mb4_general_ci',
	`created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

INSERT INTO `gasto_ggcc`.`gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/deletefile_comunicado', 10, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gasto_ggcc`.`gc_role` (`appid`, `levelid`) VALUES (315, 1);

/*******************************************************************************************/

CREATE TABLE `gc_fondos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(11) NOT NULL DEFAULT '0',
	`nombre` VARCHAR(250) NOT NULL DEFAULT '' COLLATE 'utf8mb4_general_ci',
	`active` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;



INSERT INTO `gasto_ggcc`.`gc_app` (`function`, `menuid`, `visible`, `valid`, orden, `created_at`, `updated_at`) VALUES ('admins/admin_fondos', 11, 1, 1,13, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gasto_ggcc`.`gc_role` (`appid`, `levelid`) VALUES (316, 1);
UPDATE `gasto_ggcc`.`gc_app` SET `name`='Fondos' WHERE  `id`=316;

INSERT INTO `gasto_ggcc`.`gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/add_fondo', 11, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gasto_ggcc`.`gc_role` (`appid`, `levelid`) VALUES (317, 1);

INSERT INTO `gasto_ggcc`.`gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/submit_fondo', 11, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gasto_ggcc`.`gc_role` (`appid`, `levelid`) VALUES (318, 1);


INSERT INTO `gasto_ggcc`.`gc_app` (`function`, `menuid`, `visible`, `valid`, `created_at`, `updated_at`) VALUES ('admins/delete_fondo', 11, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `gasto_ggcc`.`gc_role` (`appid`, `levelid`) VALUES (319, 1);



/*******************************************************************************************/

INSERT INTO `gasto_ggcc`.`gc_fondos` (`nombre`) VALUES ('Fondo Multas');
INSERT INTO `gasto_ggcc`.`gc_fondos` (`nombre`) VALUES ('Fondo Intereses');

/**********************************************************************************************/

UPDATE `gasto_ggcc`.`gc_tipo_deuda_detalle` SET `activo`=0 WHERE  `id`=7;
UPDATE `gasto_ggcc`.`gc_tipo_deuda_detalle` SET `activo`=0 WHERE  `id`=9;

ALTER TABLE `gc_deuda_propiedad`
	ADD COLUMN `idfondo` INT(11) UNSIGNED NOT NULL AFTER `idtipodeudadetalle`;

ALTER TABLE `gc_deuda_propiedad`
	CHANGE COLUMN `idfondo` `idfondo` INT(11) UNSIGNED NULL AFTER `idtipodeudadetalle`;

ALTER TABLE `gc_deuda_propiedad`
	CHANGE COLUMN `idtipodeudadetalle` `idtipodeudadetalle` INT(11) UNSIGNED NULL AFTER `idpropiedad`;

ALTER TABLE `gc_deuda_propiedad`
	DROP FOREIGN KEY `fk_deuda_propiedad_idtipodeudadetalle_tipo_deuda_detalle_id`;

CREATE TABLE `gc_cartola_otros_fondos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`idcuentaindividual` INT(11) UNSIGNED NULL DEFAULT NULL,
	`idfondo` INT(11) UNSIGNED NULL DEFAULT '0',
	`glosa` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`monto` INT(11) NOT NULL DEFAULT '0',
	`activo` TINYINT(4) NOT NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
	`updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `fk_cartola_caja_idcomunidad_comunidad_id` (`idcomunidad`) USING BTREE,
	INDEX `gc_cartola_fondo_reserva_cuenta_id` (`idcuentaindividual`) USING BTREE,
	CONSTRAINT `gc_cartola_otros_fondos_ibfk_2` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;




ALTER TABLE `gc_cuenta`
	CHANGE COLUMN `formapago` `formapago` ENUM('gc','fr','ci','sc','af','f') NOT NULL DEFAULT 'gc' COMMENT 'gc: gasto comun, fr: fondo reserva, ci: cobro individual, sc: sin cobro, af: activo fijo, f: fondo' COLLATE 'latin1_swedish_ci' AFTER `fecautoriza`,
	ADD COLUMN `idfondo` INT NOT NULL DEFAULT 0 AFTER `formapago`;


ALTER TABLE `gc_cartola_otros_fondos`
	ADD COLUMN `idcuenta` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `idcuentaindividual`;


/********************************************************************************************/

UPDATE `gasto_ggcc`.`gc_app` SET `name`='Fondos' WHERE  `id`=85;


/**********************************************************************************************/

ALTER TABLE `gc_comunicados`
	CHANGE COLUMN `titulo` `titulo` VARCHAR(500) NULL COLLATE 'latin1_swedish_ci' AFTER `idcomunidad`,
	CHANGE COLUMN `txt_comunicado` `txt_comunicado` TEXT NULL COLLATE 'latin1_swedish_ci' AFTER `titulo`;

	
/*****************************************************************************************************/

ALTER TABLE `gc_comunicados`
	ADD COLUMN `fec_comienzo_envio` DATETIME NULL DEFAULT NULL AFTER `fec_marca_envio`;

	