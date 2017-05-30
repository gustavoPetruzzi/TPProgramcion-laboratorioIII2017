<?php
    include_once("accesoDatos.php");
    class empleado
    {
        
        public $id;
        public $usuario;
        private $_pass;
        public $activo;

        function __construct( $usuario = NULL, $pass = NULL)
        {
            if($usuario != NULL && $pass != NULL){
                $this->usuario = $usuario;
                $this->_pass = $pass;
            }
        }

        public function getPass(){
            return $this->_pass;
        }

        public  function modificarEmpleado(){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->RetornarConsulta("UPDATE empleados SET usuario =:usuario, pass=:pass, activo = :activo");
            $consulta->bindValue(":usuario", $this->usuario, PDO::PARAM_STR);
            $consulta->bindValue(":pass", $this->pass, PDO::PARAM_STR);
            $consulta->bindValue(":activo", $this->activo, PDO::PARAM_STR);
            return $consulta->execute();
        }

        public  function guardarEmpleado(){
            $objetoGuardarDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoGuardarDatos->RetornarConsulta("INSERT INTO empleados (usuario, pass)"
                                                             . " VALUES(:usuario, :pass)");
            $consulta->bindValue(":usuario", $this->usuario, PDO::PARAM_STR);
            $consulta->bindValue(":pass", $this->getPass(), PDO::PARAM_STR);
            return $consulta->execute();
        }

        public static function TraerEmpleados(){
            $listaEmpleados = array();
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("SELECT id as id, usuario as usuario, pass as _pass FROM empleados");
            $consulta->execute();
            $listaEmpleados= $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");
            return $listaEmpleados;
        }
        public static function buscarEmpleado($id){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("SELECT id as id, usuario as usuario, pass as _pass FROM empleados
                                                             WHERE id = :id");
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->setFetchMode(PDO::FETCH_CLASS, "empleado");
            $consulta->execute();
            $empleado = $consulta->fetch();
            return $empleado;
        }
        /*
        //PARA OBTENER ID despues de crearlo.
        private static function buscarEmpleadoUsuarioPass($user, $pass){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("SELECT id as id, usuario as usuario, pass as _pass FROM empleados
                                                             WHERE user = :user AND pass = :pass");
            $consulta->bindValue(":user", $user, PDO::PARAM_STR);
            $consulta->bindValue(":pass", $pass, PDO::PARAM_STR);
            $consulta->setFetchMode(PDO::FETCH_CLASS, "empleado");
            $consulta->execute();
            $empleado = $consulta->fetch();
            return $empleado;
        }
        */

        public static function borrarEmpleado($id){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("DELETE FROM empleados WHERE id= :id");
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            if($consulta->execute() > 0){
                return true;
            }
            else{
                return false;
            }
        }

        public static function registrarLogin($id, $sesion, $entrada = true){
            $objetoAccesoDatos =accesoDatos::DameUnObjetoAcceso();
            
            $fecha = date('H:i:s');
            if($entrada){
                $dia = date('Y-m-d');
                $consulta = $objetoAccesoDatos->retornarConsulta("INSERT INTO loginempleados (idempleado, dia, sesion, entrada) 
                                                              VALUES (:id, :dia, :sesion, :entrada) ");
                $consulta->bindValue(":entrada", $fecha, PDO::PARAM_STR);
                $consulta->bindValue(":dia", $dia, PDO::PARAM_STR);
                
            }
            else {
                $consulta = $objetoAccesoDatos->retornarConsulta("UPDATE loginempleados SET salida=:salida WHERE sesion = :sesion AND idempleado=:id");
                $consulta->bindValue(":salida",$fecha, PDO::PARAM_STR);
            }
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindValue(":sesion", $sesion, PDO::PARAM_INT);
            return $consulta->execute();
        }
    }
    
?>