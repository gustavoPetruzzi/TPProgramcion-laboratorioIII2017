<?php
    require_once 'estacionamiento.php';
    require_once 'auto.php';
    require_once 'lugar.php';
    require_once 'lugarCantidad.php';
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
            if(!$estacionamiento->autoEstacionado($auto->patente)){
                if($estacionamiento->estacionar($auto,$datos->id, $request->getAttribute('lugar'))){
                $auto->agregar();
                return $response->withJson($auto);
            }
                else{
                    return $response->withJson("no se ha podido ingresar", 206);
                }    
            }
            else{
                return $response->withJson("auto ya ingresado",206);
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
                return $response->withJson("No se pudo sacar", 206);
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
            return $response->withJson('Auto inexistente', 206);
        }

        public function mas($request, $response, $args){
            $datos = estacionamientoApi::utilizadas($request, $response, $args, true);
            if($datos){
                $numero = $datos[0]['cochera'];
                $maximo = $datos[0]['cantidad'];
                $lugares = array_filter($datos,
                function($lugar) use($maximo){
                    return $lugar['cantidad'] == $maximo;
                });
                $array = array();
                foreach ($lugares as $key ) {
                    $lugar = new LugarCantidad($key['cochera'], $maximo);
                    $patente = estacionamiento::buscar($lugar->numero);
                    $lugar->patente = $patente['patente'];
                    array_push($array, $lugar);
                }
                return $response->withJson($array);    
            }
            return $response->withJson("No hay datos en esas fechas",206);
        }

        public function menos($request, $response, $args){
            $datos = estacionamientoApi::utilizadas($request, $response, $args);
            if($datos){
                $numero = $datos[0]['cochera'];
                $minimo = $datos[0]['cantidad'];
                $lugares = array_filter($datos,
                function($lugar) use($minimo){
                    return $lugar['cantidad'] == $minimo;
                });
                $array = array();
                foreach ($lugares as $key ) {
                    $lugar = new LugarCantidad($key['cochera'], $minimo);
                    $patente = estacionamiento::buscar($lugar->numero);
                    $lugar->patente = $patente['patente'];
                    array_push($array, $lugar);
                }
                return $response->withJson($array);    
            }
            return $response->withJson("No hay datos en esas fechas",206);
        }

        public function nunca($request, $response, $args){
            $datos = estacionamientoApi::utilizadas($request, $response, $args);
            $lugares = lugar::traerLugares();
            $array = array();
            $todos = array_map(
                function($lugar){
                    return $lugar->numero;
                }, $lugares);

            if($datos){
                $ocupadas = array_map(
                function($lugar){
                    return $lugar['cochera'];
                }, $datos);
                //var_dump($ocupadas);
                $desocupados = array_diff($todos, $ocupadas);
                

                foreach ($desocupados as $key ) {
                    $lugar = new LugarCantidad($key, 0);
                    $patente = estacionamiento::buscar($lugar->numero);
                    $lugar->patente = $patente['patente'];
                    array_push($array, $lugar);
                }   
            }
            else{
                foreach ($todos as $key ) {
                    $algo = lugar::buscar($key);
                    if($algo->reservado == 0){
                        $algo->reservado = true;
                    }
                    $lugar = new LugarCantidad($algo->numero, $algo->patente, $algo->piso,$algo->reservado, "0");
                    $patente = estacionamiento::buscar($lugar->numero);
                    $lugar->patente = $patente['patente'];
                    array_push($array, $lugar);
                }   
            }
            
            return $response->withJson($array);
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