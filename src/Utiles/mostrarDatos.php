<?php
    session_start();
    //Se recogerá el LÍMITE de ofertas a mostrar (rows a sacar de la BBDD)
    // y, con ello, se sacará un array con las Ofertas a mostrar
    require_once "conexionBD.php";

    if (!isset($_REQUEST['limit']) || !isset($_REQUEST['tabla'])) {
        die;
    }
    $numPagina = (int)$_REQUEST['numPag'];
    $limite = (int)$_REQUEST['limit'];
    $tabla = $_REQUEST['tabla'];

    $offset = $limite * ($numPagina - 1);
    echo getData($tabla, $limite);

    function getData($table, $limit): string {
        $conex = conexionBD::getConexion();

        if ($limit != 0 || $limit != '0') {
            $sql = "SELECT * FROM $table ORDER BY 1 LIMIT $limit";
        } else {
            $sql = "SELECT * FROM $table ORDER BY 1";
        }
        $datos = array();

        $query = $conex->query($sql);
        while ($arr = $query->fetch(PDO::FETCH_ASSOC)) {
            foreach ($arr as $key => $valor) {
                $datos[$key] = $valor;
            }
        }

        return json_encode($datos);
    }