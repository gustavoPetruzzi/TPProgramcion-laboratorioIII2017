<?php
    
     include_once("auto.php");
    class lugar 
    {
        public $auto;
        public $reservado; // bool
        function __construct($reservado=false)
        {
            $this->reservado = $reservado;
        }
        public function agregarAuto($auto){
            $this->auto = $auto;
        }
        public function ocupado(){
            if(isset($this->auto)){
                return true;
            }
        }
        public function buscar($patente){
            $retorno = false;
            if(isset($this->auto) && $this->auto->patente == $patente){
                return true;
            }
            return $retorno;
        }


    }
    
?>