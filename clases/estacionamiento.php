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

        public function agregarAuto($auto,$piso=NULL, $lugar=Null){
            if(isset($piso))

        }

    }
    


?>

