<?php
    include_once("../clases/auto.php");
    include_once("../clases/lugar.php");
    $auto = new auto("PUT001","blanco","renaul");
    $miLugar = new lugar(1);
    $miLugar->agregarAuto($auto);   
    echo $miLugar->auto->color;
?>