<?php
    include_once("../clases/piso.php");
    include_once("../clases/auto.php");
    $miPiso = new piso(1, 5);
    $segundo = new piso(6, 5);
    $tercero = new piso(11, 5);

    
    //$lugar = new lugar();
    $auto =new auto("BUD554", "ROJO", "RENAULT");
    //$lugar->agregarAuto($auto);
    var_dump($miPiso->lugares[5]);
    $retorno = $miPiso->agregarAuto($auto, 5);
    if($retorno['exito']){
        echo "agregado<br>";
    }
    else{
        echo "error<br>";
        echo $retorno['mensaje'];
    }
    /*
    if($miPiso->sacarAuto("BUD554")){
        echo "sacado<br>";
    }
    else{
        echo "error<br>";
    }
    */
    //echo $miPiso->lugaresLibre();
    echo "<br>";
    var_dump($miPiso->lugares[5]);
    echo "<br>";
    $auto = $miPiso->sacarAuto("BUD554");
    echo "<br>";
    echo "<br>";
    var_dump($miPiso->lugares[5]);
    echo "<br>";
    var_dump($auto);
    echo "<br>";
    echo "<br>";
    $miPiso->agregarAuto($auto, 5);
    echo "<br>";
    var_dump($miPiso->lugares[5]);
    echo "<br>";
    
    
    
?>