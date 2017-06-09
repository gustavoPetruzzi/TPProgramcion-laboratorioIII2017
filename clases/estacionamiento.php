<?php
    
    include_once("empleado.php");
    include_once("piso.php");
    include_once("accesoDatos.php");
    include_once("lugar.php");

    class estacionamiento 
    {

        public $precioHora;
        public $precioEstadia;
        public $precioMedia;
        function __construct($hora=null, $media=null, $estadia=null)
        {
            if($hora !=null && $media!=null && $estadia!=null){
                $this->precioHora = $hora;
                $this->precioMedia = $media;
                $this->precioEstadia = $estadia;
            }

        }
        public function traerLugares(){
            $lugares = lugar::traerLugares();
            
            
            $patentes = estacionamiento::traerPatentes();
            estacionamiento::asignarPatentes($lugares, $patentes);

            
            return $lugares;
        }
        public function loguear($usuario, $pass){
            if(!isset($_SESSION['empleado'])){
                $empleados = empleado::TraerEmpleados();
                //TODO--->VER VALIDACIONES

                $empleadoLog = new empleado($usuario, $pass);
                $retorno['exito'] = false;
                foreach ($empleados as $empleadoBase ) {
                    if($empleadoBase->usuario == $empleadoLog->usuario && $empleadoBase->getPass() == $empleadoLog->getPass()){
                        session_start();
                        $_SESSION['empleado'] = $empleadoBase;
                        $retorno['exito'] = empleado::registrarLogin($empleadoBase->id);
                        $retorno['empleado'] = $empleadoBase->usuario;
                        
                        break;
                    }
                }
            }
            else{
                session_start();
                $retorno['exito'] = true;
                $retorno['empleado'] = $_SESSION['empleado']->usuario;
            }
            if($retorno['exito']){
                $retorno['precios'] = $this;
                
                $retorno['lugares'] = $this->traerLugares();
                
                
            }
            return $retorno;
        }
        
        public static function traerEstacionamiento(){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta =$objetoAccesoDatos->RetornarConsulta("SELECT hora as precioHora, media as precioMedia, estadia as precioEstadia FROM precios");
            $consulta->setFetchMode(PDO::FETCH_CLASS, "estacionamiento");
            $consulta->execute();
            return $consulta->fetch();

        }
        // MODIFICAR PARA QUE ME SE PUEDA USAR SIN LUGAR
        public function estacionar($auto,$id, $lugar = null){
            
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $dia = date("Y-m-d");
            $hora = date("H:i:s");

            $consulta = $objetoAccesoDatos->RetornarConsulta("INSERT INTO operaciones(idempleado, lugar, patente, dia, entrada)
                                                              VALUES(:idempleado,:lugar,:patente, :dia, :entrada)");
            $consulta->bindValue(":idempleado", $id, PDO::PARAM_INT);
            $consulta->bindValue(":lugar", $lugar, PDO::PARAM_INT);
            $consulta->bindValue(":patente", $auto->patente,PDO::PARAM_STR);
            $consulta->bindValue(":dia", $dia, PDO::PARAM_STR);
            $consulta->bindValue(":entrada", $hora, PDO::PARAM_STR);
            if($consulta->execute()){
                return $dia;
            }
            else{
                return false;
            }


        }
        public function sacar($lugar){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->RetornarConsulta("UPDATE operaciones SET salida = :salida WHERE lugar=:numero AND salida = '00:00:00'");
            $hora = date("H:i:s");
            $consulta->bindValue(":salida",$hora, PDO::PARAM_STR);
            $consulta->bindValue(":numero", $lugar, PDO::PARAM_INT);
            return $consulta->execute();
        }


                            /* FUNCIONES PRIVADAS */
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