<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison em 25/01/2018
 */

namespace Config;

/**
 * Classe para o tratamento das resquest
 * @author Wallison do Carmo Costa
 */
class Request {

    private $request;

    function __construct() {
        $this->setRequest();
    }

    private function getAuthorizationHeaders() {
        foreach (getallheaders() as $name => $value) {
            if ($name == 'Authorization') {
                return $value;
            }
        }
        return null;
    }

    public function getRequest() {
        return $this->request;
    }

    public function setRequest() {
        try {
            $this->request = $_GET;
            $body = file_get_contents("php://input");
            $body = json_decode($body);
            $auth = $this->getAuthorizationHeaders();
            if ($auth) {
                $this->request['authorization'] = $auth;
            }

            $this->request['type'] = $_SERVER['REQUEST_METHOD'];
            if ($this->request['type'] == 'POST' || $this->request['type'] == 'PUT') {
                $this->request['body'] = $body;
            }
            if ($this->request['type'] == 'POST') {
                unset($this->request['id']);
            }


            $this->request['url'] = $this->getURL();
        } catch (Exception $exc) {
            exit();
        }
    }

    public function getURL() {

        $url = $this->request['controller'];

        if (isset($this->request['action'])) {
            if ($this->request['action']) {
                $url .= '/' . $this->request['action'];
            }
        }

        if (isset($this->request['id'])) {
            if ($this->request['id']) {
                $url .= '/' . $this->request['id'];
            }
        }
        
        return $url;
    }

}
