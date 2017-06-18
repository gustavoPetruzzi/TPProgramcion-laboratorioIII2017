<?php
require_once 'empleado.php';
require_once 'autentificadorJwt.php';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
require_once 'vendor/autoload.php';

/**
 * 
 */
class empleadoApi extends empleado
{
    public static function loguearEmpleadoApi(Request $request, Response $response){
        $data = $request->getParsedBody();
        $usuario = filter_var($data['usuario'], FILTER_SANITIZE_STRING);
        $pass = filter_var($data['pass'], FILTER_SANITIZE_STRING);
        $empleado = empleado::TraerEmpleado($usuario, $pass);
        $retorno['exito'] = false;
        if($empleado && $retorno['exito'] = $empleado->registrarLogin()){

            $retorno['token'] = autentificadorJwt::crearToken(array(
                'id'=> $empleado->id,
                'usuario'=> $empleado->usuario,
                'admin' => $empleado->admin,
            ));
            $retorno['usuario'] = $empleado->usuario;
            $retorno['id'] = $empleado->id;
        }
        return $response->withJson($retorno);
    }

    public static function logoutEmpleadoApi($request, $response, $args){
        
        $token = $args['token'];
        autentificadorJwt::verificarToken($token);
        $datos = autentificadorJwt::extraerData($token);
        $empleado = empleado::buscarEmpleado($datos->id);
        $retorno['exito'] = false;
        if($empleado){
            $retorno['exito'] = $empleado->registrarLogin();
        }
        return $response->withJson($retorno);
        
    }


    public static function listaEmpleadosApi($request, $response, $args){
        $token = $args['token'];
        autentificadorJwt::verificarToken($token);
        $datos = autentificadorJwt::extraerData($token);
        
        $retorno['exito'] = false;
        if(isset($datos) && $datos->admin == true){  
            $empleados = empleado::TraerEmpleados();  
            if(isset($empleados)){
                $retorno['exito'] = true;
                $retorno['empleados'] = $empleados;
            }
        }
        
        
        return $response->withJson($retorno);
    }
}

?>