drop database sitemusica;
CREATE SCHEMA IF NOT EXISTS `sitemusica` DEFAULT CHARACTER SET utf8 ;
USE `sitemusica`;
-- -----------------------------------------------------
-- Table `sitemusica`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sitemusica`.`Usuario` (
                                                      `idUsuario` INT NOT NULL AUTO_INCREMENT,
                                                      `nome` VARCHAR(45) NULL,
    `email` VARCHAR(100) NULL,
    `biografia` TEXT NULL,
    `senha` VARCHAR(50) NULL,
    `minha_music` VARCHAR(45) NULL,
    `data_ini` DATE NULL,
    `data_nasc` DATE NULL,
    PRIMARY KEY (`idUsuario`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sitemusica`.`Musica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sitemusica`.`Musica` (
                                                     `idMusica` INT NOT NULL AUTO_INCREMENT,
                                                     `genero` VARCHAR(45) NULL,
    `data` DATE NULL,
    `artista` VARCHAR(45) NULL,
    PRIMARY KEY (`idMusica`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sitemusica`.`composicao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sitemusica`.`composicao` (
                                                         `idcomposicao` INT NOT NULL AUTO_INCREMENT,
                                                         `titulo` VARCHAR(45) NULL,
    `composicaocol` VARCHAR(45) NULL,
    `Usuario_idUsuario` INT NOT NULL,
    `Documento` blob,
    PRIMARY KEY (`idcomposicao`),
    INDEX `fk_composicao_Usuario1_idx` (`Usuario_idUsuario` ASC) VISIBLE,
    CONSTRAINT `fk_composicao_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `sitemusica`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sitemusica`.`Usuario_das_Musica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sitemusica`.`Usuario_das_Musica` (
                                                                 `Usuario_idUsuario` INT NOT NULL,
                                                                 `Musica_idMusica` INT NOT NULL,
                                                                 PRIMARY KEY (`Usuario_idUsuario`, `Musica_idMusica`),
    INDEX `fk_Usuario_has_Musica_Musica1_idx` (`Musica_idMusica` ASC) VISIBLE,
    INDEX `fk_Usuario_has_Musica_Usuario1_idx` (`Usuario_idUsuario` ASC) VISIBLE,
    CONSTRAINT `fk_Usuario_has_Musica_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `sitemusica`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_Usuario_has_Musica_Musica1`
    FOREIGN KEY (`Musica_idMusica`)
    REFERENCES `sitemusica`.`Musica` (`idMusica`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sitemusica`.`amigos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sitemusica`.`amigos` (
                                                     `idUsuario` INT NOT NULL,
                                                     `idAmigo` INT NOT NULL,
                                                     PRIMARY KEY (`idUsuario`, `idAmigo`),
    INDEX `fk_Usuario_has_Usuario_Usuario2_idx` (`idAmigo` ASC) VISIBLE,
    INDEX `fk_Usuario_has_Usuario_Usuario1_idx` (`idUsuario` ASC) VISIBLE,
    CONSTRAINT `fk_Usuario_has_Usuario_Usuario1`
    FOREIGN KEY (`idUsuario`)
    REFERENCES `sitemusica`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_Usuario_has_Usuario_Usuario2`
    FOREIGN KEY (`idAmigo`)
    REFERENCES `sitemusica`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;

INSERT INTO sitemusica.Usuario (nome, email, biografia, senha, minha_music, data_ini, data_nasc)
VALUES (
           'Gabriel Boaventura',
           'gabriel@gmail.com',
           'Cantor e compositor de Choro.',
           '123456',  -- ⚠️ Senha deveria ser criptografada
           'Minha Primeira Música',
           '2025-05-22',       -- data_ini (data de início)
           '1995-03-15'        -- data_nasc (data de nascimento)
       );
