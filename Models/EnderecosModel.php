<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\Enderecos;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class EnderecosModel extends AbstractModel {

    function findAll() {
        $this->query('SELECT id,endereco FROM enderecos WHERE excluido=false ORDER BY endereco ASC;');
        $rows = $this->resultSet();
        return $rows;
    }

    function findById($id) {
        $this->query('SELECT id,endereco FROM enderecos WHERE excluido=false AND id= :id;');
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE enderecos SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(Enderecos $obj) {

        $this->query("INSERT INTO dbs_ponto.enderecos
                    (endereco,cidade,bairro,colaboradores_id)
                    VALUES
                    (:endereco,:cidade,:bairro,:colaboradores_id)");
        $this->bind(':endereco', $obj->getEndereco());
        $this->bind(':cidade', $obj->getCidade());
        $this->bind(':bairro', $obj->getBairro());
        $this->bind(':colaboradores_id', $obj->getColaborador()->getId());

        return $this->execute();
    }

    function update(Enderecos $obj) {

        $this->query("UPDATE enderecos 
                    SET 
                        endereco=:endereco, 
                        cidade=:cidade, 
                        bairro=:bairro, 
                        colaboradores_id=:colaboradores_id
                    WHERE id=:id;");
        $this->bind(':endereco', $obj->getEndereco());
        $this->bind(':cidade', $obj->getCidade());
        $this->bind(':bairro', $obj->getBairro());
        $this->bind(':colaboradores_id', $obj->getId());
        $this->bind(':id', $obj->getId());
        $this->execute();
    }

}
