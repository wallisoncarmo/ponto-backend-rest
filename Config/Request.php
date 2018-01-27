<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison em 25/01/2018
 */

namespace Config;

use Config\Exeption\StandartError;

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
            $json = file_get_contents("php://input");
            $body = (array) json_decode($json);
            $auth = $this->getAuthorizationHeaders();
            $this->request['type'] = $_SERVER['REQUEST_METHOD'];

            if ($auth) {
                $this->request['authorization'] = $auth;
            }

            if ($this->request['type'] == 'POST') {
                unset($this->request['id']);
            }
            $this->request['url'] = $this->getURL();

            if ($this->request['type'] == 'POST' || $this->request['type'] == 'PUT') {
                if (count($body)) {
                    $this->request['body'] = $body;
                } else {
                    $res = new StandartError(BAD_REQUEST_CODE, ERROR_SINTAXE . "[{$json}]", null, $this->getURL());
                    $res->getJsonError();
                    $this->request = null;
                }
            }
        } catch (Exception $exc) {
            $res = new StandartError(BAD_REQUEST_CODE, $exc->getMessage(), null, $this->getURL());
            $res->getJsonError();
            $this->request = null;
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
