<?php
    include_once("../clases/estacionamiento.php");
    include_once("../clases/auto.php");
    
    $estacionamiento = estacionamiento::traerEstacionamiento();
    var_dump($estacionamiento->traerLugares());
    //var_dump($estacionamiento);
    /*
    $auto =new auto("BUD554", "ROJO", "RENAULT");
    $auto2 =new auto("BUD555", "ROJO", "RENAULT");
    $auto3 =new auto("BUD556", "ROJO", "RENAULT");
    $auto4 =new auto("BUD557", "ROJO", "RENAULT");
    $auto5 =new auto("BUD558", "ROJO", "RENAULT");
    $autoNo =new auto("BUD559", "ROJO", "RENAULT");
    echo "<br>";
    
    $estacionamiento->estacionar($auto,1);
    $estacionamiento->estacionar($auto2,2);
    $estacionamiento->estacionar($auto3,3);
    
    $lugares = estacionamiento::traerLugares();
    var_dump($lugares[0]->getNumero());
    echo "<br>";
    var_dump($lugares[0]->getPatente());
    echo "<br>";

    var_dump($lugares[1]->getNumero());
    echo "<br>";
    var_dump($lugares[1]->getPatente());
    echo "<br>";

    var_dump($lugares[2]->getNumero());
    echo "<br>";
    var_dump($lugares[2]->getPatente());
    echo "<br>";
    */

?>