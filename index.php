<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './clases/empleado.php';
$app = new \Slim\App;
$app->post('/login', function (Request $request, Response $response) {
    if(!isset($_SESSION['usuario'])){
        $empleados = empleado::TraerEmpleados();
        //TODO--->VER VALIDACIONES
        $data = $request->getParsedBody();
        $usuarioLog = filter_var($data['usuario'], FILTER_SANITIZE_STRING);
        
        $passLog = filter_var($data['pass'], FILTER_SANITIZE_STRING);
        $empleadoLog = new empleado($usuarioLog, $passLog);
        $retorno['exito'] = false;
        foreach ($empleados as $empleadoBase ) {
            if($empleadoBase->usuario == $empleadoLog->usuario && $empleadoBase->getPass() == $empleadoLog->getPass()){
                session_start();
                $_SESSION['empleado'] = $empleadoBase;
                $retorno['exito'] = empleado::registrarLogin($empleadoBase->id);
                $retorno['usuario'] = $empleadoBase->usuario;
                
                break;
            }
        }
    }
    else{
        session_start();
        $retorno['exito'] = true;
        $retorno['usuario'] = $_SESSION['usuario'];
    }
    return $response->withJson($retorno);
});
$app->post('/desloguear', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    $id = $_SESSION['empleado']->id;
    $retorno['exito'] = empleado::registrarLogin($id, false);
    
    $_SESSION['usuario'] = null;
    session_destroy();
    
    return $response->withJson($retorno);
});

$app->get('/empleados', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado']) ) {
        $retorno['exito'] = true;
        $retorno['empleados'] = empleado::TraerEmpleados();
    }
    
    
    
    
    return $response->withJson($retorno);
});
$app->run();