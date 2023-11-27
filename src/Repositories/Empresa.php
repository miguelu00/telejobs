<?php
    require_once "../Utiles/mySQL.php";
    class Empresa implements CRUD {

        private $id_EMP, $email, $fotoPerfil, 
        $nomEmpresa, $nomPropio,  $apellidosPropio, $actividadEmp;

        public function insertar($nombreTabla, $campos, $valores): int {
            return insertInto($nombreTabla, $valores);
        }
        public function updatear($nombreTabla, $campos, $id): int {
            return update($nombreTabla, $campos, "ID = " . $id);
        }

        public function selectear(int $id) {
            
        }

        public function eliminar($nombreTabla, $id): int {
            return deleteFrom($nombreTabla, "ID=" . $id);
        }
        
        public function setIdEMP($id_EMP) {
            $this->id_EMP =$id_EMP;
        }
        public function getIdEMP() {
            return $this->id_EMP;
        }
        public function setEmail($email) {
            $this->email =$email;
        }
        public function getEmail() {
            return $this->email;
        }
        public function setFotoPerfil($fotoPerfil) {
            $this->fotoPerfil =$fotoPerfil;
        }
        public function getFotoPerfil() {
            return $this->fotoPerfil;
        }
        public function setNomEmpresa($nomEmpresa) {
            $this->nomEmpresa =$nomEmpresa;
        }
        public function getNomEmpresa() {
            return $this->nomEmpresa;
        }
        public function setNomPropio($nomPropio) {
            $this->nomPropio =$nomPropio;
        }
        public function getNomPropio() {
            return $this->nomPropio;
        }
        public function setApellidosPropio($apellidosPropio) {
            $this->apellidosPropio =$apellidosPropio;
        }
        public function getApellidosPropio() {
            return $this->apellidosPropio;
        }
        public function setActividadEmp($actividadEmp) {
            $this->actividadEmp =$actividadEmp;
        }
        public function getActividadEmp() {
            return $this->actividadEmp;
        }
    }