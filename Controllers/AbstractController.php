<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Controllers;

/**
 * Classe Abastrata para as controladoras
 * @author Wallison do Carmo Costa
 */
abstract class AbstractController {

    protected $request;
    protected $action;

    public function __construct($action, $request) {
        $this->action = $action;
        $this->request = $request;
    }

    /**
     * Executa a action que foi obtida pelo boostrap
     * @return type
     */
    public function executeAction() {
        return $this->{$this->action}();
    }

    /**
     * Monta a resposta do json
     * @param type $data o que irá vir no cabeçalho
     * @param type $code codigo http
     * @param type $url esse campo só é informado caso queria exibir um url do registro inserido
     */
    protected function returnJson($data, $code, $url = null) {

        if ($url) {
            header("Location: " . ROOT_URL . $url . '/' . $data['id']);
        } else {
            if ($data) {
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            }
        }

        http_response_code($code);
    }

}
