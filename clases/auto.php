<?php

    include_once("accesoDatos.php");
    class auto 
    {
        public $patente;
        public $color;
        public $marca;
        function __construct($patente=null, $color=null, $marca=null)
        {
            if($patente != null && $color != null && $marca!= null){
                $this->patente =$patente;
                $this->color = $color;
                $this->marca = $marca;
            }
        }

        public static function traerAutos(){
            $autos = array();
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsutal("SELECT * from autos");
            $consuta->execute();
            $autos = $consutal->fetchAll(PDO::FETCH_CLASS, "auto");
            return $autos;
        }
        public static function buscar($patente){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("SELECT * FROM autos WHERE patente = :patente");
            $consulta->bindValue(":patente", $patente, PDO::PARAM_STR);
            $consulta->execute();
            $auto = $consulta->fetchAll(PDO::FETCH_CLASS, "auto");
            return $auto;
        }
        public  function agregar(){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $inAuto = auto::buscar($this->patente);
            // VER QUE TRAE;
            if($inAuto == FALSE){
                $consulta = $objetoAccesoDatos->retornarConsulta("INSERT INTO autos (patente, color, marca) VALUES (:patente, :color, :marca)");
                $consulta->bindValue(":patente", $this->patente, PDO::PARAM_STR);
                $consulta->bindValue(":color", $this->color, PDO::PARAM_STR);
                $consulta->bindValue(":marca", $this->marca, PDO::PARAM_STR);
                return $consulta->execute();
            }
        }

        public static function sacar($patente){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("DELETE FROM autos WHERE patente = :patente");
            $consulta->bindValue(":patente", $patente, PDO::PARAM_STR);
            return $consulta->execute();
        }
        
    }
    
?>