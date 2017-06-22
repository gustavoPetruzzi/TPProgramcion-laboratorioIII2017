<?php
    include_once("accesoDatos.php");
    class empleado
    {
        
        public $id;
        public $nombre;
        public $apellido;
        public $usuario;
        private $_pass;
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
            $retorno['exito'] = $consulta->execute();
            if($retorno['exito'] && $consulta->rowCount() == 0){
                $retorno['exito'] = false;
                $retorno['mensaje'] = "No existe nadie con ese id";
            }
            return $retorno;
        }

        public  function guardarEmpleado(){
            $objetoGuardarDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoGuardarDatos->RetornarConsulta("INSERT INTO empleados (nombre, apellido,usuario, pass, activo, admin)"
                                                             . " VALUES(:nombre, :apellido, :usuario, :pass, :activo, :admin)");
            //$activo = 0;
            $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);                                                             
            $consulta->bindValue(":usuario", $this->usuario, PDO::PARAM_STR);
            $consulta->bindValue(":pass", $this->getPass(), PDO::PARAM_STR);
            $consulta->bindValue(":nombre",$this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(":apellido",$this->apellido, PDO::PARAM_STR);
            $consulta->bindValue(":admin", $this->admin, PDO::PARAM_STR);
            $consulta->bindValue(":activo", $this->activo, PDO::PARAM_INT);


            $retorno['exito'] = $consulta->execute();
            if($retorno['exito'] && $consulta->rowCount() == 0){
                $retorno['exito'] = false;
                $retorno['mensaje'] = "No pudo guardarse";
            }
            return $retorno;
        }

        public static function TraerEmpleados(){
            $listaEmpleados = array();
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("SELECT id, nombre, apellido, usuario , activo, admin  FROM empleados");
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
            $empleado = $consulta->fetch();
            return $empleado;
        }
        

        public static function borrarEmpleado($id){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("DELETE FROM empleados WHERE id= :id");
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $retorno['exito'] = $consulta->execute();
            
            if($retorno['exito'] && $consulta->rowCount() == 0 ){
                $retorno['mensaje'] = "No existe nadie  con ese id";
                $retorno['exito'] = false;
            }
                        
            return $retorno;
        }

        public function registrarLogin($entrada = true){
            $objetoAccesoDatos =accesoDatos::DameUnObjetoAcceso();
            
            $fecha = date('H:i:s');
            if($entrada){
                $dia = date('Y-m-d');
                $consulta = $objetoAccesoDatos->retornarConsulta("INSERT INTO loginempleados (idempleado, dia, entrada) 
                                                              VALUES (:id,  DATE_FORMAT(NOW(),'%Y:%m:%d'), DATE_FORMAT(NOW(),'%H:%i:%s') ) ");
                
            }
            else {
                $consulta = $objetoAccesoDatos->retornarConsulta("UPDATE loginempleados SET salida= DATE_FORMAT(NOW(),'%H:%i:%s') WHERE salida = '00:00:00' AND idempleado=:id");
            }
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            return $consulta->execute();
        }
        public static function logueos($id=null){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            if(isset($id)){
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT idempleado as id, empleados.usuario as usuario, empleados.activo as activo, loginempleados.entrada as entrada, loginempleados.salida as salida FROM `loginempleados`, `empleados` 
                                                                  WHERE loginempleados.idempleado = empleados.id AND id = :id");
                $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            }
            else{
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT idempleado as id, empleados.usuario as usuario, empleados.activo as activo, loginempleados.entrada as entrada, loginempleados.salida as salida FROM `loginempleados`, `empleados` 
                                                                  WHERE loginempleados.idempleado = empleados.id");
            }
            $retorno['exito'] = $consulta->execute();
            if($retorno['exito'] && $consulta->rowCount() == 0){
                $retorno['exito'] = false;
                $retorno['mensaje'] = "No hay logueos";
            }
            return $consulta->fetchAll();
        }

        public  function operaciones($fecha){
            $consulta = $objetoAccesoDatos->retornarConsulta("SELECT * FROM operaciones WHERE dia = :fecha AND idempleado = :id");
            $consulta->bindValue(":fecha", $fecha, PDO::PARAM_STR);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchAll();
        }
    }
    
?>