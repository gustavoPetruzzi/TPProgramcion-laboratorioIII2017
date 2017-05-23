<?php
    include_once("lugar.php");
    include_once("auto.php");
    class piso 
    {
        public $lugares;
        public $maximo;
        
        function __construct($cantidadNumero=20)
        {
            $this->lugares = array();
            $this->reservado = array();
            $this->maximo = $cantidadNumero;
        }
        

        public function lugaresLibre(){
            return $this->maximo - count($this->lugares);
        }
        
        public function agregarLugar($reservado = false){
            $retorno = false;

            return $retorno;
        }
        
    
?>