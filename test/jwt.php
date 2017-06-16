<?php
require_once "../clases/autentificadorJwt.php";
    $datos = array(
        'nombre'=> "nomber",
    );
    echo autentificadorJwt::crearToken($datos);
    

?>