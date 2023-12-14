<?php
    session_start();
    if ($_SESSION['userData'] == null) {
        header("Location: ../../index.php?checkLogin=true");
        exit();
    } elseif (!array_key_exists("id_EMP", $_SESSION['userData'])) {
        header("Location: ../redirect.php");
        exit();
    }
    require_once("../../Utiles/mySQL.php");
    //require_once "../../Utiles/comprobarSesion.php";

    $ofertas = select("ofertas_trab", "*", "id_EMP=" . $_SESSION['userData']['id_EMP']);
?>
<html lang="en">
    <head>
        <title>Gestión ofertas... - TELEJOBS Empresas</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="icon" href="../../img/icons/bullseye.png"/>
        <link rel="stylesheet" href="../../css/estilos.css"/>
        <link rel="stylesheet" href="../../css/empresa.css"/>
        <script src="../../js/jquery-3.6.3.js"></script>
    </head>
    <body class="pag-ofertas">
    <nav>
        <ul class="barra-nav">
            <li class="logo-emp">
                <a href="../empresa/">
                    <img id="img-logo" class="img-nav" src="../../logo/telejobsEMP_logoNAV.png" alt="logoEmp"/>
                </a>
            </li>
            <li>
                <a id="backBtn" class="clear">&lt; Atrás</a>
            </li>
            <li>
                <i class="fa fa-address-book-o"></i>&nbsp;&nbsp;&nbsp;<h3>Ofertas publicadas</h3>
            </li>
        </ul>
    </nav>
    <div class="flex-main">
        <aside class="aside-izq">
            <div class="flex-centered-col">
                <button id="crearOferta"><i class="fa fa-plus"></i> Publicar oferta</button>
                <button id="trashOferta"><i class="fa fa-minus"></i> Descartar oferta</button>
                <!--<button id="avisarUser"><i class="fa fa-paper-plane"></i> Notificar usuario </button>-->
            </div>
        </aside>
        <div class="main-view">
            <div id="mostrarOfertas" class="menu0">
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
            </div>
            <div id="creador" class="hidden menu1">
                <form id="creacionOferta" action="#">
                    <label for="puesto1">Se busca personal para... </label><input id="puesto1" name="puesto1" type="text" placeholder="Puesto de trabajo"/>
                    <br><br>
                    <b style="font-family: Bahnschrift, Arial, sans-serif; font-size: 24px;">Experiencia?</b>
                    <input type="checkbox" name="enableEXP" id="enableEXP" checked aria-checked="true"/>
                    <div id="cajaExperiencia">
                        <div>
                            <label for="experiencia1">Experiencia en </label> <input id="experiencia1" name="experiencia1" type="text"/> <label for="time1">durante</label>
                            <select name="time1" id="time1">
                                <option value="6mes">&ge; 6 meses</option>
                                <option value="1year">Un año</option>
                                <option value="2year">Dos años</option>
                                <option value="3year">Tres años</option>
                                <option value="4year">Cuatro años</option>
                                <option value="5plus">Cinco o más</option>
                                <option value="10plus">Diez o más años</option>
                            </select>
                            &nbsp;<button id="addExp">+</button>
                        </div>
                    </div>
                    <br>
                    <b style="font-family: Bahnschrift, sans-serif; font-size: 24px;">Con habilidades en:</b>
                    <div id="cajaSkills">
                        <div>
                            <label for="hab1">Habilidad 1</label> <select name="hab1" id="hab1"></select>
                            <img class="ajax-loading" id="ajax-loadHabilidades" src="../../img/loading-gif.gif"/>
                            &nbsp;<button id="addHabil" name="addHabil">+</button>
                            <!--AÑADIR habilidades con JS + modal-->
                        </div>
                    </div>
                    <br>
                    <div id="cajaHorario">
                        <label for="horario">Horario</label> &nbsp; 
                        <select name="dianoc" id="dianoc">
                            <option value="diurno">Diurno</option>
                            <option value="nocturno">Nocturno</option>
                        </select>
                        <br>
                        <label for="horarioInicio">de</label>&nbsp;<input type="number" max="24" min="0" value="8" id="horarioInicio" name="horarioInicio"/> &nbsp;
                        <label for="horarioFin">a</label> &nbsp; <input type="number" max="24" min="0" value="16" id="horarioFin" name="horarioFin"/>
                    </div>
                    <br>
                    <label for="dateFinish">Fecha límite para candidaturas</label>&nbsp;  <input type="date" id="dateFinish" name="dateFinish"/>
                    <br><br>
                    <button class="btnConfirm" id="confirmarOferta" type="submit">Crear oferta!</button> <span id="warnMenu1" class="warning" hidden>Antes debes completar todos los campos!</span>
                </form>
            </div>
            <div id="eliminarOfertas" class="hidden menu2">
                <div>
                    <form action="#">
                        <label for="selectOfertas">
                            Selecciona una oferta: 
                        </label>
                        <select name="ofertaDel" id="ofertaDel">
                            <?php
                            if (array_key_exists("ID_Oferta", $ofertas)) {
                                echo "<option value='{$ofertas['ID_Oferta']}'>{$ofertas['puesto']} limite: {$ofertas['fecha_limite']}</option>";
                            } else {
                                foreach ($ofertas as $oferta) {
                                    echo "<option value='{$oferta['ID_Oferta']}'>{$oferta['puesto']} limite: {$oferta['fecha_limite']}</option>";
                                }
                            }
                            ?>
                        </select>
                            <button type="button" name="delOferta" id="delOferta">X</button>
                    </form>
                </div>
            </div>
        </div>

        <dialog style="z-index: 4; position: absolute; top: 10%; left: 70%;" id="dialogoError"></dialog>
        <!--<div style="position: absolute; top:0; left:0; transform:translateX(-50%) translateY(-50%);"></div>-->
    </div>
<!--    HIDDEN Input para recoger datos de Sesión; todo cambiar por Cookies-->
    <input type="hidden" id="nomEmp" name="nomEmp" value="<?php echo $_SESSION['userData']['nombre']; ?>"/>
    <input type="hidden" id="idEmp" name="idEmp" value="<?php echo $_SESSION['userData']['id_EMP']; ?>"/>
    </body>
    <script src="../../js/ofertas.js"></script>
    <script src="../../Utiles/cookies.js"></script>
    <script src="../../js/scriptlogo.js"></script>
</html>