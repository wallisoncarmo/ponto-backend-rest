<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Controllers;

use Classes\Cargos;
use Controllers\AbstractController;
use Models\CargosModel;
use Config\Exeption\StandartError;

/**
 * Controller de compartilhamento
 * @author Wallison do Carmo Costa
 */
class CargosController extends AbstractController {

    /**
     * Recupera todos os registros
     */
    protected function findAll() {
        $viewModel = new CargosModel();
        $url = $this->request["url"];
        $res = $viewModel->findAll();

        if (!$res) {
            $res = array();
        } else {
            $this->returnJson($res, OK_CODE);
        }
    }

    /**
     * Recupera um registro
     */
    protected function findById() {
        $viewModel = new CargosModel();
        $url = $this->request["url"];
        $res = $viewModel->findById($this->request['id']);

        $code = OK_CODE;
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
        $viewModel = new CargosModel();
        $url = $this->request["url"];
        $body = (array) $this->request["body"];
        $obj = new Cargos();

        if ($obj->validaCampos($obj->getCampos(), $body, $url)) {
            $obj->setId(null);
            $obj->setCargo($body['cargo']);
            $this->returnJson($viewModel->add($obj), CREATE_CODE, $url);
        }
    }

    /**
     * Atualiza um registro
     */
    protected function update() {
        $viewModel = new CargosModel();
        $url = $this->request["url"];
        $obj = new Cargos();

        $id = $this->request["id"];
        $obj_old = $viewModel->findById($id);
        if ($obj_old) {

            $obj_new = (array) $this->request["body"];
            $body = $obj->compareDif($obj_new, $obj_old,$obj->getCampos());
            
            $code = OK_CODE;

            if ($obj->validaCampos($obj->getCampos(), $body, $url, true)) {
                $obj->setId($body['id']);
                $obj->setCargo($body['cargo']);
                $this->returnJson($viewModel->update($obj), $code, $url);
            }
        } else {
            $res = new StandartError(BAD_REQUEST_CODE, NOT_FOUND, NOT_FOUND_ID, $url);
            $res->getJsonError();
        }
    }

    /**
     * Deleta um registro
     */
    protected function delete() {
        $url = $this->request["url"];
        $viewModel = new CargosModel();
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
