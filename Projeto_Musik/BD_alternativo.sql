
CREATE SCHEMA IF NOT EXISTS `sitemusica` DEFAULT CHARACTER SET utf8 ;
USE `sitemusica` ;

-- -----------------------------------------------------
-- Table `sitemusica`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sitemusica`.`Usuario` (
                                                `idUsuario` INT NOT NULL AUTO_INCREMENT,
                                                `musica` VARCHAR(45) NULL,
    `email` VARCHAR(100) NULL,
    `senha` VARCHAR(45) NULL,
    `composi` LONGTEXT NULL,
    `data_nascimento` DATE NULL,
    `bio` VARCHAR(45) NULL,
    `foto` VARCHAR(45) NULL,
    `tipo_usuario` VARCHAR(45) NULL,
    `data_criacao` DATE NULL,
    `status_online` VARCHAR(45) NULL DEFAULT '(\'online\', \'offline\') default \'offline\'',
  PRIMARY KEY (`idUsuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sitemusica`.`amigos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sitemusica`.`amigos` (
                                               `Usuario_idUsuario` INT NOT NULL,
                                               `idAmigo` INT NOT NULL,
                                               `status` VARCHAR(45) NULL,
    `data_inicio` INT NULL,
    PRIMARY KEY (`Usuario_idUsuario`, `idAmigo`),
    CONSTRAINT `fk_amigos_Usuario`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `sitemusica`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


