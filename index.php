<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require_once './clases/empleado.php';
require_once './clases/estacionamiento.php';
$app = new \Slim\App;



$app->post('/login', function (Request $request, Response $response) {
    $estacionamiento = estacionamiento::traerEstacionamiento();
    $data = $request->getParsedBody();
    $usuarioLog = filter_var($data['usuario'], FILTER_SANITIZE_STRING);
    $passLog = filter_var($data['pass'], FILTER_SANITIZE_STRING);

    $retorno = $estacionamiento->loguear($usuarioLog, $passLog);
    //var_dump($retorno);
    return $response->withJson($retorno);
});
$app->post('/desloguear', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado'])){
        $id = $_SESSION['empleado']->id;
        $retorno['exito'] = empleado::registrarLogin($id, false);
        
        $_SESSION['empleado'] = null;
        session_destroy();        
    }
    
    return $response->withJson($retorno);
});




                                            /* EMPLEADOS */
$app->get('/empleados', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado']) && $_SESSION['empleado']->usuario == 'admin') {
        $retorno['exito'] = true;
        $retorno['empleados'] = empleado::TraerEmpleados();
    }
    
    return $response->withJson($retorno);
});

$app->post('/empleados', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado']) && $_SESSION['empleado']->usuario == 'admin'){
        
        $data = $request->getParsedBody();
        $usuario = filter_var($data['usuario'], FILTER_SANITIZE_STRING);
        $pass = filter_var($data['pass'], FILTER_SANITIZE_STRING);
        
        $nuevoEmpleado = new empleado($usuario, $pass, true);
        $retorno['exito'] = $nuevoEmpleado->guardarEmpleado();
        $retorno['exito'] = "LA PUTA MADRE";
        
    }
    return $response->withJson($retorno);
});

$app->delete('/empleados', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado']) && $_SESSION['empleado']->usuario == 'admin') {
        $data = $request->getParsedBody();
        $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
        $empleado = empleado::buscarEmpleado($id);
        $retorno['exito'] = $empleado->borrar();
        if($retorno['exito']){
            $retorno['empleado'] = $empleado;
        }
    }
    
    return $response->withJson($retorno);
});
$app->put('/empleados', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado']) && $_SESSION['empleado']->usuario == 'admin') {
        $data = $request->getParsedBody();
        $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
        $usuario = filter_var($data['usuario'], FILTER_SANITIZE_STRING);
        $pass = filter_var($data['pass'], FILTER_SANITIZE_STRING);
        $activo = filter_var($data['activo'], FILTER_SANITIZE_INT);
        $empleadoModificado = new empleado($usuario, $pass, $activo);
        $retorno['exito'] = $empleadoModificado->modificarEmpleado();
    }
    
    return $response->withJson($retorno);
});
$app->patch('/empleados', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado']) && $_SESSION['empleado']->usuario == 'admin') {
        
        $data = $request->getParsedBody();
        $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
        $empleado = empleado::buscarEmpleado($id);
        $empleado->actualizar();

        $retorno['exito'] = $empleado->modificarEmpleado();
    }
    
    return $response->withJson($retorno);
});

$app->get('/empleados/login', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado']) && $_SESSION['empleado']->usuario == 'admin') {
        $data = $request->getParsedBody();
        if(isset($data['id'])){
            $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
            $retorno['logs'] = empleados::logueos;
            if(isset($retorno['logs'])){
                $retorno['exito'] = true;
            }
        }
    }
    
    return $response->withJson($retorno);
});

$app->get('/empleados/operaciones', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado']) && $_SESSION['empleado']->usuario == 'admin') {
        $data = $request->getParsedBody();
        if(isset($data['id'])){
            $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
            $fecha = filter_var($data['fecha'], FILTER_SANITIZE_INT);
            $empleado = empleado::buscarEmpleado($id);
            if(isset($empleado)){
                $retorno['operaciones'] = $empleado->operaciones($fecha);

            }
            if(isset($retorno['operaciones'])){
                $retorno['exito'] = true;
            }
        }
    }
    
    return $response->withJson($retorno);
});



$app->get('/empleados/buscar', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado']) && $_SESSION['empleado']->usuario == 'admin') {
        $data = $request->getParsedBody();
        $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
        $empleado = empleado::buscarEmpleado($id);
        if(isset($empleado)){
            $retorno['exito'] = true;
            $retorno['empleado'] = $empleado;
        }
    }
    
    return $response->withJson($retorno);
});



$app->run();