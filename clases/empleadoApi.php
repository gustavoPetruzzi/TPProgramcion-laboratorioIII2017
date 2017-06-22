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

    public static function loguearEmpleadoApi($request, $response, $args){

        $empleado = empleado::TraerEmpleado($request->getAttribute('usuario'), $request->getAttribute('pass'));
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

        $empleado = empleado::buscarEmpleado($request->getAttribute('id'));
        $retorno['exito'] = false;
        if($empleado){
            $retorno['exito'] = $empleado->registrarLogin();
        }
        return $response->withJson($retorno);
    }


    public static function listaEmpleadosApi($request, $response, $args){
        $retorno['exito'] = false;
        $empleados = empleado::TraerEmpleados();  
        if(isset($empleados)){
            $retorno['exito'] = true;
            $retorno['empleados'] = $empleados;
        }
        return $response->withJson($retorno);
    }
    public function alta($request, $response, $args){        
        $nuevoEmpleado = new empleado($request->getAttribute('nombre'), $request->getAttribute('apellido'), $request->getAttribute('usuario'), $request->getAttribute('pass'), $request->getAttribute('activo'), $request->getAttribute('admin'));        
        return $response->withJson($nuevoEmpleado->guardarEmpleado());
    }
    public function modificar($request, $response, $args){
        $empleadoModificado = empleado::buscarEmpleado($request->getAttribute('id'));
        $empleadoModificado->nombre = $request->getAttribute('nombre');
        $empleadoModificado->apellido = $request->getAttribute('apellido');
        $empleadoModificado->usuario = $request->getAttribute('usuario');
        $empleadoModificado->pass = $request->getAttribute('pass');
        $empleadoModificado->activo = $request->getAttribute('activo');
        $empleadoModificado->admin = $request->getAttribute('admin');

        return $response->withJson($empleadoModificado->modificarEmpleado());
    }
    public function borrar($request, $response, $args){
        if(isset($args['id'])){
            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
            return $response->withJson(empleado::borrarEmpleado($id));
        }
        else{
            $retorno = false;
            $retorno['mensaje'] = "id invalido";
            return $response->withJson($retorno);
        }
    }

    public function registrosLogueos($request, $response, $args){
        if(isset($args['id'])){
            $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
            if($id){
                return $response->withJson(empleado::logueos($id));
            }
        }
        else{
            return $response->withJson(empleado::logueos());
        }
            
    }
}

?>