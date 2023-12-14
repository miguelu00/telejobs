<?php
    session_start();
    include_once("../Utiles/mySQL.php");
    include_once("../Utiles/comprobarSesion.php");

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telejobs ADMIN</title>
    <script src="../../js/jquery-3.6.3.js"></script>
    <link rel="icon" href="../img/icons/settings.png"/>
    <link rel="stylesheet" href="../css/estilos.css"/>
    <link rel="stylesheet" href="../css/admin.css"/>
    <link rel="stylesheet" href="../css/loader.css"/>
    <link rel="stylesheet" href="../css/empresa.css"/>
</head>
<body>
    <ul class="barra-nav">
        <li class="logo-emp">
            <a href="../index.php" title="Recargar la página">
                <!--INSERTAR IMAGEN CUSTOM PARA ADMIN. TELEJOBS-->
                <img id="img-logo img-pq" class="img-nav no-border" src="../img/icons/AdminBanner_tj.png" alt="logoTJ"/>
            </a>
        </li>
        <li style="font-family: monospace; font-size: 14px; max-width: 27vw;padding-right: 15pt;">
            <h3>
                Doble clic => Editar campos <br>
                Botón "Editar..." => Confirmar cambios
            </h3>
        </li>
        <li>
            <a href="http://telejobs.net?logout=true">Cerrar Sesión</a>
        </li>
    </ul>
    <br>
    <div class="mainContainer">
    <br>
        <div class="tablaCrud">
            <h2>EMPRESAS</h2>
            <table class="tabla-datos" id="tablaEMPRESAS">
                <th colspan="2"></th>
                <th>ID</th>
                <th>email</th>
                <th>Nombre Empresa</th>
                <th>Actividad Principal</th>
                <th>Teléfono</th>
                <th>Código Postal</th>
                <th>CIF</th>
                <th>Cuenta confirmada</th>
                <th>Fecha_Apertura</th>
                <th>Fecha_Inscripción</th>
                <?php
                    mostrarDatosTabla("empresas");
                ?>
            </table>
            </div>
        <br>
        <div class="tablaCrud">
            <h2>DEMANDANTES</h2>
            <table class="tabla-datos" id="tablaDEMANDANTES">
                <th colspan="2"></th>
                <th>ID</th>
                <th>E-mail</th>
                <th>DNI/NIF</th>
                <th>Apellidos</th>
                <th>Nombre</th>
                <th>Fecha de Nacimiento</th>
                <th>Teléfono</th>
                <th>Código Postal</th>
                <th>Municipio</th>
                <th>CV_Visible</th>
                <th>Confirmado</th>
                <th>Fecha de Inscripción</th>
                <?php
                    mostrarDatosTabla("demandantes");
                ?>
            </table>
        </div>
        <br>
        <div class="tablaCrud">
            <h2>OFERTAS DE TRABAJO</h2>
            <table class="tabla-datos" id="tablaOFERTAS_TRAB">
                <th colspan="2"></th>
                <th>ID_Oferta</th>
                <th>EMPRESA_ID</th>
                <th>PUESTO</th>
                <th>Horario</th>
                <th>Experiencia Req.</th>
                <th>Habilidades Pedid.</th>
                <th>Fecha limite</th>
                <?php
                    mostrarDatosTabla("ofertas_trab");
                ?>
            </table>
        </div>
        <br>
        <div class="tablaCrud">
            <h2>HABILIDADES</h2>
            <table class="tabla-datos" id="tablaHABILIDADES">
                <th colspan="2"></th>
                <th>IDHabil</th>
                <th>Tipo/Categoria</th>
                <th>Nombre</th>
                <?php
                    mostrarDatosTabla("habilidades");
                ?>
            </table>
        </div>
    </div>
    <script src="../js/adminCRUD.js"></script>
</body>
</html>

<?php
    function mostrarDatosTabla($tabla) {
        $select = []; $singleRow = false; $tipo = null;
        switch ($tabla) {
            case "empresas":
                $id = "id_EMP";
                $select = select("empresas", "id_EMP, email, nombre, actividad_p, tlf, cPostal, CIF, confirm, f_apertura, f_inscripcion");
                $tipo = "E";
                if (isset($select["id_EMP"])) {
                    $singleRow = true;
                }
                break;
            case "demandantes":
                $id = "id_DEM";
                $select = select("demandantes", "id_DEM, email, NIF, apellidos, nombre, fechaNac, tlf, cPost, munip, cv_visible, confirm, f_inscripcion");
                $tipo = "D";
                if (isset($select["id_DEM"])) {
                    $singleRow = true;
                }
                break;
            case "ofertas_trab":
                $id = "ID_Oferta";
                $select = select("ofertas_trab");
                $tipo = "O";
                if (isset($select["ID_Oferta"])) {
                    $singleRow = true;
                }
                break;
            case "habilidades":
                $id = "IDHabil";
                $select = select("habilidades", "IDHabil, tipo, nombre");
                $tipo = "H";
                if (isset($select["IDHabil"])) {
                    $singleRow = true;
                }
                break;
        }
        if (!$singleRow) {

            foreach ($select as $row) {
                echo "<tr>";
                //Creamos botones, por cada fila, para Editar/Eliminar la entrada actual (por ID)
                echo "<td><button class='edit actions' type='button' id='" . $tipo . "editar_" . $row[$id] . "' class='edit' disabled aria-disabled='true'>" .
                "<img src='../img/editar.png'/>" . "<pre>Editar...</pre>" .
                "</button></td>";
                echo "<td><button class='borrar actions' type='button' id='" . $tipo . "borrar_" . $row[$id] . "' class='borrar'>" .
                "<img src='../img/eliminar.png'/>" . "<pre>Borrar...</pre>" .
                "</button></td>";
                foreach ($row as $key => $value) {
                    echo "<td name='" . $key . "'>" . $value ."</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr>";
            echo "<td><button class='edit actions' type='button' id='" . $tipo . "editar_" . $select[$id] . "' disabled aria-disabled='true'>" .
                "<img src='../img/editar.png'/>" . "<pre>Editar...</pre>" .
                "</button></td>";
                echo "<td><button class='borrar actions' type='button' id='" . $tipo . "borrar_" . $select[$id] . "'>" .
                "<img src='../img/eliminar.png'/>" . "<pre>Borrar...</pre>" .
                "</button></td>";
            foreach ($select as $key => $data) {
                echo "<td name='" . $key . "'>". $data ."</td>";
            }
            echo "</tr>";
        }
    }
?>