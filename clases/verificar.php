<?php
    /**
     * 
     */
    require_once 'autentificadorJwt.php';
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;
    require_once 'vendor/autoload.php';
    class verificar
    {
        // TODO AÑADIR EXCEPCIONES
        public static function datosUsuarios($request, $response, $next){
            if($request->isPost()){
                $data = $request->getParsedBody();
                if( isset($data['usuario']) && !empty($data['usuario']) && isset($data['pass']) && !empty($data['pass']) ){
                    $usuario = filter_var($data['usuario'], FILTER_SANITIZE_STRING);
                    $pass = filter_var($data['pass'], FILTER_SANITIZE_STRING);    
                    if($usuario && $pass){
                        $request = $request->withAttribute('usuario', $usuario);
                        $request = $request->withAttribute('pass', $pass);
                        return $next($request, $response);
                    }
                }
                else {
                    return $response->withJson( "Datos erroneos", 400);
                }
            }
            else{

                if($request->hasHeader('token')){
                    $token = $request->getHeader('token')[0];
                    autentificadorJwt::verificarToken($token);
                    $datos = autentificadorJwt::extraerData($token);
                    $request = $request->withAttribute('id', $datos->id);
                    return $next($request, $response);
                }
            }
        }

        public static function admin($request, $response, $next){
            $datos = $request->getAttribute('datos');
            if($datos->admin){
                return  $next($request, $response);    
            }
            else {
                $retorno['exito'] = false;
                $retorno['mensaje'] = "No tiene los permisos requeridos";       
            }
            return $response->withJson($retorno);
        }

        public function token($request, $response, $next){
            if($request->hasHeader('token')){
                $token = $request->getHeader('token')[0];
                $datos = autentificadorJwt::extraerData($token);
                $request = $request->withAttribute('datos', $datos);
                return $next($request, $response);
            }
            return $response->withJson("no se ha enviado ningun token", 400);
        }

        public static function datosNuevo($request, $response, $next){
            $data = $request->getParsedBody();
            if(isset($data['id'])){
                $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
                if($id){
                    $request = $request->withAttribute('id', $id);    
                }
                else{
                    return $response->withJson('id invalido', 400);
                }
                
            }
            else{
                $request = $request->withAttribute('id', NULL);
            }
            if($request->isPost()){

                
                if(isset($data['nombre']) && isset($data['apellido']) && isset($data['usuario']) && isset($data['pass']) && isset($data['activo']) && isset($data['admin'])){
                    $nombre = filter_var($data['nombre'], FILTER_SANITIZE_STRING);
                    $apellido = filter_var($data['apellido'], FILTER_SANITIZE_STRING);
                    $usuario = filter_var($data['usuario'], FILTER_SANITIZE_STRING);
                    $pass = filter_var($data['pass'], FILTER_SANITIZE_STRING);
                    $activo = filter_var($data['activo'], FILTER_VALIDATE_BOOLEAN);
                    $admin = filter_var($data['admin'], FILTER_VALIDATE_BOOLEAN);
                    
                    if($nombre && $apellido && $usuario && $pass && $activo){ 
                        $request = $request->withAttribute('usuario', $usuario);
                        $request = $request->withAttribute('nombre', $nombre);
                        $request = $request->withAttribute('apellido', $apellido);
                        $request = $request->withAttribute('pass', $pass);
                        $request = $request->withAttribute('activo', $activo);
                        $request = $request->withAttribute('admin', $admin);
                        return $next($request, $response);
                    }
                    else{
                        $retorno = "Alguno de los datos son erroneos";
                        return $response->withJson($retorno, 400);
                    }
                }
                else{
                    $retorno = "No se pasaron los datos suficientes";
                    return $response->withJson($retorno, 400);
                }
            }
            else{
                return $next($request, $response);
            }
            //AÑADIR ELSE PARA PATCH
        }

        public static function fechas($request, $response, $next){
            $data = $request->getParsedBody();
            $desde = date("Y-m-d", $data['desde']);
            if($desde){
                $request = $request->withAttribute('desde', $desde);
            }
            else{
                return $response->withJson('Fecha mal pasada');
            }

            if(isset($data['hasta'])){
                $hasta = date("Y-m-d", $data['hasta']);
                if($hasta){
                    $request = $request->withAttribute('hasta', $hasta);
                }
                else{
                    return $response->withJson('Fecha mal pasada');
                }
            }
            else{
                $request = $request->withAttribute('hasta', NULL);
            }

            return $next($request, $response);
        }


        public static function datosEstacionar($request, $response, $next){
            $data = $request->getParsedBody();
            $colores = ['verde', 'rojo', 'azul', 'blanco', 'negro'];
            $marcas = ['peugeot', 'renault', 'ford'];

            if($request->isPost()){
                if(isset($data['lugar'])){
                    $lugar = filter_var($data['lugar'], FILTER_SANITIZE_NUMBER_INT);
                    if($lugar){
                        $request = $request->withAttribute('lugar', $lugar);
                    }
                    else{
                        return $response->withAttribute('lugar invalido', 400);
                    }
                }
                else{
                    $request = $request->withAttribute('lugar', NULL);
                }


                $color = strtolower(filter_var($data['color'], FILTER_SANITIZE_STRING));
                $marca = strtolower(filter_var($data['marca'], FILTER_SANITIZE_STRING));
                $patente = verificar::patenteVieja($data['patente']);

                if($patente){
                    $request = $request->withAttribute('patente', $patente);
                }
                else{
                    $patente = $this->patenteNueva($data['patente']);
                    if($patente){
                        $request = $request->withAttribute('patente', $patente);
                    }
                    else{
                        return $response->withJson("Patente incorrecta", 400);
                    }
                }
                if(in_array($color, $colores)){
                    $request = $request->withAttribute('color', $color);
                }
                else{
                    return $response->withJson("Color incorrecto", 400);
                }

                if(in_array($marca, $marcas)){
                    $request = $request->withAttribute('marca', $marca);
                }
                else{
                    return $response->withJson("Marca incorrecta", 400);
                }

                return $next($request, $response);
            }
            elseif ($request->isDelete()) {
                $dato = $request->getAttribute('route')->getArguments('datos');
                $patente = verificar::patenteVieja($dato['datos']);
                if($patente){
                    $request = $request->withAttribute('patente', $patente);
                }
                elseif($id= filter_var($dato['datos'], FILTER_SANITIZE_NUMBER_INT)){
                    $request = $request->withAttribute('lugar', $id);
                }
                else{
                    return $response->withJson('No se han enviado datos',400);
                }
                return $next($request, $response);
            }
            elseif($request->isGet()){
                if(isset($data['patente'])){
                    $patente = $this->patenteVieja($data['patente']);
                    if($patente){
                        $request = $request->withAttribute('patente', $patente);
                    }
                    else{
                        $patente = $this->patenteNueva($data['patente']);
                        if($patente){
                            $request = $request->withAttribute('patente', $patente);
                        }
                        else{
                            return $response->withJson('patente invalidad',400);
                        }
                    }
                }
            }
            return $next($request, $response);
        }



        /**
         * verifica que la patente pasada sea una patente vieja valida
         *
         * @param [type] $patente string a ser verificado
         * @return devuelve la patente "curada" si es correcta o false si no es valida.
         */
        public  static function patenteVieja($patente){
            $patente = trim(str_replace("-", "", strtoupper($patente)));
            
            if(preg_match("/^[A-Z]{3}[0-9]{3}$/",$patente)){
                return $patente;
            }
            else{
                return false;
            }
        }

        public static function patenteNueva($patente){
            return true;
        }

    }
    
?>