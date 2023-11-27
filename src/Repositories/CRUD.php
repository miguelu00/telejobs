<?php

    interface CRUD {

        const TABLA_EMPRESA = "empresas";
        const TABLA_DEMANDANTES = "demandantes";
        const TABLA_HABILIDADES = "habilidades";
        const TABLA_OFERTAS = "ofertas_trab";
        const TABLA_RECUPERACION = "recup_cuenta";
        const NOT_FOUND = "404";
        const FORBIDDEN = "403";
        const SUCCESS = "200";

        /**
        * Métodos para implementar funciones CRUD en las clases DEMANDANTE y EMPRESA
        * 
        * En todos ellos, recibiremos como resultado 0 (error) ó 1 (correcto)
        */
        public function insertar($nombreTabla, $campos, $valores): int;
        public function updatear($nombreTabla, $valores, $id): int;
        public function eliminar($nombreTabla, $id): int;
    }