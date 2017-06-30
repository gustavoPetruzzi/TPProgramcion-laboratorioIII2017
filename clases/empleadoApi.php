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
    /**
     * Loguea al empleado y le asigna un token
     *
     * @param [type] $request pasa como atributo el usuario y el pass
     * @param [type] $response
     * @param [type] $args
     * @return voi devuelve el token si se pudo loguear, sino devuelve el error y el status code 400
     */
    public static function loguearEmpleadoApi($request, $response, $args){

        $empleado = empleado::TraerEmpleado($request->getAttribute('usuario'), $request->getAttribute('pass'));
        $retorno['exito'] = false;
        if($empleado){
            if($empleado->activo){
                if($retorno['exito'] = $empleado->registrarLogin()){
                    $retorno['token'] = autentificadorJwt::crearToken(array(
                        'id'=> $empleado->id,
                        'usuario'=> $empleado->usuario,
                        'admin' => $empleado->admin,
                    ));
                    $retorno['usuario'] = $empleado->usuario;
                    $retorno['id'] = $empleado->id;
                }
            }
            else{
                $retorno['mensaje'] = "Empleado suspendido";
            }
        }
        else{
            $retorno['mensaje'] = "Usuario o contraseña invalido";
        }
        if($retorno['exito']){
            return $response->withJson($retorno);    
        }
        else{
            return $response->withJson($retorno, 400);
        }
        
    }
    /**
     * Registrar el deslogueo del empleado
     *
     * @param [type] $request recibe el id para desloguear al empleado
     * @param [type] $response
     * @param [type] $args
     * @return devuelve true si pudo registrar el deslogueo o false si no pudo
     */
    public static function logoutEmpleadoApi($request, $response, $args){

        $empleado = empleado::buscarEmpleado($request->getAttribute('id'));
        $retorno['exito'] = false;
        if($empleado){
            $retorno['exito'] = $empleado->registrarLogin(false);
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
        if($retorno['exito']){
            return $response->withJson($retorno);    
        }
        return $response->withJson($retorno, 400);
    }


    public function alta($request, $response, $args){        
        $nuevoEmpleado = new empleado($request->getAttribute('nombre'), $request->getAttribute('apellido'), $request->getAttribute('usuario'), $request->getAttribute('pass'), $request->getAttribute('activo'), $request->getAttribute('admin'));        
        return $response->withJson($nuevoEmpleado->guardarEmpleado());
    }
    public function modificar($request, $response, $args){
        $empleadoModificado = empleado::buscarEmpleado($request->getAttribute('id'));
        if($empleadoModificado){
            $empleadoModificado->nombre = $request->getAttribute('nombre');
            $empleadoModificado->apellido = $request->getAttribute('apellido');
            $empleadoModificado->usuario = $request->getAttribute('usuario');
            $empleadoModificado->pass = $request->getAttribute('pass');
            $empleadoModificado->activo = $request->getAttribute('activo');
            $empleadoModificado->admin = $request->getAttribute('admin');
            return $response->withJson($empleadoModificado->modificarEmpleado());    
        }
        else{
            return $response->withJson('No existe ningun empleado con ese id', 400);
        }
    }
    public function borrar($request, $response, $args){
        $id = filter_var($request->getAttribute('id'), FILTER_SANITIZE_NUMBER_INT);
        if($id){
            return $response->withJson(empleado::borrarEmpleado($id));
        }
        else{
            return $response->withJson("id invalido", 400);
        }
    }
    public function actualizarEstado($request, $response, $args){
        $id = filter_var($request->getAttribute('id'), FILTER_SANITIZE_NUMBER_INT);
        if($id){
            $empleado = empleado::buscarEmpleado($id);
            if($empleado){
                $empleado->actualizar();
                return $response->withJson($empleado->modificarEmpleado());
            }
            return $response->withJson("No existe el empleado", 400);
        }
        return $response->withJson("id invalido", 400);
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