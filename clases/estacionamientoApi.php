<?php
    require_once 'estacionamiento.php';
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;
    require_once 'vendor/autoload.php';

    class estacionamientoApi extends estacionamiento 
    {
        public function lugares($request, $response, $args){
            $estacionamiento = estacionamiento::traerEstacionamiento();
            $retorno['precios'] = $estacionamiento;
            $retorno['lugares'] = $estacionamiento->traerLugares();
            return $response->withJson($retorno);
        }
    }
    
?>