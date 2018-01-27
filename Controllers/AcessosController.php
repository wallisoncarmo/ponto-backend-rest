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
        $viewModel = new AcessosModel();
        $url = $this->request["url"];
        $res = $viewModel->findAll();

        if (!$res) {
            $res = array();
        } else {
            $this->returnJson($res, 200);
        }
    }

    /**
     * Recupera um registro
     */
    protected function findById() {
        $viewModel = new AcessosModel();
        $url = $this->request["url"];
        $res = $viewModel->findById($this->request['id']);

        $code = 200;
        if (!$res) {
            $res = array();
            $code = 404;
        }
        $this->returnJson($res, $code);
    }

    /**
     * ADD share
     */
    protected function add() {
        $viewModel = new AcessosModel();
        $url = $this->request["url"];
        $body = (array) $this->request["body"];
        $obj = new Acessos();

        if ($obj->validaCampos($obj->getCampos(), $body, $url)) {
            $obj->setId(null);
            $obj->setAcesso($body['acesso']);
            $this->returnJson($viewModel->add($obj), 201, $url);
        }
    }

    /**
     * Atualiza um registro
     */
    protected function update() {
        $viewModel = new AcessosModel();
        $url = $this->request["url"];
        $obj = new Acessos();

        $id = $this->request["id"];
        $obj_old = $viewModel->findById($id);
        if ($obj_old) {

            $obj_new = (array) $this->request["body"];
            $body = $obj->compareDif($obj_new, $obj_old);
            
            $code = 200;

            if ($obj->validaCampos($obj->getCampos(), $body, $url, true)) {
                $obj->setId($body['id']);
                $obj->setAcesso($body['acesso']);
                $this->returnJson($viewModel->update($obj), $code, $url);
            }
        } else {
            $res = new StandartError(400, 'Not Found', 'O Id informado não existe!', $url);
            $res->getJsonError();
        }
    }

    /**
     * Deleta um registro
     */
    protected function delete() {
        $url = $this->request["url"];
        $viewModel = new AcessosModel();
        $obj = $viewModel->findById($this->request['id']);

        if ($obj) {
            $res = $viewModel->delete($this->request['id']);
            $this->returnJson($res, 200);
        } else {
            $res = new StandartError(400, 'Not Found', 'O Id informado não existe!', $url);
            $res->getJsonError();
        }
    }

}
