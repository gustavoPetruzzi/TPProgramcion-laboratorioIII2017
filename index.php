<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require_once './clases/empleado.php';
require_once './clases/estacionamiento.php';
$app = new \Slim\App;

$app->get('/', function (Request $request, Response $response) {
    return $response->withRedirect("./index.html");
    
});

$app->get('/estacionamiento', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado'])) {
        $estacionamiento = estacionamiento::traerEstacionamiento();
        $retorno['lugares'] = $estacionamiento->traerLugares();
        $retorno['precios'] = $estacionamiento;
        $retorno['exito'] = true;
    }
    
    return $response->withJson($retorno);
});
$app->post('/estacionamiento', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado'])){
        $estacionamiento = estacionamiento::traerEstacionamiento();
        $data = $request->getParsedBody();
        $patente = filter_var($data['patente'], FILTER_SANITIZE_STRING);
        $color = filter_var($data['color'], FILTER_SANITIZE_STRING);
        $marca = filter_var($data['marca'], FILTER_SANITIZE_STRING);
        $lugar = filter_var($data['lugar'], FILTER_SANITIZE_NUMBER_INT);
        $auto = new auto($patente, $color, $marca);

        $auto->agregar();
        $retorno['entrada'] = $estacionamiento->estacionar($auto, $_SESSION['empleado']->id, $lugar);
        if(isset($retorno['entrada'])){
            $retorno['auto'] = $auto;
            $retorno['exito'] = true;
        }
    }
    return $response->withJson($retorno);
});


$app->delete('/estacionamiento', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado'])) {
        $estacionamiento = estacionamiento::traerEstacionamiento();
        $data = $request->getParsedBody();
        
        $retorno = $estacionamiento->sacar($data);
    }
    
    return $response->withJson($retorno);
});







                                        /* LOGIN */
$app->post('/login', function (Request $request, Response $response) {
    $estacionamiento = estacionamiento::traerEstacionamiento();
    $data = $request->getParsedBody();
    $usuarioLog = filter_var($data['usuario'], FILTER_SANITIZE_STRING);
    $passLog = filter_var($data['pass'], FILTER_SANITIZE_STRING);

    $retorno = $estacionamiento->loguear($usuarioLog, $passLog);
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
        
        $empleadoModificado = empleado::buscarEmpleado($id);
        $empleadoModificado->usuario = $usuario;
        $empleadoModificado->setPass($pass);
        $retorno['exito'] = $empleadoModificado->modificarEmpleado();
    }
    
    return $response->withJson($retorno);
});

// EMPLEADO NO SE ACTUALIZA
$app->patch('/empleados', function (Request $request, Response $response) {
    session_start();
    $retorno['exito'] = false;
    if(isset($_SESSION['empleado']) && $_SESSION['empleado']->usuario == 'admin') {
        
        $data = $request->getParsedBody();
        $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
        $empleado = empleado::buscarEmpleado($id);
        $empleado->actualizar();

        $retorno['exito'] = $empleado->modificarEmpleado();
        if($retorno['exito']){
            $retorno['empleado'] = $empleado;
        }
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