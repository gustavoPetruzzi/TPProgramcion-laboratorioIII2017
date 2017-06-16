<?php
    require_once 'vendor/autoload.php';
    use Firebase\JWT\JWT;
    /**
     * 
     */
    class autentificadorJwt 
    {

        private static $claveSecreta = "una-clave-secreta";
        private static $alg = "HS256";
        public static function crearToken($datos){
            $datos = $datos;
            
            $ahora = time();
            $payload = array(
                'iat'=> $ahora,
                'exp'=> $ahora + 60 *30,
                'data'=>$datos,
                'app'=> 'apiRestJwt'
            );
            return JWT::encode($payload,self::$claveSecreta);
        }
        public static function verificarToken($token){
            try{
                $decodificado = JWT::decode($token, self::$claveSecreta, [self::$algo]);
            }
            catch(Exception $e){
                echo $e;
            }

        }
        public static function verificarTokenViejo($token){

        }
    }
    
?>