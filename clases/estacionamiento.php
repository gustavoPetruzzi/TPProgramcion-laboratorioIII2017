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
        
        public static function traerEstacionamiento(){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta =$objetoAccesoDatos->RetornarConsulta("SELECT hora as precioHora, media as precioMedia, estadia as precioEstadia FROM precios");
            $consulta->setFetchMode(PDO::FETCH_CLASS, "estacionamiento");
            $consulta->execute();
            return $consulta->fetch();

        }
        public function autoEstacionado($patente){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT * FROM operaciones WHERE patente = :patente AND salida = '0000-00-00 00:00:00'");
            $consulta->bindValue(":patente", $patente, PDO::PARAM_STR);
            $consulta->execute();
            if($consulta->rowCount() == 0){
                return false;
            }
            else{
                return true;
            }
        }
        public  function estacionar($auto,$id, $lugar = null){
            
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();

            $consulta = $objetoAccesoDatos->RetornarConsulta("INSERT INTO operaciones(idempleado, lugar, patente, entrada)
                                                              VALUES(:idempleado,:lugar,:patente, NOW())");
            $consulta->bindValue(":idempleado", $id, PDO::PARAM_INT);
            $consulta->bindValue(":lugar", $lugar, PDO::PARAM_INT);
            $consulta->bindValue(":patente", $auto->patente,PDO::PARAM_STR);
            return $consulta->execute();

        }
        public function sacar($data){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            if(isset($data['lugar'])){                
                $lugar = $this->buscar($data['lugar']);
                $patente = $lugar['patente'];
            }
            else{
                
                $patente = $data['patente'];
            }
            
            $retorno['exito'] = false;
            $consulta = $objetoAccesoDatos->RetornarConsulta("UPDATE operaciones SET salida = NOW() WHERE patente= :patente AND salida = '0000-00-00 00:00:00'");
            $consulta->bindValue(":patente", $patente, PDO::PARAM_STR);
            $consulta->execute();

            if($consulta->rowCount() !=0){
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
            else{
                $retorno['exito'] = false;
                $retorno['mensaje'] = "Patente no localizada";
            }
            return $retorno;
        }

        public static function registrosAutos($patente, $desde, $hasta = NULL){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $desde = $desde."%";
            if(!isset($hasta)){
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, entrada, salida, precio FROM operaciones WHERE patente = :patente AND entrada LIKE :desde");
            }
            else{
                $hasta = $hasta."%";
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, entrada, salida, precio FROM operaciones WHERE patente = :patente AND entrada BETWEEN :desde AND :hasta");
                $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
            }

            $consulta->bindValue(':desde', $desde, PDO::PARAM_STR);
            $consulta->bindValue(':patente', $patente, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll();
        }

        public static function registrosCocheras($orden, $desde, $hasta = NULL){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $desde = $desde."%";
            if($orden){
                if(!isset($hasta)){
                    $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, COUNT(*) AS cantidad FROM operaciones WHERE entrada LIKE :desde GROUP BY cochera ORDER BY cantidad DESC");
                }
                else{
                    $hasta = $hasta."%";
                    $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, COUNT(*) AS cantidad FROM operaciones WHERE entrada BETWEEN :desde AND :hasta GROUP BY cochera ORDER BY cantidad DESC");
                    $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
                }
            }
            else{
                if(!isset($hasta)){
                    $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, COUNT(*) AS cantidad FROM operaciones WHERE entrada LIKE :desde GROUP BY cochera ORDER BY cantidad ASC");
                }
                else{
                    $hasta = $hasta."%";
                    $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, COUNT(*) AS cantidad FROM operaciones WHERE entrada BETWEEN :desde AND :hasta GROUP BY cochera ORDER BY cantidad ASC");
                    $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
                }
            }

            $consulta->bindValue(':desde', $desde,  PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll();
        }

        public function calcularPrecio($patente) {
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->RetornarConsulta("SELECT TIMESTAMPDIFF(MINUTE, entrada,salida) as diferencia FROM operaciones WHERE precio = 0 AND patente = :patente");
            $consulta->bindValue(":patente", $patente, PDO::PARAM_STR);
            if($consulta->execute()){
                $numero = $consulta->fetch();    
                $tiempo = $numero['diferencia'];

                $total = 0;

                $estadia =  $this->precioEstadia * floor($tiempo /(60*24));
                $total += $estadia;
                $tiempoRestante = $tiempo % (60*24);

                $media = $this->precioMedia * floor($tiempoRestante / (60*12));
                $tiempoRestante = $tiempoRestante % (60*12);
                $total += $media;

                $hora = $this->precioHora * floor($tiempoRestante / 60);
                $tiempoRestante = $tiempoRestante % 60;
                $total += $hora;
                if($total == 0){
                    $total = 10;
                }
                else{
                    $fraccion = round(($this->precioHora / 6)) * floor($tiempoRestante / 10);
                    $tiempoRestante = $tiempoRestante % 10;
                    $total += $fraccion;
                }
                
                
                
                return $total;
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
        public static function traerPatentes(){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta =$objetoAccesoDatos->RetornarConsulta("SELECT lugar, patente FROM operaciones WHERE salida = '0000-00-00 00:00:00'");
            $consulta->execute();
            return $consulta->fetchAll();
        }
        public static function asignarPatentes($lugares, $patentes){
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