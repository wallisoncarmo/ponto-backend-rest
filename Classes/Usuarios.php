<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Classes;

use Classes\AbstractClasse;

/**
 * Entidade de Usuarios
 * @author Wallison do Carmo Costa
 */
class Usuarios extends AbstractClasse {

    private $id;
    private $email;
    private $senha;
    private $token;
    private $acesso;
    private $cadatro;
    private $atualizado;
    private $ativo;
    private $excluido;

    function getId() {
        return $this->id;
    }

    function getEmail() {
        return $this->email;
    }

    function getSenha() {
        return $this->senha;
    }

    function getToken() {
        return $this->token;
    }

    function getAcesso() {
        return $this->acesso;
    }

    function getCadatro() {
        return $this->cadatro;
    }

    function getAtualizado() {
        return $this->atualizado;
    }

    function getAtivo() {
        return $this->ativo;
    }

    function getExcluido() {
        return $this->excluido;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setSenha($senha) {
        $this->senha = $senha;
    }

    function setToken($token) {
        $this->token = $token;
    }

    function setAcesso(Acessos $acesso) {
        $this->acesso = $acesso;
    }

    function setCadatro($cadatro) {
        $this->cadatro = $cadatro;
    }

    function setAtualizado($atualizado) {
        $this->atualizado = $atualizado;
    }

    function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    function setExcluido($excluido) {
        $this->excluido = $excluido;
    }

    /**
     * Aqui deve se informar os campos e seus detalhes
     * [tipo] Dependendo do que foi checkado será validado pelo 
     * seu formato podendo ser [string][integer][boolean][cpf][cnpj][email][date]
     * [obrigatorio] = se o campo é obrigatorio
     * [max] quantidade maxima de caracter
     * [min] quantidade minima de caracter
     * [key] informa se ele é chave primaria
     */
    function getCampos() {
        return [
            'id' => ['tipo' => 'integer', 'obrigatorio' => true, 'key' => true],
            'email' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'senha' => ['tipo' => 'string', 'max' => 50, 'min' => 5, 'obrigatorio' => true],
            'token' => ['tipo' => 'string', 'max' => 250, 'min' => 10, 'obrigatorio' => false],
            'acessos_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'cadastro' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'atualizado' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'ativo' => ['tipo' => 'boolean', 'obrigatorio' => false],
            'excluido' => ['tipo' => 'boolean', 'obrigatorio' => false],
        ];
    }

}
