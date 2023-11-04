<?php
    session_start();
    require_once "../Utiles/mySQL.php";
    require_once "../Utiles/conexionBD.php";
//Recogerá los datos, y según la variable sabrá si el usuario es DEMANDANTE ó EMPRESA
// y lo logeará

$user = strip_tags($_REQUEST['username']);
$passwd = strip_tags($_REQUEST['passwd']);

if (isset($_REQUEST['inPERSONAL'])) {
    ingresar(0, $user, $passwd);
}

if (isset($_REQUEST['inENTERPRISE'])) {
    ingresar(1, $user, $passwd);
}

if (isset($_GET['confirmarCorreo'])) {
    confirmarCuenta($_GET['confirmarCorreo'], $_GET['tipoRegistro'], $_GET['token']);
}
?>
<html lang="es">
    <head>
        <?php
            if (isset($_GET['continuarRegistro'])) {
                echo "<title>Cambiar contraseña... - TELEJOBS</title>";
            } elseif (isset($_GET['eliminarCuenta'])) {
                echo "<title>Abortar registro - TELEJOBS</title>";
            } else {
                echo "<title>Confirmar cuenta... - TELEJOBS</title>";
            }
        ?>
        <link rel="icon" href="">
        <style>
            body {
                text-align: center;
                background-color: #4CAF50;
                font-size: 110%;
                font-family: Calibri, "Gill Sans MT", sans-serif;
            }
            a {
                border: 2px solid greenyellow;
                width: 2vw;
                height: auto;
                padding: 4px;
            }
            div {
                width: fit-content;
                height: auto;
                padding: 7px;
            }
            div.ok {
                background-color: #4CAF50;
            }
            div.error {
                background-color: #fd2e1b;
            }
            form#formConfirmar {
                border: 2px solid lightseagreen;
                background-color: #0a58ca;
                color: white;
            }
            form input[type=password], input[type=text] {
                padding: 10px;
                width: 200px;
                height: auto;
            }
            .recup1 {
                width: 50%;
                height: auto;

                border: 2px solid greenyellow;
                border-radius: 10px;
                padding: 15px;
                margin: 10px;
                align-self: start;
            }
        </style>
    </head>
    <body>
<?php
    if (isset($_GET['continuarRegistro'])) {
        echo "<div class='recup1'>";
            echo "<h2><b>CONTINUAR REGISTRO en TELEJOBS</b></h2>";
            echo "<br>";
            echo "<p>Por favor, indique su TIPO de Usuario, y a continuación proporcione una nueva contraseña (puede ser la misma que utilizó en el registro)</p>";
            echo "<br>";
            echo "<form action='completar.php' method='post'>";
                echo "<input type='hidden' id='email1' name='email1'/>";
                echo "<input type='radio' id='tipUser1' name='tipUser' value='demand'> <label for='tipUser1'>DEMANDANTE de Empleo</label>";
                echo "<input type='radio' id='tipUser2' name='tipUser' value='empresa'> <label for='tipUser2'>EMPRESA</label>";
                echo "<br>";
                echo "<input type='password' placeholder='Nueva contraseña' name='passwd1' id='passwd1'/>";
                echo "<input type='password' placeholder='Confirmar contraseña...' name='passwd2' id='passwd2'/>";
                echo "<br>";
                echo "<button type='submit' name='updateCuenta'></button>";
            echo "</form>";
        echo "</div>";
    }
?>

<?php
//Consulta a BBDD; sino se encuentra al usuario mostramos mensaje de error
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
                echo "<h2>ERROR en el ingreso</h2><br><br>Compruebe que ha introducido bien su email--contraseña";
            }
            break;
        case 1:
            $datosUser = select('empresas', '*', 'email LIKE "' . $usuario . '"');
            if (count($datosUser) > 0 && password_verify($contra, $datosUser['passwd'])) {
                //Ingresado correctamente!
                $_SESSION['user'] = $datosUser['email'];
                header('Location: ../index/empresa/index.php');
            } else {
                //ERROR al ingresar. Compruebe que ha introducido bien su email--contraseña
                echo "<h2>ERROR en el ingreso</h2><br><br>Compruebe que ha introducido bien su email--contraseña";
            }
            break;
    }
}

function confirmarCuenta(string $correo, string $tipoCuenta, string $token): void
{
    if (password_verify($token, $correo)) {
        if ($tipoCuenta == 0) {
            update("demandantes", "confirm = 2", "email LIKE '{$correo}'");
        } elseif ($tipoCuenta == 1) {
            update("empresas", "confirm = 2", "email LIKE '{$correo}'");
        }
        echo "<div class='ok'>Correo confirmado! <br><a href='../index.php'>Volver a TELEJOBS</a></div>";
    } else {
        echo "<div class='error'>ERROR. No intentes cosas raras... <br><br><a href='../index.php'>Volver a TELEJOBS</a></div>";
    }
}

function lanzarError() {
    $_SESSION['errores'] = "Ha ocurrido un error en su Ingreso!. <br> Inténtelo de nuevo más tarde, o póngase en contacto con el <a href='mailto:mafvpersonal@gmail.com'>administrador de la Web</a>";
}
?>
    </body>
</html>
