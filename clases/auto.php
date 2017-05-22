<?php

    /**
     * 
     */
    class auto 
    {
        public $patente;
        public $color;
        public $marca;
        function __construct($patente, $color, $marca)
        {
            $this->patente =$patente;
            $this->color = $color;
            $this->marca = $marca;
        }
    }
    
?>