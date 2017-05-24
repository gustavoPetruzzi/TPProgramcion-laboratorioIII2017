<?php
    include_once("lugar.php");
    include_once("auto.php");
    class piso 
    {
        public $lugares;
        public $maximo;
        
        function __construct($cantidadNumero=20)
        {
            $this->maximo = $cantidadNumero;
            for ($i=0; $i < $cantidadNumero; $i++) {
                $this->lugares[$i+1] = new lugar();
            }
        }
        

        public function lugaresLibre(){
            return $this->maximo - $this->usados();
        }
        
        public function reservar($numero ){
            $this->lugares[$numero]->reservado = true;

        }
        public function agregarAuto($auto, $numero=null){
            if($this->lugaresLibre() > 0){
                if(isset($numero) && !$this->lugares[$numero]->ocupado()){
                    $this->lugares[$numero]->auto = $auto;
                }
                else{
                    $this->lugares[$this->buscarLibre()]->auto = $auto;    
                }
            }
        }

        public function sacarAuto($patente){
            $retorno = false;
            foreach ($this->lugares as $key ) {
                if(isset($lugar) && $lugar->buscar($patente)){
                    unset()
                }
            }
        }













        // siempre devuelve un valor porque se usa junto a lugaresLibres.
        private function buscarLibre(){
            for ($i=1; $i < $this->maximo+1; $i++) { 
                if(!$this->lugares[$i]->ocupado()){
                    return $i;
                }
            }
        }        
        private function usados(){
            $usados = 0;
            foreach ($this->lugares as $lugar ) {
                if($lugar->ocupado() ){
                    $usados++;
                }
            }
            return $usados;
        }
    }
    
?>