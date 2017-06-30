<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './clases/vendor/autoload.php';
require_once './clases/empleadoApi.php';
require_once './clases/estacionamientoApi.php';
require_once './clases/verificar.php';
$app = new \Slim\App;

$app->get('/', function (Request $request, Response $response) {
    return $response->withRedirect("./index.html");
    
});

$app->add(function($request, $response, $next){
    $response = $next($request, $response);

    return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://gustavopetruzziutn.hol.es/')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
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




                        //LOGIN
$app->group('/log', function(){

    $this->post('/in', \empleadoApi::class . ':loguearEmpleadoApi');
    $this->get('/out', \empleadoApi::class. ':logoutEmpleadoApi');

})->add(\verificar::class . ':datosUsuarios');

                                    //EMPLEADOS
$app->group('/empleados', function(){
    $this->get('/lista', \empleadoApi::class . ':listaEmpleadosApi');

    $this->post('/alta', \empleadoApi::class . ':alta');
    $this->post('/modificar', \empleadoApi::class . ':modificar');
    $this->delete('/borrar/{id}', \empleadoApi::class . ':borrar');

    $this->get('/logueos/{id}',\empleadoApi::class . ':registrosLogueos');
    $this->get('/logueos',\empleadoApi::class . ':registrosLogueos');
    $this->patch('/actualizar/{id}', \empleadoApi::class . ':actualizar');
})->add(\verificar::class . ':datosNuevo')->add(\verificar::class . ':admin')->add(\verificar::class . ':token');


$app->group('/estacionamiento', function (){
    $this->get('/lugares',\estacionamientoApi::class . ':lugares');
    $this->post('/estacionar', \estacionamientoApi::class . ':alta');
    $this->delete('/sacar', \estacionamientoApi::class . ':baja');
})->add(\verificar::class . ':datosAuto')->add(\verificar::class . ':token');




$app->run();