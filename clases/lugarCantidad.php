<?php

    require_once('lugar.php');
    class lugarCantidad extends lugar 
    {
        public $cantidad;

        function __construct($numero = NULL, $cantidad = NULL)
        {
            if($numero != NULL && $cantidad !== NULL){
                $lugar = parent::buscar($numero);
                $this->cantidad = $cantidad;
                parent::__construct($lugar->numero, $lugar->patente, $lugar->piso, $lugar->reservado);
            }
        }
    }
    
?>