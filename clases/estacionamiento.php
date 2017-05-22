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
            for ($i=0; $i < $cantidadPisos; $i++) {
                echo "ENTRA";
                $piso = new piso($lugaresPorPiso);
                $this->pisos[] = $piso;
            }
        }

        function agregarEmpleado($empleado){
            //VER VALIDACIONES
            $this->empleados[] = $empleado;
        }
    }
    


?>

