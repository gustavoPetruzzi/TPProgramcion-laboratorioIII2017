<?php
    include_once("../clases/estacionamiento.php");
    $estacionamiento = new estacionamiento(3, 20);
    var_dump($estacionamiento);
    echo "<br>";
    echo $estacionamiento->pisos[1]->maximo;

?>