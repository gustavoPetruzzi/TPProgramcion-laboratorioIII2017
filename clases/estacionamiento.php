<?php
    //include_once("auto.php");
    include_once("empleado.php");
    include_once("piso.php");


    class estacionamiento 
    {
        public $pisos;
        public $empleados;
        private $precioHora;
        private $precioEstadia;
        private $precioMedia;
        function __construct($arrayDatos,$hora, $media, $estadia)
        {
            $this->pisos = array();
            $this->empleados = array();
            //$this->lugaresReservados = array();
            $inicio = 1;
            $this->precioHora = $hora;
            $this->precioMedia = $media;
            $this->precioEstadia = $estadia;
            foreach ($arrayDatos as $key => $cantidad) {
                $this->pisos[$key] = new piso($inicio, $cantidad);
                $inicio+= $cantidad;
            }
        }
        public function traerLugares(){
            $lugares = lugar::traerLugares();
            

        }
        
    }


?>

