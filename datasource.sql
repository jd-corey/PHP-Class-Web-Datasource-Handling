CREATE TABLE IF NOT EXISTS `myDatabase`.`datasource` (
  `id` VARCHAR(10) NOT NULL,
  `name` VARCHAR(255) NULL,
  `base_url` TEXT NULL,
  `data_format` VARCHAR(15) NULL,
  `license` VARCHAR(255) NULL,
  `owner` VARCHAR(255) NULL,
  `available_status` TINYINT(1) NULL,
  `available_datetime` DATETIME NULL,
  `insert_datetime` DATETIME NULL,
  `update_datetime` DATETIME NULL,
  `status` TINYINT(1) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB