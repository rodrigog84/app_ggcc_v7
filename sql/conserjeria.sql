--  Agrega nuevo perfil
INSERT INTO `gc_level` (`name`, `description`, `valid`) VALUES ('pers', 'Personal Condominio', 1);

--  Permisos perfil personal
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (97, 5); -- admins/profile
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (98, 5); -- admins/submit_profile
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (91, 5); -- admins/cambio_clave
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (92, 5); -- admins/validate_password_user
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (93, 5); -- admins/submit_clave
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (93, 5); -- admins/submit_clave
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (77, 5); -- admins/admin_bodega
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (81, 5); -- admins/admin_estacionamiento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (258, 5); -- admins/comunicados

--  Asocia usuario del personal
ALTER TABLE `gc_personal`
    ADD COLUMN `iduser` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `gc_personal`
    ADD CONSTRAINT `fk_gc_personal_iduser_gc_users_idi`
    FOREIGN KEY (`iduser`) REFERENCES `gc_users`(`id`);

--  Agrega app estacionamiento visita
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/admin_estacionamiento_visita', 'Estacionamientos Visitas', 10, 1, 1, 8, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- admin_estacionamiento_visita
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/add_estacionamiento_visita', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- add_estacionamiento_visita
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/submit_estacionamiento_visita', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- submit_estacionamiento_visita
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/delete_estacionamiento_visita', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- delete_estacionamiento_visita

--  Permisos estacionamiento visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (273, 5); -- Personal - admin_estacionamiento_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (273, 1); -- Admin - admin_estacionamiento_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (273, 4); -- SysAdmin - admin_estacionamiento_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (274, 1); -- Admin - add_estacionamiento_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (274, 4); -- SysAdmin - add_estacionamiento_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (275, 1); -- Admin - submit_estacionamiento_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (275, 4); -- SysAdmin - submit_estacionamiento_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (276, 1); -- Admin - delete_estacionamiento_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (276, 4); -- SysAdmin - delete_estacionamiento_visita

--  Crea tabla estacionamiento visita
CREATE TABLE `gc_estacionamiento_visita` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(11) UNSIGNED NOT NULL,
	`nombre` VARCHAR(100) NULL DEFAULT NULL,
	`active` TINYINT(3) UNSIGNED NULL DEFAULT '1',
	`valid` TINYINT(3) UNSIGNED NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_gc_estacionamiento_visita_idcomunidad_gc_comunidad_id` (`idcomunidad`),
	CONSTRAINT `fk_gc_estacionamiento_visita_idcomunidad_gc_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;

