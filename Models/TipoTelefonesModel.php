<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\TipoTelefones;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class TipoTelefonesModel extends AbstractModel {

    function findAll() {
        $this->query('SELECT id,tipo_telefone FROM tipo_telefones WHERE excluido=false ORDER BY tipo_telefone ASC;');
        $rows = $this->resultSet();
        return $rows;
    }

    function findById($id) {
        $this->query('SELECT id,tipo_telefone FROM tipo_telefones WHERE excluido=false AND id= :id;');
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE tipo_telefones SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(TipoTelefones $obj) {

        $this->query("INSERT INTO tipo_telefones(tipo_telefone) VALUES (:tipo_telefone);");
        $this->bind(':tipo_telefone', $obj->getTipoTelefone());

        $this->execute();
        $id = $this->lastInsertId();

        return ['id'=>$id];
    }

    function update(TipoTelefones $obj) {

        $this->query("UPDATE tipo_telefones SET tipo_telefone=:tipo_telefone, atualizado=now() WHERE id=:id;");
        $this->bind(':tipo_telefone', $obj->getTipoTelefone());
        $this->bind(':id', $obj->getId());

        $this->execute();
    }

}
