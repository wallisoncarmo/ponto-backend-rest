<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Classes;

use Classes\AbstractClasse;
use Classes\Colaboradores;

/**
 * Entidade de Enderecos
 * @author Wallison do Carmo Costa
 */
class Enderecos extends AbstractClasse {

    private $id;
    private $endereco;
    private $cidade;
    private $bairro;
    private $cep;
    private $colaborador;
    private $cadatro;
    private $atualizado;
    private $ativo;
    private $excluido;

    function getId() {
        return $this->id;
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

    function getColaborador() {
        return $this->colaborador;
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

    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    function setColaborador(Colaboradores $colaborador) {
        $this->colaborador = $colaborador;
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

    function getCep() {
        return $this->cep;
    }

    function setCep($cep) {
        $this->cep = $cep;
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
            'endereco' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'cidade' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'bairro' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'cep' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'tipo_enderecos_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'colaboradores_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'cadastro' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'atualizado' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'ativo' => ['tipo' => 'boolean', 'obrigatorio' => false],
            'excluido' => ['tipo' => 'boolean', 'obrigatorio' => false],
        ];
    }

}
