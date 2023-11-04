<?php
    if (!isset($_SESSION['user'])) {
        header('Location: ../../index.php?sessionErr=true');
        die;
    }

    //CHECK ERRORES

    if (isset($_SESSION['errores'])) {
        echo "<dialog open>" . $_SESSION['errores'] . " <br>
        <p class='grayOut'>F5 para descartar</p></dialog>";
        unset($_SESSION['errores']);
    }