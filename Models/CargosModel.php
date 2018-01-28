<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\Cargos;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class CargosModel extends AbstractModel {

    function findAll() {
        $this->query('SELECT id,cargo FROM cargos WHERE excluido=false ORDER BY cargo ASC;');
        $rows = $this->resultSet();
        return $rows;
    }

    function findById($id) {
        $this->query('SELECT id,cargo FROM cargos WHERE excluido=false AND id= :id;');
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE cargos SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(Cargos $obj) {

        $this->query("INSERT INTO cargos(cargo) VALUES (:cargo);");
        $this->bind(':cargo', $obj->getCargo());

        $this->execute();
        $id = $this->lastInsertId();

        return ['id' => $id];
    }

    function update(Cargos $obj) {

        $this->query("UPDATE cargos SET cargo=:cargo, atualizado=now() WHERE id=:id;");
        $this->bind(':cargo', $obj->getCargo());
        $this->bind(':id', $obj->getId());

        $this->execute();
    }

}
