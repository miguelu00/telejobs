<?php
    $tablasList = ["candidaturas", "categoria_emp", "chat_messages", "demandantes", "empresas", "habilidades", "notificaciones", "ofertas_trab", "recup_cuenta"];

    //Uso de POST para evitar cambios/acceso no autorizados
    if (in_array($_POST['tabla'], $tablasList)) {
        
    }

    if (isset($_POST['accion']) && isset($_POST['tabla'])) {
        switch ($_POST['accion']) {
            case "UPDATE":
                $return = update($_POST['tabla'], "", "");
                break;
            case "DELETE":
                $return = deleteFrom($_POST['tabla'], "");
        }
    } elseif (!isset($_POST['tabla'])) {
        echo json_encode([
            "response" => 400,
            "data" => "Request inválido"
        ]);
    }

    if (isset($_POST["tabla"])) {
        switch ($_POST['tabla']) {
            case "habilidades":
                $data = select("habilidades");
                break;
            case "empresas":
                $data = select("empresas");
                break;
        }
        if (is_null($_POST['tabla'])) {
            $return = [
                "response" => 400,
                "data" => "nothing requested"
            ];
            echo json_encode($return);
            die();
        }
        if (is_null($data)) {
            $return = [
                "response" => 404,
                "data" => null
            ];
        } else {
            $return = [
                "response" => 200,
                "data" => $data
            ];
        }    
        echo json_encode($return);
        die();
    }