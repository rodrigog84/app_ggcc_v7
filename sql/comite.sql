-- Poblado app comite
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/admin_comite', 'Comite', '10', '1', '1', '8', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- admin_comite
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/add_miembro_comite', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- add_miembro_comite
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/submit_miembro_comite', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- submit_miembro_comite
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/delete_miembro_comite', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- delete_miembro_comite
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/admin_documentos', 'Documentos', '10', '1', '1', '9', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- admin_documento
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/add_documento', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- add_documentos
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/submit_documento', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- submit_documento
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/delete_documento', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- delete_documento
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/admin_asambleas', 'Asambleas', '10', '1', '1', '10', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- admin_asamblea
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/add_asamblea', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- add_asamblea
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/submit_asamblea', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- submit_asamblea
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/delete_asamblea', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- delete_asamblea
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/historial_documentos', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- historial_documentos
INSERT INTO `gc_app` (`function`, `name`, `menuid`, `visible`, `valid`, `orden`, `created_at`, `updated_at`) VALUES ('admins/historial_asambleas', '', '10', '0', '1', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); -- historial_asambleas


-- Poblado permisos comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (287, 1); -- Admin - admin_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (287, 2); -- comadmin - admin_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (288, 1); -- Admin - add_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (289, 1); -- Admin - submit_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (290, 1); -- Admin - delete_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (291, 1); -- Admin - admin_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (291, 2); -- comadmin - admin_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (292, 1); -- Admin - add_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (293, 1); -- Admin - submit_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (294, 1); -- Admin - delete_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (295, 1); -- Admin - admin_asambleas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (295, 2); -- comadmin - admin_asambleas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (296, 1); -- Admin - add_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (297, 1); -- Admin - submit_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (298, 1); -- Admin - delete_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (299, 1); -- Admin - historial_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (299, 2); -- Comadmin - historial_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (300, 1); -- Admin - historial_asambleas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (300, 2); -- Comadmin - historial_asambleas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (287, 5); -- SysAdmin - admin_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (288, 5); -- SysAdmin - add_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (289, 5); -- SysAdmin - submit_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (290, 5); -- SysAdmin - delete_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (291, 5); -- SysAdmin - admin_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (292, 5); -- SysAdmin - add_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (293, 5); -- SysAdmin - submit_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (294, 5); -- SysAdmin - delete_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (295, 5); -- SysAdmin - admin_asambleas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (296, 5); -- SysAdmin - add_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (297, 5); -- SysAdmin - submit_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (298, 5); -- SysAdmin - delete_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (299, 5); -- SysAdmin - historial_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (300, 5); -- Comadmin - historial_asambleas


