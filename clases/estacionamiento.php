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

            $consulta = $objetoAccesoDatos->RetornarConsulta("INSERT INTO operaciones(idempleado, lugar, patente, entrada)
                                                              VALUES(:idempleado,:lugar,:patente, NOW())");
            $consulta->bindValue(":idempleado", $id, PDO::PARAM_INT);
            $consulta->bindValue(":lugar", $lugar, PDO::PARAM_INT);
            $consulta->bindValue(":patente", $auto->patente,PDO::PARAM_STR);
            if($consulta->execute()){
                return $auto;
            }
            else{
                return false;
            }


        }
        public function sacar($data){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            if(isset($data['lugar'])){
                $lugar = $this->buscar(filter_var($data['lugar'], FILTER_SANITIZE_NUMBER_INT));
                $patente = $lugar['patente'];
            }
            else{
                var_dump($data);
                $patente = filter_var($data['patente'], FILTER_SANITIZE_STR);
            }
            $retorno['exito'] = false;
            $consulta = $objetoAccesoDatos->RetornarConsulta("UPDATE operaciones SET salida = NOW() WHERE patente= :patente AND salida = '0000-00-00 00:00:00'");
            $consulta->bindValue(":patente", $patente, PDO::PARAM_STR);

            if($consulta->execute()){
                $precio = $this->calcularPrecio($patente);
                $consulta = $objetoAccesoDatos->RetornarConsulta("UPDATE operaciones SET precio = :precio WHERE patente= :patente AND precio = 0");
                $consulta->bindValue(":precio", $precio, PDO::PARAM_STR);
                $consulta->bindValue(":patente",$patente, PDO::PARAM_STR);
                $retorno['exito'] = false;
                if($consulta->execute()){
                    $retorno['precio'] = $precio;
                    $retorno['auto'] = auto::buscar($patente);
                    $retorno['exito'] = true;
                }
            }
            return $retorno;
        }
        // VER QUE PASA SI ENTRA Y SALE ANTES DE LA HORA. 
        public function calcularPrecio($patente) {
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT HOUR(TIMEDIFF(salida, entrada)) as `diferencia` FROM operaciones WHERE precio = 0 AND patente = :patente");
            $consulta->bindValue(":patente", $patente, PDO::PARAM_STR);
            if($consulta->execute()){
                $numero = $consulta->fetch();    
            }

            if($numero['diferencia'] > 24){
                return $this->precioEstadia;
            }
            elseif($numero['diferencia'] > 12){

                return $this->precioMedia;
            }
            elseif($numero['diferencia'] == 0){
                return $this->precioHora;
            }
            else{
                return ($numero['diferencia'] * $this->precioHora);
            }

        }
        public function buscar($lugar){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT patente from operaciones WHERE lugar= :lugar AND salida = '0000-00-00 00:00:00'");
            $consulta->bindValue(":lugar", $lugar, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetch();
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