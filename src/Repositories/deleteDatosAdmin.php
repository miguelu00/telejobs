<?php
    include_once("../Utiles/mySQL.php");
    //Script creado ya que no era capaz de eliminar usando el verbo DELETE en la
    // API principal de Telejobs

    if (!isset($_REQUEST['tabla']) || !isset($_REQUEST['WHERE'])) {
        devolverCodigoHTTP(CRUD_Int::BAD_REQUEST);
        exit();
    } else {
        $borrado = deleteFrom($_REQUEST['tabla'], $_REQUEST['WHERE']);
        if ($borrado == 1) {
            devolverCodigoHTTP(CRUD_Int::SUCCESS_ALLDONE);
            exit();
        } else {
            devolverCodigoHTTP(CRUD_Int::SUCCESS_NOTDONE);
            exit();
        }
    }

    function devolverCodigoHTTP($codigo) {
        header('X-PHP-Response-Code: ' . $codigo, true, $codigo);
    }