<?php

    class CRUD_Int extends conexionBD {


        private const USERNAME = 'root';
        private const PASSWD = '4321';
        private const BDNAME = 'telejobs';
        static $_instancia;

        const TABLA_EMPRESA = "empresas";
        const TABLA_DEMANDANTES = "demandantes";
        const TABLA_HABILIDADES = "habilidades";
        const TABLA_OFERTAS = "ofertas_trab";
        const TABLA_RECUPERACION = "recup_cuenta";
        const SERVER_ERROR = 500;
        const BAD_GATEWAY = 502;
        const NOT_FOUND = 404;
        const FORBIDDEN = 403;
        const UNAUTHORIZED = 401;
        const BAD_REQUEST = 400;
        const SUCCESS_ALLDONE = 204;
        const SUCCESS_NOTDONE = 202;
        const SUCCESS_DONE = 201;
        const SUCCESS = 200;

        const TABLASLIST = ["candidaturas", "categoria_emp", "demandantes", "empresas", "habilidades", "ofertas_trab", "recup_cuenta"];
        /**
         * Estas tablas son las que tendrán los datos CRÍTICOS como CANDIDATURAS, EMPRESAS, OFERTAS_TRAB
         */
        const CRITICALTABLES = ["candidaturas", "empresas", "demandantes", "ofertas_trab"];
        
        public function __construct() {
            new PDO('mysql:host=localhost:3307;dbname=' . self::BDNAME, self::USERNAME, self::PASSWD);
        }
    
        public static function getConexion(): PDO {
            if (self::$_instancia == null) {
                try {
                    self::$_instancia = new PDO('mysql:host=localhost:3307;dbname=' . self::BDNAME, self::USERNAME, self::PASSWD);
                } catch (PDOException $ex) {
                    $_SESSION['status'] = 'ERROR PDO: <span>' .  $ex->getMessage() . '</span>';
                }
                return self::$_instancia;
            } else {
                return self::$_instancia;
            }
        }

        const CHECKUPDATEON = ["empresas", "demandantes", "ofertas_trab"];
    //Tablas en las que comprobaremos que se hizo UPDATE/Cambió algo
    //Para comprobar que esto se ha hecho, usar $_SESSION['updateDONE'];

/**
 * Hacer un SELECT en la tabla mySQL que indiquemos.
 * En el caso de especificar más de un campo, separarlos por comas EN EL MISMO
 * STRING. <br>
 * Para más información ir a <a>https://dev.mysql.com/doc/refman/8.0/en/select.html</a>
 *
 * @param string $nombreTabla - La tabla que indiquemos para consultar
 * @param string $campo - EL campo dentro de la tabla de la consulta
 * @param string $cond_WHERE - OPCIONAL condición WHERE SQL para los resultados
 * @param int $orderby - OPCIONAL Ordenar los datos según criterios mySQL
 * @param int $LIMIT - OPCIONAL Limitar el nº de registros que se devuelven, por defecto no se limitan.
 * @return array|null - Devuelve un <b>array</b> asociativo con los resultados de la CONSULTA SELECT (segun PDO), <b>NULL</b> si no encuentra nada
 *
 */
    static public function select($nombreTabla, $campo='*', $cond_WHERE=null, $orderby = 1, $LIMIT = null): array|null
    {
        $array = null;
        try {
            if ($cond_WHERE != null) {
                if ($LIMIT) {
                    $sql = "SELECT $campo FROM $nombreTabla WHERE $cond_WHERE ORDER BY $orderby LIMIT $LIMIT";
                } else {
                    $sql = "SELECT $campo FROM $nombreTabla WHERE $cond_WHERE ORDER BY $orderby";
                }
            } else {
                if ($LIMIT) {
                    $sql = "SELECT $campo FROM $nombreTabla ORDER BY $orderby LIMIT $LIMIT";
                } else {
                    $sql = "SELECT $campo FROM $nombreTabla ORDER BY $orderby";
                }
            }

            self::$_instancia = conexionBD::getConexion();
            if (self::$_instancia == null) {
                return null;
            }
            $stmt = self::$_instancia->query($sql);
            if ($stmt->rowCount() > 1) {
                //Array multifila / devolverá un array [0 => ['IDHabil' => X, 'id_EMP' => Y, 'puesto' => 'Analista Software', ...], 1 => ['IDHabil' => Z, 'id_EMP' => P, 'puesto' => 'Arquitecto', ...], ...]
                $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    foreach ($res as $clave => $valor) {
                        $array[$clave] = $valor;
                    }
                }
            }


        } catch (Exception $e) {
            
        }
        return $array;
    }

    /** 
     * Comprobar si la clave de API pasada por parámetro se encuentra en la lista
     * y coincide con alguna guardada en la Base de Datos.
     */
    static public function api_existe($clave_api): bool {
        return self::select("api_keys", "*", "api_key = '" . $clave_api . "'") != null;
    }