/*
-- Poblado permisos comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (291, 1); -- Admin - admin_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (291, 2); -- comadmin - admin_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (292, 1); -- Admin - add_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (293, 1); -- Admin - submit_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (294, 1); -- Admin - delete_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (295, 1); -- Admin - admin_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (295, 2); -- comadmin - admin_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (296, 1); -- Admin - add_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (297, 1); -- Admin - submit_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (298, 1); -- Admin - delete_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (299, 1); -- Admin - admin_asambleas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (299, 2); -- comadmin - admin_asambleas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (300, 1); -- Admin - add_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (301, 1); -- Admin - submit_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (302, 1); -- Admin - delete_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (303, 1); -- Admin - historial_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (303, 2); -- Comadmin - historial_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (304, 1); -- Admin - historial_asambleas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (304, 2); -- Comadmin - historial_asambleas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (291, 5); -- SysAdmin - admin_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (292, 5); -- SysAdmin - add_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (293, 5); -- SysAdmin - submit_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (294, 5); -- SysAdmin - delete_miembro_comite
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (295, 5); -- SysAdmin - admin_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (296, 5); -- SysAdmin - add_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (297, 5); -- SysAdmin - submit_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (298, 5); -- SysAdmin - delete_documento
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (299, 5); -- SysAdmin - admin_asambleas
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (300, 5); -- SysAdmin - add_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (301, 5); -- SysAdmin - submit_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (302, 5); -- SysAdmin - delete_asamblea
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (303, 5); -- SysAdmin - historial_documentos
INSERT INTO `gc_role` (`appid`, `levelid`) VALUES (304, 5); -- Comadmin - historial_asambleas
*/
-- Crea tabla cargo comite
CREATE TABLE `gc_cargo_comite`(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `cargo` VARCHAR(20) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=INNODB
ROW_FORMAT=COMPACT;

-- Poblado tabla cargo comite
INSERT INTO `gc_cargo_comite` (`cargo`) VALUES ('Presidente');
INSERT INTO `gc_cargo_comite` (`cargo`) VALUES ('Vicepresidente');
INSERT INTO `gc_cargo_comite` (`cargo`) VALUES ('Delegado');
INSERT INTO `gc_cargo_comite` (`cargo`) VALUES ('Tesorero');

-- Crea tabla comite
CREATE TABLE `gc_comite`(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `iduser` INT(11) UNSIGNED NULL,
    `idcomunidad` INT(11) UNSIGNED NOT NULL,
    `idcargo` INT(11) UNSIGNED NOT NULL,
    `first_name` VARCHAR(30) NOT NULL,
    `last_name` VARCHAR(30) NOT NULL,
    `active` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
    `valid` TINYINT(3) UNSIGNED NULL DEFAULT '1',
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_gc_comite_iduser_gc_users_id` (`iduser`),
    CONSTRAINT `fk_gc_comite_iduser_gc_users_id` FOREIGN KEY (`iduser`) REFERENCES `gc_users` (`id`),
    INDEX `fk_gc_comite_idcomunidad_gc_comunidad_id` (`idcomunidad`),
    CONSTRAINT `fk_gc_comite_idcomunidad_gc_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`),
    INDEX `fk_gc_comite_idcargo_gc_cargo_comite_id` (`idcargo`),
    CONSTRAINT `fk_gc_comite_idcargo_gc_cargo_comite_id` FOREIGN KEY (`idcargo`) REFERENCES `gc_cargo_comite` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=INNODB
ROW_FORMAT=COMPACT;

-- Crea tabla tipo_documento
CREATE TABLE `gc_tipo_documento`(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tipo` VARCHAR(20) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=INNODB
ROW_FORMAT=COMPACT;

-- Poblado tabla tipo_documento
INSERT INTO `gc_tipo_documento` (`tipo`) VALUES ('Ley');
INSERT INTO `gc_tipo_documento` (`tipo`) VALUES ('Reglamento');
INSERT INTO `gc_tipo_documento` (`tipo`) VALUES ('Manual');
INSERT INTO `gc_tipo_documento` (`tipo`) VALUES ('Documento');

-- Crea tabla documento
CREATE TABLE `gc_documento`(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `descripcion` VARCHAR(100) NOT NULL,
    `path` VARCHAR(100) NOT NULL,
    `idcomunidad` INT(11) UNSIGNED NOT NULL,
    `idtipodocumento` INT(11) UNSIGNED NOT NULL,
    `active` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `archived_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_gc_documento_idcomunidad_gc_comunidad_id` (`idcomunidad`),
    CONSTRAINT `fk_gc_documento_idcomunidad_gc_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`),
    INDEX `fk_gc_documento_idtipodocumento_gc_tipo_documento_id` (`idtipodocumento`),
    CONSTRAINT `fk_gc_documento_idtipodocumento_gc_tipo_documento_id` FOREIGN KEY (`idtipodocumento`) REFERENCES `gc_tipo_documento` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=INNODB
ROW_FORMAT=COMPACT;

-- Crea tabla tipo_asamblea
CREATE TABLE `gc_tipo_asamblea`(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tipo` VARCHAR(20) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=INNODB
ROW_FORMAT=COMPACT;

-- Poblado tabla tipo_documento
INSERT INTO `gc_tipo_asamblea` (`tipo`) VALUES ('Ordinaria');
INSERT INTO `gc_tipo_asamblea` (`tipo`) VALUES ('Extraordinaria');

-- Crea table asamblea
CREATE TABLE `gc_asamblea`(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `asunto` VARCHAR(100) NOT NULL,
    `fecha` date NOT NULL,
    `path` VARCHAR(100) NOT NULL,
    `idcomunidad` INT(11) UNSIGNED NOT NULL,
    `idtipoasamblea` INT(11) UNSIGNED NOT NULL,
    `active` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `archived_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_gc_asamblea_idcomunidad_gc_comunidad_id` (`idcomunidad`),
    CONSTRAINT `fk_gc_asamblea_idcomunidad_gc_comunidad_id` FOREIGN KEY (`idcomunidad`) REFERENCES `gc_comunidad` (`id`),
    INDEX `fk_gc_asamblea_idtipoasamblea_gc_tipo_asamblea_id` (`idtipoasamblea`),
    CONSTRAINT `fk_gc_asamblea_idtipoasamblea_gc_tipo_asamblea_id` FOREIGN KEY (`idtipoasamblea`) REFERENCES `gc_tipo_asamblea` (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=INNODB
ROW_FORMAT=COMPACT;
