<?php
    require_once("../Utiles/mySQL.php");

    $ofertas = select(CRUD::TABLA_OFERTAS);

    if ($ofertas != null) {
        echo json_encode($ofertas);
    } else {
        echo json_encode("-1");
    }