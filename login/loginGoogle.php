<?php
session_start();
require_once "../Utiles/mySQL.php";
$user = $_SESSION['login_email'];

$passwd = $_SESSION['ucode'];
    if ($_SESSION['tipoReg'] == "empresa") {
        if (checkConfirm($user,"empresas") == 1) {
            update("empresas", "confirm = '3'", "email LIKE '" . $user . "'");
        }
        ingresar(0, $user, $passwd);
    }
    if ($_SESSION['tipoReg'] == "demandante") {
        ingresar(1, $user, $passwd);
    }

function ingresar(int $tipo, string $usuario, string $contra) {
    switch ($tipo) {
        case 0:
            $datosUser = select('demandantes', '*', 'email LIKE "' . $usuario . '"');
            if (!isset($datosUser)) {
                lanzarError();
            }
            if (count($datosUser) > 0 && password_verify($contra, $datosUser['passwd'])) {
                //Ingresado correctamente!
                $_SESSION['user'] = $datosUser['email'];
                header('Location: ../index/demandantes/index.php');
            } else {
                //ERROR al ingresar. Compruebe que ha introducido bien su email--contraseña
                echo "<br><br>Compruebe que ha introducido bien su email--contraseña";
            }
            break;
        case 1:
            $datosUser = select('empresas', '*', 'email LIKE "' . $usuario . '"');
            if (!isset($datosUser)) {
                lanzarError();
            }
            if (count($datosUser) > 0 && password_verify($contra, $datosUser['passwd'])) {
                //Ingresado correctamente!
                $_SESSION['user'] = $datosUser['email'];
                header('Location: ../index/empresa/index.php');
            } else {
                //ERROR al ingresar. Compruebe que ha introducido bien su email--contraseña
                echo "<br><br>Compruebe que ha introducido bien su email--contraseña";
            }
            break;
    }
}

function lanzarError() {
    $_SESSION['errores'] = "Ha ocurrido un error ingresando con GOOGLE. <br> Inténtelo de nuevo más tarde, o póngase en contacto con el <a href='mailto:mafvpersonal@gmail.com'>administrador de la Web</a>";
}