<?php

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
                "status" => 405,
                "data" => "nothing requested"
            ];
            echo json_encode($return);
            die();
        }
        if (is_null($data)) {
            $return = [
                "status" => "404",
                "data" => null
            ];
        } else {
            $return = [
                "status" => "200",
                "data" => $data
            ];
        }    
        echo json_encode($return);
    }