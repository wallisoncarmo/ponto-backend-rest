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
            $exc = new StandartError(405, "Method Not Allowed", 'Metodo não existe', $this->request['url']);
            $exc->getJsonError();
        } else {
            $this->controller = $this->request['controller'] . 'Controller';
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
                    }
                }
            } else {
                $this->action = $this->request['action'];
            }
        }
    }

    public function createController() {
 
        $this->controller="Controllers\\".$this->controller;
        
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
                    $exc = new StandartError(405, "Method Not Allowed", 'Metodo não existe', $this->request['url']);
                    $exc->getJsonError();
                }
            } else {
                $exc = new StandartError(400, "Not Found", 'A Controlladora não existe', $this->request['url']);
                $exc->getJsonError();
            }
        } else {
            $exc = new StandartError(404, "Not Found", 'Controller não existe', $this->request['url']);
            $exc->getJsonError();
        }
    }

}
