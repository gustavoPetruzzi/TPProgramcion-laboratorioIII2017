<?php
    include_once("../clases/empleado.php");
    $accion = $_POST['accion'];
    switch ($accion) {
        case 'loguear':
            $empleados = empleado::TraerEmpleados();
            
            $usuario = $_POST['usuario'];
            $pass= $_POST['pass'];
            foreach ($empleados as $empleadoBase ) {
                if($empleadoBase->usuario == $usuario && $empleadoBase->getPass() == $pass){
                    session_start();
                    $_SESSION['usuario'] = $empleadoBase;
                    $_SESSION['sesion'] = rand(1, 999);
                    include("../partes/navbar.php");
                    break;
                }
            }
            
            break;
        case 'desloguear':
            session_start();
            $_SESSION['usuario'] = null;
            session_destroy();
            include("../partes/navbar.php");
            break;
        default:
            # code...
            break;
    }

?>