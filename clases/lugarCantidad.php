<?php

    require_once('lugar.php');
    class lugarCantidad extends lugar 
    {
        public $cantidad;

        function __construct($numero = NULL, $cantidad = NULL)
        {
            if($numero != NULL && $cantidad !== NULL){
                $lugar = parent::buscar($numero);
                $this->cantidad = $cantidad;
                parent::__construct($lugar->numero, $lugar->patente, $lugar->piso, $lugar->reservado);
            }
        }
        public static function masUtilizados($desde, $hasta = NULL){
            $datos = lugarCantidad::cantidades(true, $desde, $hasta);
            if($datos){
                $numero = $datos[0]->numero;
                $maximo = $datos[0]->cantidad;
                $lugares = array_filter($datos,
                    function($lugar) use($maximo){
                        return $lugar->cantidad == $maximo;
                    });
                return $lugares;
            }
            return false;
        }

        public static function menosUtilizados($desde, $hasta = NULL){
            $datos = lugarCantidad::cantidades(false, $desde, $hasta);
            if($datos){
                $minimo = $datos[0]->cantidad;
                $lugares = array_filter($datos,
                    function($lugar) use($minimo){
                        return $lugar->cantidad == $minimo;
                    });
                return $lugares;
            }
            return false;
        }

        public static function cantidades($orden, $desde, $hasta = NULL){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $datos = array();
            $desde = $desde."%";
            if($orden){
                if(!isset($hasta)){
                    $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, COUNT(*) AS cantidad FROM operaciones WHERE entrada LIKE :desde GROUP BY cochera ORDER BY cantidad DESC, cochera ASC");
                }
                else{
                    $hasta = $hasta."%";
                    $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, COUNT(*) AS cantidad FROM operaciones WHERE entrada BETWEEN :desde AND :hasta GROUP BY cochera ORDER BY cantidad DESC, cochera ASC");
                    $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
                }
            }
            else{
                if(!isset($hasta)){
                    $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, COUNT(*) AS cantidad FROM operaciones WHERE entrada LIKE :desde GROUP BY cochera ORDER BY cantidad ASC, cochera ASC");
                }
                else{
                    $hasta = $hasta."%";
                    $consulta = $objetoAccesoDatos->retornarConsulta("SELECT lugar as cochera, COUNT(*) AS cantidad FROM operaciones WHERE entrada BETWEEN :desde AND :hasta GROUP BY cochera ORDER BY cantidad ASC, cochera ASC");
                    $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
                }
            }

            $consulta->bindValue(':desde', $desde,  PDO::PARAM_STR);
            $consulta->setFetchMode(PDO::FETCH_ASSOC);
            $consulta->execute();
            $datos = $consulta->fetchAll();
            if($consulta->rowCount() == 0){
                return false;
            }
            $lugares = array();
            
            foreach ($datos as $key ) {
                array_push($lugares, new lugarCantidad($key['cochera'], $key['cantidad']));
            }
            return $lugares;
        }

        
    }
    
?>