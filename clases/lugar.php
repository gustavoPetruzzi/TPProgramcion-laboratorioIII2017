<?php
    /**
     * 
     */
    class lugar 
    {
        public $auto;
        function __construct()
        {
            
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