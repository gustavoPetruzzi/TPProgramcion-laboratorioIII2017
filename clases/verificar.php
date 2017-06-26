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
                if( isset($data['usuario']) && isset($data['pass'])){
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
            if(isset($data['id'])){
                $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
                if($id){
                    $request = $request->withAttribute('id', $id);    
                }
                else{
                    return $response->withJson('id invalido', 400);
                }
                
            }
            if($request->isPost()){
                $data = $request->getParsedBody();

                
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

    }
    
?>