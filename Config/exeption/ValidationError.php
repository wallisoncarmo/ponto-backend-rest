<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Config\Exeption;

/**
 * Classe responsável por controlar as mensagem de validação dos campos
 * ela herda de StandartError 
 * @author Wallison do Carmo Costa
 */
class ValidationError extends StandartError {

    private $erros;

    function getErros() {
        return $this->erros;
    }

    function setErros($fieldName, $message) {
        $this->erros[] = ["field" => $fieldName, 'message' => $message];
    }

    public function getJsonError() {
        print json_encode([
            "timestamp" => $this->getTimestamp(),
            "status" => $this->getStatus(),
            "error" => $this->getErros(),
            "path" => $this->getPath()
                        ], JSON_UNESCAPED_UNICODE);
        http_response_code($this->getStatus());
    }

}
