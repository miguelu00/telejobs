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
    if ($_SESSION['userData'] == null) {
        header("Location: telejobs.net?checkLogin=true");
    }
    $userD = $_SESSION['userData'];
    if ($userD == null) {
        header("Location: ../demandantes/");
    }

    $ofertas = select("ofertas_trab", "*");
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
                    <p style="margin-top: 0; margin-right: 5px; text-align: right;">ofertas</p>
                </div>
                <?php
                if ($ofertas != null) {
                    if (array_key_exists("ID_Oferta", $ofertas)) {
                        $arrExperiencia = explode(",", $ofertas['exp_requerida']);
                        echo "
                        <div class='cardOferta2'>
                        <h2>" . $ofertas['puesto'] . "</h2>
                        <p>Horario: De " . $ofertas['horario'] . "h.</p>" .
                        "<br> Experiencia requerida:
                        <ul>";
                        foreach ($arrExperiencia as $exp) {
                            echo "<li>" . $exp . "</li>";
                        }
                        echo "
                        </ul>
                            <p>Fecha limite: {$ofertas['fecha_limite']}</p>
                        </div>";
                    } else {
                        foreach ($ofertas as $oferta) {
                            $arrExperiencia = explode(",", $oferta['exp_requerida']);
                            echo "
                            <div class='cardOferta2'>
                            <h2>" . $oferta['puesto'] . "</h2>
                            <p>Horario: De " . $oferta['horario'] . "h.</p>" .
                            "<br> Experiencia requerida:
                            <ul>";
                            foreach ($arrExperiencia as $exp) {
                                echo "<li>" . $exp . "</li>";
                            }
                            echo "
                            </ul>
                                <p>Fecha limite: {$oferta['fecha_limite']}</p>
                            </div>";
                        }
                    }
                }
                ?>
                <br>
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