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
                    $retorno['exito'] = true;
                    $retorno['data'] = include("../partes/navbar.php");
                    
                    break;
                }
            }
            echo json_encode($retorno);
            
            break;
        case 'desloguear':
            session_start();
            $_SESSION['usuario'] = null;
            $_SESSION['sesion'] = null;
            session_destroy();
            include("../partes/navbar.php");
            break;
        default:
            # code...
            break;
    }

?>