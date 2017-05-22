<?php
    include_once("lugar.php");
    class piso 
    {
        public $lugares;
        public $maximo;
        function __construct($cantidadNumero=20)
        {
            $this->lugares = array();
            $this->maximo = $cantidadNumero;
        }
        public function lugaresLibre(){
            return $this->maximo - count($this->lugares);
        }
        public function agregarLugar($auto){
            if($this->lugaresLibre() > 0){
                $lugar = new lugar();
                $lugar->agregarAuto($auto);
                $this->lugares[] = $lugar;
            }
        }
    }
    
?>