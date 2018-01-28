<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Controllers;

use Classes\Colaboradores;
use Classes\Usuarios;
use Classes\Cargos;
use Classes\Areas;
use Classes\Telefones;
use Classes\TipoTelefones;
use Classes\Enderecos;
use Controllers\AbstractController;
use Config\Exeption\StandartError;
use Models\ColaboradoresModel;
use Models\UsuariosModel;
use Models\EnderecosModel;
use Models\TelefonesModel;

/**
 * Controller de compartilhamento
 * @author Wallison do Carmo Costa
 */
class ColaboradoresController extends AbstractController {

    /**
     * Recupera todos os registros
     */
    protected function findAll() {
        $viewModel = new ColaboradoresModel();
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
        $viewModel = new ColaboradoresModel();
        $url = $this->request["url"];
        $res = $viewModel->findById($this->request['id']);

        $code = OK_CODE;
        if (!$res) {
            $res = array();
            $code = 404;
        }
        $this->returnJson($res, $code);
        return;
    }

    /**
     * ADD share
     */
    protected function add() {
        $url = $this->request["url"];

        //valida quem tem acesso a esse metodo
        if ($this->authorization([ADMINISTRADOR, GERENTE], $this->request["authorization"], $url)) {
            $body = (array) $this->request["body"];
            $obj = new Colaboradores();

            if ($obj->validaCampos($obj->getCampos(), $body, $url)) {
                $usuarioModel = new UsuariosModel();
                $colaboradoresModel = new ColaboradoresModel();
                $telefonesModel = new TelefonesModel();
                $enderecosModel = new EnderecosModel();

                // bloco de usuário
                $obj->setUsuario(new Usuarios());
                $obj->getUsuario()->setEmail($body['email']);
                $obj->getUsuario()->setSenha($body['senha']);
                $obj->getUsuario()->setAcesso(new \Classes\Acessos());
                $obj->getUsuario()->getAcesso()->setId(COLABORADOR_ID);
                $usuarios_id = $usuarioModel->add($obj->getUsuario());

                if (!$usuarios_id) {
                    $res = new StandartError(BAD_REQUEST_CODE, BAD_REQUEST, ERROR_SINTAXE, $url);
                    $res->getJsonError();
                    return;
                }

                // COLABORADOR
                $obj->setId(null);
                $obj->setNome($body['nome']);
                $obj->setCpf($body['cpf']);
                $obj->setRg($body['rg']);
                $obj->setGenero($body['genero']);
                $obj->setMatricula($body['matricula']);
                $obj->setCarga_horaria($body['carga_horaria']);
                $obj->setArea(new Areas());
                $obj->getArea()->setId($body['areas_id']);
                $obj->setCargo(new Cargos());
                $obj->getCargo()->setId($body['cargos_id']);
                $obj->getUsuario()->setId($usuarios_id["id"]);
                $colaborador = $colaboradoresModel->add($obj);

                // ENDERECOS

                $obj->setEndereco(new Enderecos());
                $obj->setId($colaborador['id']);
                $obj->getEndereco()->setColaborador($obj);
                $obj->getEndereco()->setEndereco($body['endereco']);
                $obj->getEndereco()->setCidade($body['cidade']);
                $obj->getEndereco()->setBairro($body['bairro']);
                $obj->getEndereco()->setCep($body['cep']);

                $enderecos = $enderecosModel->add($obj->getEndereco());

                // TELEFONES
                $obj->SetTelefone(new Telefones());
                $obj->getTelefone()->setColaborador($obj);
                $obj->getTelefone()->setTelefone($body['telefone1']);
                $obj->getTelefone()->setTipoTelefone(new TipoTelefones());
                $obj->getTelefone()->getTipoTelefone()->setId($body['tipo1']);

                $telefone = $telefonesModel->add($obj->getTelefone());

                if (isset($body['telefone2']) && $body['tipo2']) {
                    $obj->getTelefone()->setTelefone($body['telefone1']);
                    $obj->getTelefone()->setTipoTelefone(new TipoTelefones());
                    $obj->getTelefone()->getTipoTelefone()->setId($body['tipo1']);
                }

                $telefone = $telefonesModel->add($obj->getTelefone());

                $this->returnJson($colaborador, CREATE_CODE, $url);
                return;
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

            $viewModel = new ColaboradoresModel();
            $obj = new Colaboradores();
            $endereco = null;
            $telefone = null;

            $id = $this->request["id"];
            $obj_old = $viewModel->findById($id);

            if ($obj_old) {
                $obj_new = (array) $this->request["body"];

                // verifico se possue alteração em endereco
                if (isset($obj_new['endereco']) || isset($obj_new['cidade']) || isset($obj_new['bairro']) || isset($obj_new['cep'])) {
                    $endereco = 1;
                }

                // verifico se possue alteração em telefone
                if (isset($obj_new['telefone']) || isset($obj_new['tipo_telefones_id'])) {
                    $telefone = 1;
                }

                $body = $obj->compareDif($obj_new, $obj_old, $obj->getCampos());

                $code = OK_CODE;

                if ($obj->validaCampos($obj->getCampos(), $body, $url, true)) {

                    // COLABORADOR
                    $obj->setId($id);
                    $obj->setNome($body['nome']);
                    $obj->setCpf($body['cpf']);
                    $obj->setRg($body['rg']);
                    $obj->setGenero($body['genero']);
                    $obj->setMatricula($body['matricula']);
                    $obj->setCarga_horaria($body['carga_horaria']);
                    $obj->setArea(new Areas());
                    $obj->getArea()->setId($body['areas_id']);
                    $obj->setCargo(new Cargos());
                    $obj->getCargo()->setId($body['cargos_id']);
                    $obj->setUsuario(new Usuarios());
                    $obj->getUsuario()->setId($body['usuarios_id']);

                    $colaborador = $viewModel->update($obj);

                    // ENDERECOS
                    if ($endereco) {
                        $enderecosModel = new EnderecosModel();
                        $obj->setEndereco(new Enderecos());
                        $obj->getEndereco()->setId($colaborador['enderecos_id']);
                        $obj->getEndereco()->setColaborador($obj);
                        $obj->getEndereco()->setEndereco($body['endereco']);
                        $obj->getEndereco()->setCidade($body['cidade']);
                        $obj->getEndereco()->setBairro($body['bairro']);
                        $obj->getEndereco()->setCep($body['cep']);

                        $enderecos = $enderecosModel->update($obj->getEndereco());
                    }

                    // TELEFONES
                    if ($telefone) {

                        $telefonesModel = new TelefonesModel();
                        $obj->SetTelefone(new Telefones());
                        $obj->SetTelefone()->setId($colaborador['telefones_id']);
                        $obj->getTelefone()->setColaborador($obj);
                        $obj->getTelefone()->setTelefone($body['telefone1']);
                        $obj->getTelefone()->setTipoTelefone(new TipoTelefones());
                        $obj->getTelefone()->getTipoTelefone()->setId($body['tipo1']);

                        $telefone = $telefonesModel->update($obj->getTelefone());
                    }


                    $this->returnJson($colaborador, $code, $url);
                    return;
                }
            } else {
                $res = new StandartError(BAD_REQUEST_CODE, NOT_FOUND, NOT_FOUND_ID, $url);
                $res->getJsonError();
                return;
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
            $viewModel = new ColaboradoresModel();
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
