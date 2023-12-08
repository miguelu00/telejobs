<?php
    session_start();
    include_once("../Utiles/autoload.php");
    include_once("../Utiles/comprobarSesion.php");
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="../../js/jquery-3.6.3.js"></script>
    <link rel="icon" href="../img/icons/settings.png"/>
    <link rel="stylesheet" href="../css/estilos.css"/>
    <link rel="stylesheet" href="../css/admin.css"/>
    <link rel="stylesheet" href="../css/loader.css"/>
    <link rel="stylesheet" href="../css/empresa.css"/>
    <link rel="stylesheet" href="../css/modalAdmin.css"/>
</head>
<body>
    <ul class="barra-nav">
        <li class="logo-emp">
            <a href="../index.php">
                <!--INSERTAR IMAGEN CUSTOM PARA ADMIN. TELEJOBS-->
                <img id="img-logo img-pq" class="img-nav no-border" src="../img/icons/AdminBanner_tj.png" alt="logoTJ"/>
            </a>
        </li>
    </ul>
    <br>
    <div class="mainContainer">
    <a href="#openModalDem" id="mostrarEditDEM" title="Editar..." class="hidden">
        <i style="font-size: 36px;" class="fas fa-edit"></i> Editar Demandantes...
    </a>
    <a href="#openModalEmp" id="mostrarEditEMP" title="Editar..." class="hidden">
        <i style="font-size: 36px;" class="fas fa-edit"></i> Editar Empresas...
    </a>
    <!-- MODALES para INSERTAR DEMANDANTES/EMPRESAS -->
    <div id="openModalDem" class="modalDialogDEM">
        <div>
            <a href="#closeDEM" title="Cerrar" class="closeDEM">X</a>
            <h2>Editar DEMANDANTE #</h2>
            <br>
            <table>
                <form id="formDemandante" action="" method="post" enctype="multipart/form-data">
                    <tbody>
                        <tr>
                            <td>ID DEMANDANTE: </td>
                            <td><input id="idDem" type="text" name="idDem" disabled aria-disabled="true" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>IDs de HABILIDADES: </td>
                            <td>
                                <select id="habilidadesDem" name="habilidadesDem" required aria-required="true">
                                    <!--Rellenar este select con AJAX, los value serán los ID de Habilidades-->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Experiencia: </td>
                            <td><input type="text" name="experienciaDem" id="experienciaDem" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Nombre: &nbsp; <input type="text" name="nombreDem" id="nombreDem" required aria-required="true"/></td>
                            <td>Apellidos: &nbsp; <input type="text" name="apellidosDem" id="apellidosDem" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Fecha nacimiento: </td>
                            <td><input type="date" name="fechaNacDem" id="fechaNacDem" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Teléfono: </td>
                            <td><input type="number" min="111111111" max="999999999" minlength="9" maxlength="9" name="tlfDem" id="tlfDem" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Cód. Postal: </td>
                            <td><input type="number" min="100" max="99999" minlength="3" maxlength="5" name="cPostalDem" id="cPostalDem" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Provincia y Municipio: </td>
                            <td><input type="text" class="provincia1" disabled id="provincia1" name="provincia1" required aria-required="true"/>
                                <select id="municipioDem" name="municipioDem" class="listaMunips">
                                    
                                </select> <!--&nbsp; <div class="loader img-pq" id="loadMuns" name="loadMuns"></div>-->
                            </td>
                        </tr>
                        <tr>
                            <td><span style="color:brown;">EMAIL: </span></td>
                            <td><input type="text" name="emailDem" id="emailDem" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Foto de perfil: </td>
                            <td><input type="file" name="fotoDem" id="fotoDem" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>NIF: </td>
                            <td><input type="text" maxlength="9" minlength="9" name="nifDem" id="nifDem" required aria-required="true"></td>
                        </tr>
                        <tr>
                            <td>¿Es público su CV? </td>
                            <td>
                                <select name="cvPublicoDem" id="cvPublicoDem" required aria-required="true">
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Email verificado (Confirmación de cuenta): </td>
                            <td><label id="confirmacionDem">1</label></td>
                        </tr>
                        <tr>
                            <td>
                                <button class="btn-success" type="button" id="guardarCambiosDem" name="guardarCambiosDem">GUARDAR CAMBIOS</button>
                                <button class="btn-orange" type="button" id="confirmarCuentaDem" name="confirmarCuentaDem">CONFIRMAR CUENTA</button>
                                <button class="btn-normal" id="cancelBtnDem">CANCELAR</button>
                            </td>
                            <td>
                                <button class="btn-danger" type="reset">
                                    <a href="#close" id="cancelBtnDem" name="cancelBtnDem" class="noEstiloshref">CANCELAR</a>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </form>
            </table>
        </div>
    </div>

    <div id="openModalEmp" class="modalDialogEMP">
        <div>
            <a href="#closeEMP" title="Cerrar" class="closeEMP">X</a>
            <h2>Editar EMPRESA #</h2>
            <br>
            <table>
                <form id="formEmpresa" action="" method="post" enctype="multipart/form-data">
                    <tbody>
                        <tr>
                            <td>ID EMPRESA: </td>
                            <td><input type="text" name="idEmp" disabled aria-disabled="true" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>IDs de HABILIDADES: </td>
                            <td>
                                <select name="habilidadesEmp" required aria-required="true">
                                    <!--Rellenar este select con AJAX, los value serán los ID de Habilidades-->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span style="color: darkcyan;">EMAIL: </span></td>
                            <td><input type="text" name="emailEmp" id="emailEmp" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Foto de perfil: </td>
                            <td><input type="file" name="fotoEmp" id="fotoEmp" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Nombre EMPRESA: &nbsp; <input type="text" name="nombreEmp" id="nombreEmp" required aria-required="true"/></td>
                            <td>Nombre EMPRESARI@: &nbsp; <input type="text" name="nombrePropEmp" id="nombrePropEmp" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Apellidos EMPRESARI@: </td>
                            <td><input type="text" id="apellidosEmp" name="apellidosEmp" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Actividad de Empresa: </td>
                            <td><input type="text" name="actividadEmp" id="actividadEmp" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Descripción: </td>
                            <td><input type="date" name="descripcionEmp" id="descripcionEmp" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Teléfono: </td>
                            <td><input type="number" min="111111111" max="999999999" minlength="9" maxlength="9" name="tlfEmp" id="tlfEmp" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Cód. Postal: </td>
                            <td><input type="number" min="100" max="99999" minlength="3" maxlength="5" name="cPostalDem" id="cPostalDem" required aria-required="true"/></td>
                        </tr>
                        <tr>
                            <td>Provincia y Municipio: </td>
                            <td><input type="text" class="provincia1" disabled id="provincia1" name="provincia1" required aria-required="true"/>
                                <select id="municipioEmp" name="municipioEmp" class="listaMunips">

                                </select> &nbsp; <div class="loader img-pq" id="loadMuns" name="loadMuns"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>CIF: </td>
                            <td><input type="text" name="cifEmp" id="cifEmp" maxlength="9" minlength="9" aria-required="true"></td>
                        </tr>
                        <tr>
                            <td>¿Es una empresa ETT / "Head-Hunter"? </td>
                            <td>
                                <select name="ettEmp" id="ettEmp" required aria-required="true">
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Email verificado (Confirmación de cuenta): </td>
                            <td><label id="confirmacionDem">1</label></td>
                        </tr>
                        <tr>
                            <td>
                                <button class="btn-success" type="button" id="guardarCambiosDem" name="guardarCambiosDem">GUARDAR CAMBIOS</button>
                                <button class="btn-orange" type="button" id="confirmarCuentaDem" name="confirmarCuentaDem">CONFIRMAR CUENTA</button>
                                <button class="btn-normal" id="cancelBtnDem">CANCELAR</button>
                            </td>
                            <td>
                                <button class="btn-danger" type="reset">
                                    <a href="#close" id="cancelBtnDem" name="cancelBtnDem" class="noEstiloshref">CANCELAR</a>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </form>
            </table>
        </div>
    </div>
    <br>
        <div class="tablaCrud">
        <h2>EMPRESAS</h2>
        <table class="tabla-datos" id="tablaEMPRESAS">
            <th>ID</th>
            <th>email</th>
            <th>Nombre Empresa</th>
            <th>Nombre_propio</th>
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
    </div>
    <script src="../js/adminCRUD.js"></script>
