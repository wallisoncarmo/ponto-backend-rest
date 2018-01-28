<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\Telefones;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class TelefonesModel extends AbstractModel {

    function findAll() {
        $this->query('SELECT id,telefone FROM telefones WHERE excluido=false ORDER BY telefone ASC;');
        $rows = $this->resultSet();
        return $rows;
    }

    function findById($id) {
        $this->query('SELECT id,telefone FROM telefones WHERE excluido=false AND id= :id;');
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE telefones SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(Telefones $obj) {
        $this->query("INSERT INTO 
                    telefones(telefone,tipo_telefones_id,colaboradores_id) 
                    VALUES (:telefone,:tipo_telefones_id,:colaboradores_id);");
        $this->bind(':telefone', $obj->getTelefone());
        $this->bind(':tipo_telefones_id', $obj->getTipoTelefone()->getId());
        $this->bind(':colaboradores_id', $obj->getColaborador()->getId());
        return $this->execute();
    }

    function update(Telefones $obj) {
        $this->query("UPDATE dbs_ponto.telefones
                    SET telefone = :telefone,
                    colaboradores_id = :colaboradores_id,
                    tipo_telefones_id = :tipo_telefones_id
                    WHERE id =:id");
        $this->bind(':telefone', $obj->getTelefone());
        $this->bind(':tipo_telefones_id', $obj->getTipo_telefone()->getId());
        $this->bind(':colaboradores_id', $obj->getColaborador()->getId());
        $this->bind(':id', $obj->getId());

        $this->execute();
    }

}
