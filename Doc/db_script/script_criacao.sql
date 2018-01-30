-- MySQL Workbench Synchronization
-- Generated: 2018-01-27 18:54
-- Model: New Model
-- Version: 1.0
-- Project: Sistema de Ponto
-- Author: Wallison do Carmo Costa

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `dbs_ponto` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`usuarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(250) NOT NULL,
  `senha` VARCHAR(50) NOT NULL,
  `acessos_id` INT(11) NOT NULL,
  `cadastro` DATETIME NOT NULL DEFAULT NOW(),
  `atualizado` DATETIME NULL DEFAULT NULL,
  `ativo` TINYINT(4) NOT NULL DEFAULT 1,
  `excluido` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_usuarios_acessos_idx` (`acessos_id` ASC),
  CONSTRAINT `fk_usuarios_acessos`
    FOREIGN KEY (`acessos_id`)
    REFERENCES `dbs_ponto`.`acessos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`acessos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `acesso` VARCHAR(250) NOT NULL,
  `cadastro` DATETIME NOT NULL DEFAULT NOW(),
  `atualizado` DATETIME NULL DEFAULT NULL,
  `ativo` TINYINT(4) NOT NULL DEFAULT 1,
  `excluido` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`colaboradores` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(250) NOT NULL,
  `cpf` VARCHAR(45) NOT NULL,
  `rg` VARCHAR(250) NOT NULL,
  `genero` INT(11) NOT NULL,
  `matricula` VARCHAR(250) NOT NULL,
  `carga_horaria` INT(11) NOT NULL,
  `areas_id` INT(11) NOT NULL,
  `cargos_id` INT(11) NOT NULL,
  `cadastro` DATETIME NOT NULL DEFAULT NOW(),
  `atualizado` DATETIME NULL DEFAULT NULL,
  `ativo` TINYINT(4) NOT NULL DEFAULT 1,
  `excluido` TINYINT(4) NOT NULL DEFAULT 0,
  `usuarios_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_colaboradores_areas1_idx` (`areas_id` ASC),
  INDEX `fk_colaboradores_cargos1_idx` (`cargos_id` ASC),
  INDEX `fk_colaboradores_usuarios1_idx` (`usuarios_id` ASC),
  CONSTRAINT `fk_colaboradores_areas1`
    FOREIGN KEY (`areas_id`)
    REFERENCES `dbs_ponto`.`areas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_colaboradores_cargos1`
    FOREIGN KEY (`cargos_id`)
    REFERENCES `dbs_ponto`.`cargos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_colaboradores_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `dbs_ponto`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`enderecos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `endereco` VARCHAR(250) NOT NULL,
  `cidade` VARCHAR(250) NOT NULL,
  `bairro` VARCHAR(250) NOT NULL,
  `cep` VARCHAR(250) NOT NULL,
  `colaboradores_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_enderecos_colaboradores1_idx` (`colaboradores_id` ASC),
  CONSTRAINT `fk_enderecos_colaboradores1`
    FOREIGN KEY (`colaboradores_id`)
    REFERENCES `dbs_ponto`.`colaboradores` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`telefones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `telefone` VARCHAR(12) NOT NULL,
  `colaboradores_id` INT(11) NOT NULL,
  `tipo_telefones_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_telefones_colaboradores1_idx` (`colaboradores_id` ASC),
  INDEX `fk_telefones_tipo_telefones1_idx` (`tipo_telefones_id` ASC),
  CONSTRAINT `fk_telefones_colaboradores1`
    FOREIGN KEY (`colaboradores_id`)
    REFERENCES `dbs_ponto`.`colaboradores` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_telefones_tipo_telefones1`
    FOREIGN KEY (`tipo_telefones_id`)
    REFERENCES `dbs_ponto`.`tipo_telefones` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`marcacoes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `marcacao` DATETIME NOT NULL,
  `colaboradores_id` INT(11) NOT NULL,
  `cadastro` DATETIME NOT NULL DEFAULT NOW(),
  `atualizado` DATETIME NULL DEFAULT NULL,
  `ativo` TINYINT(4) NOT NULL DEFAULT 1,
  `excluido` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_horarios_colaboradores1_idx` (`colaboradores_id` ASC),
  CONSTRAINT `fk_horarios_colaboradores1`
    FOREIGN KEY (`colaboradores_id`)
    REFERENCES `dbs_ponto`.`colaboradores` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`justificativas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `justificativa` TEXT NOT NULL,
  `periodo` DATE NOT NULL,
  `tipo_justificativas_id` INT(11) NOT NULL,
  `colaboradores_id` INT(11) NOT NULL,
  `cadastro` DATETIME NOT NULL DEFAULT NOW(),
  `atualizado` DATETIME NULL DEFAULT NULL,
  `ativo` TINYINT(4) NOT NULL DEFAULT 1,
  `excluido` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_justificativas_tipo1_idx` (`tipo_justificativas_id` ASC),
  INDEX `fk_justificativas_colaboradores1_idx` (`colaboradores_id` ASC),
  CONSTRAINT `fk_justificativas_tipo1`
    FOREIGN KEY (`tipo_justificativas_id`)
    REFERENCES `dbs_ponto`.`tipo_justificativas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_justificativas_colaboradores1`
    FOREIGN KEY (`colaboradores_id`)
    REFERENCES `dbs_ponto`.`colaboradores` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`tipo_justificativas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo_justificativa` VARCHAR(250) NOT NULL,
  `cadastro` DATETIME NOT NULL DEFAULT NOW(),
  `atualizado` DATETIME NULL DEFAULT NULL,
  `ativo` TINYINT(4) NOT NULL DEFAULT 1,
  `excluido` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`tipo_telefones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo_telefone` VARCHAR(250) NOT NULL,
  `cadastro` DATETIME NOT NULL DEFAULT NOW(),
  `atualizado` DATETIME NULL DEFAULT NULL,
  `ativo` TINYINT(4) NOT NULL DEFAULT 1,
  `excluido` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`areas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `area` VARCHAR(250) NOT NULL,
  `sigla` VARCHAR(2) NOT NULL,
  `cadastro` DATETIME NOT NULL DEFAULT NOW(),
  `atualizado` DATETIME NULL DEFAULT NULL,
  `ativo` TINYINT(4) NOT NULL DEFAULT 1,
  `excluido` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`cargos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cargo` VARCHAR(250) NOT NULL,
  `cadastro` DATETIME NOT NULL DEFAULT NOW(),
  `atualizado` DATETIME NULL DEFAULT NULL,
  `ativo` TINYINT(4) NOT NULL DEFAULT 1,
  `excluido` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `dbs_ponto`.`usuarios_token` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(250) NOT NULL,
  `tempo_vida` DATETIME NOT NULL,
  `cadastro` DATETIME NOT NULL DEFAULT now(),
  `usuarios_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_usuarios_token_usuarios1_idx` (`usuarios_id` ASC),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC),
  CONSTRAINT `fk_usuarios_token_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `dbs_ponto`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
