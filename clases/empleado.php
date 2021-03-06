<?php
    include_once("accesoDatos.php");
    class empleado
    {
        
        public $id;
        public $nombre;
        public $apellido;
        public $usuario;
        protected  $_pass;
        public $activo;
        public $admin;

        function __construct( $nombre = NULL, $apellido =NULL, $usuario = NULL, $pass = NULL, $activo = NULL, $admin = NULL)
        {
            if( $nombre != NULL &&  $apellido != NULL && $usuario != NULL && $pass != NULL && $activo !== NULL && $admin !== NULL){
                $this->usuario = $usuario;
                $this->nombre = $nombre;
                $this->apellido = $apellido;
                $this->_pass = $pass;
                $this->activo = $activo;
                $this->admin = $admin;
            }
        }

        public function actualizar(){
            $this->activo = !$this->activo;
        }
        public function setPass($nuevo){
            $this->_pass  = $nuevo;
        }
        public function getPass(){
            return $this->_pass;
        }

        public  function modificarEmpleado(){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->RetornarConsulta("UPDATE empleados SET nombre =:nombre, apellido =:apellido, usuario =:usuario, pass=:pass, activo = :activo WHERE id =:id");
            $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(":apellido", $this->apellido, PDO::PARAM_STR);

            $consulta->bindValue(":usuario", $this->usuario, PDO::PARAM_STR);
            $consulta->bindValue(":pass", $this->_pass, PDO::PARAM_STR);
            $consulta->bindValue(":activo", $this->activo, PDO::PARAM_STR);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            $retorno = $consulta->execute();
            if($retorno && $consulta->rowCount() == 0){
                $retorno = false;
            }
            return $retorno;
        }

        public  function guardarEmpleado(){
            $objetoGuardarDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoGuardarDatos->RetornarConsulta("INSERT INTO empleados (nombre, apellido,usuario, pass, activo, admin)"
                                                             . " VALUES(:nombre, :apellido, :usuario, :pass, :activo, :admin)");
            //$activo = 0;
            $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);                                                             
            $consulta->bindValue(":apellido", $this->apellido, PDO::PARAM_STR);
            $consulta->bindValue(":pass", $this->getPass(), PDO::PARAM_STR);
            $consulta->bindValue(":usuario",$this->usuario, PDO::PARAM_STR);
            $consulta->bindValue(":apellido",$this->apellido, PDO::PARAM_STR);
            $consulta->bindValue(":admin", $this->admin, PDO::PARAM_STR);
            $consulta->bindValue(":activo", $this->activo, PDO::PARAM_INT);


            $retorno = $consulta->execute();
            if($retorno && $consulta->rowCount() == 0){
                $retorno = false;
            }
            return $retorno;
        }

        public static function TraerEmpleados(){
            $listaEmpleados = array();
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("SELECT id, nombre, apellido, usuario, pass as _pass, activo, admin  FROM empleados");
            $consulta->execute();
            $listaEmpleados= $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");
            return $listaEmpleados;
        }
        
        public static function TraerEmpleado($usuario, $pass){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("SELECT id,nombre, apellido, usuario, pass as _pass, activo, admin FROM empleados WHERE usuario = :usuario AND pass =:pass");
            $consulta->bindValue(":usuario",$usuario,PDO::PARAM_STR);
            $consulta->bindValue(":pass",$pass,PDO::PARAM_STR);
            $consulta->setFetchMode(PDO::FETCH_CLASS, "empleado");
            $consulta->execute();
            $empleado = $consulta->fetch();
            return $empleado;
        }
        public static function buscarEmpleado($id){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("SELECT id , nombre, apellido, usuario, pass as _pass, activo, admin FROM empleados
                                                             WHERE id = :id");
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->setFetchMode(PDO::FETCH_CLASS, "empleado");
            $consulta->execute();
            if($consulta->rowCount() == 0){
                return false;
            }
            $empleado = $consulta->fetch();
            return $empleado;
        }
        

        public static function borrarEmpleado($id){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("DELETE FROM empleados WHERE id= :id");
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            
            $retorno = $consulta->execute();
            if($retorno && $consulta->rowCount() == 0 ){
                
                $retorno = false;
            }
                        
            return $retorno;
        }

        public function registrarLogin($entrada = true){
            $objetoAccesoDatos =accesoDatos::DameUnObjetoAcceso();
            
            
            if($entrada){
                
                $consulta = $objetoAccesoDatos->retornarConsulta("INSERT INTO loginempleados (idempleado, dia, entrada) 
                                                                  VALUES (:id,  DATE_FORMAT((SELECT CONVERT_TZ(NOW(), '+00:00', '-02:56')),'%Y:%m:%d'), DATE_FORMAT((SELECT CONVERT_TZ(NOW(), '+00:00', '-02:56')),'%H:%i:%s') ) ");
                
            }
            else {
                $consulta = $objetoAccesoDatos->retornarConsulta("UPDATE loginempleados SET salida= DATE_FORMAT((SELECT CONVERT_TZ(NOW(), '+00:00', '-02:56')),'%H:%i:%s') WHERE salida = '00:00:00' AND idempleado=:id");
            }
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT); 
            return $consulta->execute();
        }
        public  function logueos($id, $desde, $hasta = NULL){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $desde = $desde."%";
            if(!isset($hasta)){
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT loginempleados.dia as dia, loginempleados.entrada as entrada, loginempleados.salida as salida FROM `loginempleados`  
                                                                  WHERE loginempleados.idempleado = :id AND dia LIKE :desde");
                
            }
            else{
                $hasta = $hasta."%";
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT loginempleados.dia as dia, loginempleados.entrada as entrada, loginempleados.salida as salida FROM `loginempleados` 
                                                                  WHERE loginempleados.idempleado = :id AND dia BETWEEN :desde AND :hasta");
                $consulta->bindValue(":hasta", $hasta, PDO::PARAM_STR);
            }
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindValue(":desde", $desde, PDO::PARAM_STR);
            $consulta->setFetchMode(PDO::FETCH_ASSOC);
            
            $consulta->execute();
            if($consulta->rowCount() == 0){
                return false;   
            }
            
            return $consulta->fetchAll();
        }
    }
    
?>