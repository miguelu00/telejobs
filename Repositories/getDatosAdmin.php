<?php
    include_once("../Utiles/mySQL.php");
    if (!isset($_POST['select'])) {
        die(-1);
    }

    switch ($_POST['select']) {
        case "empresas":
            $htmlString = mostrarDatosTabla(select("empresas"));
            echo json_encode(array(
                "message" => "success",
                "data" => $htmlString
            ));
            break;
        case "demandantes":
            $htmlString = mostrarDatosTabla(select("demandantes"));
            echo json_encode(array(
                "message" => "success",
                "data" => $htmlString
            ));
            break;
    }

    function mostrarDatosTabla($datos) {
        $html = "";
        foreach ($datos as $fila) {
            $html .= "<tr>";
            foreach ($fila as $key => $value) {
                $html .= "<td>" . $value . "</td>";
            }
            $html .= "</tr>";
        }

        return $html;
    }