<?php
    require_once 'empleado.php';
    class empleadoOperaciones extends empleado //implements Iserializable
    {
        public $dia;
        public $entrada;
        public $salida;
        function __construct($id =NULL)
        {
            if($id !== NULL){
                $empleado = parent::buscar($id);
                parent__construct($empleado->nombre, $empleado->apellido, $empleado->usuario, $empleado->pass, $empleado->getPass(), $empleado->activo, $empleado->admin);    
            }            
        }
        public function operaciones($desde, $hasta= NULL){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $desde = $desde."%";
            $listaEmpleados = array();
            if(!isset($hasta)){
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT idempleado as id, dia, entrada, salida FROM operaciones WHERE entrada LIKE :desde AND idempleado = :id");
            }
            else{   
                $hasta = $hasta."%";
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT idempleado as id, dia, entrada, salida FROM operaciones WHERE idempleado = :id AND  entrada  BETWEEN :desde AND  :hasta");
                $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
            }

            $consulta->bindValue(":desde", $desde, PDO::PARAM_STR);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            $listaEmpleados= $consulta->fetchAll(PDO::FETCH_CLASS, "empleadoOperaciones");
            return $listaEmpleados;
        }
    }
    
?>