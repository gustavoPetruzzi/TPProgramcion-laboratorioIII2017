<?php
    include_once("../clases/piso.php");
    include_once("../clases/auto.php");
    $miPiso = new piso(10);
    
    //$lugar = new lugar();
    $auto =new auto("BUD554", "ROJO", "RENAULT");
    //$lugar->agregarAuto($auto);
    $miPiso->agregarAuto($auto);
    echo $miPiso->lugaresLibre();
    echo "<br>";
    var_dump($miPiso);
    //$miPiso->sacarAuto("BUD554");
    echo "<br>";
    echo $miPiso->lugaresLibre();
    echo "<br>";
    
    
?>