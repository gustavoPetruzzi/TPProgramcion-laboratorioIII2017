<?php
    
     include_once("auto.php");
    class lugar 
    {
        private $auto;
        public $reservado; // bool
        function __construct($reservado=false)
        {
            $this->reservado = $reservado;
        }
        public function agregarAuto($auto){
            $retorno = false;
            if(isset($auto) && !$this->ocupado()){
                $this->auto = $auto;
                $retorno = true;
            }
            return $retorno;
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
        public function sacarAuto(){
            unset($this->auto);
        }


    }
    
?>