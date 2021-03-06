<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './clases/vendor/autoload.php';
require_once './clases/empleadoApi.php';
require_once './clases/estacionamientoApi.php';
require_once './clases/verificar.php';
$config['displayErrorDetails'] = true;

$app = new \Slim\App(["settings" => $config]);

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
    $this->patch('/actualizar/{id}', \empleadoApi::class . ':actualizarEstado');

    $this->get('/logueos/{id}/{desde}[/{hasta}]',\empleadoApi::class . ':registrosLogueos')
                ->add(\verificar::class . ':fechas');
    $this->get('/operaciones/{id}/{desde}[/{hasta}]',\empleadoApi::class . ':registrosOperaciones')
                ->add(\verificar::class . ':fechas');

})->add(\verificar::class . ':datosNuevo')->add(\verificar::class . ':admin')->add(\verificar::class . ':token');

                                    //ESTACIONAMIENTO
$app->group('/estacionamiento', function (){
    $this->get('/lugares',\estacionamientoApi::class . ':lugares');
    $this->post('/estacionar', \estacionamientoApi::class . ':alta');   
    $this->delete('/sacar/{datos}', \estacionamientoApi::class . ':baja');
    $this->get('/buscar/{patente}/{desde}[/{hasta}]', estacionamientoApi::class . ':buscarAuto')->add(\verificar::class . ':fechas');

    $this->get('/buscarTodos/{desde}[/{hasta}]', estacionamientoApi::class . ':buscarTodosAutos')->add(\verificar::class . ':fechas');
})->add(\verificar::class . ':datosEstacionar')->add(\verificar::class . ':token');

                                   //COCHERAS
//CHEQUEAR SI ES ADMIN
$app->group('/cocheras', function(){
    $this->get('/mas/{desde}[/{hasta}]', \estacionamientoApi::class . ':mas');
    $this->get('/mas/excel/{desde}[/{hasta}]', \estacionamientoApi::class . ':masExcel');
    $this->get('/menos/{desde}[/{hasta}]', \estacionamientoApi::class . ':menos');
    $this->get('/nunca/{desde}[/{hasta}]', \estacionamientoApi::class . ':nunca');
})->add(\verificar::class . ':fechas')->add(\verificar::class . ':admin')->add(\verificar::class . ':token');

$app->group('/reportes', function(){
    //EXCEL
    $this->get('/logueos/{token}/{id}/{desde}[/{hasta}]', \empleadoApi::class . ':excelLogueos');
    //PDF
    $this->get('/operaciones/{token}/{id}/{desde}[/{hasta}]', \empleadoApi::class . ':pdfOperaciones');
})->add(\verificar::class . ':fechas')->add(\verificar::class . ":admin")->add(\verificar::class . ':token');


$app->run();