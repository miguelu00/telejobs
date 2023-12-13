<?php
//creador contraseñas hash

    $contra = $_GET['contra'];
    $hash = password_hash($contra, PASSWORD_DEFAULT);

    echo $hash;
?>