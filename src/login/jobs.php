<?php
    session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../css/login3.css"/>
    <link rel="icon" href="../img/telefono.png"/>
    <title>ENTRAR - TELEJOBS Empleo</title>
</head>
<body>
<h5><img src="../img/TELEJOBS_minilogo2.png" alt="logoTelejobs"/></h5>
    <div>
        <fieldset>
            <legend>Entra como TRABAJADOR...</legend>

            <form action="login.php" method="post">
                <div class="inputs-trab">
                    <span><label for="username">Nombre de usuario</label></span>
                    <input type="email" name="username" id="username"/>
                </div>
                <div class="inputs-trab">
                    <span><label for="passwd">Contraseña</label></span>
                    <input type="password" name="passwd" id="passwd"/>
                </div>
                <br>
                <div class="login-botones-trab">
                    <input type="submit" name="inPERSONAL" value="LOGIN"/>
                    <input type="reset" value="Limpiar campos"/>
                </div>
                <hr>
                <br>
                <div style="width: 100%;" class="controls">
                    <p style="text-align: right;">¿No tienes cuenta? --> <a class="butt-login" href="../registro/index.php">REGISTRO</a></p>
                </div>
            </form>
        </fieldset>
        <dialog class="errores">

        </dialog>
    </div>
<script src="login.js"></script>
</body>
</html>