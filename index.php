<?php

header('Content-type: application/json');
// Configurações iniciais
require('config.php');
require('autoload.php');

// importa as funcoes publicas
require('Util/public_function.php');

// Importa as configurações
use Config\Bootstrap;
use Config\Request;
require('Config/exeption/StandartError.php');
require('Config/exeption/ValidationError.php');

// importa as controllers
// prepara o request
$request = new Request();

$bootstrap = new Bootstrap($request->getRequest());
$controller = $bootstrap->createController();

if ($controller) {
    $controller->executeAction();
}
