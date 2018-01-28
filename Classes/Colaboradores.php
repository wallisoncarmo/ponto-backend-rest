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
    private $genero;
    private $matricula;
    private $carga_horaria;
    private $area;
    private $cargo;
    private $usuario;
    private $telefone;
    private $cep;
    private $endereco;
    private $cidade;
    private $bairro;
    private $cadatro;
    private $atualizado;
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

    function getGenero() {
        return $this->genero;
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

    function getUsuario() {
        return $this->usuario;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function getCep() {
        return $this->cep;
    }

    function getEndereco() {
        return $this->endereco;
    }

    function getCidade() {
        return $this->cidade;
    }

    function getBairro() {
        return $this->bairro;
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

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    function setRg($rg) {
        $this->rg = $rg;
    }

    function setGenero($genero) {
        $this->genero = $genero;
    }

    function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    function setCarga_horaria($carga_horaria) {
        $this->carga_horaria = $carga_horaria;
    }

    function setArea(Areas $area) {
        $this->area = $area;
    }

    function setCargo(Cargos $cargo) {
        $this->cargo = $cargo;
    }

    function setUsuario(Usuarios $usuario) {
        $this->usuario = $usuario;
    }

    function setTelefone(Telefones $telefone) {
        $this->telefone = $telefone;
    }

    function setCep($cep) {
        $this->cep = $cep;
    }

    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
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
     * seu formato podendo ser [string][integer][boolean][cpf][cnpj][email]
     * [obrigatorio] = se o campo é obrigatorio
     * [max] quantidade maxima de caracter
     * [min] quantidade minima de caracter
     * [key] informa se ele é chave primaria
     */
    function getCampos() {
        return [
            'id' => ['tipo' => 'integer', 'obrigatorio' => true, 'key' => true],
            'email' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'senha' => ['tipo' => 'string', 'max' => 50, 'min' => 10, 'obrigatorio' => true],
            'nome' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'cpf' => ['tipo' => 'string', 'max' => 20, 'min' => 20, 'obrigatorio' => true],
            'rg' => ['tipo' => 'string', 'max' => 20, 'min' => 20, 'obrigatorio' => true],
            'genero' => ['tipo' => 'integer', 'obrigatorio' => true],
            'matricula' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'carga_horaria' => ['tipo' => 'integer', 'obrigatorio' => true],
            'areas_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'cargos_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'usuarios_id' => ['tipo' => 'integer', 'obrigatorio' => false],
            'telefone1' => ['tipo' => 'string', 'max' => 30, 'min' => 9, 'obrigatorio' => true],
            'telefone2' => ['tipo' => 'string', 'max' => 30, 'min' => 9, 'obrigatorio' => false],
            'tipo1' => ['tipo' => 'integer', 'obrigatorio' => true],
            'tipo2' => ['tipo' => 'integer', 'obrigatorio' => false],
            'cep' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'endereco' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'cidade' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'bairro' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'cadastro' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'atualizado' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'ativo' => ['tipo' => 'boolean', 'obrigatorio' => false],
            'excluido' => ['tipo' => 'boolean', 'obrigatorio' => false],
        ];
    }

}
