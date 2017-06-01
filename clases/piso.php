<?php
    include_once("lugar.php");
    include_once("accesoDatos.php");
    class piso 
    {
        public $numero;
        public $cantidad;
        function __construct($numero, $cantidad)
        {
            $this->numero = $numero;
            $this->cantidad = $cantidad;
        }
        public static function maximo($piso){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $objetoAccesoDatos->retornarConsulta("SELECT cantidad WHERE piso= :piso");
            $objetoAccesoDatos->execute();
            return $objetoAccesoDatos->fetchAll();
        }
    }
?>