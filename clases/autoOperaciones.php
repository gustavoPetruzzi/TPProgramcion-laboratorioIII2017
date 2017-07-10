<?php
    require_once 'auto.php';
    class autoOperaciones extends auto 
    {
        public $operaciones = array();
        function __construct($patente = NULL){
            if($patente != NULL){
                $auto = parent::buscar($patente);
                if(!$auto){
                    throw new Exception('Auto no encontrado');
                }
                parent::__construct($auto->patente, $auto->color, $auto->marca);
            }
        }
        /**
         * Trae los autos que estan o estuvieron estacionados en la fecha pasado por parametro o entre las fechas pasados por parametro.
         *
         * @param [type] $desde 
         * @param [type] $hasta
         * @return void
         */
        public function traerOperaciones($desde, $hasta = NULL){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            
            $desde = $desde."%";
            if(!isset($hasta)){
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, entrada, salida, precio FROM `operaciones` 
                                                                 WHERE patente = :patente AND (entrada LIKE :desde OR salida = '0000-00-00 00:00:00')");            
            }
            else{
                $hasta = $hasta."%";
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, entrada, salida, precio FROM `operaciones` 
                                                                 WHERE patente = :patente AND (entrada BETWEEN :desde AND :hasta OR salida = '0000-00-00 00:00:00')");
                $consulta->bindValue(":hasta", $hasta, PDO::PARAM_STR);
            }

            $consulta->bindValue(":desde", $desde, PDO::PARAM_STR);
            $consulta->bindValue(":patente", $this->patente, PDO::PARAM_STR);
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