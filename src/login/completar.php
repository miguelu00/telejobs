<?php
//unicamente, setteamos la variable que indica el registro parcial, y así podemos 

    $_COOKIE['reg_parcial'] = "true";
    $_COOKIE['tipoUser'] = $_REQUEST['tipUser'];
    header('Location: ../registro/index.php');