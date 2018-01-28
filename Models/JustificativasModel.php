<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\Justificativas;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class JustificativasModel extends AbstractModel {

    function findAll() {
        $this->query("SELECT j.id,justificativa,DATE_FORMAT(periodo, '%d/%m/%Y') as periodo,tipo_justificativa,nome,tipo_justificativas_id,colaboradores_id 
                    FROM justificativas AS j
                    INNER JOIN tipo_justificativas AS tj ON (tj.id=tipo_justificativas_id)
                    INNER JOIN colaboradores AS c ON (c.id=colaboradores_id)
                    WHERE j.excluido=false 
                    ORDER BY periodo DESC;");
        $rows = $this->resultSet();
        return $rows;
    }

    function findByIdDetail($id) {
        $this->query("SELECT j.id,justificativa,DATE_FORMAT(periodo, '%d/%m/%Y') as periodo,tipo_justificativa,nome,tipo_justificativas_id,colaboradores_id 
                    FROM justificativas AS j
                    INNER JOIN tipo_justificativas AS tj ON (tj.id=tipo_justificativas_id)
                    INNER JOIN colaboradores AS c ON (c.id=colaboradores_id)
                    WHERE j.excluido=false 
                    AND j.id=:id
                    ORDER BY periodo DESC;");
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function findById($id) {
        $this->query('SELECT id,justificativa,periodo,tipo_justificativas_id ,colaboradores_id FROM justificativas WHERE excluido=false AND id= :id;');
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE justificativas SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(Justificativas $obj) {

        $this->query("INSERT INTO dbs_ponto.justificativas
                (justificativa,periodo,tipo_justificativas_id,colaboradores_id)
                VALUES
                (:justificativa,:periodo,:tipo_justificativas_id,:colaboradores_id)");
        $this->bind(':justificativa', $obj->getJustificativa());
        $this->bind(':periodo', $obj->getPeriodo());
        $this->bind(':tipo_justificativas_id', $obj->getTipoJustifitivas()->getId());
        $this->bind(':colaboradores_id', $obj->getColaborador()->getId());

        $this->execute();
        $id = $this->lastInsertId();

        if (!$id) {
            
        } else {
            return $this->findById($id);
        }
    }

    function update(Justificativas $obj) {

        $this->query("UPDATE justificativas 
                    SET 
                     justificativa=:justificativa,
                     periodo=:periodo,
                     tipo_justificativas_id=:tipo_justificativas_id,
                     colaboradores_id=:colaboradores_id,
                     atualizado=now() 
                    WHERE id=:id;");
        $this->bind(':justificativa', $obj->getJustificativa());
        $this->bind(':periodo', $obj->getPeriodo());
        $this->bind(':tipo_justificativas_id', $obj->getTipoJustifitivas()->getId());
        $this->bind(':colaboradores_id', $obj->getColaborador()->getId());
        $this->bind(':id', $obj->getId());

        $this->execute();
    }

}
