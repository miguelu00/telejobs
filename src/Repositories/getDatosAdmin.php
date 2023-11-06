<?php
    include_once("../Utiles/mySQL.php");
    if (!isset($_POST['select'])) {
        die(-1);
    }

    if (!isset($_POST['id'])) {
        $id = "%";
    } else {
        $id = $_POST["id"];
    }

    switch ($_POST['select']) {
        case "empresas":
            $datosResult = select("empresas", '*', "id_EMP=" . $id);
            echo json_encode(array(
                "message" => "success",
                "data" => $datosResult
            ));
            break;
        case "demandantes":
            $datosResult = select("demandantes", '*', "id_DEM=" . $id);
            echo json_encode(array(
                "message" => "success",
                "data" => $datosResult
            ));
            break;
    }