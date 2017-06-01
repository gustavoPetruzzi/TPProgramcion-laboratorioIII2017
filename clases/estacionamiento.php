<?php
    //include_once("auto.php");
    include_once("empleado.php");
    include_once("piso.php");
    include_once("accesoDatos.php");


    class estacionamiento 
    {

        public $precioHora;
        public $precioEstadia;
        public $precioMedia;
        function __construct($hora, $media, $estadia)
        {

            $this->precioHora = $hora;
            $this->precioMedia = $media;
            $this->precioEstadia = $estadia;

        }
        public function traerLugares(){
            $lugares = lugar::traerLugares();

            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $patentes = estacionamiento::traerPatentes();
            estacionamiento::asignarPatentes($lugares, $patentes);

            
            return $lugares;
        }
        // MODIFICAR PARA QUE ME SE PUEDA USAR SIN LUGAR
        public function estacionar($auto, $lugar){
            
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $dia = date("Y-m-d");
            $hora = date("H:i:s");

            $consulta = $objetoAccesoDatos->RetornarConsulta("INSERT INTO operaciones(idempleado, lugar, patente, dia, entrada)
                                                              VALUES(:idempleado,:lugar,:patente, :dia, :entrada)");
            $consulta->bindValue(":idempleado", 2, PDO::PARAM_INT);
            $consulta->bindValue(":lugar", $lugar, PDO::PARAM_INT);
            $consulta->bindValue(":patente", $auto->patente,PDO::PARAM_STR);
            $consulta->bindValue(":dia", $dia, PDO::PARAM_STR);
            $consulta->bindValue(":entrada", $hora, PDO::PARAM_STR);
            return $consulta->execute();

        }
        private static function traerPatentes(){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta =$objetoAccesoDatos->RetornarConsulta("SELECT lugar, patente FROM operaciones WHERE salida = '00:00:00'");
            $consulta->execute();
            return $consulta->fetchAll();
        }
        private static function asignarPatentes($lugares, $patentes){
            $function = function ($lugar, $clave,$patente ){
                if($lugar->getNumero() == $patente['lugar']){
                    $lugar->setPatente($patente['patente']);
                }
            };
            foreach ($patentes as $key ) {
                array_walk($lugares, $function,$key);
            }
        }
    }


?>

