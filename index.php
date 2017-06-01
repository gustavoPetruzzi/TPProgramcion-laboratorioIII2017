<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './clases/empleado.php';
$app = new \Slim\App;

$app->get('/empleados', function (Request $request, Response $response) {
    $empleados = empleado::TraerEmpleados();
    $response->getBody()->write($response->withJson($empleados));
    
    return $response;
});
$app->run();