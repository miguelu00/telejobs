<?php
session_start();
require_once "../Utiles/mySQL.php";

//Registro parcial (USAR SESSION['tipoReg'] EN OAUTH)
if (isset($_POST['startReg'])) {
    $_SESSION['tipoReg'] = $_REQUEST['modoReg'];
    registrarParcial($_SESSION['tipoReg']); //-- grabamos sólo el email y la contraseña
    header('Location: registro2.php');
    die();
} else {
    header('Location: index.php');
    die();
}