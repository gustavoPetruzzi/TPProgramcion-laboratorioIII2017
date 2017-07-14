<?php
    require_once 'estacionamiento.php';
    require_once 'auto.php';
    require_once 'autoOperaciones.php';
    require_once 'lugar.php';
    require_once 'lugarCantidad.php';
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;
    require_once 'vendor/autoload.php';

    class estacionamientoApi extends estacionamiento 
    {
        public function lugares($request, $response, $args){
            sleep(5);
            $estacionamiento = estacionamiento::traerEstacionamiento();
            $retorno['precios'] = $estacionamiento;
            $retorno['lugares'] = $estacionamiento->traerLugares();
            return $response->withJson($retorno);
        }

        public function alta($request, $response, $args){
            $auto = new auto($request->getAttribute('patente'),$request->getAttribute('color'), $request->getAttribute('marca'));
            $datos = $request->getAttribute('datos');
            $estacionamiento = estacionamiento::traerEstacionamiento();
            if(lugar::buscar($request->getAttribute('lugar'))){
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
            else{
                return $response->withJson('lugar inexistente', 206);
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
                return $response->withJson($retorno['mensaje'], 206);
            }
        }

        public function buscarAuto($request, $response, $args){
            $patente = $request->getAttribute('patente');
            
            try{
                $auto = new autoOperaciones($patente);
            }
            catch(Exception $e){
                return $response->withJson($e->getMessage(), 206);
            }

            if($auto->traerOperaciones($request->getAttribute('desde'), $request->getAttribute('hasta'))){
                return $response->withJson($auto);
            }
            else{
                return $response->withJson("No hay operaciones en esa fecha", 206);
            }
        }

        public function buscarTodosAutos($request, $response, $args){
            $autos = auto::traerAutos();
            $autosOperaciones = array();
            foreach ($autos as $key ) {
                $patente = $key->patente;
                $auto = new autoOperaciones($patente);
                if($auto->traerOperaciones($request->getAttribute('desde'), $request->getAttribute('hasta'))){
                    array_push($autosOperaciones, $auto);
                }
            }
            return $response->withJson($autosOperaciones);
        }

        public function mas($request, $response, $args){

            $cantidades = lugarCantidad::masUtilizados($request->getAttribute('desde'), $request->getAttribute('hasta'));
            if(!$cantidades){
                return $response->withJson("No hay datos en esas fechas", 206);
            }
            $patente = estacionamiento::traerPatentes();
            estacionamiento::asignarPatentes($cantidades, $patente);

            return $response->withJson($cantidades);
        }

        




        public function menos($request, $response, $args){
            $cantidades = lugarCantidad::menosUtilizados($request->getAttribute('desde'), $request->getAttribute('hasta'));
            if(!$cantidades){
                return $response->withJson("No hay datos en esas fechas", 206);
            }
            $patente = estacionamiento::traerPatentes();
            estacionamiento::asignarPatentes($cantidades, $patente);

            return $response->withJson($cantidades);
        }

        public function nunca($request, $response, $args){
            $datos = lugarCantidad::cantidades(true,$request->getAttribute('desde'), $request->getAttribute('hasta'));

            $lugares = lugar::traerLugares();
            $array = array();
            $todos = array_map(
                function($lugar){
                    return $lugar->numero;
                }, $lugares);

            if($datos){
                
                
                $ocupadas = array_map(
                function($lugar){
                    return $lugar->numero;
                }, $datos);
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
                    $lugar = new LugarCantidad($key, 0);
                    $patente = estacionamiento::buscar($lugar->numero);
                    $lugar->patente = $patente['patente'];
                    array_push($array, $lugar);
                }   
            }
            
            return $response->withJson($array);
        }
    }
    
?>