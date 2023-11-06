<?php
    session_start();
    require_once "../../Utiles/mySQL.php";

        if (!isset($_SESSION['userData'])) {
            header("Location: ../../index.php?checkLogin=true");
        }
        if (array_key_exists("id_DEM", $_SESSION['userData'])) {
            $tipoUser = "demandantes";
        } elseif (array_key_exists("id_EMP", $_SESSION['userData'])) {
            $tipoUser = "empresas";
        }

?>

<html lang="es">
<head>
    <title>Configuración... - TELEJOBS Empleo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../../js/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../../css/estilos.css"/>
    <link rel="stylesheet" href="../../css/demandante.css"/>
    <link rel="icon" href="../../img/icons/settings.png"/>
</head>
<body class="body-config">
    <ul class="barra-nav">
        <li class="logo-emp">
            <a href="index.php">
                <img id="img-logo" class="img-nav no-border" src="../../logo/telejobsDEM_logoNAV.png" alt="logoEmp"/>
            </a>
        </li>
        <li style="padding-top: 30px;"><a href="index.php">&lt; Volver atrás</a></li>
    </ul>
    <div class="main-config">
        <div class="menuOpciones">
            <a href="#" class="active" id="openMenu1">
                <i class="fa fa-envelope"></i> Email &amp; contacto
            </a>
            <a href="#" id="openMenu2"><i class="fa fa-info-circle"></i> Sobre TELEJOBS</a>
            <a href="#" id="openMenu3" class="danger-link"><i class="fa fa-sign-out"></i> ELIMINAR CUENTA</a>
        </div>
        <!--Cada menú tendrá su DIV, y clase enumerada; al añadir menús habrá que tener en cuenta esto.-->
        <div id="menu">
            <div class="menu1">
                <h2 id="title">Email &amp; Contacto</h2>
                <br>
                <label for="email">Dirección de Correo <span class="orange">*</span></label><br>
                <input required aria-required="true" type="email" id="email" name="email" value="<?php echo $_SESSION['userData']['email'] ?>" disabled/>&nbsp; <a class="editToggle">Cambiar</a>
                <br><br>
                <label for="telefono">Teléfono Personal</label>
                <input required aria-required="true" type="number" id="tlf" name="tlf" value="<?php echo $_SESSION['userData']['tlf'] ?>" disabled/>&nbsp; <a class="editToggle">Cambiar</a>
                <div class="warn danger-box">
                    <p>*<i class="fa fa-exclamation-triangle"></i> ATENCIÓN: Si modifica su correo electrónico, YA NO PODRÁ INICIAR SESIÓN CON SU OTRO CORREO HASTA QUE LO VUELVA A CAMBIAR. Proceda con cautela</p>
                </div>
            </div>
            <div class="menu2 hidden">
                <img style="width: 200px; height: auto;" src="../../img/TELEJOBS_minilogo2.png" alt="TELEJOBS 1.0 logo"/>
                <br>
                <h3>TELEJOBS v0.3</h3>
                <p>¡La aplicación por excelencia para encontrar empleo!</p>
                <p class="txt-pq">&copy;Telejobs 2023. Todos los derechos reservados.</p>
            </div>
            <div class="menu3 hidden">
                <h2 style="color: red;">ATENCIÓN: VA A ELIMINAR SU CUENTA DE TELEJOBS</h2>
                <P>Sólo proceda si está de acuerdo.</P>
                <p>Esta acción no se puede deshacer</p>

                <button id="borrarCuenta" class="danger-link">
                    <i class="fa fa-close"></i> ELIMINAR CUENTA
                </button>
            </div>
        </div>
    </div>
    <!-- Estos hidden input se usan para conocer el TIPO DE USUARIO -->
    <input type="hidden" id="tipoUser" value="<?php echo $tipoUser ?>"/>

    <script src="../../js/config.js"></script>
    <script src="../../js/scriptlogo.js"></script>
</body>
</html>