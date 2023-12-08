<?php
    if (!isset($_SESSION['user'])) {
        header('Location: ../../index.php?sessionErr=true');
        die;
    }

    //Check tipo de sesión [EMPRESA/DEMANDANTE]
    if (isset($_SESSION['userData'])) {
        if (array_key_exists("id_EMP", $_SESSION['userData'])) {
            $_SESSION['tipoUser'] = "E";
            
        }
        if (array_key_exists("id_DEM", $_SESSION['userData'])) {
            $_SESSION['tipoUser'] = "D";
        }
    }

    //CHECK ERRORES
    if (isset($_SESSION['errores'])) {
        echo "<dialog open>" . $_SESSION['errores'] . " <br>
        <p class='grayOut'>F5 para descartar</p></dialog>";
        unset($_SESSION['errores']);
    }