<?php
    //include_once("../clases/auto.php");
    include_once("../clases/lugar.php");
    $auto = new auto("PUT001","blanco","renaul");
    $lugares = lugar::traerLugares();
    var_dump($lugares);
        
    
    //echo $miLugar->getauto->color;
?>