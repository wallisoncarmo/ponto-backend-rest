<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Classes;

use Config\Exeption\ValidationError;

/**
 * Classe Abastrata para as Entidades
 * @author Wallison do Carmo Costa
 */
abstract class AbstractClasse {

    /**
     * Função para validar automaticamente os campos
     * @param array $campos Lista dos campos
     * @param array $obj Recebe o post que veio do cliente
     * @param type $path Caminho que foi usado
     * @param type $update Caso seja informado não será exigido
     * @return boolean true caso retorne tudo certo
     */
    public function validaCampos($campos, $obj, $path, $update = null) {

        // prepara a mensagem de alerta
        $res = new ValidationError(BAD_REQUEST_CODE, ERROR_DADOS_INCORRETOS, null, $path);

        $result = array();

        foreach ($obj as $key => $value) {

            if (array_key_exists($key, $campos)) {
                if (isset($campos[$key]["key"]) && $update) {

                    $msg = null;
                    switch (strtolower($campos[$key]["tipo"])) {
                        case 'integer':
                            $msg = $this->validInteger($value);
                            if ($msg) {
                                $res->setErros($key, $msg);
                            }
                            break;

                        case 'boolean':
                            $msg = $this->validString($value, $campos[$key]['max'], $campos[$key]['min']);
                            if ($msg) {
                                $res->setErros($key, $msg);
                            }
                            break;

                        case 'string' || 'char' || 'text':
                            $msg = $this->validString($value, $campos[$key]['max'], $campos[$key]['min']);
                            if ($msg) {
                                $res->setErros($key, $msg);
                            }
                            break;

                        default:
                            $res->setErros($key, ERROR_TIPO_INVALIDO . $campos[$key]["tipo"]);
                            break;
                    }
                }
                unset($campos[$key]);
            } else {
                $res->setErros($key, ERROR_CAMPO_NAO_EXISTE);
            }
        }

        if (!empty($campos) && !$update) {
            $msg = '';
            foreach ($campos as $key => $value) {
                if ($value["obrigatorio"] && !isset($value['key'])) {
                    $res->setErros($key, ERROR_CAMPO_NECESSARIO . $key);
                }
            }
        }

        if ($res->getErros()) {
            $res->getJsonError();
            return false;
        } else {
            $res->__destroy();
            return true;
        }
    }

    /**
     * Valida um campo de string
     * @param type $value
     * @param type $max
     * @param type $min
     * @return string
     */
    public function validString($value, $max = null, $min = null) {

        if ($value) {
            if ($max) {
                if (strlen($value) > $max) {
                    return ERROR_CAMPO_MAX_MSG . $max . CARACTER;
                }
            }
            if ($min) {
                if (strlen($value) < $min) {
                    return ERROR_CAMPO_MIN_MSG . $min . CARACTER;
                }
            }
            return '';
        }
        return ERROR_CAMPO_OBRIGATORIO;
    }

    /**
     * Valida um campo de string
     * @param type $value
     * @return string
     */
    public function validInteger($value) {
        if (is_numeric($value)) {
            return '';
        }
        return ERROR_INTEIRO;
    }

    /**
     * Valida um campo de string
     * @param type $value
     * @return string
     */
    public function validBoolean($value) {

        if (is_bool($value)) {
            return '';
        }
        return ERROR_BOOLEAN;
    }

    /**
     * CREDITOS https://gist.github.com/rafael-neri/ab3e58803a08cb4def059fce4e3c0e40
     * @param type $cpf
     * @return string
     */
    public function validaCPF($cpf) {

        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return ERROR_CPF;
        }
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return ERROR_CPF;
        }
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return ERROR_CPF;
            }
        }
        return '';
    }

    /**
     * recebe um texto e retorna ele sem caracter especial
     * @param type $value 
     * @return type
     */
    public function removerCaracterEspecial($value, $espaco = null) {
        if (!$espaco) {
            return str_replace(" ", "_", preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($valor))));
        } else {
            return preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($value)));
        }
    }

    /**
     * valida email
     * @param type $email
     * @return string
     */
    function validaEmail($email) {
        $conta = "^[a-zA-Z0-9\._-]+@";
        $domino = "[a-zA-Z0-9\._-]+.";
        $extensao = "([a-zA-Z]{2,4})$";
        $pattern = $conta . $domino . $extensao;
        if (ereg($pattern, $email)) {
            return '';
        } else {
            return ERROR_EMAIL;
        }
    }

    /**
     * Compara dois arrays e subistitui as diferenças
     * @param type $objNew Novo Array 
     * @param type $obj Array que sofrerar alteração
     * @param type $campos Array com os campos
     * @return type
     */
    public function compareDif($objNew, $obj, $campos) {

        $result = array();

        foreach ($obj as $key => $value) {
            if (array_key_exists($key, $objNew)) {
                $result[$key] = $objNew[$key];
                unset($objNew[$key]);
            } else {
                if (array_key_exists($key, $campos)) {
                    $result[$key] = $obj[$key];
                }
            }
        }

        // caso ainda tenha elemento no outro objeto ele é adicionado ao result
        if (!empty($objNew)) {
            foreach ($objNew as $key => $value) {
                $result[$key] = $objNew[$key];
            }
        }

        return $result;
    }

}
