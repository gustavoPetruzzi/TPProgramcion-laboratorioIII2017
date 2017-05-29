<?php
    include_once("../clases/empleado.php");
    /*
    $empleadoUno = new empleado("Juan", "1234");
    $empleadoDos = new empleado("Jose", "5678");
    var_dump($empleadoUno);
    echo "<br>";
    var_dump($empleadoDos);
    echo "<br>";
    */
    $empleados = empleado::traerEmpleados();
    var_dump($empleados[0]);
?>