<?php
    include_once("../clases/piso.php");
    include_once("../clases/auto.php");
    $miPiso = new piso(10);
    //$lugar = new lugar();
    $auto =new auto("BUD554", "ROJO", "RENAULT");
    //$lugar->agregarAuto($auto);
    $miPiso->agregar($auto, 1);
    echo $miPiso->lugaresLibre();
    echo "<br>";
    var_dump($miPiso);
    $miPiso->sacar(1);
    echo "<br>";
    echo $miPiso->lugaresLibre();
    echo "<br>";
    var_dump($miPiso);
?>