    <?php

    include_once("accesoDatos.php");
    include_once("auto.php");
    class lugar 
    {
        private $numero;
        private $piso;
        private $patente;
        private $reservado; 
        

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
        public function getReservado(){
            return $this->reservado;
        }


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

        public static function agregar($numero, $piso, $patente){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $inLugar = lugar::buscarAuto($patente);
            $retorno = false;
            if(!$inLugar){
                $consulta = $objetoAccesoDatos->retornarConsulta("UPDATE lugares set patente = :patente WHERE numero = :numero");
                $consulta->bindValue(":numero", $numero, PDO::PARAM_INT);
                $consulta->bindValue(":patente",$patente, PDO::PARAM_STR);
                $retorno = $consulta->execute();
            }
            return $retorno;
        }
        public static function sacar($patente){
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("UPDATE lugares set patente = NULL WHERE patente = :vieja");
            $consulta->bindvalue(":vieja", $patente, PDO::PARAM_STR);
            return $consulta->execute();
        }

        public static function reservar($numero) {
            $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
            $consulta = $objetoAccesoDatos->retornarConsulta("UPDATE lugares set reservado = 1 WHERE numero = :numero ");
            $consulta->bindValue(":numero", $numero, PDO::PARAM_INT);
            return $consulta->execute();

        }
        
        public static function buscarAuto($patente){
             $objetoAccesoDatos = accesoDatos::DameUnObjetoAcceso();
             $consulta = $objetoAccesoDatos->retornarConsulta("SELECT * FROM lugares WHERE patente = :patente");
             $consulta->bindValue(":patente", $patente, PDO::PARAM_STR);
             $consulta->execute();
             $consulta->setFetchMode(PDO::FETCH_CLASS, 'lugar');
             $lugar = $consulta->fetch();
             return $lugar;
        }
        




        /*
        public function agregarAuto($auto){
            $retorno = false;
            if(isset($auto) && !$this->ocupado()){
                $this->auto = $auto;
                $retorno = true;
            }
            return $retorno;
        }
        public function ocupado(){
            if(isset($this->auto)){
                return true;
            }
        }
        public function getAuto(){
            return $this->auto;
        }
        public function sacarAuto(){
            unset($this->auto);
        }
        public function esta($patente){
            if($this->ocupado() && $this->auto->patente == $patente){
                return true;
            }
            return false;
        }

        */

    }
    
?>