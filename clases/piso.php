<?php
    include_once("lugar.php");
    include_once("auto.php");
    class piso 
    {
        public $lugares;
        public $maximo;
        private $inicio;
        //private $cantidad;
        function __construct($inicio, $cantidad)
        {
            $this->maximo = $inicio + $cantidad;
            $this->cantidad = $cantidad;
            $this->lugares = array();
            $this->inicio = $inicio;
            /*
            for ($i=$inicio; $i < $this->maximo; $i++) {
                $this->lugares[$i] = new lugar();
            }
            */
        }
        

        public function lugaresLibre(){
            return count($this->lugares) - $this->usados();
        }
        
        public function reservar($numero ){
            if(isset($numero)){
                $this->lugares[$numero]->reservado = true;
            }

        }
        public function agregarAuto($auto, $numero=null){
            $retorno['exito'] = false;
            if($this->lugaresLibre() > 0){
                if(isset($numero)){
                    if(array_key_exists($numero, $this->lugares)){
                        $retorno['exito'] = $this->lugares[$numero]->agregarAuto($auto);
                        if(!$retorno['exito']){
                            $retorno['mensaje'] = "Lugar ocupado";
                        }
                    }
                    else{
                        $retorno['mensaje'] = "No se encuentra en el piso";
                    }
                }
                else{
                    $retorno['exito'] = $this->lugares[$this->buscarLibre()]->agregarAuto($auto);
                }
            }
            else{
                $retorno['mensaje'] = "Piso lleno";
            }
            return $retorno;
                
        }

        public function sacarAuto($patente){
            
            foreach ($this->lugares as $lugar ) {
                if($lugar->esta($patente)){
                    $auto = $lugar->getAuto();
                    $lugar->sacarAuto();
                    return $auto;
                }
            }
            return null;
        }













        // siempre devuelve un valor porque se usa junto a lugaresLibres.
        private function buscarLibre(){
            for ($i=$this->inicio; $i < $this->maximo; $i++) { 
                if(!$this->lugares[$i]->ocupado()){
                    return $i;
                }
            }
        }        
        private function usados(){
            $usados = 0;
            foreach ( $this->lugares as $lugar ) {
                if($lugar->ocupado()){
                    $usados++;
                }
            }
            return $usados;
        }
    }
    
?>