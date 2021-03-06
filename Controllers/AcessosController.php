<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Controllers;

use Classes\Acessos;
use Controllers\AbstractController;
use Models\AcessosModel;
use Config\Exeption\StandartError;

/**
 * Controller de compartilhamento
 * @author Wallison do Carmo Costa
 */
class AcessosController extends AbstractController {

    /**
     * Recupera todos os registros
     */
    protected function findAll() {
        $url = $this->request["url"];

        //valida quem tem acesso a esse metodo
        if ($this->authorization([ADMINISTRADOR], $this->request["authorization"], $url)) {
            $viewModel = new AcessosModel();
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
            $viewModel = new AcessosModel();
            $res = $viewModel->findById($this->request['id']);

            $code = OK_CODE;
            if (!$res) {
                $res = array();
                $code = 404;
            }
            $this->returnJson($res, $code);
        }
    }

    /**
     * ADD 
     */
    protected function add() {
        $url = $this->request["url"];
        
        //valida quem tem acesso a esse metodo
        if ($this->authorization([ADMINISTRADOR], $this->request["authorization"], $url)) {
            $viewModel = new AcessosModel();
            $body = (array) $this->request["body"];
            $obj = new Acessos();

            if ($obj->validaCampos($obj->getCampos(), $body, $url)) {
                $obj->setId(null);
                $obj->setAcesso($body['acesso']);
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
            $viewModel = new AcessosModel();
            $obj = new Acessos();

            $id = $this->request["id"];
            $obj_old = $viewModel->findById($id);
            if ($obj_old) {

                $obj_new = (array) $this->request["body"];
                $body = $obj->compareDif($obj_new, $obj_old, $obj->getCampos());

                $code = OK_CODE;

                if ($obj->validaCampos($obj->getCampos(), $body, $url, true)) {
                    $obj->setId($body['id']);
                    $obj->setAcesso($body['acesso']);
                    $this->returnJson($viewModel->update($obj), $code, $url);
                }
            } else {
                $res = new StandartError(NOT_FOUND_CODE, NOT_FOUND, NOT_FOUND_ID, $url);
                $res->getJsonError();
            }
        }
    }

    /**
     * Deleta um registro
     */
    protected function delete() {
        $url = $this->request["url"];
        if ($this->authorization([ADMINISTRADOR], $this->request["authorization"], $url)) {
            $viewModel = new AcessosModel();
            $obj = $viewModel->findById($this->request['id']);

            if ($obj) {
                $res = $viewModel->delete($this->request['id']);
                $this->returnJson($res, OK_CODE);
            } else {
                $res = new StandartError(NOT_FOUND_CODE, NOT_FOUND, NOT_FOUND_ID, $url);
                $res->getJsonError();
            }
        }
    }

}