--  Agrega app libro visitas
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('comunity/libro_visitas', 'Libro Visitas', 10, 1, 1, 9, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- comunity/libro_visitas
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('comunity/add_registro_visita', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- comunity/add_registro_visita
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('comunity/submit_registro_visita', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- comunity/submit_registro_visita
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('comunity/add_salida_visita', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- comunity/add_salida_visita
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('comunity/historial_visitas', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- comunity/historial_visitas

--  Permisos libro visitas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (277, 5); -- Personal - libro_visitas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (277, 1); -- Admin - libro_visitas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (277, 4); -- SysAdmin - libro_visitas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (278, 5); -- Personal - add_registro_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (278, 4); -- SysAdmin - add_registro_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (279, 5); -- Personal - submit_registro_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (279, 4); -- SysAdmin - submit_registro_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (280, 5); -- Personal - add_salida_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (280, 4); -- SysAdmin - add_salida_visita
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (281, 5); -- Personal - historial_visitas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (281, 1); -- Admin - historial_visitas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (281, 4); -- SysAdmin - historial_visitas

--  Crea tabla libro visitas
CREATE TABLE `gc_libro_visitas` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `idcomunidad` INT(11) UNSIGNED NOT NULL,
    `idpropiedad` INT(11) UNSIGNED NOT NULL,
    `idestacionamiento` INT(11) UNSIGNED NULL DEFAULT NULL,
    `rut` INT(11) UNSIGNED NOT NULL,
    `dv` CHAR(1) NOT NULL,
    `nombre` VARCHAR(100) NOT NULL,
    `apellidos` VARCHAR(100) NOT NULL,
    `patente` VARCHAR(50) NULL DEFAULT NULL,
	`active` TINYINT(3) UNSIGNED NULL DEFAULT '1',
	`registro_entrada` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`registro_salida` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_gc_libro_visita_idpropiedad_gc_propiedad_id` (`idpropiedad`),
	INDEX `fk_gc_libro_visita_idestacionamiento_gc_est_visita_id` (`idestacionamiento`),
	INDEX `fk_gc_libro_visita_idcomunidad_gc_comunidad_id` (`idcomunidad`),
	CONSTRAINT `fk_gc_libro_visita_idpropiedad_gc_propiedad_id` FOREIGN KEY (`idpropiedad`) REFERENCES `gc_propiedad` (`id`),
	CONSTRAINT `fk_gc_libro_visita_idestacionamiento_gc_est_visita_id` FOREIGN KEY (`idestacionamiento`) REFERENCES `gc_estacionamiento_visita` (`id`),
	CONSTRAINT `fk_gc_libro_visita_idcomunidad_gc_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;

--  Agrega app libro novedades
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('comunity/libro_novedades', 'Libro Novedades', 10, 1, 1, 10, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- comunity/libro_novedades
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('comunity/add_bitacora', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- comunity/add_bitacora
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('comunity/submit_bitacora', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- comunity/submit_bitacora
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('comunity/historial_novedades', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- comunity/historial_novedades
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('comunity/archive_bitacora', '', 10, 0, 1, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- comunity/archive_bitacora

--  Permisos libro novedades
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (282, 5); -- Personal - libro_novedades
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (282, 2); -- Comite - libro_novedades
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (282, 1); -- Admin - libro_novedades
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (282, 4); -- SysAdmin - libro_novedades
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (283, 5); -- Personal - add_bitacora
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (283, 4); -- SysAdmin - add_bitacora
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (284, 5); -- Personal - submit_bitacora
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (284, 4); -- SysAdmin - submit_bitacora
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (285, 5); -- Personal - historial_novedades
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (285, 2); -- Comite - historial_novedades
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (285, 1); -- Admin - historial_novedades
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (285, 4); -- SysAdmin - historial_novedades
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (286, 5); -- Personal - archive_bitacora
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (286, 4); -- SysAdmin - archive_bitacora

--  Crea tabla libro novedades
CREATE TABLE `gc_libro_novedades` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `iduser` INT(11) UNSIGNED NOT NULL,
    `idcomunidad` INT(11) UNSIGNED NOT NULL,
    `accion` VARCHAR(100) NOT NULL,
    `descripcion` TEXT NOT NULL,
	`active` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    `archived_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_gc_libro_novedades_iduser_gc_users` (`iduser`),
	INDEX `fk_gc_libro_novedades_idcomunidad_gc_comunidad` (`idcomunidad`),
	CONSTRAINT `fk_gc_libro_novedades_iduser_gc_users` FOREIGN KEY (`iduser`) REFERENCES `gc_users` (`id`),
	CONSTRAINT `fk_gc_libro_novedades_idcomunidad_gc_comunidad` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;



/******************************************************************/

CREATE TABLE `gc_estacionamiento_visita` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`idcomunidad` INT(11) UNSIGNED NOT NULL,
	`nombre` VARCHAR(100) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`active` TINYINT(3) UNSIGNED NULL DEFAULT '1',
	`valid` TINYINT(3) UNSIGNED NULL DEFAULT '1',
	`created_at` DATETIME NOT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `fk_gc_estacionamiento_visita_idcomunidad_gc_comunidad_id` (`idcomunidad`) USING BTREE,
	CONSTRAINT `fk_gc_estacionamiento_visita_idcomunidad_gc_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gasto_ggcc`.`gc_comunidad` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;



--  Crea tabla libro visitas
CREATE TABLE `gc_libro_visitas` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `idcomunidad` INT(11) UNSIGNED NOT NULL,
    `idpropiedad` INT(11) UNSIGNED NOT NULL,
    `idestacionamiento` INT(11) UNSIGNED NULL DEFAULT NULL,
    `rut` INT(11) UNSIGNED NOT NULL,
    `dv` CHAR(1) NOT NULL,
    `nombre` VARCHAR(100) NOT NULL,
    `apellidos` VARCHAR(100) NOT NULL,
    `patente` VARCHAR(50) NULL DEFAULT NULL,
	`active` TINYINT(3) UNSIGNED NULL DEFAULT '1',
	`registro_entrada` DATETIME NOT NULL,
	`registro_salida` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `fk_gc_libro_visita_idpropiedad_gc_propiedad_id` (`idpropiedad`),
	INDEX `fk_gc_libro_visita_idestacionamiento_gc_est_visita_id` (`idestacionamiento`),
	INDEX `fk_gc_libro_visita_idcomunidad_gc_comunidad_id` (`idcomunidad`),
	CONSTRAINT `fk_gc_libro_visita_idpropiedad_gc_propiedad_id` FOREIGN KEY (`idpropiedad`) REFERENCES `gc_propiedad` (`id`),
	CONSTRAINT `fk_gc_libro_visita_idestacionamiento_gc_est_visita_id` FOREIGN KEY (`idestacionamiento`) REFERENCES `gc_estacionamiento_visita` (`id`),
	CONSTRAINT `fk_gc_libro_visita_idcomunidad_gc_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;



--  Crea tabla libro novedades
CREATE TABLE `gc_libro_novedades` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `iduser` INT(11) UNSIGNED NOT NULL,
    `idcomunidad` INT(11) UNSIGNED NOT NULL,
    `accion` VARCHAR(100) NOT NULL,
    `descripcion` TEXT NOT NULL,
	`active` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
    `created_at` DATETIME NOT NULL,
    `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    `archived_at` DATETIME NULL,
	PRIMARY KEY (`id`),
	INDEX `fk_gc_libro_novedades_iduser_gc_users` (`iduser`),
	INDEX `fk_gc_libro_novedades_idcomunidad_gc_comunidad` (`idcomunidad`),
	CONSTRAINT `fk_gc_libro_novedades_iduser_gc_users` FOREIGN KEY (`iduser`) REFERENCES `gc_users` (`id`),
	CONSTRAINT `fk_gc_libro_novedades_idcomunidad_gc_comunidad` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT
;


