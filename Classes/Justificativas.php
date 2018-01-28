<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Classes;

use Classes\AbstractClasse;
use Classes\TipoJustificativas;
use Classes\Colaboradores;

/**
 * Entidade de Justificativas
 * @author Wallison do Carmo Costa
 */
class Justificativas extends AbstractClasse {

    private $id;
    private $justificativa;
    private $cadatro;
    private $periodo;
    private $tipo_justifitivas;
    private $colaborador;
    private $atualizado;
    private $ativo;
    private $excluido;

    function getId() {
        return $this->id;
    }

    function getJustificativa() {
        return $this->justificativa;
    }

    function getCadatro() {
        return $this->cadatro;
    }

    function getPeriodo() {
        return $this->periodo;
    }

    function getTipoJustifitivas() {
        return $this->tipo_justifitivas;
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

    function setJustificativa($justificativa) {
        $this->justificativa = $justificativa;
    }

    function setCadatro($cadatro) {
        $this->cadatro = $cadatro;
    }

    function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    function setTipoJustifitivas(TipoJustificativas $tipo_justifitivas) {
        $this->tipo_justifitivas = $tipo_justifitivas;
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

    function getColaborador() {
        return $this->colaborador;
    }

    function setColaborador(Colaboradores $colaborador) {
        $this->colaborador = $colaborador;
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
            'justificativa' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'periodo' => ['tipo' => 'string', 'max' => 250, 'min' => 5, 'obrigatorio' => true],
            'tipo_justificativas_id' => ['tipo' => 'integer', 'obrigatorio' => true],
            'colaboradores_id' => ['tipo' => 'integer', 'obrigatorio' => false],
            'cadastro' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'atualizado' => ['tipo' => 'string', 'max' => 2, 'min' => 10, 'obrigatorio' => false],
            'ativo' => ['tipo' => 'boolean', 'obrigatorio' => false],
            'excluido' => ['tipo' => 'boolean', 'obrigatorio' => false],
        ];
    }

}
