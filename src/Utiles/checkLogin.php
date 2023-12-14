<?php
require_once "../Utiles/mySQL.php";
/**
 * COMPROBACIÓN DATOS PARA LOGIN, DEVUELVE UN ARRAY CON:
 *  1 . ['tipoUser'] => 3 para EMPRESA, 4 para DEMANDANTE
 *  2 . ['loginCorrecto'] => "1" CORRECTO // "0" CONTRASEÑA INCORRECTA
 */

$email = strip_tags($_REQUEST['correo']);
$password = strip_tags($_REQUEST['passwd']);
$errors = [];

//EVITAMOS errores si la cuenta simplemente no existe en la BBDD
if (comprobarVacio("empresas", $email) && comprobarVacio("demandantes", $email)) {
    $errors["noExiste"] = 1;
    echo json_encode($errors);
    die();
}

    $tipoUsuario = tipoUsuario($email);
    $errors["tipoUser"] = $tipoUsuario;

    if ($tipoUsuario == 3)
        $contraCorrecta = password_verify($password, select("empresas", "passwd", "email LIKE '" . $email . "'")['passwd']);

    if ($tipoUsuario == 4)
        $contraCorrecta = password_verify($password, select("demandantes", "passwd", "email LIKE '" . $email . "'")['passwd']);

    if ($tipoUsuario == 1) {
        redireccionarUser();
    }
    if (isset($contraCorrecta)) {
        $contraCorrecta ? $errors['loginCorrecto']="1" : $errors['loginCorrecto']="0";
    }

    echo json_encode($errors);