<?php
    session_start();
    require_once "../Utiles/mySQL.php";
    $verTodos = true;
    $sesionAct = false;

    //Si no tenemos ofertas de antes en sesión, volver a cargarlas...
    if (!isset($_SESSION['ofertas'])) {
        $ofertas = select("ofertas_trab");
        $_SESSION['ofertas'] = $ofertas;
    }
    if (isset($_SESSION['userData'])) {
        $sesionAct = true;
    }
    if (isset($_GET['ofertaID'])) {
        $verTodos = false;
        $ofertaCargada = select("ofertas_trab", "*", "ID_Oferta=" . $_GET['ofertaID'], "1");
        $fotoEmp = select("");
    }
?>
<html lang="es">
<head>
    <?php
        ($verTodos) ? print("<title>Buscar ofertas... - TELEJOBS</title>") : print("<title>TELEJOBS Empleo</title>");
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../../js/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../../css/estilos.css"/>
</head>
<body class="body-verFree">
<?php
    if ($_SESSION['ofertas'] && ($verTodos)) {
        require_once "gestOfertas/vistaOfertas.php";
    }
?>
</body>
