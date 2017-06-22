<?php

    interface IApiUsable {
        public  function buscar($request, $response, $args);
        public  function traerTodos($request, $response, $args);
        public  function alta($request, $response, $args);
        public  function borrar($request, $response, $args);
        public  function modificar($request, $response, $args);
    }
?>