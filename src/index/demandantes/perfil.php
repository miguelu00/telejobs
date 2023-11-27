<?php
require_once "../../Utiles/mySQL.php";
if (!isset($_SESSION['userData'])) {
    if (!array_key_exists('id_DEM', $_SESSION['userData'])) {
        header('Location: ../../index.php?checkLogin=true');
    }
    $_SESSION['userData'] = select("demandantes", "*", "email LIKE '" . $_SESSION['user'] . "'");
}
//Si ha ocurrido un update, recargará los datos [variable creada en AJAX]
if (isset($_SESSION['updateDONE'])) {
    $_SESSION['userData'] = select("demandantes", "*", "email LIKE '" . $_SESSION['user'] . "'");
    unset($_SESSION['updateDONE']);
}
    $userD = $_SESSION['userData'];
    if ($userD == null) {
        header("Location: ../empresa/");
    }
?>
<html lang="es">
<head>
    <title>TELEJOBS - Mi perfil</title>
    <link rel="stylesheet" href="../../css/estilos.css"/>
    <link rel="stylesheet" href="../../css/demandante.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../../js/jquery-3.6.3.js"></script>
    <link rel="icon" href="../../img/icon1.png"/>
</head>
<body class="profile-body">
<nav>
    <ul class="barra-nav"  style="margin-top: 0">
        <li class="logo-emp">
            <a href="index.php">
                <img id="img-logo" class="img-nav no-border" src="../../logo/telejobsDEM_logoNAV.png" alt="logoEmp"/>
            </a>
        </li>
        <div style="display: flex; width: 100%; height: auto; justify-content: space-between">
            <li>
                <h2 class="tj-font" style="margin-left: 10px;">Editar mi Perfil de TELEJOBS</h2>
            </li>
            <li>
                <a class="logout-btn" href="../../index.php?logout=true">Cerrar Sesión <i class="fa fa-close"></i></a>
            </li>
        </div>
    </ul>
</nav>
<!-- Se actualizan los datos del usuario, puramente a través de AJAX -->
<div>
    <div class="flex-profile">
        <div class="img-edit">
            <div>
                <form action="" id="formIMG" method="post" enctype="multipart/form-data">
                    <label for="editProfile"><img id="previewFoto" src="../../img/fotosUser/<?php echo $_SESSION['userData']['foto'] ?>" alt="fotoDePerfil"/></label>
                    <i class="fa fa-edit"></i>
                    <input type="file" style="display:none;" id="editProfile" name="editProfile" accept="image/jpeg, image/png">
                    <br>
                    <div id="opcsImagen" style="display:none;">
                        <input id="sendIMG" type="button" value="GUARDAR"/>
                        <input id="resetIMG" type="reset" value="CANCELAR"/>
                    </div>
                </form>
            </div>
            <p style="padding-left: 10px;">Click en la imagen para editar...</p>
        </div>
        <hr>
        <div class="flex-centered-col">
            <fieldset>
                <legend>Datos personales...</legend>
                <label for="nombre">Nombre</label>
                <br>
                <input title="El campo no puede quedar vacío" required aria-required="true" id="nombre" name="nombre" type="text" value="<?php echo $_SESSION['userData']['nombre'] ?>" disabled>&nbsp; <button class="editToggle">Editar</button>
                <br>
                <label for="apellidos">Apellidos</label><br>
                <input title="El campo no puede quedar vacío" required aria-required="true" id="apellidos" name="apellidos" type="text" value="<?php echo $_SESSION['userData']['apellidos'] ?>" disabled>&nbsp; <button class="editToggle">Editar</button>
                <br>
                <label for="tlf">Teléfono de Contacto</label><br>
                <input title="El campo no puede quedar vacío" required aria-required="true" id="tlf" name="tlf" type="number" maxlength="9" value="<?php echo $_SESSION['userData']['tlf'] ?>" disabled/>&nbsp; <button class="editToggle">Editar</button>
                <br>
                <label for="fechaNac">Fecha de Nacimiento</label><br>
                <input title="El campo no puede quedar vacío" required aria-required="true" type="date" id="fechaNac" name="fechaNac" value="<?php echo $_SESSION['userData']['fechaNac'] ?>" disabled/>&nbsp; <button class="editToggle">Editar</button>
            </fieldset>
            <div>
                <p class="warn"> <i class="fa fa-warning"></i>*Importante: la dirección de Correo indicada será la que se use para <b>INICIAR SESIÓN</b>, comunicaciones, avisos de otras empresas, y otros mensajes referentes a la plataforma...</p>
            </div>
        </div>
    </div>
</div>
<dialog class="mensajes">

</dialog>
<!-- Estos hidden input se usan para conocer el TIPO DE USUARIO -->
<input type="hidden" id="tipoUser" value="demandante"/>
<input type="hidden" id="emailUser" value="<?php echo $_SESSION['userData']['email'] ?>"/>
</body>
<script src="../../js/perfil.js"></script>
<script src="../../js/scriptlogo.js"></script>
</html>
