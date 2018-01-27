<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\Usuarios;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class UsuariosModel extends AbstractModel {

    function findAll() {
        $this->query('SELECT u.id,email,acesso,acessos_id FROM usuarios AS u INNER JOIN acessos AS a ON (a.id=acessos_id) WHERE u.excluido=false ORDER BY email ASC;');
        $rows = $this->resultSet();
        return $rows;
    }

    private function checkEmail(Usuarios $obj) {

        $this->query("SELECT id FROM usuarios WHERE email=:email");
        $this->bind(':email', $obj->getEmail());

        if ($this->findOne()) {
            return null;
        } else {
            return ERROR_LOGIN_EMAIL;
        };
    }

    private function checkEmailSenha(Usuarios $obj) {

        $this->query("SELECT id FROM usuarios WHERE email=:email AND senha=md5(:senha)");
        $this->bind(':email', $obj->getEmail());
        $this->bind(':senha', $obj->getSenha());
        $res = $this->findOne();

        if ($res) {
            $obj->setId($res['id']);
            return $this->checkToken($obj);
        } else {
            return ERROR_LOGIN_SENHA;
        };
    }

    public function checkToken(Usuarios $obj) {

        $this->query("SELECT token,TIMEDIFF(tempo_vida,(ADDDATE( now(), INTERVAL " . TOKEN_CINCO_MINUTOS . "))) AS tempo_vida  FROM dbs_ponto.usuarios_token WHERE tempo_vida>NOW() AND usuarios_id=:id ORDER BY tempo_vida DESC;");
        $this->bind(':id', $obj->getId());
        $teste = $this->findOne();

        if ($teste) {
            if (strtotime($teste['tempo_vida']) > strtotime('00:00:00')) {
                return $teste["token"];
            };
        }
        return $this->createToken($obj);
    }

    public function createToken(Usuarios $obj) {

        $cstrong = True;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

        $this->query("INSERT INTO dbs_ponto.usuarios_token(token,tempo_vida,cadastro,usuarios_id)VALUES(:token,ADDDATE( now(), INTERVAL " . TOKEN_DEZ_MINUTOS . "),now(),:usuarios_id)");
        $this->bind(':token', $obj->getId() . $token);
        $this->bind(':usuarios_id', $obj->getId());
        $this->findOne();

        return $this->findTokenById($this->lastInsertId());
    }

    function findTokenById($id) {
        $this->query("SELECT token FROM dbs_ponto.usuarios_token WHERE id=:id");
        $this->bind(':id', $id);
        $teste = $this->findOne();
        return $teste["token"];
    }

    function login(Usuarios $obj) {

        // verifica se o email existe
        if ($this->checkEmail($obj) != ERROR_LOGIN_EMAIL) {
            //verifica se a senha e o login batem
            return $this->checkEmailSenha($obj); // retorna o token ou um erro
        }

        return ERROR_LOGIN_EMAIL;
    }

    function findById($id) {
        $this->query('SELECT u.id,email,acesso,acessos_id FROM usuarios AS u INNER JOIN acessos AS a ON (a.id=acessos_id) WHERE u.excluido=false AND u.id= :id;');
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE usuarios SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(Usuarios $obj) {

        $this->query("INSERT INTO dbs_ponto.usuarios (email,senha,acessos_id) VALUES (:email, md5(:senha),:acessos_id)");
        $this->bind(':email', $obj->getEmail());
        $this->bind(':senha', $obj->getSenha());
        $this->bind(':acessos_id', $obj->getAcesso()->getId());

        $this->execute();
        $id = $this->lastInsertId();

        if (!$id) {
            
        } else {
            return $this->findById($id);
        }
    }

    function update(Usuarios $obj) {

        $this->query('UPDATE dbs_ponto.usuarios SET email = :email, acessos_id = :acessos_id, atualizado= NOW() WHERE id = :id');
        $this->bind(':email', $obj->getEmail());
        $this->bind(':acessos_id', $obj->getAcesso()->getId());
        $this->bind(':id', $obj->getId());

        $this->execute();
    }

}
