<?php
    session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--    Insertar ICONOS     -->
    <!--------------------------------------->
    <script src="../js/jquery-3.6.3.js"></script>
    <link rel="icon" href="../img/oficina1.png"/>
    <link rel="stylesheet" href="../css/estilos.css"/>
    <link rel="stylesheet" href="../css/login2.css"/>
    <title>ENTRAR - TELEJOBS Empresas</title>
</head>
<body>
<h5><img src="../img/TELEJOBS_minilogo2.png" alt="logoTelejobs"/></h5>
    <div>
        <fieldset>
            <legend>Entra como EMPRESA...</legend>

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
                    <input type="submit" name="inENTERPRISE" value="Ingresar"/>
                    <input type="reset" value="Limpiar campos"/>
                </div>
                <hr>
                <br>
                <div class="controls">
                    <p>¿Deseas registrar tu Empresa?
                    <a class="butt-login" href="../registro/index.php">REGISTRO</a></p>
                </div>
                <div class="align-r-selfR ">
                    <input class="med-sz" title="Configuración ADMIN" type="button" name="adminConf" id="adminConf" value="🛠"/>
                </div>
            </form>
        </fieldset>
        <dialog class="errores">

        </dialog>
        <script src="login.js"></script>
    </div>

</body>
</html>