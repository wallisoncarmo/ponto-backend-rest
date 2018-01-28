<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\Areas;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class AreasModel extends AbstractModel {

    function findAll() {
        $this->query('SELECT id,area,sigla FROM areas WHERE excluido=false ORDER BY area ASC;');
        $rows = $this->resultSet();
        return $rows;
    }

    function findById($id) {
        $this->query('SELECT id,area,sigla FROM areas WHERE excluido=false AND id= :id;');
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE areas SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(Areas $obj) {

        $this->query("INSERT INTO areas(area,sigla) VALUES (:area,:sigla);");
        $this->bind(':area', $obj->getArea());
        $this->bind(':sigla', $obj->getSigla());

        $this->execute();
        $id = $this->lastInsertId();

        return ['id' => $id];
    }

    function update(Areas $obj) {

        $this->query("UPDATE areas SET area=:area, sigla=:sigla, atualizado=now() WHERE id=:id;");
        $this->bind(':area', $obj->getArea());
        $this->bind(':sigla', $obj->getSigla());
        $this->bind(':id', $obj->getId());

        $this->execute();
    }

}
