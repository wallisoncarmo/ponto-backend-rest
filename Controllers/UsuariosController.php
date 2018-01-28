<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Controllers;

use Classes\Usuarios;
use Classes\Acessos;
use Controllers\AbstractController;
use Models\UsuariosModel;
use Config\Exeption\StandartError;

/**
 * Controller de compartilhamento
 * @author Wallison do Carmo Costa
 */
class UsuariosController extends AbstractController {

    /**
     * Recupera todos os registros
     */
    protected function findAll() {
        $url = $this->request["url"];

        //valida quem tem acesso a esse metodo
        if ($this->authorization([ADMINISTRADOR], $this->request["authorization"], $url)) {
            $viewModel = new UsuariosModel();
            $res = $viewModel->findAll();

            if (!$res) {
                $res = array();
            } else {
                $this->returnJson($res, OK_CODE);
            }
        }
    }

    /**
     * Recupera um registro
     */
    protected function findById() {
        $url = $this->request["url"];

        //valida quem tem acesso a esse metodo
        if ($this->authorization([ADMINISTRADOR], $this->request["authorization"], $url)) {
            $viewModel = new UsuariosModel();
            $res = $viewModel->findById($this->request['id']);

            $code = OK_CODE;
            if (!$res) {
                $res = array();
                $code = NOT_FOUND_CODE;
            }
            $this->returnJson($res, $code);
        }
    }

    /**
     * ADD share
     */
    protected function login() {
        $url = $this->request["url"];

        //verifica se está sendo acessado pelo metodo POST
        if ($this->request['type'] == "POST") {

            $viewModel = new UsuariosModel();
            $body = (array) $this->request["body"];
            $obj = new Usuarios();

            if (isset($body['email']) && isset($body['senha'])) {
                $obj->setEmail($body['email']);
                $obj->setSenha($body['senha']);

                $token = $viewModel->login($obj);

                if ($token == ERROR_LOGIN_EMAIL) {
                    $res = new StandartError(NOT_FOUND_CODE, NOT_FOUND, ERROR_LOGIN_EMAIL, $url);
                    $res->getJsonError();
                } else if ($token == ERROR_LOGIN_SENHA) {
                    $res = new StandartError(NOT_FOUND_CODE, NOT_FOUND, ERROR_LOGIN_SENHA, $url);
                    $res->getJsonError();
                } else {
                    $this->returnJsonLogin($token, OK_CODE);
                }
            }
        } else {
            $res = new StandartError(METHOD_NOT_ALLOWED_CODE, METHOD_NOT_ALLOWED, METHOD_NOT_ALLOWED_MSG, $url);
            $res->getJsonError();
        }
    }

    /**
     * ADD share
     */
    protected function add() {
        $url = $this->request["url"];

        //valida quem tem acesso a esse metodo
        if ($this->authorization([ADMINISTRADOR], $this->request["authorization"], $url)) {
            $viewModel = new UsuariosModel();
            $body = (array) $this->request["body"];

            $obj = new Usuarios();

            if ($obj->validaCampos($obj->getCampos(), $body, $url)) {

                $obj->setId(null);
                $obj->setEmail($body['email']);
                $obj->setSenha($body['senha']);
                $obj->setAcesso(new Acessos());
                $obj->getAcesso()->setId($body['acessos_id']);
                $this->returnJson($viewModel->add($obj), CREATE_CODE, $url);
            }
        }
    }

    /**
     * Atualiza um registro
     */
    protected function update() {
        $url = $this->request["url"];

        //valida quem tem acesso a esse metodo
        if ($this->authorization([ADMINISTRADOR], $this->request["authorization"], $url)) {
            $viewModel = new UsuariosModel();

            $obj = new Usuarios();

            $id = $this->request["id"];
            $obj_old = $viewModel->findById($id);
            if ($obj_old) {

                $obj_new = (array) $this->request["body"];
                $body = $obj->compareDif($obj_new, $obj_old, $obj->getCampos());

                $code = OK_CODE;

                if ($obj->validaCampos($obj->getCampos(), $body, $url, true)) {
                    $obj->setId($body['id']);
                    $obj->setEmail($body['email']);
                    $obj->setAcesso(new Acessos());
                    $obj->getAcesso()->setId($body['acessos_id']);

                    $this->returnJson($viewModel->update($obj), $code);
                }
            } else {
                $res = new StandartError(BAD_REQUEST_CODE, NOT_FOUND, NOT_FOUND_ID, $url);
                $res->getJsonError();
            }
        }
    }

    /**
     * Deleta um registro
     */
    protected function delete() {
        $url = $this->request["url"];

        //valida quem tem acesso a esse metodo
        if ($this->authorization([ADMINISTRADOR], $this->request["authorization"], $url)) {
            $viewModel = new UsuariosModel();
            $obj = $viewModel->findById($this->request['id']);

            if ($obj) {
                $res = $viewModel->delete($this->request['id']);
                $this->returnJson($res, OK_CODE);
            } else {
                $res = new StandartError(BAD_REQUEST_CODE, NOT_FOUND, NOT_FOUND_ID, $url);
                $res->getJsonError();
            }
        }
    }

    /**
     * Monta a saida do serviço
     * @param type $data
     */
    protected function returnJsonLogin($token, $code) {

        $token = "Beare " . $token;
        header("Authorization: {$token}");
        http_response_code($code);
    }

}
