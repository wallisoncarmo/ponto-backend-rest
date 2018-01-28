<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Config;

use Config\Exeption\StandartError;

/**
 * Arquivo de configuração inicial
 * @author Wallison do Carmo Costa
 */
class Bootstrap {

    private $controller;
    private $action;
    private $request;

    public function __construct($request) {

        $this->request = $request;

        // verifca o valor da controller
        if ($this->request['controller'] == "") {
            // metodo não listado
            $exc = new StandartError(METHOD_NOT_ALLOWED_CODE, METHOD_NOT_ALLOWED, METHOD_NOT_ALLOWED_MSG, $this->request['url']);
            $exc->getJsonError();
        } else {
            // monta o nome da controller
            $this->controller = str_replace(["_", "-"], ["", ""], $this->request['controller']) . 'Controller';
            // verifca o valor da action
            if ($this->request['action'] == "") {

                if (!$this->request['action']) {

                    if ($this->request["type"] == 'GET') {
                        if ($this->request['id']) {
                            $this->action = 'findById';
                        } else {
                            $this->action = 'findAll';
                        }
                    } else if ($this->request["type"] == 'POST') {
                        $this->action = 'add';
                    } else if ($this->request["type"] == 'PUT') {
                        $this->action = 'update';
                    } else if ($this->request["type"] == 'DELETE') {
                        $this->action = 'delete';
                    } else {
                        $exc = new StandartError(METHOD_NOT_ALLOWED_CODE, METHOD_NOT_ALLOWED, METHOD_NOT_ALLOWED_MSG, $this->request['url']);
                        $exc->getJsonError();
                    }
                }
            } else {
                // monta o nome do metodo caso já possua
                $this->action = str_replace(["_", "-"], ["", ""], $this->request['action'] . $this->request["type"]); // OS METODOS NÃO PADRÕES É NECESSARIO QUE SEJA INFORMADO O SEU TIPO NO FINAL
            }
        }
    }

    public function createController() {

        $this->controller = "Controllers\\" . $this->controller;

        // verifica se a classe existe
        if (class_exists($this->controller)) {


            $parent = class_parents($this->controller);

            // Verifica se a classe abstrata de controller existe
            if (in_array("Controllers\AbstractController", $parent)) {

                //verifica se o metodo existe
                if (method_exists($this->controller, $this->action)) {
                    return new $this->controller($this->action, $this->request);
                } else {

                    // metodo não listado
                    $exc = new StandartError(METHOD_NOT_ALLOWED_CODE, METHOD_NOT_ALLOWED, METHOD_NOT_ALLOWED_MSG, $this->request['url']);
                    $exc->getJsonError();
                }
            } else {
                $exc = new StandartError(BAD_REQUEST_CODE, BAD_REQUEST_CODE, BAD_REQUEST_CONTOLLER, $this->request['url']);
                $exc->getJsonError();
            }
        } else {
            $exc = new StandartError(NOT_FOUND_CODE, NOT_FOUND, NOT_FOUND_CONTOLLER, $this->request['url']);
            $exc->getJsonError();
        }
    }

}
