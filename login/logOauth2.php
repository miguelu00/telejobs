<?php
    require_once "Utiles/mySQL.php";
    // Cada 60 segundos, revivir la sesión en caso de que expire
    if (!isset($_SESSION['last_access']) || time() - $_SESSION['last_access'] < 60) {
        $_SESSION['last_access'] = time();
    }

//Login con OAuth2
if (isset($_GET['code'])) {
    // Conseguimos el token de Google, y comprobamos errores
    $token = $gclient->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        // Almacenamos el código de inicio/token en la cuenta de Google
        $gclient->setAccessToken($token['access_token']);
        $_SESSION['tipoReg'] = "GOOGLE";

        // store access token
        $_SESSION['access_token'] = $token['access_token'];
        

        // Get Account Profile using Google Service
        $gservice = new Google_Service_Oauth2($gclient);

        // Get User Data
        $udata = $gservice->userinfo->get();
        $_SESSION['gUserData'] = $udata;
        foreach ($udata as $k => $v) {
            $_SESSION['login_' . $k] = $v;
        }
        $_SESSION['ucode'] = $_GET['code'];

        //Comprobar si existe la cuenta de correo en cualquiera de las tablas de Usuario BBDD; hacer login si es así
        if (checkRegister($_SESSION['login_email'], ["empresas", "demandantes"])) {
            header("Location: login/loginGoogle.php");
        }
    }
}