/**
 * Insertar en la Tabla que indiquemos, introducir tantos argumentos como campos en la tabla haya para insertar
 *
 * @param string $nombreTabla - El nombre de la tabla en que queremos hacer INSERT
 * @param null|int ...$datos - Cuantos datos como queramos insertar; uno por argumento y en el orden de los campos en la tabla
 * @return int - Devuelve 1 si hace el INSERT correctamente; 0 si no
 *
 */
    static function insertInto($nombreTabla, ...$datos): int {
        try {
            $strDatos = "";
            foreach ($datos as $dato) {
                $strDatos .= "'" . $dato . "',"; //String con todos los datos, concatenados separando por coma
            }
            if (!empty($strDatos)) {
                $strDatos = substr($strDatos, 0, strlen($strDatos)-1); //quitar la coma que queda al final
            }
            $sql = "INSERT INTO $nombreTabla VALUES($strDatos)";
            self::$_instancia = conexionBD::getConexion();

            $stmt = self::$_instancia->prepare($sql);
            if ($stmt->execute()) {
                return 1;
            }
            return 0;
        } catch (Exception $e) {

        }
        return 0;
    }

    /*
    function insertINTO2(string $nombreTabla, string ...$datos): int {
        try {
            $strColumns = "";
            $strDatos = "";
            $sqlString = "INSERT INTO " + $nombreTabla + " (";

            foreach ($columnas as $col) {
                $sqlString .= $col . ",";
            }

            $sqlString = substr($sqlString, 0, strlen($sqlString)-1); //quitar la coma al final
            $sqlString .= ")";    //añadir el paréntesis, y el resto de String SQL
            $sqlString .= " VALUES (";

            foreach ($datos as $dato) {
                $sqlString .= "'" . $dato . "', ";
            }

            $sqlString = substr($sqlString, 0, strlen($sqlString)-2);
            $sqlString .= ")";

            self::$_instancia = conexionBD::getConexion();
            $stmt = self::$_instancia->prepare($sqlString);

            if ($stmt->execute()) {
                return 1;
            }
            return 0;
        } catch (Exception $e) {
            return -1; //se ha producido un error.
        }
    }
    */

