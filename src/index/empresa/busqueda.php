<?php
session_start();

require_once "../../Utiles/mySQL.php";
require_once "../../Utiles/comprobarSesion.php";
//Al recoger los datos en una variable de Sesión, se garantiza UNA sóla conexion a la BBDD
$_SESSION['userData'] = select('empresas', 'id_EMP, nombre, email, nombre_Prop, apels, tlf, CIF, confirm', 'email LIKE "' . $_SESSION['user'] . '"');
if ($_SESSION['userData'] == null) {
    header("Location: ../demandantes/");
}
?>
<html lang="es">
<head>
    <title>Búsqueda candidatos... - TELEJOBS Empresas</title>
    <link rel="stylesheet" href="../../css/estilos.css"/>
    <link rel="stylesheet" href="../../css/empresa.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../../js/jquery-3.6.3.js"></script>
    <link rel="icon" href="../../img/icon1.png"/>
</head>
<body class="main-body">
<nav>
    <ul class="barra-nav">
        <li class="logo-emp">
            <a href="index.php">
                <img id="img-logo" class="img-nav no-border" src="../../logo/telejobsEMP_logoNAV.png" alt="logoEmp"/>
            </a>
        </li>
        <li><a href="../gestOfertas">Gestión ofertas</a></li>
        <li>
            <details>
                <summary><a href="#"><i class="fa fa-users"></i> Perfiles</a></summary>
                <div class="list-desplegado flex-centered-col">
                    <a href="busqueda.php">Buscar demandantes</a>
                </div>
            </details>
        </li>
        <?php
        if (isset($_SESSION['user'])) {
            echo "<li><a href=''>Publicaciones " . $_SESSION['userData']['nombre'] . "</a></li>";
        } else {
            echo "<li><a href=''>Publicaciones NOMBRE_EMPRESA</a></li>";
        }
        ?>
        <div>
            <a class="userImg usermenu-toggle">
                <img src="../../img/fotosUser/default_icon.jpg" alt="usericon"/>
            </a>
        </div>
    </ul>
</nav>
<dialog id="userMenu1">
    <div class="abs-usermenu">
        <pre>Opciones para <b><?php echo $_SESSION['userData']['nombre'] . "!</b>" ?></pre>
        <ul>
            <li><a href="configuracion.php"><i class="fa fa-gear icon-pq"></i>Configuración</a></li>
            <li class="red-link"><a href="../../index.php?logout=true"><i class="fa fa-external-link icon-pq"></i>Cerrar sesión</a></li>
        </ul>
    </div>
</dialog>
<!--Separación BODY, en forma de GRID-->
<div class="content-main">
    <aside class="aside-izq">
        <div>
            <h2>Nuevos demandantes</h2>
            <!--Comenzar bucle con ajax para obtener demandantes recientes (segun fecha de inscripción)-->
            <div id="data1" class="ajaxDemand">
                <!-- 'SELECT * FROM demandantes WHERE email IS NOT LIKE "' . $_SESSION['userData']['email'] . '" ORDER BY f_inscripcion ASC';-->
            </div>
        </div>
    </aside>
    <div class="main-view">
        <h1>Búsq. de Demandantes</h1>
        <div style="float: right; margin: 10px 15px 0 0;" class="spinner1">
            <label for="numOfertas">Mostrar máx.: </label><input class="spinner1" id="numOfertas" type="number" min="3" max="10" value="5"/>
            <br>
            <p style="margin-top: 0px; margin-right: 5px; text-align: right;">perfiles</p>
        </div>
        <br>
        <div class="select1">
            <label for="orderBy">
                Ordenar por...
            </label> <select name="orderBy" id="orderBy">
                <option value="relevancia">Relevancia (experiencia sim.)</option>
                <option value="nombre">Nombre (A-Z)</option>
            </select>
        </div>
        <br>

        <div class="paginas-div">
            <ul>
                <li><button id="befButton" class="active" disabled aria-disabled="true">Anterior</button></li>
                <li><button id="nextButton" aria-disabled="true" disabled >Siguiente</button></li>
            </ul>
        </div>
    </div>
</div>

<footer class="footer-emp">
    <div class="footer-container">
        <div class="footer-column prim-col">
            <h3>TELEJOBS Empresa</h3>
            <p>Encuentra ahora mismo a tu candidato ideal! Hacemos simple para tí, y miles de empresari@s, encontrar el perfil que mejor se adapta a tu empresa</p>
        </div>
        <div class="footer-column">
            <h3>Contacto</h3>
            <ul class="footer-links">
                <li><i class="fa fa-phone"></i> 603 58 96 82</li>
                <li><i class="fa fa-envelope"></i> mafvpersonal@gmail.com</li>
                <li><i class="fa fa-map-marker"></i> Dónde puedas, dónde quieras</li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Más</h3>
            <ul class="footer-links">
                <li><a href="../download.php?file=<?php echo urlencode("manualTELEJOBS.pdf") ?>">¿Cómo usar TeleJobs?</a></li>
                <li><a href="../terminos.php">Política de Privacidad</a></li>
                <li><a href="../terminos.php">Términos del servicio</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Redes TELEJOBS</h3>
            <ul class="social-links">
                <li><a href="https://www.twitter.com/"><i class="fa fa-twitter"></i></a></li>
                <li><a href="https://linkedin.com/"><i class="fa fa-linkedin"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="footer-credits">
        <p>&copy; 2023 TeleJobs. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
<script src="../../js/scriptlogo.js"></script>
<script src="../../js/busqueda.js"></script>
<script src="../../js/imgnav.js"></script>
</html>