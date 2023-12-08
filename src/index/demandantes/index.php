<?php
    session_start();
    require_once "../../Utiles/mySQL.php";

    if (!isset($_SESSION['user'])) {
        header("Location: ../../index.php?checkLogin=true");
        die();
    }
    if (isset($_SESSION['updateDONE'])) {
        $_SESSION['userData'] = select("demandantes", "*", "email LIKE '" . $_SESSION['user'] . "'");
        unset($_SESSION['updateDONE']);
    } else {
        if (!isset($_SESSION['userData'])) {
            $_SESSION['userData'] = select("demandantes", "*", "email LIKE '" . $_SESSION['user'] . "'");
        }
    }
    $userD = $_SESSION['userData'];
    if ($userD == null) {
        header("Location: ../empresa/");
    }
?>

<html lang="es">
    <head>
        <title>TELEJOBS Empleo</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="icon" href="../../favicon.ico"/>
        <script src="../../js/jquery-3.6.3.js"></script>
        <link rel="stylesheet" href="../../css/estilos.css"/>
        <link rel="stylesheet" href="../../css/demandante.css"/>
    </head>
    <body class="main-body">
    <ul class="barra-nav">
        <li class="logo-emp">
            <a href="index.php">
                <img id="img-logo" class="img-nav no-border" src="../../logo/telejobsDEM_logoNAV.png" alt="logoEmp"/>
            </a>
        </li>
        <li><a class="activo" href="index.php">Bolsa de Empleo</a></li>
        <li><a href="">Ver mis Ofertas (0)</a></li>
        <li><a href="generadorCV.php">Generador de CV <img class="img-nav-pq" src="../../img/register.png"</a></li>
        <div>
            <a class="userImg usermenu-toggle">
                <img src="../../img/fotosUser/<?php echo $_SESSION['userData']['foto'] ?>" alt="usericon"/>
            </a>
        </div>
    </ul>
    <dialog id="userMenu1">
        <div class="abs-usermenu">
            <pre>Hola, <b><?php echo $_SESSION['userData']['nombre'] . "!</b>" ?></pre>
            <ul>
                <li><i class="fa fa-user icon-pq"></i><a class="clear" href="perfil.php">Mi perfil</a></li>
                <li><a href="configuracion.php"><i class="fa fa-gear icon-pq"></i>Configuración</a></li>
                <li class="red-link"><a href="../../index.php?logout=true"><i class="fa fa-external-link icon-pq"></i>Cerrar sesión</a></li>
            </ul>
        </div>
    </dialog>

    <div class="content-main">
        <aside class="aside-izq">
            <div>
                <h3>Ofertas recientes</h3>
                <div id="data1" class="ajaxDemand">
                    <a id="targetLink" href="">
                        <div class="card-aside">
                            <div class="loader"></div>
                        </div>
                    </a>

                    <!-- colocar DOM ;; fadeOut... ;; cambiar DOM ;; fadeIn... -->
                    <!-- <div class="card-aside ..."></div> -->
                    <!-- 'SELECT * FROM ofertas_trab WHERE email IS NOT LIKE "' . $_SESSION['userData']['email'] . '" ORDER BY f_inscripcion ASC';-->
                </div>
            </div>
        </aside>
        <div class="main1-wrap">
            <!-- Contenido principal, Nuevas ofertas de Empleo -->
            <div>
                
            </div>
        </div>
    </div>
    <footer class="footer-emp">
        <div class="footer-container">
            <div class="footer-column prim-col">
                <h3>TELEJOBS Empleo</h3>
                <p>Trabaja en tu sueño más realista! Hacemos simple para tí, y miles de trabajadorxs, encontrar las empresas que mejor se adapten a tus necesidades.</p>
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
                    <li><a href="../download.php?file=<?php echo urlencode("manualTELEJOBS.pdf") ?>">¿Cómo usar TeleJobs? <i class="fa fa-external-link" style="color: black; font-size: 10px"></i></a></li>
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
    <script src="../../js/getDemandantes.js"></script>
    <script src="../../js/scriptlogo.js"></script>
    <script src="../../js/imgnav.js"></script>
</html>