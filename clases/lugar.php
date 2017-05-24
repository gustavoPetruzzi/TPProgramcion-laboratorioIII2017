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
        public function agregarAuto($auto){
            $this->auto = $auto;
        }
        public function ocupado(){
            if(isset($auto)){
                return true;
            }
        }


    }
    
?>