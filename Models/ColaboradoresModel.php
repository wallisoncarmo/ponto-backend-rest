<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\Colaboradores;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class ColaboradoresModel extends AbstractModel {

    function findAll() {
        $this->query('SELECT 
                        c.id, 
                        nome,
                        cpf, 
                        rg, 
                        genero, 
                        matricula,
                        carga_horaria, 
                        areas_id,
                        area,
                        cargos_id, 
                        cargo,
                        usuarios_id,
                        email,
                        
                        endereco,
                        cep,
                        cidade,
                        bairro,
                        telefone as telefone1,
                        
                        tipo_telefone,
                        tipo_telefones_id as tipo1
                    FROM dbs_ponto.colaboradores AS c
                    LEFT JOIN enderecos AS e ON (c.id = e.colaboradores_id) 
                    LEFT JOIN telefones AS t ON (c.id = t.colaboradores_id) 
                    LEFT JOIN tipo_telefones AS tt ON (tt.id = tipo_telefones_id)  
                    LEFT JOIN areas AS a ON (a.id = areas_id)  
                    LEFT JOIN cargos AS ca ON (ca.id = cargos_id) 
                    LEFT JOIN usuarios AS u ON (u.id = usuarios_id) 
                    WHERE c.excluido=false ORDER BY nome ASC;');
        $rows = $this->resultSet();
        return $rows;
    }

    function findById($id) {
        $this->query('SELECT 
                        c.id, 
                        nome,
                        cpf, 
                        rg, 
                        genero, 
                        matricula,
                        carga_horaria, 
                        areas_id,
                        area,
                        cargos_id, 
                        cargo,
                        usuarios_id,
                        email,
                        endereco,
                        e.id as endereco_id,
                        cep,
                        cidade,
                        bairro,
                        telefone as telefone1,
                        telefone as telefone_id,
                        tipo_telefone,
                        tipo_telefones_id as tipo1
                    FROM dbs_ponto.colaboradores AS c
                    LEFT JOIN enderecos AS e ON (c.id = e.colaboradores_id) 
                    LEFT JOIN telefones AS t ON (c.id = t.colaboradores_id) 
                    LEFT JOIN tipo_telefones AS tt ON (tt.id = tipo_telefones_id)  
                    LEFT JOIN areas AS a ON (a.id = areas_id)  
                    LEFT JOIN cargos AS ca ON (ca.id = cargos_id) 
                    LEFT JOIN usuarios AS u ON (u.id = usuarios_id) 
                    WHERE c.excluido=false AND c.id= :id;');
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE colaboradores SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(Colaboradores $obj) {

        $this->query("INSERT INTO dbs_ponto.colaboradores
                    (nome,cpf,rg,genero,matricula,carga_horaria,areas_id,cargos_id,usuarios_id)
                    VALUES
                    (:nome,:cpf,:rg,:genero,:matricula,:carga_horaria,:areas_id,:cargos_id,:usuarios_id);");
        $this->bind(':nome', $obj->getNome());
        $this->bind(':cpf', $obj->getCpf());
        $this->bind(':rg', $obj->getCpf());
        $this->bind(':genero', $obj->getGenero());
        $this->bind(':matricula', $obj->getMatricula());
        $this->bind(':carga_horaria', $obj->getCarga_horaria());
        $this->bind(':areas_id', $obj->getArea()->getId());
        $this->bind(':cargos_id', $obj->getCargo()->getId());
        $this->bind(':usuarios_id', $obj->getUsuario()->getId());

        $this->execute();
        $id = $this->lastInsertId();

        return ['id' => $id];
    }

    function update(Colaboradores $obj) {

        $this->query("UPDATE colaboradores 
                    SET 
                    nome=:nome,
                    cpf=:cpf,
                    rg=:rg,
                    genero=:genero,
                    matricula=:matricula,
                    carga_horaria=:carga_horaria,
                    areas_id=:areas_id,
                    cargos_id=:cargos_id,
                    usuarios_id=:usuarios_id,
                    atualizado=now() WHERE id=:id;");

        $this->bind(':nome', $obj->getNome());
        $this->bind(':cpf', $obj->getCpf());
        $this->bind(':rg', $obj->getRg());
        $this->bind(':genero', $obj->getGenero());
        $this->bind(':matricula', $obj->getMatricula());
        $this->bind(':carga_horaria', $obj->getCarga_horaria());
        $this->bind(':areas_id', $obj->getArea()->getId());
        $this->bind(':cargos_id', $obj->getCargo()->getId());
        $this->bind(':usuarios_id', $obj->getUsuario()->getId());
        $this->bind(':id', $obj->getId());

        $this->execute();
    }

}
