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
        
        



        public function agregarEmpleado($empleado){
            //VER VALIDACIONES
            $this->empleados[] = $empleado;
        }
        
        public function estacionar($auto,$lugar=NULL, $piso=Null){
            $retorno['exito'] = false;
            if(isset($piso) && isset($lugar)){
                $retorno = $this->pisos[$piso]->agregarAuto($lugar);
                return $retorno;
            }
            elseif(isset($lugar)){
                foreach ($this->pisos as $piso) {
                    if(array_key_exists($lugar, $piso->lugares)){
                        $retorno = $piso->agregaAuto($auto, $lugar);
                    }
                }
            }
            else {
                foreach ($this->pisos as $piso ) {
                    $retorno = $piso->agregarAuto($auto);
                    if($retorno['exito']){
                        break;
                    }
                }
            }
            return $retorno;

        }

        public function sacarAuto($patente){
            foreach ($this->pisos as $piso) {
                $auto = $piso->sacarAuto($patente);
                if(isset($auto)){
                    break;
                }
            }
            return $auto;
        }

        
    }
    


?>

