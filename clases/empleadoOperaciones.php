<?php
    require_once 'empleado.php';
    class empleadoOperaciones extends empleado //implements Iserializable
    {
        public $operaciones = array();
        function __construct($id =NULL)
        {
            if($id !== NULL){
                $empleado = parent::buscarEmpleado($id);
                if(!$empleado){
                throw new Exception('Empleado no encontrado');
                }
                parent::__construct($empleado->nombre, $empleado->apellido, $empleado->usuario, $empleado->getPass(), $empleado->getPass(), $empleado->activo, $empleado->admin);
                $this->id = $id;
            }            
        }
        public function traerOperaciones($desde, $hasta= NULL){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $desde = $desde."%";
            
            if(!isset($hasta)){
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT  entrada, salida, precio FROM operaciones WHERE entrada LIKE :desde AND idempleado = :id");
            }
            else{   
                $hasta = $hasta."%";
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT  entrada, salida, precio FROM operaciones WHERE idempleado = :id AND  entrada  BETWEEN :desde AND  :hasta");
                $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
            }

            $consulta->bindValue(":desde", $desde, PDO::PARAM_STR);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            $consulta->setFetchMode(PDO::FETCH_ASSOC);
            $consulta->execute();
            
            if($consulta->rowCount() == 0){
                return false;
            }
            else{
                $this->operaciones = $consulta->fetchAll();
                return true;
            }
        }
    }
    
?>