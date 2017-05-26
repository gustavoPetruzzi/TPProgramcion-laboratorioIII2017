<?php
    include_once("../clases/piso.php");
    include_once("../clases/auto.php");
    $miPiso = new piso(1, 5);
    $segundo = new piso(6, 5);
    $tercero = new piso(11, 5);

    
    //$lugar = new lugar();
    $auto =new auto("BUD554", "ROJO", "RENAULT");
    $auto2 =new auto("BUD555", "ROJO", "RENAULT");
    $auto3 =new auto("BUD556", "ROJO", "RENAULT");
    $auto4 =new auto("BUD557", "ROJO", "RENAULT");
    $auto5 =new auto("BUD558", "ROJO", "RENAULT");
    $autoNo =new auto("BUD559", "ROJO", "RENAULT");
    //$lugar->agregarAuto($auto);
    //var_dump($miPiso->lugares[5]);
    $retorno = $miPiso->agregarAuto($auto, 1);
    echo "<br>Lugares Libre ".$miPiso->lugaresLibre();
    
    $retorno = $miPiso->agregarAuto($auto2, 2);
    echo "<br>Lugares Libre ".$miPiso->lugaresLibre();
    $retorno = $miPiso->agregarAuto($auto3, 3);
    echo "<br>Lugares Libre ".$miPiso->lugaresLibre();
    $retorno = $miPiso->agregarAuto($auto4, 4);
    echo "<br>Lugares Libre ".$miPiso->lugaresLibre();
    $retorno = $miPiso->agregarAuto($auto5, 5);
    echo "<br>Lugares Libre ".$miPiso->lugaresLibre();
    $retorno = $miPiso->agregarAuto($autoNo,5);
    echo "<br>";
    
    echo "<br>Lugares Libre ".$miPiso->lugaresLibre();
    if($retorno['exito']){
        echo "<br>agregado<br>";
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
    //var_dump($miPiso->lugares[5]);
    echo "<br>";
    $auto = $miPiso->sacarAuto("BUD554");
    $auto = $miPiso->sacarAuto("BUD554");

    echo "<br>";
    echo "<br>";
    //var_dump($miPiso->lugares[5]);
    echo "<br>";
    var_dump($auto);
    echo "<br>";
    echo "<br>";
    $miPiso->agregarAuto($auto, 5);
    echo "<br>";
    //var_dump($miPiso->lugares[5]);
    echo "<br>";
    
    
    
?>