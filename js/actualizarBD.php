<?php
    session_start();
    require_once "../Utiles/mySQL.php";

    $tabla = $_REQUEST['tabla'];
    $campo = $_REQUEST['editar'];
    $data = $_REQUEST['dato'];
    $email = $_REQUEST['email'];

    if ($tabla == "empresas") {
        $id = "id_EMP";
    } else {
        $id = "id_DEM";
    }
    if ($_REQUEST['accion'] == 'subirFotoTEMP') {
        $foto = $_FILES['editProfile']; $TRAYECTO = "../img/fotosUser/tmp";

        if (!is_dir($TRAYECTO)) {
            mkdir($TRAYECTO, 0777, true);
        }

        $files = scandir($TRAYECTO . "/"); //Borramos todos los ficheros en TMP
        unset($files[0], $files[1]);
        if (count($files) !== 0) {
            foreach ($files as $filename) {
                unlink($TRAYECTO . "/" . $filename);
            }
        }

        if (move_uploaded_file($foto['tmp_name'], $TRAYECTO . "/" . $foto['name'])) {
            echo "../" . $TRAYECTO . "/" . $foto['name'];
        } else {
            echo "0";
        }

        die();
    }
    if ($_REQUEST['accion'] == 'confirmarFoto') {
        $fotoTMP = $data;
        $tipoUser = tipoUsuario($_SESSION['userData']['email']);

        //Recogemos el ID del usuario para nombrar la imagen confirmada.
        if ($tipoUser == 3) {
            $idUsuario = $_SESSION['userData']['id_EMP'];
            $newName = "../img/fotosUser/userEMP_" . $idUsuario . substr($fotoTMP, -4);
            if (rename($fotoTMP, $newName)) {
                $res = update("empresas", "foto = 'userEMP_" . $idUsuario . substr($fotoTMP, -4) . "'", "id_EMP=" . $idUsuario);
            } else {
                echo json_encode("0");
            }
        } elseif ($tipoUser == 4) {
            $idUsuario = $_SESSION['userData']['id_DEM'];
            $newName = "../img/fotosUser/userDEM_" . $idUsuario . substr($fotoTMP, -4);
            if (rename($fotoTMP, $newName)) {
                $res = update("demandantes", "foto = 'userDEM_" . $idUsuario . substr($fotoTMP, -4) . "'", "id_DEM=" . $idUsuario);
            } else {
                echo json_encode("0");
            }
        }

        if ($res == 1) {
            echo json_encode("1");
        } else {
            echo json_encode("0");
        }
        die();
    }
    if (update($tabla, "" . $campo . "=" . "'" . $data . "'","email LIKE '" . $email . "'") == 1) {
        echo "1";
        $_SESSION['updateDONE'] = "1";
        //Setteamos aquí la variable para comprobar el UPDATE
    } else {
        echo "0";
    }

function filtrarFICHERO() {
    //función para filtrar los ficheros, si se tratan de archivos HTMLcv/JS/Otros maliciosos 'vestidos' de imagenes

}