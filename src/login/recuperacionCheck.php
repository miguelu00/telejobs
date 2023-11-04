<?php
    session_start();
    require_once "../Utiles/mySQL.php";

    if (strip_tags($_REQUEST['accion']) == 'checkEmail') {
        $email = strip_tags($_REQUEST['correo']);

        if (!comprobarVacio("empresas", $email)) {
            $tipo = "empresas";
        }
        if (!comprobarVacio("demandantes", $email)) {
            $tipo = "demandantes";
        }

        if (isset($tipo)) {
            //Existe el correo en la BBDD, enviamos confirmación y request a la BBDD
            $datos = select($tipo, "*", "email LIKE '" . $email .  "'");
            if (array_key_exists('id_EMP', $datos)) {
                $idUser = $datos['id_EMP'];
            } elseif (array_key_exists('id_DEM', $datos)) {
                $idUser = $datos['id_DEM'];
            }
            if ($datos['confirm'] == 1) {
                //Puede abortar/eliminar el registro, ya que no ha confirmado su cuenta aún
                $confirmado = false;
            } else {
                $confirmado = true;
            }
            $token = password_hash($email, PASSWORD_ARGON2_DEFAULT_THREADS);
            if ($tipo == "empresas") {
                insertInto("recup_cuenta", null, $idUser, null, $token);
            } else {
                insertInto("recup_cuenta", null, null, $idUser, $token);
            }
            if (!$confirmado) {
                header("Location: ../Utiles/enviarMailRECUP.php?mail=$email&token=$token&noConfirm=true");
            } else {
                header("Location: ../Utiles/enviarMailRECUP.php?mail=$email&token=$token");
            }
            echo "1";
            die();
        }

        echo "0"; //NO SE HA ENCONTRADO EL EMAIL EN LA BBDD

    }

    if ($_REQUEST['accion'] == 'cambiaContra') {
        $email = strip_tags($_REQUEST['correo']);
        $contraNew = strip_tags($_REQUEST['contra']);
        $contraNew = password_hash($contraNew, PASSWORD_DEFAULT);

        $tipoUser = tipoUsuario($email);
        $tipoUser = ($tipoUser == 3) ? "empresas" : "demandantes";
        echo json_encode(update($tipoUser, "passwd = '" . $contraNew . "'", "email LIKE '" . $email . "'"));

    }