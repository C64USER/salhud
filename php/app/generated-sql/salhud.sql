
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- disease
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `disease`;

CREATE TABLE `disease`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- region
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `region`;

CREATE TABLE `region`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `population` INTEGER NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- town
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `town`;

CREATE TABLE `town`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `mapping` INTEGER NOT NULL,
    `region_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `town_fi_c400b0` (`region_id`),
    CONSTRAINT `town_fk_c400b0`
        FOREIGN KEY (`region_id`)
        REFERENCES `region` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- case
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `case`;

CREATE TABLE `case`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `year` INTEGER NOT NULL,
    `disease_id` INTEGER NOT NULL,
    `town_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `case_fi_4a095c` (`disease_id`),
    INDEX `case_fi_e6e5c4` (`town_id`),
    CONSTRAINT `case_fk_4a095c`
        FOREIGN KEY (`disease_id`)
        REFERENCES `disease` (`id`),
    CONSTRAINT `case_fk_e6e5c4`
        FOREIGN KEY (`town_id`)
        REFERENCES `town` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
