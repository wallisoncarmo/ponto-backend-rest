<?php
/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

/**
 * Funções e utilitários
 * @author Wallison do Carmo Costa
 */


/**
 * faz um var_dump
 * @param type $text
 */
function vd($text) {
    var_dump('<pre>', $text);
}

/**
 * faz um var_dump e exit
 * @param type $text
 */
function vde($text) {
    var_dump('<pre>', $text);
    exit();
}

/*
 * Monta um json_encode
 */
function je($je) {
    $json = json_encode($je);
}

/*
 * Monta um json_encode com exit
 */
function jex($je) {
    $json = json_encode($je);
    exit();
}


function removeLast($text){
    return substr($text, 0, -1);;
}

