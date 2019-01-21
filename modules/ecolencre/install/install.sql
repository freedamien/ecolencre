ALTER TABLE `ps_store` ADD 
`level` TINYINT(1) NOT NULL AFTER `date_upd`, 
ADD `id_parentstore` INT(10) NULL AFTER `level`, 
ADD `id_employee` INT(10) NOT NULL AFTER `id_parentstore`,
ADD `id_cms` INT(10) NULL AFTER `id_parentstore`;


