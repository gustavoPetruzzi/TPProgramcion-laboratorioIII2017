<?php
    include_once("../clases/piso.php");
    include_once("../clases/auto.php");
    $miPiso = new piso(10);
    $lugar = new lugar(1);
    $auto =new auto("BUD554", "ROJO", "RENAULT");
    $lugar->agregarAuto($auto);
    $miPiso->agregarLugar($lugar);
    var_dump($miPiso);
?>