<?php
    session_start();
    include_once("../Utiles/mySQL.php");
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="../../js/jquery-3.6.3.js"></script>
    <link rel="stylesheet" href="../css/estilos.css"/>
    <link rel="stylesheet" href="../css/empresa.css"/>
    <link rel="stylesheet" href="../css/modal.css"/>
</head>
<body>
    <ul class="barra-nav">
        <li class="logo-emp">
            <a href="index.php">
                <!--INSERTAR IMAGEN CUSTOM PARA ADMIN. TELEJOBS-->
                <img id="img-logo" class="img-nav no-border" src="" alt="logoEmp"/>
            </a>
        </li>
    </ul>
    <br>
    <h2>EMPRESAS</h2>
    <table class="tabla-datos" id="tablaEMPRESAS">
        <th>ID</th>
        <th>email</th>
        <th>passwd</th>
        <th>Foto de perfil</th>
        <th>Nombre Empresa</th>
        <th>Nombre_propio</th>
        <th>Apellidos</th>
        <th>Actividad Principal</th>
        <th>Descripción</th>
        <th>Teléfono</th>
        <th>Código Postal</th>
        <th>Dirección</th>
        <th>Municipio Sede</th>
        <th>CIF</th>
        <th>Es una ETT</th>
        <th>Cuenta confirmada</th>
        <th>Fecha_Apertura</th>
        <th>Fecha_Inscripción</th>
        <th>---</th>
        <?php
             mostrarDatosTabla("empresas");
        ?>
    </table>

    <br>
    <h2>DEMANDANTES</h2>
    <table class="tabla-datos" id="tablaDEMANDANTES">
        <th>ID</th>
        <th>Skill_IDs</th>
        <th>Experiencia</th>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Fecha de Nacimiento</th>
        <th>Teléfono</th>
        <th>Código Postal</th>
        <th>Municipio</th>
        <th>E-mail</th>
        <th>Passwd</th>
        <th>Foto de perfil</th>
        <th>DNI/NIF</th>
        <th>CV_Visible</th>
        <th>Confirmado</th>
        <th>Fecha de Inscripción</th>
        <th>---</th>
        <?php
             mostrarDatosTabla("demandantes");
        ?>
    </table>
    
<a href="#openModal" title="Editar...">
    <i style="font-size: 36px;" class="fas fa-edit"></i> Editar...
</a>
    <script src="../js/adminCRUD.js"></script>
</body>
</html>

<?php
    function mostrarDatosTabla($tipoUser) {
        $select = []; $hayDatos = false; $singleRow = false;
        switch ($tipoUser) {
            case "empresas":
                $select = select("empresas", "*");
                if ($select['id_EMP'] != null) {
                    $singleRow = true;
                }
                break;
            case "demandantes":
                $select = select("demandantes", "*");
                if ($select['id_DEM'] != null) {
                    $singleRow = true;
                }
                break;
        }

        if (!$singleRow) {
            foreach ($select as $row) {
                $i = 0;
                echo "<tr>";
                foreach ($row as $key => $value) {
                    $i++;
                    //El primer campo es el ID, así que recogemos ese dato
                    if ($i == 1) {
                        $id = $value;
                    }
                    echo "<td>" . $value ."</td>";
                    $hayDatos = true;
                }
                //Creamos botones, por cada fila, para Editar/Eliminar la entrada actual (por ID)
                if ($hayDatos) {
                    echo "<td><button type='button' id='editar_" . $id . "' class='edit'>" .
                    "<img src='../img/guardar.png'/>" . "<pre>Editar...</pre>" .
                    "</button>";
                    echo "<td><button type='button' id='borrar_" . $id . "' class='borrar'>" .
                    "<img src='../img/delete.png'/>" . "<pre>Borrar...</pre>" .
                    "</button>";
                }
                echo "</tr>";
            }
        } else {
            $i = 0;
            $id = "";
            echo "<tr>";
            foreach ($select as $data) {
                $i++;
                $hayDatos = true;
                //El primer campo es el ID, así que recogemos ese dato
                if ($i == 1) {
                    $id = $data;
                }
                echo "<td>". $data ."</td>";
            }
            echo "<br><button type='button' id='editar_" . $id . "' class='edit'>" .
            "<pre>Editar...</pre>" .
            "</button>";
            echo "<br><button type='button' id='borrar_" . $id . "' class='borrar'>" .
            "<pre>Borrar...</pre>" .
            "</button>";
        }
    }
?>