<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Config\Exeption;

/**
 * Classe que controla os erros e exceções
 * @author Wallison do Carmo Costa
 */
class StandartError {

    private $timestamp;
    private $status;
    private $error;
    private $message;
    private $path;

    /**
     * Cria mensagem de erro
     * @param type $status numero do codigo
     * @param type $error Error
     * @param type $message mensagem do erro
     * @param type $path  caminho do erro
     */
    function __construct($status, $error, $message, $path) {
        $this->status = $status;
        $this->error = $error;
        $this->message = $message;
        $this->path = $path;
        $this->timestamp = time();
    }

    /**
     * 
     */
    function __destroy() {
        $this->status = null;
        $this->error = null;
        $this->message = null;
        $this->path = null;
        $this->timestamp = null;
    }

    /**
     * Monta o Json do error
     */
    public function getJsonError() {
        print json_encode([
            "timestamp" => $this->getTimestamp(),
            "status" => $this->getStatus(),
            "error" => $this->getError(),
            "message" => $this->getMessage(),
            "path" => $this->getPath()
                        ], JSON_UNESCAPED_UNICODE);
        http_response_code($this->getStatus());
    }

    function getTimestamp() {
        return $this->timestamp;
    }

    function getStatus() {
        return $this->status;
    }

    function getError() {
        return $this->error;
    }

    function getMessage() {
        return $this->message;
    }

    function getPath() {
        return $this->path;
    }

    function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setError($error) {
        $this->error = $error;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setPath($path) {
        $this->path = $path;
    }

}
