<?php
    session_start();
//    //Chequear usuario
//    if ($_SESSION['userData'] == null) {
//        header("Location: ../index.php?checkLogin=true");
//        die();
//    }
if (!isset($_SESSION['userData'])) {
    header("Location: ../index.php?logout=true");
    die();
}
    if (array_key_exists('id_DEM', $_SESSION['userData'])) {
        $index = "demandantes/";
        $title = "Volver a TeleJobs EMPLEO";
    } elseif (array_key_exists('id_EMP', $_SESSION['userData'])) {
        $index = "empresa/";
        $title = "Volver a TeleJobs EMPRESAS";
    }
?>
<html lang="es">
    <head>
        <title>Chatear con... - TELEJOBS</title>
        <link rel="stylesheet" href="../css/chat.css"/>
        <link rel="icon" href=""/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="../js/jquery-3.6.3.js"></script>
    </head>
    <body>
        <nav>
            <ul class="barra-nav flex">
                <li>
                    <a href="<?php echo $index ?>" title="<?php echo $title ?>"><img class="img-nav" src="../logo/Telejobs_300px.png" alt="logo Telejobs"></a>
                </li>
                <div style="margin-left: 40%; float: right;">
                    <a class="userImg usermenu-toggle">
                        <img src="../img/fotosUser/<?php echo $_SESSION['userData']['foto'] ?>" alt="usericon"/>
                    </a>
                </div>
                <li>
                    <a href="../index.php?logout=true" class="logout-btn">Cerrar Sesión</a>
                </li>
            </ul>
        </nav>
        <dialog id="userMenu1">
            <div class="abs-usermenu">
                <pre>Hola, <b><?php echo $_SESSION['userData']['nombre'] . "!</b>" ?></pre>
                <ul>
                    <li><i class="fa fa-user icon-pq"></i><a class="clear" href="perfil.php">Mi perfil</a></li>
                    <li><i class="fa fa-gear icon-pq"></i>Configuración</li>
                    <li class="red-link"><a href="../../index.php?logout=true"><i class="fa fa-external-link icon-pq"></i>Cerrar sesión</a></li>
                </ul>
            </div>
        </dialog>

        <div class="flex-main">
            <div class="chat-window">
                <div id="userInfo">
                    <p id="username">VENTANA MAIN CONVERSACIÓN</p>
                    <p class="txt-pq">en línea</p>
                </div>
            </div>
            <div class="listaUsers">
                <div id="title">LISTA USUARIOS</div>
                <ul>

                    <li>A - Último mensaje...</li>
                    <li>B - +34 664 78 43 22</li>
                </ul>
            </div>
        </div>
        <script src="../js/chat.js"></script>
    </body>
</html>