/**
 * Orden DELETE FROM para una tabla, POR DEFECTO se borra la primera fila.
 *
 * @param $nombreTabla - Nombre de la tabla en que queremos hacer DELETE
 * @param $where - Condición WHERE del DELETE (por defecto, 'ROWNUM=1')
 * @return int - Devuelve 1 si hace el DELETE correctamente; 0 si no
 *
 */
    public function deleteFrom($nombreTabla, $where = 'ROWNUM=1', $ROWNUM = null): int {
        try {
            if ($where != 'ROWNUM=1') {
                $sql = "DELETE FROM $nombreTabla WHERE $where";
            } else {
                if ($ROWNUM != null) {
                    $sql = "DELETE FROM $nombreTabla WHERE ROWNUM=$ROWNUM";
                } else {
                    $sql = "DELETE FROM $nombreTabla WHERE ROWNUM=1";
                }
            }

            self::$_instancia = conexionBD::getConexion();
            $stmt = self::$_instancia->prepare($sql);
            if ($stmt->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {

        }
        return 0;
    }

/**
 * @param $nombreTabla - Nombre de la tabla en la que actualizar los valores
 * @param $SET - [Indicador SET] Campos que cambiar, y sus valores; [ "CAMPO1= 'X', CAMPO2= 55, ..."]
 * @param $WHERE - [Indicador WHERE] condiciones a seguir al aplicar el SET; recuerde que los VARCHAR deberán ir entre comillas dobles/simples
 * @return int - Devuelve <b>1</b> si se consigue actualizar; <b>0</b> si se falla
 */
    public function update(string $nombreTabla, string $SET, ?string $WHERE): int {
        try {
            if ($WHERE != null) {
                $sql = "UPDATE $nombreTabla SET {$SET} WHERE {$WHERE}";
            } else {
                $sql = "UPDATE $nombreTabla SET {$SET}";
            }

            self::$_instancia = conexionBD::getConexion();
            $stmt = self::$_instancia->prepare($sql);

            if ($stmt->execute()) {
                if (in_array($nombreTabla, CHECKUPDATEON)) {
                    $_SESSION['updateDONE'] = true; // Para cuando hacemos un UPDATE en las tablas de Usuarios
                }
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {

        }
        return 0;
    }

/**
 * -Sacará un SELECT de las tablas que indiquemos en el array ($tables); y después comprobará si el
 *  string ($email) que hemos indicado coincide con el campo que hayamos creado.
 *
 * [MANEJO DE PRIVILEGIOS] => Adicionalmente, seteará una variable de sesión con el nombre de la tabla en la que ha encontrado el correo.
 * @param string $correo - Correo a chequear en el campo "email" de la/s tabla/s especificada/s
 * @param array $tables - Tablas a especificar, en un array de Strings | array("tabla1", "tabla3"[, ...])
 * @return bool - Devolverá <b>true</b> si algún campo "email" de las tablas contiene el email indicado
 */
    public function checkRegister(string $correo, array $tables) {
        $data = []; $cont = 0;

        foreach ($tables as $table) {
            $data[] = select($table, "email");
            if ($data) {
                if ($data[$cont]['email'] == $correo) {
                    $_SESSION['tipoReg'] = substr($table, 0, strlen($table)-1); //[substr opcional], quita la ultima letra
                    return true;
                }
            }
        }
        return false;
    }

/**
 * Simple refactorización. Comprueba que esté vacía una tabla, según tenga un correo o no.
 * @param $tabla
 * @param $correo
 * @return bool - <b>True</b> si está vacía. <b>False</b> si existen registros con ese Email.
 */
    public function comprobarVacio($tabla, $correo) {
        return empty(select($tabla, 'email', 'email LIKE "' . $correo . '"'));
    }

/**
 * @param $email
 * @param $tabla
 * @return string|null - 0 ó 1 Dependiendo si está confirmado el usuario o no
 */
    public function checkConfirm($email, $tabla) {
        return select($tabla, "confirm", "email LIKE '" . $email . "'")['confirm'];
    }

/** Como sólo puede existir un email registrado por cada tipo de usuario, esta función indicará según este dato, si la cuenta es EMPRESA ó DEMANDANTE
 * @param $correo - Correo a comprobar
 * @return int - Devolverá <b>3</b> si es EMPRESA; <b>4</b> para DEMANDANTE
 */
    public function tipoUsuario($correo) {
        if (!comprobarVacio("empresas", $correo)) {
            return 3;
        }
        if (!comprobarVacio("demandantes", $correo)) {
            return 4;
        }
    }

public function redireccionarUser()
{
    if (tipoUsuario($_SESSION['user']) == 3) {
        header("Location: index/empresa/");
    } elseif (tipoUsuario($_SESSION['user']) == 4) {
        header("Location: index/demandantes/");
    }
}
    }