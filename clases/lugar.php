<?php
    /**
     * 
     */
    class lugar 
    {
        public $auto;
        public $reservado; // bool
        function __construct($reservado)
        {
            $this->reservado = $reservado;
        }
        function agregarAuto($auto){
            $this->auto = $auto;
        }
        function ocupada(){
            if(isset($auto)){
                return true;
            }
        }


    }
    
?>