<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
}
if (isset($_SESSION['user'])) {
    if (isset($_SESSION['userData']['id_DEM'])) {
        header("Location: index/demandantes/");
    } elseif (isset($_SESSION['userData']['id_EMP'])) {
        header("Location: index/empresa/");
    }
    if (isset($_SESSION['user'])) {
        header("Location: ./index/redirect.php");
    }
}
    if (isset($_GET['sessionErr'])) {
        $_SESSION['errores'] = "<b>ERROR: </b>Se ha producido un error con tu sesión. Por favor, intenta iniciar sesión nuevamente.";
    } elseif (isset($_GET['checkLogin'])) {
        $_SESSION['errores'] = "<b>ERROR: </b>ACCESO DENEGADO: <br>¡Inicie sesión antes, por favor!";
    } elseif (isset($_GET['checkRegister'])) {
        $_SESSION['errores'] = "<b>ERROR: </b>Se ha producido un error con tu Registro, inténtalo de nuevo más tarde...";
    } else {
        $_SESSION['errores'] = "Al usar TELEJOBS, aceptas el uso de cookies de sesión. <br> &gt; <a href='./index/terminos.php'>Términos y condiciones</a>";
    }
//Cargamos dependencias y rellenamos datos para el Login con Google
    require_once('vendor/autoload.php');
    $clientID = "288499945993-j1f931k98vof5e0uv4lud6cldbdqvcbq.apps.googleusercontent.com";
    $secret = "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";

    // Google API Client
    $gclient = new Google_Client();

    // Set the ClientID
    $gclient->setClientId($clientID);
    // Set the ClientSecret
    $gclient->setClientSecret($secret);
    // Set the Redirect URL after successful Login
    $gclient->setRedirectUri('http://localhost/PROYECTO/index.php');

    // Adding the Scope (los datos que queremos recoger del usuario: email y perfil de Google)
    $gclient->addScope('email');
    $gclient->addScope('profile');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/icon1.png"/>
    <link rel="stylesheet" href="./css/estilos.css"/>
    <title>Bienvenido a TELEJOBS!</title>
</head>
<body class="primera-vista2">
<h5><img src="./img/TELEJOBS_minilogo2.png" alt="logoTelejobs"/></h5>
    <div class="flex-centered-col centrar">
        <div class="botons">
            <a class="emp" href="login/enterprise.php"><span class="md-text">EMPRESAS</span></a>
            <a class="dem" href="login/jobs.php"><span class="md-text">DEMANDANTES</span></a>
        </div>
        <div class="google-login">
            <a href='<?php echo $gclient->createAuthUrl() ?>' title="Iniciar sesión con Google"><img style="width: 3vw; height: auto;" src="img/google_icon.png" alt="logoGoogle"> Entrar con Google</a>
        </div>
        <a style="text-align: left;" title="Cambiar tu contraseña, ó bien Continuar su registro en TELEJOBS" href="login/recuperacion.php">Tengo problemas para Iniciar Sesión</a>
        <div class="cont-Dialog">
            <?php
            if (isset($_SESSION['errores'])) {
                echo "<dialog open>" . $_SESSION['errores'] . "</dialog>";
            }
            ?>
        </div>
    </div>
<script>
    let tout1,
    aBotons = document.querySelectorAll('div.botons a');


    aBotons[0].addEventListener('mouseenter', function() {
        cambiarColorBG("Empresas");
    });
    aBotons[1].addEventListener('mouseenter', function() {
        cambiarColorBG("Demandantes");
    });
    aBotons[0].addEventListener('mouseleave', function() {
        clearTimeout(tout1);
        cambiarColorBG();
    });
    aBotons[1].addEventListener('mouseleave', function() {
        clearTimeout(tout1);
        cambiarColorBG();
    });


    function cambiarColorBG(tipo) {
        document.body.style.animation = "fondo" + tipo + " 0.5s ease-in-out forwards";
        //Timeout declarado al comienzo del script.
        tout1 = setTimeout(function() {
            let color = "";
            if (tipo === "Empresas") {
                color = "rgb(22,195,255)";
            } else if (tipo === "Demandantes") {
                color = "rgb(155, 218, 29)";
            } else {
                color = "rgb(180, 180, 180)";
            }
            document.body.style.backgroundColor = color + "";
            document.body.style.animation = "";
        }, 600);
    }
</script>
</body>
</html>
<?php
require_once "login/logOauth2.php"; 