<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Controllers;

use Classes\Marcacoes;
use Classes\Colaboradores;
use Controllers\AbstractController;
use Models\MarcacoesModel;
use Config\Exeption\StandartError;

/**
 * Controller de compartilhamento
 * @author Wallison do Carmo Costa
 */
class MarcacoesController extends AbstractController {

    /**
     * Recupera todos os registros
     */
    protected function findAll() {
        //valida quem tem acesso a esse metodo
        $url = $this->request["url"];

        $user = $this->authorization([ADMINISTRADOR, GERENTE, COLABORADOR], $this->request["authorization"], $url);

        if ($user) {
            $viewModel = new MarcacoesModel();

            $res = $viewModel->findAll($user['colaboradores_id']);

            if (!$res) {
               $this->returnJson($res, NOT_FOUND_CODE);
            } else {
                $this->returnJson($res, OK_CODE);
            }
        }
    }

    /**
     * Recupera todos os registros
     */
    protected function espelhoPontoGET() {
        //valida quem tem acesso a esse metodo
        $url = $this->request["url"];

        $user = $this->authorization([ADMINISTRADOR, GERENTE, COLABORADOR], $this->request["authorization"], $url);

        if ($user) {
            $viewModel = new MarcacoesModel();
            if ($this->request["ano"]) {
                $ano = trim($this->request["ano"]);
            } else {
                $ano = date("Y");
            }

            if ($this->request["id"]) {
                $mes = trim($this->request["id"]);
            } else {
                $mes = date("m");
            }

            $res = $viewModel->findAllByAnoMes($user['colaboradores_id'], $ano, $mes);

            if (!$res) {
                $this->returnJson($res, NOT_FOUND_CODE);
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
        if ($this->authorization([ADMINISTRADOR, GERENTE], $this->request["authorization"], $url)) {
            $viewModel = new MarcacoesModel();
            $res = $viewModel->findByIdDetail($this->request['id']);

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
    protected function baterPontoGET() {
        $url = $this->request["url"];

        $user = $this->authorization([ADMINISTRADOR, GERENTE, COLABORADOR], $this->request["authorization"], $url);

        if ($user) {

            $viewModel = new MarcacoesModel();

            $id = $user['colaboradores_id'];
            $dateTime = date('Y-m-d H:s');
            $horas = date('H:s');

            $carga_horaria = $user['carga_horaria'] / 5;
            $carga_horaria = date('H:i', strtotime($carga_horaria . ':00'));

            $date = date('Y-m-d');

            $marcacao = $viewModel->findByDate($id, $date);

            if (isset($marcacao["qtd_marcacao"])) {

                if ($marcacao["qtd_marcacao"] > LIMITE_MARCAOES) {
                    $res = new StandartError(BAD_REQUEST_CODE, BAD_REQUEST, ERROR_LIMITE_MARCAOES, $url);
                    $res->getJsonError();
                    return;
                }
            }

            $verf = verfificaDiaHoraUtil($dateTime);

            if ($verf) {
                $res = new StandartError(BAD_REQUEST_CODE, BAD_REQUEST, $verf, $url);
                $res->getJsonError();
                return;
            }

            $obj = new Marcacoes();
            $obj->setId(null);
            $obj->setMarcacao($dateTime);
            $obj->setColaborador(new Colaboradores());
            $obj->getColaborador()->setId($id);
            $result = $viewModel->add($obj);

            if (isset($marcacao["qtd_marcacao"])) {

                if ($marcacao["qtd_marcacao"] == LIMITE_MARCAOES - 1) {

                    $verf = boolHoraMaior($horas, $marcacao["marcacao"]["almoco_fim"], $marcacao["total"], $carga_horaria);

                    if ($verf) {

                        $url_inserido = str_replace(["_", "-", "baterponto"], ["", "", ""], $url);

                        header("Location: " . ROOT_URL . $url . '/' . $url_inserido);
                        $res = new StandartError(CREATE_CODE, WARNING, $verf, $url);
                        $res->getJsonError();
                        return;
                    }
                }
            }
            $this->returnJson($result, CREATE_CODE, $url);
        }
    }

    /**
     * ADD 
     */
    protected function pontoHojeGET() {
        $url = $this->request["url"];

        $user = $this->authorization([ADMINISTRADOR, GERENTE, COLABORADOR], $this->request["authorization"], $url);

        if ($user) {

            $viewModel = new MarcacoesModel();

            $date = date('Y-m-d');

            $result = $viewModel->findByDate($user['colaboradores_id'], $date);

            $this->returnJson($result, OK_CODE);
        }
    }

    /**
     * ADD 
     */
    protected function add() {
        $url = $this->request["url"];
        if ($this->authorization([ADMINISTRADOR], $this->request["authorization"], $url)) {

            $viewModel = new MarcacoesModel();
            $body = (array) $this->request["body"];
            $obj = new Marcacoes();

            if ($obj->validaCampos($obj->getCampos(), $body, $url)) {
                $obj->setId(null);
                $obj->setMarcacao($body['marcacao']);
                $obj->setColaborador(new Colaboradores());
                $obj->getColaborador()->setId($body['colaboradores_id']);
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
        if ($this->authorization([ADMINISTRADOR, GERENTE], $this->request["authorization"], $url)) {
            $viewModel = new MarcacoesModel();
            $obj = new Marcacoes();

            $id = $this->request["id"];
            $obj_old = $viewModel->findById($id);
            if ($obj_old) {

                $obj_new = (array) $this->request["body"];
                $body = $obj->compareDif($obj_new, $obj_old, $obj->getCampos());

                $code = OK_CODE;

                if ($obj->validaCampos($obj->getCampos(), $body, $url, true)) {
                    $obj->setId($body['id']);
                    $obj->setMarcacao($body['marcacao']);
                    $obj->setColaborador(new Colaboradores());
                    $obj->getColaborador()->setId($body['colaboradores_id']);
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
            $viewModel = new MarcacoesModel();
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
