<?php
    //include_once("auto.php");
    include_once("empleado.php");
    include_once("piso.php");

    /**
     * 
     */
    class estacionamiento 
    {
        public $pisos;
        public $empleados;
        function __construct($cantidadPisos=1, $lugaresPorPiso)
        {
            $this->pisos = array();
            $this->empleados = array();
            $this->lugaresReservados = array();
            for ($i=0; $i < $cantidadPisos; $i++) {
                $piso = new piso($lugaresPorPiso);
                $this->pisos[$i+1] = $piso;
            }
            $this->pisos[1]->reservar(1);
            $this->pisos[1]->reservar(2);
            $this->pisos[1]->reservar(3);
        }
        
        



        public function agregarEmpleado($empleado){
            //VER VALIDACIONES
            $this->empleados[] = $empleado;
        }
        
        public function estacionar($auto,$piso=NULL, $lugar=Null){
            $retorno['exito'] = false;
            if(isset($piso) && isset($lugar)){
                

            }

        }












        
        private function lugarEnpiso($lugar, $piso = null){
            $retorno['exito'] = false;
            $lugarValidado;
            if(isset($piso)){
                $lugarValidado = $this->pisos[$piso]->maximo - $lugar;
                if($lugarValidado >= 0){
                    $retorno['exito'] = true;
                    $retorno['lugar'] = $lugarValidado;
                    
                }
            }
            else{
                foreach ($this->pisos as $piso ) {
                    $lugarValidado = $piso->maximo - $lugar;
                    if($lugarValidado >= 0){
                        $retorno['exito'] = true;
                        $retorno['lugar'] = $lugarValidado;
                        $retorno['piso'] = $piso;
                        break;
                    }
                }
            }
            return $retorno;
        }
        
    }
    


?>

