<?php
    include_once("../clases/estacionamiento.php");
    include_once("../clases/auto.php");
    $datos = array(5,5,5);
    $estacionamiento = new estacionamiento($datos, 1,1,1);
    //var_dump($estacionamiento);
    $auto =new auto("BUD554", "ROJO", "RENAULT");
    $auto2 =new auto("BUD555", "ROJO", "RENAULT");
    $auto3 =new auto("BUD556", "ROJO", "RENAULT");
    $auto4 =new auto("BUD557", "ROJO", "RENAULT");
    $auto5 =new auto("BUD558", "ROJO", "RENAULT");
    $autoNo =new auto("BUD559", "ROJO", "RENAULT");
    echo "<br>";
    $estacionamiento->estacionar($auto);
    var_dump($estacionamiento->pisos[0]->lugares[1]);
    echo "<br>";
    echo $estacionamiento->pisos[1]->maximo;
    echo "<br>";
    $autoSacado = $estacionamiento->sacarAuto("BUD554");
    var_dump($autoSacado);


?>