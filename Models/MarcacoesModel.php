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
        $this->query("SELECT m.id,DATE_FORMAT(marcacao, '%d/%m/%Y') AS datas,DATE_FORMAT(marcacao,'%H:%m') AS horas   
                    FROM dbs_ponto.marcacoes AS m
                    INNER JOIN colaboradores AS c ON (colaboradores_id=c.id)
                    INNER JOIN usuarios AS u ON (usuarios_id=U.id)
                    WHERE c.id=:id
                    AND m.excluido=false
                    AND DATE_FORMAT(marcacao,'%m/%Y')= DATE_FORMAT(NOW(),'%m/%Y')
                    ORDER BY m.id ASC");
        $this->bind(':id', $id);
        $rows = $this->resultSet();

        $result = $this->montaListaMarcacao($rows);

        return $result;
    }

    function findByDate($id, $date) {
        $this->query("SELECT m.id,DATE_FORMAT(marcacao, '%d/%m/%Y') AS datas,DATE_FORMAT(marcacao,'%H:%m:%s') AS horas   
                    FROM dbs_ponto.marcacoes AS m
                    INNER JOIN colaboradores AS c ON (colaboradores_id=c.id)
                    INNER JOIN usuarios AS u ON (usuarios_id=U.id)
                    WHERE colaboradores_id=:id
                    AND m.excluido=false
                    AND DATE_FORMAT(marcacao,'%Y-%m-%d')= :date
                    ORDER BY m.id ASC");
        $this->bind(':id', $id);
        $this->bind(':date', $date);
        $rows = $this->resultSet();

        if (!empty($rows)) {
            $result = $this->montaListaMarcacao($rows);
            return current($result[1]);
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

        if (!$id) {
            
        } else {
            return $this->findById($id);
        }
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
        $dia = 0;

        foreach ($list as $key => $value) {


            if ($data != $value["datas"]) {

                if ($dias) {
                    $result[$dia] = [$this->montaMarcacao($dias, $data, $countMarcacao)];
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

        $ultimo = $this->montaMarcacao($dias, $data,$countMarcacao);

        if ($ultimo) {
            $result[$dia] = [$ultimo];
        }

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

        $almoco_inicio = strtotime($dias['almoco_inicio']);
        $almoco_fim = strtotime($dias['almoco_fim']);
        $entrada = strtotime($dias['entrada']);
        $saida = strtotime($dias['saida']);

        // verifica se as datas são do mesmo tamanho

        $almoco = $this->verificaDateTime($almoco_fim, $almoco_inicio);

        $manha = $this->verificaDateTime($almoco_inicio, $entrada);

        $tarde = $this->verificaDateTime($saida, $almoco_fim);

        $horas_trabalhadas = $manha + $tarde;
        $total = $horas_trabalhadas + $almoco;

        if ($horas_trabalhadas > 0) {
            $horas_trabalhadas = date('H:i', strtotime('-1 hour', $horas_trabalhadas));
        } else {
            $horas_trabalhadas = "00:00";
        }

        if ($total > 0) {
            $total = date('H:i', strtotime('-1 hour', $total));
        } else {
            $total = "00:00";
        }

        return [
            'data' => $data,
            'horas_manha' => date('H:i', strtotime('-1 hour', $manha)),
            'descanco' => date('H:i', strtotime('-1 hour', $almoco)),
            'horas_tarde' => date('H:i', strtotime('-1 hour', $tarde)),
            'marcacao' => $dias,
            'horas_trabalhadas' => $horas_trabalhadas,
            'total' => $total,
            'qtd_marcacao' => $qtd_marcacao,
        ];
    }

    private function verificaDateTime($maior, $menor) {
        if ($maior > $menor) {
            return( $maior - $menor);
        } else {
            return 0;
        }
    }

}
