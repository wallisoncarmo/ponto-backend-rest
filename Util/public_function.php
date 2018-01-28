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

function removeLast($text) {
    return substr($text, 0, -1);
    ;
}

function getFeriados($ano = null) {

    if (!$ano) {
        $ano = date(Y);
    }

    return [
        strtotime("{$ano}-01-01") => ['data' => "01/01/{$ano}", 'feriado' => 'Confraternização Universal'],
        strtotime("{$ano}-02-12") => ['data' => "12/02/{$ano}", 'feriado' => 'Semana de Carnaval'],
        strtotime("{$ano}-02-13") => ['data' => "13/02/{$ano}", 'feriado' => 'Semana de Carnaval'],
        strtotime("{$ano}-02-14") => ['data' => "14/02/{$ano}", 'feriado' => 'Quarta-feira de cinzas'],
        strtotime("{$ano}-03-30") => ['data' => "30/03/{$ano}", 'feriado' => 'Paixão de Cristo'],
        strtotime("{$ano}-04-21") => ['data' => "21/04/{$ano}", 'feriado' => 'Tiradentes'],
        strtotime("{$ano}-05-01") => ['data' => "01/05/{$ano}", 'feriado' => 'Dia Mundial do Trabalho'],
        strtotime("{$ano}-05-31") => ['data' => "31/05/{$ano}", 'feriado' => 'Corpus Christi'],
        strtotime("{$ano}-09-07") => ['data' => "07/09/{$ano}", 'feriado' => 'Independência do Brasil'],
        strtotime("{$ano}-10-12") => ['data' => "12/10/{$ano}", 'feriado' => 'Nossa Senhora Aparecida'],
        strtotime("{$ano}-10-28") => ['data' => "28/10/{$ano}", 'feriado' => 'Dia do Servidor Público'],
        strtotime("{$ano}-11-02") => ['data' => "02/11/{$ano}", 'feriado' => 'Finados'],
        strtotime("{$ano}-11-15") => ['data' => "15/11/{$ano}", 'feriado' => 'Proclamação da República'],
        strtotime("{$ano}-12-25") => ['data' => "25/12/{$ano}", 'feriado' => 'Natal'],
        strtotime("{$ano}-12-31") => ['data' => "31/12/{$ano}", 'feriado' => 'Confraternização Universal'],
    ];
}

function verfificaDiaUtil($dia = null) {

    $ano;
    if ($dia) {
        $data = strtotime($dia);
    } else {
        $data = strtotime(date('Y-m-d H:i'));
    }

    $ano = date('Y', $data);
    $hora = date('H:m', $data);
    $diasemana_numero = date('w', $data);

    if ($diasemana_numero == 0 || $diasemana_numero == 6) {
        return "Não é possível bater o ponto em um Domingo";
    }
    if ($diasemana_numero == 6) {
        return "Não é possível bater o ponto em um sábado";
    }

    $feriado = getFeriados($ano);

    if (array_key_exists($data, $feriado)) {
        return "Dia {$feriado[$data]['data']} é fériado de {$feriado[$data]['feriado']}, por isso não é possível bater o ponto.";
    }

    if (!(strtotime($hora) >= strtotime(HORA_INICIO) && strtotime($hora) <= strtotime(HORA_FINAL_MAX))) {
        return "Não é possível bater o ponto essa hora, o horario de funcionamentos são das " . HORA_INICIO . " até as " . HORA_FINAL_MIN . " podendo se extender até as " . HORA_FINAL_MAX . ".";
    }

    return false;
}

/* verifica se as data é maior que a incio
 * 
 */

function boolHoraMaior($atual, $ultima, $total, $carga) {

    $result = (strtotime($atual) - strtotime($ultima)) + strtotime($total);

    $result = strtotime('-1 hour', $result);

    if ($result <= strtotime("$carga + 5 minutes")) {
        return false;
    }
    return WARNING_MARCACAO_MSG . ". Hoje foram feitas " . date('H:i', $result) . " horas e sua carga é de " . $carga . " horas semanais.";
}
