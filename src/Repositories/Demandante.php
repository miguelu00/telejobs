<?php

require "../Utiles/mySQL.php";
class Demandante implements CRUD {
    private $skill_ids;
    private $experiencia;
	private $nombre;
	private $apellidos;
    private $fechaNac;
    private $tlf;
    private $codPostal;
    private $municipio;
    private $email;
    private $password;
    private $fotoPerfil;
    private $NIF;
    private $cv_visible;
    private $confirmado;
    private $fecha_inscripcion;

    
	public function __construct($skill_ids, $experiencia, $nombre, $apellidos, $fechaNac, $tlf, $codPostal, $municipio, $email, $password, $fotoPerfil, $NIF, $cv_visible, $confirmado, $fecha_inscripcion) {
        $this->skill_ids = $skill_ids;
        $this->experiencia = $experiencia;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->fechaNac = $fechaNac;
        $this->tlf = $tlf;
        $this->codPostal = $codPostal;
        $this->municipio = $municipio;
        $this->email = $email;
        $this->password = $password;
        $this->fotoPerfil = $fotoPerfil;
        $this->NIF = $NIF;
        $this->cv_visible = $cv_visible;
        $this->confirmado = $confirmado;
        $this->fecha_inscripcion = $fecha_inscripcion;
    }

    //+ métodos para inscribir / sacar datos / borrar / ver cuanto tiempo lleva logeado...
	public function insertar($nombreTabla, $campos, $valores): int {
		return insertInto2($nombreTabla, $campos, $valores);
	}

	public function updatear($nombreTabla, $valores, $id): int {
		return update($nombreTabla, $valores, "id_DEM = " + $id);
	}

	public function eliminar( $nombreTabla, $id): int {
		return deleteFrom($nombreTabla, "id_DEM = " + $id);
	}


	public function getNombre(){
		return $this->nombre;
	}

	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

	public function getApellidos(){
		return $this->apellidos;
	}

	public function setApellidos($apellidos){
		$this->apellidos = $apellidos;
	}

	public function getSkill_ids(){
		return $this->skill_ids;
	}

	public function setSkill_ids($skill_ids){
		$this->skill_ids = $skill_ids;
	}

	public function getExperiencia(){
		return $this->experiencia;
	}

	public function setExperiencia($experiencia){
		$this->experiencia = $experiencia;
	}

	public function getFechaNac(){
		return $this->fechaNac;
	}

	public function setFechaNac($fechaNac){
		$this->fechaNac = $fechaNac;
	}

	public function getTlf(){
		return $this->tlf;
	}

	public function setTlf($tlf){
		$this->tlf = $tlf;
	}

	public function getCodPostal(){
		return $this->codPostal;
	}

	public function setCodPostal($codPostal){
		$this->codPostal = $codPostal;
	}

	public function getMunicipio(){
		return $this->municipio;
	}

	public function setMunicipio($municipio){
		$this->municipio = $municipio;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setPassword($password){
		$this->password = $password;
	}

	public function getFotoPerfil(){
		return $this->fotoPerfil;
	}

	public function setFotoPerfil($fotoPerfil){
		$this->fotoPerfil = $fotoPerfil;
	}

	public function getNIF(){
		return $this->NIF;
	}

	public function setNIF($NIF){
		$this->NIF = $NIF;
	}

	public function getCv_visible(){
		return $this->cv_visible;
	}

	public function setCv_visible($cv_visible){
		$this->cv_visible = $cv_visible;
	}

	public function getConfirmado(){
		return $this->confirmado;
	}

	public function setConfirmado($confirmado){
		$this->confirmado = $confirmado;
	}

	public function getFecha_inscripcion(){
		return $this->fecha_inscripcion;
	}

	public function setFecha_inscripcion($fecha_inscripcion){
		$this->fecha_inscripcion = $fecha_inscripcion;
	}
}