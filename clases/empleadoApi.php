<?php
require_once 'empleado.php';
require_once 'autentificadorJwt.php';

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
            ));
            $retorno['usuario'] = $empleado->usuario;
            $retorno['id'] = $empleado->id;
        }
        return $response->withJson($retorno);
    }

    public static function logoutEmpleadoApi(Request $request, Response $response){
        $data = $request->getParsedBody();
        $empleado = empleado::buscarEmpleado(filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT));
        $retorno['exito'] = false;
        if($empleado){
            $retorno['exito'] = $empleado->registrarLogin();
        }
        return $response->withJson($retorno);
    }
}

?>