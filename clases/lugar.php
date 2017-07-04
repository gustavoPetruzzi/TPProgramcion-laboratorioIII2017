<?php

    include_once("accesoDatos.php");
    include_once("auto.php");
    class lugar 
    {
        public $numero;
        public $piso;
        public $patente;
        public $reservado; 
        

        function __construct($numero = NULL,$patente = NULL, $piso = NULL, $reservado=false)
        {
            if($piso != NULL && $reservado != NULL){
                $this->patente = $patente;
                $this->numero = $numero;
                $this->piso = $piso;
                $this->reservado = $reservado;
            }
        }
                                     /* GETTERS */
        public function getNumero(){
            return $this->numero;
        }
        public function getPiso(){
            return $this->piso;
        }
        
        public function getPatente(){
            return $this->patente;
        }
        public function setPatente($patente){
            $this->patente = $patente;
        }
        public function getReservado(){
            return $this->reservado;
        }
        /*
        SELECT lugares.numero as numero, lugares.piso as piso , operaciones.patente as patente, lugares.reservado as reservado  
        FROM `lugares`, operaciones WHERE lugares.numero = operaciones.lugar AND operaciones.salida = null
        */
        public static function traerLugares($piso=null){
            $lugares =array();
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            if(isset($piso)){
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT * FROM lugares WHERE piso = :piso");
                $consulta->bindValue(":piso", $piso,PDO::PARAM_INT);
            }
            else{
                $consulta = $objetoAccesoDatos->retornarConsulta("SELECT * FROM lugares");
            }
            $consulta->execute();
            $lugares = $consulta->fetchAll(PDO::FETCH_CLASS, "lugar");

            return $lugares;
        }
        public static function buscar($numero){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("SELECT * FROM lugares WHERE numero = :numero");
            $consulta->bindValue(":numero", $numero, PDO::PARAM_INT);
            $consulta->setFetchMode(PDO::FETCH_CLASS, 'lugar');
            $consulta->execute();
            return $consulta->fetch();
        }


        public static function reservar($numero) {
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("UPDATE lugares set reservado = 1 WHERE numero = :numero ");
            $consulta->bindValue(":numero", $numero, PDO::PARAM_INT);
            return $consulta->execute();

        }
        


    }
    
?>