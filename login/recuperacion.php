<?php
    session_start();
    if (isset($_SESSION['emailSent'])) {
        $sent = true;
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--    Insertar ICONOS     -->
    <!--------------------------------------->
    <script src="../js/jquery-3.6.3.js"></script>
    <link rel="icon" href="../img/icons/mouse_error.png"/>
    <link rel="stylesheet" href="../css/estilos.css"/>
    <link rel="stylesheet" href="../css/login2.css"/>
    <title>RECUPERAR CUENTA - TELEJOBS Empresas</title>
</head>
<body>
<h5><a href="../index.php">
        <img src="../img/TELEJOBS_minilogo2.png" alt="logoTelejobs"/>
    </a></h5>
<div>
    <fieldset>
        <legend>Recupere su cuenta...</legend>
        <p style="color: black; font-size: 28px; font-family: 'Gill Sans MT', Calibri, sans-serif;">Si se ha registrado y quiere cambiar su contraseña olvidada, o simplemente abortar el registro en TELEJOBS, <br>
        introduzca su correo electrónico y haga clic en el botón verde:</p>
        <form action="" method="post">
            <div class="inputs-trab">
                <span><label for="username">Correo electrónico de registro</label></span>
                <input type="email" name="username" id="username" required aria-required="true"/>
            </div>
            <br>
            <div class="login-botones-trab">
                <input type="submit" name="inRECUPERAR" value="Recuperar cuenta"/>
                <input type="reset" value="Borrar input"/>
            </div>
        </form>
        <hr>
        <a href="../index.php">&lt;-- Volver atrás...</a>
    </fieldset>
    <?php
        if (isset($sent)) {
            echo "<dialog open class='errores'>";
        } else {
            echo "<dialog class='errores'>";
        }
    ?>
        ¡Email enviado! Si su dirección de correo figura en nuestra Base de Datos, se le enviará en breve un correo electrónico para recuperar su cuenta.
    </dialog>
    <script src="recuperar.js"></script>
</div>

</body>
</html>