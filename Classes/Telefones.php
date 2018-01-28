<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Classes;

use Classes\AbstractClasse;
use Classes\TipoTelefones;
use Classes\Colaboradores;

/**
 * Entidade de Telefones
 * @author Wallison do Carmo Costa
 */
class Telefones extends AbstractClasse {

    private $id;
    private $telefone;
    private $tipo_telefone;
    private $colaborador;
    private $cadatro;
    private $atualizado;
    private $ativo;
    private $excluido;

    function getId() {
        return $this->id;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function getTipoTelefone() {
        return $this->tipo_telefone;
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

    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    function setTipoTelefone(TipoTelefones $tipo_telefone) {
        $this->tipo_telefone = $tipo_telefone;
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
            'telefone' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'tipo_telefones_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'colaboradores_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'cadastro' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'atualizado' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'ativo' => ['tipo' => 'boolean', 'obrigatorio' => false],
            'excluido' => ['tipo' => 'boolean', 'obrigatorio' => false],
        ];
    }

}
