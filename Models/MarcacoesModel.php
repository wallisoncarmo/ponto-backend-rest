<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Models;

use Models\AbstractModel;
use Classes\Marcacoes;

/**
 * Model de compartilhamento
 * @author Wallison do Carmo Costa
 */
class MarcacoesModel extends AbstractModel {

    function findAll($id) {
        $this->query("SELECT 
                        m.id,
                        DATE_FORMAT(marcacao, '%d/%m/%Y') AS datas,
                        DATE_FORMAT(marcacao,'%H:%m') AS horas,   
                        WEEK(marcacao) AS semana   
                    FROM dbs_ponto.marcacoes AS m
                    INNER JOIN colaboradores AS c ON (colaboradores_id=c.id)
                    INNER JOIN usuarios AS u ON (usuarios_id=U.id)
                    WHERE c.id=:id
                    AND m.excluido=false
                    AND DATE_FORMAT(marcacao,'%m/%Y')= DATE_FORMAT(NOW(),'%m/%Y')
                    ORDER BY m.id ASC");
        $this->bind(':id', $id);
        $rows = $this->resultSet();

        if ($rows) {
            $result = $this->montaListaMarcacao($rows);
            return $result;
        }
        return $rows;
    }

    function findAllByAnoMes($id, $ano, $mes) {
        $this->query("SELECT 
                        m.id,
                        DATE_FORMAT(marcacao, '%d/%m/%Y') AS datas,
                        DATE_FORMAT(marcacao,'%H:%m') AS horas,   
                        WEEK(marcacao) AS semana
                    FROM dbs_ponto.marcacoes AS m
                    INNER JOIN colaboradores AS c ON (colaboradores_id=c.id)
                    INNER JOIN usuarios AS u ON (usuarios_id=U.id)
                    WHERE c.id=:id
                    AND m.excluido=false
                    AND DATE_FORMAT(marcacao,'%Y')= {$ano}
                    AND DATE_FORMAT(marcacao,'%m')= {$mes}
                    ORDER BY m.id ASC");
        $this->bind(':id', $id);

        $rows = $this->resultSet();

        if ($rows) {
            $result = $this->montaListaMarcacao($rows);
            return $result;
        }
        return $rows;
    }

    function findByDate($id, $date) {
        $this->query("SELECT 
                        m.id,
                        DATE_FORMAT(marcacao, '%d/%m/%Y') AS datas,
                        DATE_FORMAT(marcacao,'%H:%m') AS horas,   
                        WEEK(marcacao) AS semana  
                    FROM dbs_ponto.marcacoes AS m
                    INNER JOIN colaboradores AS c ON (colaboradores_id=c.id)
                    INNER JOIN usuarios AS u ON (usuarios_id=U.id)
                    WHERE colaboradores_id=:colaboradores_id
                    AND m.excluido=false
                    AND DATE_FORMAT(marcacao,'%Y-%m-%d')= :date
                    ORDER BY m.id ASC");
        $this->bind(':colaboradores_id', $id);
        $this->bind(':date', $date);
        $rows = $this->resultSet();

        if (!empty($rows)) {
            $result = $this->montaListaMarcacao($rows);
            return $result[1];
        }
        return($rows);
    }

    function findByIdDetail($id) {
        $this->query("SELECT 
                        m.id,
                        usuarios_id,
                        nome,
                        DATE_FORMAT(marcacao, '%d/%m/%Y') AS datas,
                        DATE_FORMAT(marcacao,'%H:%m:%s') AS horas, 
                        DATE_FORMAT(m.cadastro, '%d/%m/%Y as %H:%m:%s') AS criacao,
                        DATE_FORMAT(m.atualizado, '%d/%m/%Y as %H:%m:%s') AS atualizado
                    FROM dbs_ponto.marcacoes AS m
                    INNER JOIN colaboradores AS c ON (colaboradores_id=c.id)
                    INNER JOIN usuarios AS u ON (usuarios_id=U.id)
                    WHERE m.excluido=false AND m.id= :id;");
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function findById($id) {
        $this->query("SELECT id,marcacao,colaboradores_id
                    FROM dbs_ponto.marcacoes AS m
                    WHERE m.excluido=false AND m.id= :id;");
        $this->bind(':id', $id);
        $rows = $this->findOne();
        return $rows;
    }

    function delete($id) {
        $this->query('UPDATE marcacoes SET ativo=false, excluido=true, atualizado=now() WHERE id= :id;');
        $this->bind(':id', $id);
        $this->execute();
        return;
    }

    function add(Marcacoes $obj) {

        $this->query("INSERT INTO dbs_ponto.marcacoes
                    (marcacao,colaboradores_id)
                    VALUES
                    (:marcacao,:colaboradores_id)");
        $this->bind(':marcacao', $obj->getMarcacao());
        $this->bind(':colaboradores_id', $obj->getColaborador()->getId());

        $this->execute();
        $id = $this->lastInsertId();

        return ['id' => $id];
    }

    function update(Marcacoes $obj) {

        $this->query("UPDATE dbs_ponto.marcacoes
                    SET marcacao =:marcacao,colaboradores_id = :colaboradores_id,atualizado = now() WHERE id = :id");
        $this->bind(':marcacao', $obj->getMarcacao());
        $this->bind(':colaboradores_id', $obj->getColaborador()->getId());
        $this->bind(':id', $obj->getId());


        $this->execute();
    }

    /**
     * Gera uma lista de marcaçoes
     * @param array $list recebe uma lista
     * @return array retorna uma lista
     */
    private function montaListaMarcacao($list) {
        $result = array();
        $dias = array();
        $data = '';
        $count = 0;
        $countMarcacao = 0;
        $semana_horas_trabalhadas = array();
        $relatorio_semana = array();
        $relatorio_status = array();

        $relatorio_status = [
            ['label' => 'na_hora', 'value' => 0],
            ['label' => 'atrasado', 'value' => 0],
            ['label' => 'devendo', 'value' => "00:00"],
            ['label' => 'sobrando', 'value' => "00:00"],
            ['label' => 'hora_completa', 'value' => 0],
        ];

        $dia = 0;

        foreach ($list as $key => $value) {


            if ($data != $value["datas"]) {

                if ($dias) {
                    $result[$dia] = $this->montaMarcacao($dias, $data, $countMarcacao);
                    $semana_horas_trabalhadas[$value['semana']][] = $result[$dia]['horas_trabalhadas'];
                }

                $data = $value["datas"];
                $count = 0;
                $countMarcacao = 0;
                $dia++;
                $dias = array();
            }

            $tipo = '';
            switch (++$count) {
                case 1:
                    $tipo = 'entrada';
                    if ($value["horas"] == "08:00" || $this->verificaDatas("08:15", $value["horas"])) {
                        $relatorio_status[0]['value'] ++; // na hora
                    } else {
                        $relatorio_status[1]['value'] ++; // atrasado
                    }
                    break;
                case 2:
                    $tipo = 'almoco_inicio';
                    break;
                case 3:
                    $tipo = 'almoco_fim';
                    break;
                default:
                    $tipo = 'saida';
                    break;
            }
            $countMarcacao++;
            $dias[$tipo] = $value["horas"];
        }

        $ultimo = $this->montaMarcacao($dias, $data, $countMarcacao);

        if ($ultimo) {
            $result[$dia] = $ultimo;
            $semana_horas_trabalhadas[$value['semana']][] = $result[$dia]['horas_trabalhadas'];
        }

        $total = "00:00";
        foreach ($semana_horas_trabalhadas as $key => $value) {

            foreach ($value as $horas) {
                $total = $this->somaHoras($total, $horas);

                if ($horas == "08:00") {
                    $relatorio_status[4]['value'] ++; // hora completa
                } else if ($this->verificaDatas($horas, "08:00")) {
                    $relatorio_status[3]['value'] = $this->somaHoras($horas, $relatorio_status[3]['value']); //sobrando
                } else {
                    $relatorio_status[2]['value'] = $this->somaHoras($horas, $relatorio_status[2]['value']); //devendo
                }
            }

            if ($total != "00:00") {
                $relatorio_semana[$key]['semana'] = 'Semana Nº' . ($key + 1);
                $relatorio_semana[$key]['media'] = $this->mediaHoras($total, count($value));
                $relatorio_semana[$key]['total'] = $total;
                $total = "00:00";
            }
        }

        $result['data'] = $result;
        $result['relatorios'] = ['relatorio_semana' => $relatorio_semana, 'relatorio_status' => $relatorio_status];

        return $result;
    }

    /**
     * 
     * @param array $dias com as marcacoes do dia
     * @param string $data data do dia
     * @return type
     */
    private function montaMarcacao($dias, $data, $qtd_marcacao) {

        if (!isset($dias['entrada'])) {
            $dias['entrada'] = "00:00";
        }
        if (!isset($dias['almoco_inicio'])) {
            $dias['almoco_inicio'] = "00:00";
        }
        if (!isset($dias['almoco_fim'])) {
            $dias['almoco_fim'] = "00:00";
        }
        if (!isset($dias['saida'])) {
            $dias['saida'] = "00:00";
        }

        $manha = $this->intervalo($dias['entrada'], $dias['almoco_inicio']);

        $descanco = $this->intervalo($dias['almoco_inicio'], $dias['almoco_fim']);

        $tarde = $this->intervalo($dias['almoco_fim'], $dias['saida']);


        $total = $this->intervalo($dias['entrada'], $dias['saida']);

        $horas_trabalhadas = $this->intervalo($descanco, $total);

        return [
            'data' => $data,
            'horas_manha' => date('H:i', strtotime($manha)),
            'descanco' => date('H:i', strtotime($descanco)),
            'horas_tarde' => date('H:i', strtotime($tarde)),
            'marcacao' => $dias,
            'horas_trabalhadas' => date('H:i', strtotime($horas_trabalhadas)),
            'horas_trabalhadas_milissegundos' => strtotime($horas_trabalhadas),
            'total' => date('H:i', strtotime($total)),
            'qtd_marcacao' => $qtd_marcacao,
        ];
    }

    private function verificaDatas($maior, $menor) {

        if (strtotime($maior) > strtotime($menor)) {
            return true;
        } else {
            return false;
        }
    }

    private function intervalo($entrada, $saida) {
        $entrada = explode(':', $entrada);
        $saida = explode(':', $saida);
        $minutos = ( $saida[0] - $entrada[0] ) * 60 + $saida[1] - $entrada[1];
        if ($minutos < 0)
            $minutos += 24 * 60;
        return sprintf('%d:%d', $minutos / 60, $minutos % 60);
    }

    private function somaHoras($data1, $data2) {
        $data1 = explode(':', $data1);
        $data2 = explode(':', $data2);
        $minutos = ( $data2[0] + $data1[0] ) * 60 + $data2[1] + $data1[1];
        if ($minutos < 0)
            $minutos += 24 * 60;
        return sprintf('%d:%d', $minutos / 60, $minutos % 60);
    }

    private function mediaHoras($total, $count) {

        $total = explode(':', $total);
        $minutos = (( $total[0] ) * 60 + $total[1]) / $count;
        if ($minutos < 0)
            $minutos += 24 * 60;
        return sprintf('%d:%d', $minutos / 60, $minutos % 60);
    }

}
