<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\TipoJustificativas;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class TipoJustificativasModel extends AbstractModel {

    function findAll() {
        $this->query('SELECT id,tipo_justificativa FROM tipo_justificativas WHERE excluido=false ORDER BY tipo_justificativa ASC;');
        $rows = $this->resultSet();
        return $rows;
    }

    function findById($id) {
        $this->query('SELECT id,tipo_justificativa FROM tipo_justificativas WHERE excluido=false AND id= :id;');
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE tipo_justificativas SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(TipoJustificativas $obj) {

        $this->query("INSERT INTO tipo_justificativas(tipo_justificativa) VALUES (:tipo_justificativa);");
        $this->bind(':tipo_justificativa', $obj->getTipoJustificativa());

        $this->execute();
        $id = $this->lastInsertId();

        if (!$id) {
            
        } else {
            return $this->findById($id);
        }
    }

    function update(TipoJustificativas $obj) {

        $this->query("UPDATE tipo_justificativas SET tipo_justificativa=:tipo_justificativa, atualizado=now() WHERE id=:id;");
        $this->bind(':tipo_justificativa', $obj->getTipoJustificativa());
        $this->bind(':id', $obj->getId());

        $this->execute();
    }

}
