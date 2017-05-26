<?php
    include_once("../clases/estacionamiento.php");
    $datos = array(5,5,5);
    $estacionamiento = new estacionamiento($datos);
    var_dump($estacionamiento);
    echo "<br>";
    echo $estacionamiento->pisos[1]->maximo;

?>