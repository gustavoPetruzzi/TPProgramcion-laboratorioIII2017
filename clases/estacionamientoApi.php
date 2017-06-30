<?php
    require_once 'estacionamiento.php';
    require_once 'auto.php';
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

        public function alta($request, $response, $args){
            $auto = new auto($request->getAttribute('patente'),$request->getAttribute('color'), $request->getAttribute('marca'));
            $datos = $request->getAttribute('datos');
            $estacionamiento = estacionamiento::traerEstacionamiento();
            if($estacionamiento->estacionar($auto,$datos->id, $request->getAttribute('lugar'))){
                $auto->agregar();
                return $response->withJson($auto);
            }
            else{
                return $response->withJson("no se ha podido ingresar", 400);
            }
        }
        public function baja($request, $response, $args){
            $estacionamiento = estacionamiento::traerEstacionamiento();
            $retorno = $estacionamiento->sacar($request->getAttributes());
            if($retorno['exito']){
                return $response->withJson($retorno);
            }
            else{
                return $response->withJson("No se pudo sacar", 400);
            }
        }
    }
    
?>