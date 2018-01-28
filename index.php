<?php

// Cabeçalho para JSON 
//header('Content-type: application/json');
// Configurações iniciais
require('config.php');
require('autoload.php');
require('Config/leng/texto_pt.php');

// importa as funcoes publicas
require('Util/public_function.php');

// Importa as configurações
use Config\Bootstrap;
use Config\Request;

// prepara o request
$request = new Request();

if ($request->getRequest()) {    
// inicia o boostrap
    $bootstrap = new Bootstrap($request->getRequest());

// cria a controller
    $controller = $bootstrap->createController();

//Executa a contoladora recuperada do boostrap
    if ($controller) {
        $controller->executeAction();
    }
}
