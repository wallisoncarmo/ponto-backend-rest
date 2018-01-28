<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\Acessos;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class AcessosModel extends AbstractModel {

    function findAll() {
        $this->query('SELECT id,acesso FROM acessos WHERE excluido=false ORDER BY acesso ASC;');
        $rows = $this->resultSet();
        return $rows;
    }

    function findById($id) {
        $this->query('SELECT id,acesso FROM acessos WHERE excluido=false AND id= :id;');
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE acessos SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(Acessos $obj) {

        $this->query("INSERT INTO acessos(acesso) VALUES (:acesso);");
        $this->bind(':acesso', $obj->getAcesso());

        $this->execute();
        $id = $this->lastInsertId();

        return ['id' => $id];
    }

    function update(Acessos $obj) {

        $this->query("UPDATE acessos SET acesso=:acesso, atualizado=now() WHERE id=:id;");
        $this->bind(':acesso', $obj->getAcesso());
        $this->bind(':id', $obj->getId());

        $this->execute();
    }

}
