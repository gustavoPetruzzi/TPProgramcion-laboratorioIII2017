<?php
    include_once("accesoDatos.php");
    class empleado
    {
        //public static $proximoId = 1;
        public $id;
        public $usuario;
        private $_pass;

        function __construct($usuario = NULL, $pass = NULL)
        {
            if($usuario != NULL && $pass != NULL){
                $this->usuario = $usuario;
                $this->_pass = $pass;
            }
        }

        public function getPass(){
            return $this->_pass;
        }

        private static function guardarEmpleado($empleado){
            $resultado = false;
            $objetoGuardarDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoGuardarDatos->RetornarConsulta("INSERT INTO empleados (usuario, pass)"
                                                             . " VALUES(:usuario, :pass)");
            $consulta->bindValue(":usuario", $empleado->usuario, PDO::PARAM_STR);
            $consulta->bindValue(":pass", $empleado->getPass(), PDO::PARAM_STR);
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