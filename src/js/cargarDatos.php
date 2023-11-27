<?php
    session_start();
    require_once "../Utiles/mySQL.php";

    if (isset($_REQUEST['datos'])) {
        switch($_REQUEST['datos']) {
            case "usuarioSesion":
                echo json_encode($_SESSION['userData']);
                break;
            case "demandantes":
                //Buscar lista de demandantes RECIENTES (orderBY + Limit)
                $res = [];
                $res['results_1'] = select("demandantes", "*", null, "1");
                $res['results_2'] = select("demandantes", "*", "");

                break;
            case "ofertas":
                //Lista de ofertas de empleo con LIMIT
                if (isset($_REQUEST['startNum']) && isset($_REQUEST['idEMPRESA'])) {
                    $res = select("ofertas_trab", "*", "id_EMP=" . $_REQUEST['idEMPRESA'], "1", $_REQUEST['startNum']);
                } else {
                    $res = select("ofertas_trab", "*", "id_EMP=" . $_REQUEST['idEMPRESA'], "1");
                }
                echo json_encode($res);
                break;
            case "habilidades":
                $res = select("habilidades", "IDHabil, nombre");

                echo json_encode($res);
                break;
            case "actividades":
                $res = select("habilidades", "DISTINCT tipo");
                echo utf8_encode(json_encode($res));
                break;
            case "subirOferta":
                $result = insertInto("ofertas_trab", "");
            case "randOfertas":
                //Sacar tres ofertas random de la tabla de Ofertas de Trabajo; para el index con ajax
                $res = select("ofertas_trab", "*", null, "RAND()", "3");
                echo json_encode($res);
                break;
        }
    }
    if (isset($_REQUEST['accion'])) {
        switch($_REQUEST['accion']) {
            case "checkPW":
                $passwdInput = $_REQUEST['contra'];
                if ($_REQUEST['tipoUser'] == "demandantes") {
                    $passwdUser = select("demandantes", "passwd", "id_DEM LIKE " . $_SESSION['userData']['id_DEM']);
                } elseif ($_REQUEST['tipoUser'] == "empresas") {
                    $passwdUser = select("empresas", "passwd", "id_EMP LIKE " . $_SESSION['userData']['id_EMP']);
                }

                if (password_verify($passwdInput, $passwdUser)) {
                    if ($_REQUEST['tipoUser'] == "demandantes") {
                        $result = deleteFrom("demandantes", "id_DEM=" . $_SESSION['userData']['id_DEM']);
                    } elseif ($_REQUEST['tipoUser'] == "empresas") {
                        $result = deleteFrom("empresas", "id_EMP=" . $_SESSION['userData']['id_EMP']);
                    }
                    if ($result == 1) {
                        session_destroy();
                    }
                    echo json_encode($result);
                } else {
                    echo json_encode("0");
                }
                break;
        }
    }

    if (isset($_REQUEST["tabla"])) {
        $data = select("habilidades");
        if (is_null($data)) {
            $return = [
                "status" => "404",
                "data" => null
            ];
        } else {
            $return = [
                "status" => 
            ]
        }
        echo json_encode($return);
    }