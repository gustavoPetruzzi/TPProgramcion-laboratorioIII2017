<?php
    //include_once("../clases/auto.php");
    include_once("../clases/lugar.php");
    $auto = new auto("PUT001","blanco","renaul");
    $lugares = lugar::traerLugares();
    $lugar = lugar::buscarAuto("PUT000");
    lugar::agregar(3,2,$auto->patente);
    lugar::sacar($auto->patente);
    lugar::reservar(1);
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo $lugar->getNumero();
    echo "<br>";
    echo $lugar->getPatente();
    echo "<br>";
    echo $lugar->getPiso();
    echo "<br>";
    echo $lugar->getReservado();
    echo "<br>";
        
    
    //echo $miLugar->getauto->color;
?>