</body>
</html>

<?php
    function mostrarDatosTabla($tipoUser) {
        $select = []; $singleRow = false; $empresas = false;
        switch ($tipoUser) {
            case "empresas":
                $id = "id_EMP";
                $select = select("empresas", "id_EMP, email, nombre, nombre_Prop, actividad_p, tlf, cPostal, CIF, confirm, f_apertura, f_inscripcion");
                $empresas = true;
                if (isset($select["id_EMP"])) {
                    $singleRow = true;
                }
                break;
            case "demandantes":
                $id = "id_DEM";
                $select = select("demandantes", "id_DEM, email, NIF, apellidos, nombre, fechaNac, tlf, cPost, munip, cv_visible, confirm, f_inscripcion");
                if (isset($select["id_DEM"])) {
                    $singleRow = true;
                }
                break;
        }
        $tipo = ($empresas) ? "E" : "D";
        if (!$singleRow) {

            foreach ($select as $row) {
                echo "<tr>";
                //Creamos botones, por cada fila, para Editar/Eliminar la entrada actual (por ID)
                echo "<td><button type='button' id='" . $tipo . "editar_" . $row[$id] . "' class='edit'>" .
                "<img src='../img/guardar.png'/>" . "<pre>Editar...</pre>" .
                "</button>";
                echo "<td><button type='button' id='" . $tipo . "borrar_" . $row[$id] . "' class='borrar'>" .
                "<img src='../img/delete.png'/>" . "<pre>Borrar...</pre>" .
                "</button>";
                foreach ($row as $key => $value) {
                    echo "<td>" . $value ."</td>";
                }
                echo "</tr>";
            }
        } else {
            
            echo "<tr>";
            echo "<br><button type='button' id='" . $tipo . "editar_" . $select[$id] . "' class='btnCrud edit'>" .
            "<pre>Editar...</pre>" .
            "</button>";
            echo "<br><button type='button' id='borrar_" . $select[$id] . "' class='btnCrud borrar'>" .
            "<pre>Borrar...</pre>" .
            "</button>";
            foreach ($select as $data) {
                echo "<td>". $data ."</td>";
            }
            echo "</tr>";
        }

        // TODO - INVESTIGAR AUTOLOAD PARA ESTO, E IMPLEMENTAR CLASES//POO EN EL PROYECTO :(
        //$demand = new Demandante();
    }
?>