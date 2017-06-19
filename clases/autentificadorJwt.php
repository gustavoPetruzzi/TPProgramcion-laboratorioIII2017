<?php
    require_once 'vendor/autoload.php';
    use Firebase\JWT\JWT;
    /**
     * 
     */
    class autentificadorJwt 
    {

        private static $claveSecreta = "una-clave-secreta";
        private static $algoritmo = "HS256";
        public static function crearToken($datos){
            $datos = $datos;
            
            $ahora = time();
            $payload = array(
                'iat'=> $ahora,
                'exp'=> $ahora + 30,
                'data'=>$datos,
                'app'=> 'apiRestJwt'
            );
            return JWT::encode($payload,self::$claveSecreta);
        }
        public static function verificarToken($token){
            try{
                $decodificado = JWT::decode($token, self::$claveSecreta, [self::$algoritmo]);
                return true;
            }
            catch(Exception $e){
                 return false;
            }

        }
        public static function extraerData($token){
            try{
                return JWT::decode($token, self::$claveSecreta, [self::$algoritmo] )->data;    
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
        }
    }
    
?>