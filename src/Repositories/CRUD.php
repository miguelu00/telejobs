<?php

    interface CRUD {

        const TABLA_EMPRESA = "empresas";
        const TABLA_DEMANDANTES = "demandantes";
        
        public function insertar($nombreTabla, $campos, $valores);
        public function updatear($nombreTabla, $valores, $id);
        public function eliminar($nombreTabla, $id);
    }