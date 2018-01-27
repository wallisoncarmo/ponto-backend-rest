<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Classes;

use Classes\AbstractClasse;

/**
 * Entidade de Colaborador
 * @author Wallison do Carmo Costa
 */
class Colaboradores extends AbstractClasse {

    private $id;
    private $nome;
    private $cpf;
    private $rg;
    private $genereo;
    private $matricula;
    private $carga_horaria;
    private $area;
    private $cargo;
    private $user;
    private $cadatro;
    private $atualizacao;
    private $ativo;
    private $excluido;

    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getCpf() {
        return $this->cpf;
    }

    function getRg() {
        return $this->rg;
    }

    function getGenereo() {
        return $this->genereo;
    }

    function getMatricula() {
        return $this->matricula;
    }

    function getCarga_horaria() {
        return $this->carga_horaria;
    }

    function getArea() {
        return $this->area;
    }

    function getCargo() {
        return $this->cargo;
    }

    function getUser() {
        return $this->user;
    }

    function getCadatro() {
        return $this->cadatro;
    }

    function getAtualizacao() {
        return $this->atualizacao;
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

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    function setRg($rg) {
        $this->rg = $rg;
    }

    function setGenereo($genereo) {
        $this->genereo = $genereo;
    }

    function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    function setCarga_horaria($carga_horaria) {
        $this->carga_horaria = $carga_horaria;
    }

    function setArea($area) {
        $this->area = $area;
    }

    function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setCadatro($cadatro) {
        $this->cadatro = $cadatro;
    }

    function setAtualizacao($atualizacao) {
        $this->atualizacao = $atualizacao;
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
     * seu formato podendo ser [string][integer][boolean][cpf][cnpj][email]
     * [obrigatorio] = se o campo é obrigatorio
     * [max] quantidade maxima de caracter
     * [min] quantidade minima de caracter
     * [key] informa se ele é chave primaria
     */
    function getCampos() {
        return [
            'id' => ['tipo' => 'integer', 'obrigatorio' => true, 'key' => true],
            'nome' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'cpf' => ['tipo' => 'string', 'max' => 20, 'min' => null, 'obrigatorio' => true],
            'rg' => ['tipo' => 'string', 'max' => 20, 'min' => null, 'obrigatorio' => true],
            'genero' => ['tipo' => 'integer', 'obrigatorio' => true],
            'matricula' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'carga_horaria' => ['tipo' => 'integer', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'area_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'cargo_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'user_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'cadastro' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'atualizado' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'ativo' => ['tipo' => 'boolean', 'obrigatorio' => false],
            'excluido' => ['tipo' => 'boolean', 'obrigatorio' => false],
        ];
    }

}
