<?php
    /**
     * 
     */
    class verificar
    {
        public static function verificarDatosUsuarios($request, $response, $next){
            if($request->isPost()){
                $data = $request->getParsedBody();
                if( isset($data['usuario']) && isset($data['pass'])){
                    $usuario = filter_var($data['usuario'], FILTER_SANITIZE_STRING);
                    $pass = filter_var($data['pass'], FILTER_SANITIZE_STRING);    
                    if($usuario && $pass){
                        $request->setAttribute('usuario', $usuario);
                        $request->setAttribute('pass');
                        
                        $response = $next($request, $response);
                        $response->withJson()        
                    }
                } 
            }
            else{

            }
        }
    }
    
?>