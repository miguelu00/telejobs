<?php
session_start();
require_once "../Utiles/mySQL.php";

$password = strip_tags($_REQUEST['password']);

if (!isset($_REQUEST['correo'])) {
    $correo = $_SESSION['login_email']; //En caso de que logeemos con GOOGLE, usaremos la var. en sesión
} else {
    $correo = strip_tags($_REQUEST['correo']);
}

$tipo = strip_tags($_REQUEST['tipoReg']);

//Primero, comprobaremos el email
if (!comprobarEmail($correo)) {
    echo "3"; //email YA EN USO
    die();
} else {
    if (preg_match("/^(?=.*[A-Z])(?=.*\d).+$/", $password)) {
        echo "1"; die(); //todo OK
    } else {
        echo "2"; die(); //contraseña BAD
    }
}

function comprobarEmail($correo): bool
{
    if (isset($_SESSION['tipoReg'])) {
        $tipo = $_SESSION['tipoReg']; //Variable tipo declarada aquí, para poder usar la función con REGISTRO GOOGLE
    } else {
        $tipo = strip_tags($_REQUEST['tipoReg']);
    }
    if (comprobarVacio('empresas', $correo) && comprobarVacio('demandantes', $correo)) {
        registrarParcial($tipo);
        return true;
    }
    return false;
    //todo SI YA SE HA REGISTRADO, NO DEJAR QUE CONTINÚE POR AQUI, DEBERÁ LOGEARSE y el Login al redireccionar dirá
    //todo si se trata de un REGISTRO de EMPRESA ó DEMANDANTE ((mediante SESIÓN!))
}

/**
 * Almacenará los datos del usuario PARCIALMENTE; esto es,
 * su usuario(email) y contraseña en la BBDD.
 * <b>Se necesita tener en memoria/POST los datos de REQUEST['correo'], y de REQUEST['password']</b>
 * @return void|bool
 */
function registrarParcial($tipoRegistro) {
    try {
        if (isset($_SESSION['login_picture'])) {
            //foto con google ; otros datos ya están
            $foto = $_SESSION['login_picture'];
            if ($foto == '') {
                $foto = "default_icon.jpg";
            }
            $email = $_SESSION['login_email'];
            $nombre = $_SESSION['login_name'];
            $passwd = password_hash($_SESSION['ucode'], PASSWORD_DEFAULT);
            //al terminar, eliminamos las variables de Sesión por seguridad.
            unset($_SESSION['login_picture'], $_SESSION['login_email'], $_SESSION['login_name'], $_SESSION['ucode'], $_SESSION['access_token']);
        } else {
            //Datos por defecto, con la foto de Perfil default
            $foto = "default_icon.jpg";
            $email = strip_tags($_REQUEST['correo']);
            $passwd = strip_tags($_REQUEST['password']);
            $nombre = strip_tags($_REQUEST['correo']);
        }
        if ($tipoRegistro == "empresa") {
            //El tipo de usuario es EMPRESA
            if (insertInto('empresas', null, $email, password_hash($passwd, PASSWORD_DEFAULT), $foto, $nombre, '', '', '', 000000000, 00000, '', '', '', '0', '1','', date("d/m/Y h:i")) != 1) {
                $_SESSION['errores'] = "No se ha podido registrar tu correo! Chequea tu conexión y vuelve a intentarlo...";
                header('Location: index.php');
                return false;
            }
            unset($_SESSION['errores']);
        } elseif ($tipoRegistro == "demandante") {
            //El tipo de usuario es DEMANDANTE
            if (insertInto('demandantes', null, '', $nombre, '', '', '', 00000, '', $_REQUEST['correo'], password_hash($_REQUEST['password'], PASSWORD_DEFAULT), $foto, '', '0', '1', date("d/m/Y h:i")) != 1) {
                $_SESSION['errores'] = "No se ha podido registrar tu correo! Chequea tu conexión y vuelve a intentarlo...";
                header('Location: index.php');
                return false;
            }
            unset($_SESSION['errores']);
        }
    } catch (PDOException $pdo) {
        $_SESSION['errores'] = $pdo->getMessage();
    }
}