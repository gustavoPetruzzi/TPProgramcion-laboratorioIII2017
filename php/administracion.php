<?php
    include_once("../clases/empleado.php");
    $accion = $_POST['accion'];
    switch ($accion) {
        case 'loguear':
            $empleados = empleado::TraerEmpleados();
            
            $usuario = $_POST['usuario'];
            $pass= $_POST['pass'];
            $retorno['exito'] = false;
            foreach ($empleados as $empleadoBase ) {
                if($empleadoBase->usuario == $usuario && $empleadoBase->getPass() == $pass){
                    session_start();
                    $_SESSION['usuario'] = $empleadoBase;
                    $_SESSION['sesion'] = rand(1, 999);
                    $retorno['exito'] = empleado::registrarLogin($empleadoBase->id, $_SESSION['sesion']);
                    
                    break;
                }
            }
            if(!$retorno['exito']){
                echo json_encode($retorno['exito']);
            }        
            else{
                include("../partes/navbar.php");
            }            
            
            break;
        case 'desloguear':
            session_start();
            $retorno['exito'] = false;
            $sesion = $_SESSION['sesion'];
            $id = $_SESSION['usuario']->id;
            $retorno['exito'] = empleado::registrarLogin($id, $sesion, false);
            
            
            if(!$retorno['exito']){
                echo json_encode($retorno['exito']);
            }
            else{
                $_SESSION['sesion'] = null;
                $_SESSION['usuario'] = null;
                session_destroy();
                include("../partes/navbar.php");    
            }
            break;
        default:
            # code...
            break;
    }

?>