<?php
    require_once 'estacionamiento.php';
    require_once 'auto.php';
    require_once 'lugar.php';
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
            $atributos = $request->getAttributes();
            
            $retorno = $estacionamiento->sacar($atributos);
            if($retorno['exito']){
                return $response->withJson($retorno);
            }
            else{
                return $response->withJson("No se pudo sacar", 400);
            }
        }

        public function buscarAuto($request, $response, $args){
            $patente = $request->getAttribute('patente');
            $auto = auto::buscar($patente);
            
            if(!empty($auto)){
                $retorno['operaciones'] = estacionamiento::registrosAutos($patente, $request->getAttribute('desde'), $request->getAttribute('hasta'));
                $retorno['auto'] = $auto;
                return $response->withJson($retorno);
            }
            return $response->withJson('Auto inexistente', 400);
        }

        public function mas($request, $response, $args){
            $datos = estacionamientoApi::utilizadas($request, $response, $args, true);
            if($datos){
                $retorno['cochera']= lugar::buscar($datos[0]['cochera']);
                $retorno['cantidad'] =$datos[0]['cantidad'];
                $retorno['cochera']->patente = estacionamiento::buscar($retorno['cochera']->numero);
                return $response->withJson($retorno);    
            }
            return $response->withJson("No hay datos en esas fechas",400);
        }

        public function menos($request, $response, $args){
            $datos = estacionamientoApi::utilizadas($request, $response, $args);
            if($datos){
                $retorno['cochera']= lugar::buscar($datos[0]['cochera']);
                $retorno['cantidad'] =$datos[0]['cantidad'];
                $dato = estacionamiento::buscar($retorno['cochera']->numero);
                $retorno['cochera']->patente = $dato[0]['patente'];
                return $response->withJson($retorno);
            }
            return $response->withJson("No hay datos en esas fechas",400);
        }

        public function nunca($request, $response, $args){
            $datos = estacionamientoApi::utilizadas($request, $response, $args);
            $lugares = lugar::traerLugares();
            
            $todos = array_map(
                function($lugar){
                    return $lugar->numero;
                }, $lugares);

            
            return $response->withJson($ocupados);
        }
        private static function utilizadas($request, $response, $args, $orden = false){
            $utilizadas = estacionamiento::registrosCocheras($orden, $request->getAttribute('desde'), $request->getAttribute('hasta'));
            if(empty($utilizadas)){
                $utilizadas = false;
            }
            return $utilizadas;
        }
    }
    
?>