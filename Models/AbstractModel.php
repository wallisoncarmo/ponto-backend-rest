<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Config\Exeption\StandartError;

/**
 * Classe Abastrata para as model, nela é feito tanto as conexão com o 
 * banco como aqui estão instanciados todo o pdo
 * @author Wallison do Carmo Costa
 */
abstract class AbstractModel {

    protected $db;
    protected $stmt;

    /**
     * Cria uma instancia do banco
     */
    public function __construct() {
        $this->db = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    }

    /**
     * Prepara a query para ser usada no banco
     * @param type $query Recebe a query
     */
    public function query($query) {
        $this->stmt = $this->db->prepare($query);

        if (!$this->stmt) {
            $this->getErrorSTMT();
        };
    }

    /**
     * Monta o Bind e adiciona ao stmt do PDO
     * @param type $param - Posição no bind
     * @param type $value - Valor do parametro
     * @param type $type  - Tipo do parametro
     */
    public function bind($param, $value, $type = null) {

        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;

                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;

                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;

                default:
                    $type = \PDO::PARAM_STR;
                    break;
            }
        }

        if (!$this->stmt->bindValue($param, $value, $type)) {
            $this->getErrorSTMT();
        };
    }

    /**
     * Executa uma query, UPDATE, DELETE, INSERT
     */
    public function execute() {
        if (!$this->stmt->execute()) {
            $this->getErrorSTMT();
        }
    }

    /**
     * Executa uma query e retorna uma lista, SELECT
     * @return type
     */
    public function resultSet() {
        if (!$this->stmt->execute()) {
            $this->getErrorSTMT();
        }
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Recupera o ultimo id inserido
     * @return type retorna o id inserido
     */
    public function lastInsertId() {
        $id = $this->db->lastInsertId();
        if (!$id) {
            $this->getErrorDB();
        }

        return $id;
    }

    /**
     * Recupera um unico registro
     * @return type
     */
    public function findOne() {
        $this->execute();

        $res = $this->stmt->fetch(\PDO::FETCH_ASSOC);


        return $res;
    }

    /**
     * Recupera erro do stmt
     */
    private function getErrorSTMT() {
        $error = $this->stmt->errorInfo();
        $res = new StandartError(400, 'Bad Request', '[' . $error[0] . ']' . $error[2], '');
        $res->getJsonError();
        exit();
    }

    /**
     * Recupera erro do banco
     */
    private function getErrorDB() {
        $error = $this->db->errorInfo();
        $res = new StandartError(400, 'Bad Request', '[' . $error[0] . ']' . $error[2], '');
        $res->getJsonError();
        exit();
    }

}
