<?php

/**
 * CLASE conexionBD
 *
 *  Se emplea para conectar a la base de datos; utiliza el método 'Singleton',
 *  que asegura que tan sólo haya UNA conexión a la BBDD por llamada.
 */
class conexionBD {
    //Constantes BBDD; cambiar segun el entorno
    private const USERNAME = 'root';
    private const PASSWD = '4321';
    private const BDNAME = 'telejobs';
    private static $_instancia;

    private function __construct() {
        return new PDO('mysql:host=localhost:3307;dbname=' . conexionBD::BDNAME, conexionBD::USERNAME, conexionBD::PASSWD);
    }

    public static function getConexion() {
        if (self::$_instancia == null) {
            try {
                self::$_instancia = new PDO('mysql:host=localhost:3307;dbname=' . conexionBD::BDNAME, conexionBD::USERNAME, conexionBD::PASSWD);
            } catch (PDOException $ex) {
                $_SESSION['status'] = 'ERROR PDO: <span>' .  $ex->getMessage() . '</span>';
            }
            return self::$_instancia;
        } else {
            return self::$_instancia;
        }
    }

    public function __clone() {
        //Dejando este método mágico vacío, evitamos que se use la función
        //  clone() sobre instancias de este objeto
    }
    //Ya a partir de haber creado una instancia con esta clase,
    // podremos usar los métodos de los objetos PDO

    public static function hashPW($pass) {
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        return $pass;
    }
}