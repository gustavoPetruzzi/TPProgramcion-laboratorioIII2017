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
        

        private function lugaresLibre(){
            return $this->maximo - count($this->lugares);
        }
        
        public function agregarLugar($numero, $reservado = false){
            $retorno = false;
            if($this->lugaresLibre() > 0){
                if(isset($numero)){
                    if(!$this->lugares->ocupado()){
                        
                    }

                }
            }
            return $retorno;
        }
        // siempre devuelve un valor porque se usa junto a lugaresLibres.
        private function buscarLibre(){
            for ($i=0; $i < $this->lugares; $i++) {
                if(!isset($this->lugares[$i+1])){
                    return $i;
                }
            }
        }        
    
?>