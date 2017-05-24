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
            $this->lugares = array();
            /*
            for ($i=0; $i < $cantidadNumero; $i++) {
                $this->lugares[$i+1] = new lugar();
            }
            */
        }
        

        public function lugaresLibre(){
            return $this->maximo - $this->usados();
        }
        
        public function reservar($numero ){
            if(isset($this->lugares[$numero])){
                $this->lugares[$numero]->reservado = true;
            }
            else{
                $this->lugares[$numero] = new lugar(true);
            }


        }
        public function agregarAuto($auto, $numero=null){
            $retorno['exito'] = false;
            if($this->lugaresLibre() > 0){
                if(isset($numero)){
                    $retorno['exito'] = $this->lugares[$numero]->agregarAuto($auto);
                    if(!$retorno['exito']){
                        $retorno['mensaje'] = "Lugar ocupado";
                    }
                }
                else{
                    $retorno['retorno'] = $this->lugares[$this->buscarLibre()]->agregarAuto($auto);
                }
            }
            else{
                $retorno['mensaje'] = "Piso lleno";
            }
            return $retorno;
                
        }

        public function sacarAuto($patente){
            $retorno = false;
            foreach ($this->lugares as $lugar ) {
                if(isset($lugar) && $lugar->buscar($patente)){
                    $lugar->sacarAuto();
                    $retorno = true;
                    break;
                }
            }
            return $retorno;
        }













        // siempre devuelve un valor porque se usa junto a lugaresLibres.
        private function buscarLibre(){
            for ($i=1; $i < $this->maximo+1; $i++) { 
                if(isset($this->lugares[$i]) && !$this->lugares[$i]->ocupado()){
                    return $i;
                }
                else{
                    $this->lugares[$i] = new lugar();
                    return $i;
                }
            }
        }        
        public function usados(){
            $usados = 0;
            foreach ((array) $this->lugares as $lugar ) {
                if(isset($lugar) && $lugar->ocupado()){
                    $usados++;
                }
            }
            return $usados;
        }
    }
    
?>