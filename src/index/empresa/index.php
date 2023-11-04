<?php
    session_start();

    require_once "../../Utiles/mySQL.php";
    //Al recoger los datos en una variable de Sesión, se garantiza UNA sola query a la BBDD; ['updateDONE'] indica que hemos actualizado el usuario, para actualizarlo
    if (!isset($_SESSION['user'])) {
        header("Location: ../../index.php?checkLogin=true");
        die();
    }
    if (isset($_SESSION['updateDONE'])) {
        $_SESSION['userData'] = select("empresas", "*", "email LIKE '" . $_SESSION['user'] . "'");
        unset($_SESSION['updateDONE']);
    } else {
        if (!isset($_SESSION['userData'])) {
            $_SESSION['userData'] = select("empresas", "*", "email LIKE '" . $_SESSION['user'] . "'");
        }
    }
    $userD = $_SESSION['userData'];
    if ($userD == null) {
        header("Location: ../demandantes/");
    }
?>
<html lang="es">
    <head>
        <title>TELEJOBS Empresas</title>
        <link rel="stylesheet" href="../../css/estilos.css"/>
        <link rel="stylesheet" href="../../css/empresa.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="icon" href="../../img/icons/telejobs_icon.png"/>
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
                    <!--<li><i class="fa fa-calculator icon-pq"></i>Ver datos empresa</li>-->
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
                    <!--Comenzar bucle con ajax para obtener demandantes recientes (según fecha de inscripción)-->
                    <div id="data1" class="ajaxDemand">
                        <!-- 'SELECT * FROM demandantes WHERE email IS NOT LIKE "' . $_SESSION['userData']['email'] . '" ORDER BY f_inscripcion ASC';-->
                    </div>
                </div>
            </aside>
            <div class="main-view">
                <h1>Ofertas en Proceso</h1>
                <div style="float: right; margin: 10px 15px 0 0;" class="spinner1">
                    <label for="numOfertas">Mostrar máx.: </label><input class="spinner1" id="numOfertas" type="number" min="3" max="10" value="5"/>
                    <br>
                    <p style="margin-top: 0; margin-right: 5px; text-align: right;">perfiles</p>
                </div>
                <br>
                <div id="ajax-ofertas" class="">
                    <div>
                        <div class="cardOferta slider">
                            <h4>Oracle Developer</h4>
                            <p class="red offer-status">24/04/2023</p>
                        </div>
                        <div class="slidee">
                            <div class="offer-info">
                                <h3><a href="">Usuario Gonzalez</a></h3>
                                <details>
                                    <summary>Habilidades: <span style="color: green">Con <b>2</b> habilidades en común</span>
                                    </summary>
                                    <div>
                                        <ul>
                                            <li><b>Nº1</b> Trabajo en Equipo</li>
                                            <li><b>Nº3</b> HTML Básico</li>
                                            <li><b>Nº8</b> SQL<li>
                                            <li><b>Nº9</b> Oracle Developer</li>
                                            <li><b>Nº5</b> Gestión de Equipos</li>
                                        </ul>
                                    </div>
                                </details>
                            </div>
                        </div>
                        <br>
                        <a href="">Ver PERFIL</a>
                    </div>
                    <div>
                        <div class="cardOferta slider">
                            <h4>Cirujano</h4>
                            <p class="offer-status">15/06/2023</p>
                        </div>
                        <div class="slidee">
                            <div class="offer-info">
                                <h3>Migue Fuentes</h3>
                                <details>
                                    <summary>Habilidades: <span style="color: red">Con <b>1</b> habilidad en común</span>
                                    </summary>
                                    <div>
                                        <ul>
                                            <li><b>Nº1</b> Trabajo en Equipo</li>
                                            <li><b>Nº10</b> Enfermeria</li>
                                            <li><b>Nº4</b> Organización y gestión de situaciones difíciles</li>
                                        </ul>
                                    </div>
                                </details>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="paginas-div">
                    <ul>
                        <li><button id="beforeBtn">Anterior</button></li>
                        <li><button id="nextBtn">Siguiente</button></li>
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
    <script src="../../js/getEmpresa.js"></script>
    <script src="../../js/scriptlogo.js"></script>
    <script src="../../js/scriptAJAX.js"></script>
    <script src="../../js/imgnav.js"></script>
</html>