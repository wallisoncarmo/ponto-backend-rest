<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Controllers;

use Classes\Justificativas;
use Classes\TipoJustificativas;
use Classes\Colaboradores;
use Controllers\AbstractController;
use Models\JustificativasModel;
use Config\Exeption\StandartError;

/**
 * Controller de compartilhamento
 * @author Wallison do Carmo Costa
 */
class JustificativasController extends AbstractController {

    /**
     * Recupera todos os registros
     */
    protected function findAll() {
        $viewModel = new JustificativasModel();
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
        $viewModel = new JustificativasModel();
        $url = $this->request["url"];
        $res = $viewModel->findByIdDetail($this->request['id']);

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
        $url = $this->request["url"];

        $user = $this->authorization([ADMINISTRADOR, GERENTE, COLABORADOR], $this->request["authorization"], $url);

        if ($user) {
            $viewModel = new JustificativasModel();
            $body = (array) $this->request["body"];
            $obj = new Justificativas();


            if ($obj->validaCampos($obj->getCampos(), $body, $url)) {

                $verf = (verfificaDiaUtil($body['periodo']));

                if ($verf) {
                    $res = new StandartError(BAD_REQUEST_CODE, BAD_REQUEST, $verf, $url);
                    $res->getJsonError();
                    return;
                }
                $obj->setId(null);
                $obj->setJustificativa($body['justificativa']);
                $obj->setPeriodo($body['periodo']);
                $obj->setTipoJustifitivas(new TipoJustificativas());
                $obj->getTipoJustifitivas()->setId($body['tipo_justificativas_id']);
                $obj->setColaborador(new Colaboradores());
                $obj->getColaborador()->setId($user['colaboradores_id']);
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
        $user = $this->authorization([ADMINISTRADOR, GERENTE, COLABORADOR], $this->request["authorization"], $url);
        if ($user) {
            $viewModel = new JustificativasModel();
            $obj = new Justificativas();

            $id = $this->request["id"];
            $obj_old = $viewModel->findById($id);
            if ($obj_old) {

                $obj_new = (array) $this->request["body"];
                $body = $obj->compareDif($obj_new, $obj_old, $obj->getCampos());

                $code = OK_CODE;

                if ($obj->validaCampos($obj->getCampos(), $body, $url, true)) {

                    $verf = (verfificaDiaUtil($body['periodo']));

                    if ($verf) {
                        $res = new StandartError(BAD_REQUEST_CODE, BAD_REQUEST, $verf, $url);
                        $res->getJsonError();
                        return;
                    }

                    $obj->setId($body['id']);
                    $obj->setJustificativa($body['justificativa']);
                    $obj->setPeriodo($body['periodo']);
                    $obj->setTipoJustifitivas(new TipoJustificativas());
                    $obj->getTipoJustifitivas()->setId($body['tipo_justificativas_id']);
                    $obj->setColaborador(new Colaboradores());
                    $obj->getColaborador()->setId($user['colaboradores_id']);
                    $this->returnJson($viewModel->update($obj), $code, $url);
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
        if ($this->authorization([ADMINISTRADOR, GERENTE], $this->request["authorization"], $url)) {
            $viewModel = new JustificativasModel();
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

}
