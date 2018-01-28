<?php

/*
 * Projeto feito para 2º fase da stefanini
 * Sistema de ponto
 * Criado por Wallison 25/01/2018
 */

namespace Controllers;

use Config\Exeption\StandartError;

/**
 * Controller de compartilhamento
 * @author Wallison do Carmo Costa
 */
class AuthController {

    /**
     * 
     * @param array $perfil lista de perfil que tem acesso essa parte
     * @param type $token token do usuario
     */
    protected function authorization($perfil, $token, $url) {
        if (!$token) {
            $res = new StandartError(FORBIDDEN_CODE, FORBIDDEN, FORBIDDEN_MSG, $url);
            $res->getJsonError();
            return false;
        }

        $viewModel = new \Models\UsuariosModel();
        $res = $viewModel->findUsuarioByToken($token);

        if (in_array($res['acesso'], $perfil, true)) {
            return $res;
        }

        $res = new StandartError(FORBIDDEN_CODE, FORBIDDEN, FORBIDDEN_MSG, $url);
        $res->getJsonError();
        return false;
    }

    protected function findUserByToken($token) {
        $viewModel = new \Models\UsuariosModel();
        return $res = $viewModel->findUsuarioByToken($token);
    }

}
