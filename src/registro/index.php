<?php
    session_start();
?>
<html lang="es">
<head>
    <title>Registrate hoy! - TELEJOBS</title>
    <link rel="stylesheet" href="../css/estilos.css"/>
    <link rel="stylesheet" href="../css/registro.css"/>
    <link rel="icon" href="../img/register.png"/>
    <script src="../js/jquery-3.6.3.js"></script>
</head>
<body class="registro">
<h5><img src="../img/TELEJOBS_minilogo2.png" alt="logoTelejobs"/></h5>
    <div class="container1">
        <div class="ventanaMain">
            <div class="flex-spaced" style="margin-bottom: 20px;">
                <h1 id="h1Registro">Regístrate en TELEJOBS!</h1>
                <p class="tip">1/2</p>
            </div>
            <a id="resetRegistro" href="index.php">&lt;-- Abortar Registro</a>
            <div class="loading-gif">
                <img class="cargando-elems" src="../img/loading_ajax.gif" alt="cargando..."/>
            </div>
            <fieldset class="registro">
                <legend>Registrarse como...</legend>
                <form action="index.php" method="post">
                    <div class="elec-mode">
                        <span class="active">EMPRESA</span>
                        <button type="button" id="modo" name="modoReg" class="toggle-btn">
                            <span class="toggle-btn-icon"></span>
                        </button>
                        <span class="inactive">DEMANDANTE</span>
                    </div>

                    <br>

                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="email123@ejemplo.com" required/>

                    <label for="password">Crea una contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="Introduce tu contraseña" required/>

                    <label for="confirmar_password">Repite tu contraseña:</label>
                    <input type="password" id="confirmar_password" name="confirmar_password" placeholder="Confirmar contraseña" required/>

                    <input name="startReg" type="submit" value="Regístrate!"/>
                    <input type="reset" value="Limpiar campos"/>
                </form>
            </fieldset>
            <div id="divOculto1">
                <form action="registro2.php" method="post">
                    <input type="hidden" name="emailHid"/>
                <fieldset>
                    <label for="nom1">Nombre:</label>
                    <input type="text" id="nom1" name="nom1"/>

                    <label for="apels1">Apellidos:</label>
                    <input type="text" id="apels1" name="apels1"/>

                    <label for="fnac1">¿Cuándo naciste?</label>
                    <input max="31/12/2004" type="date" id="fnac1" name="fnac1"/>

                    <label for="nif1">NIF/DNI:</label>
                    <input type="text" id="nif1" name="nif1" maxlength="9" placeholder="XXXXXXXXA"/>
                    <br>

                    <label for="gen1">Género (opc.)</label><br>
                    <input id="genH" name="gen1" type="radio" value="mal"/> <label for="genH">Hombre</label> <br>
                    <input id="genM" name="gen1" type="radio" value="fem"/> <label for="genM">Mujer</label>
                    <input id="genB" name="gen1" type="radio" value="n/b/"> <label for="genB">Prefiero no indicar</label>
                    <br><br>
                    <label for="tlf1">Teléfono de contacto:</label>
                    <div class="flex-nums">
                        <input type="text" placeholder="+XX" value="+34" title="Sólo se aceptan núms. de España, lo sentimos!" disabled/>
                        <input name="tlf1" id="tlf1" type="number" maxlength="13" max="999999999"/>
                    </div>

                    <label for="cpos1">Cód. Postal:</label>
                    <input id="cpos1" name="cpos1" type="number" max="99999" maxlength="5"/>

                    <label for="provincia1">Provincia:</label>

                    <div class="flex-nums">
                        <input class="provincia1" id="provincia1" name="provincia1" type="text" value="---" disabled aria-disabled="true"/>
                        <select class="listaMunips" name="munip1" id="listaMunips">
                            <!--Cargamos lista de municipios por AJAX-->
                        </select>
                        <div class="loading-gif-pq">
                            .
                            <img id="loadMuns" class="loading-gif-pq" src="../img/loading-gif.gif" alt="carga...">
                        </div>
                    </div>
                    <br>
                    <!--habilidades-->
                    <label for="habils">Habilidades / SKILLS</label>
                    <select name="habils" id="habils">

                    </select>
                    <br>
                    <textarea name="masHabils" id="masHabils" cols="30" rows="10" placeholder="Habilidades adicionales..." required></textarea>
                    <br>
                    <hr>
                    <input type="submit" name="registroDem" id="registroDem" value="COMPLETAR REGISTRO">

                </fieldset>
                </form>
            </div>
            <div id="divOculto2">
                <form action="registro2.php" method="post">
                    <input type="hidden" name="emailHid"/>
                    <fieldset>
                        <label for="nomEmpresa">Nombre de su EMPRESA:</label>
                        <input type="text" id="nomEmpresa" name="nomEmpresa"/>

                        <label for="fnac2">Fecha inicio de Actividad:</label>
                        <input max="31/12/2024" type="date" id="fnac2" name="fnac2"/>

                        <label for="cif1">CIF empresa:</label>
                        <input type="text" id="cif1" name="cif1" maxlength="9" placeholder="AXXXXXXXX"/>
                        <br>

                        <br><br>

                            <h3><b>Contacto:</b></h3> <h5>¡Haceos oír!</h5>

                        <label for="nom3">Nombre personal:</label>
                        <input type="text" id="nom3" name="nom3"/>

                        <label for="apels2">Apellidos:</label>
                        <input type="text" id="apels2" name="apels2"/>
                        <label for="tlf2">Teléfono de contacto:</label>
                        <div class="flex-nums">
                            <input type="text" placeholder="+XX" value="+34" title="Lo sentimos, sólo se permiten núms. Españoles"/>
                            <input name="tlf2" id="tlf2" type="number" maxlength="13" max="999999999"/>
                        </div>

                        <br>
                        <input type="checkbox" id="checkETT" name="checkETT"/>
                        <label for="checkETT">Se trata de una empresa de selección (Head-hunter,ETT).</label>
                        <br><br>
                        <label for="cpos2">Cód. Postal:</label>
                        <input id="cpos2" name="cpos2" type="number" max="99999" maxlength="5"/>

                        <label for="provincia1">Provincia:</label>

                        <div class="flex-nums">
                            <input class="provincia1" id="provincia1" name="provincia1" type="text" value="---" disabled aria-disabled="true"/>
                            <select class="listaMunips" name="munip2" id="listaMunips2">
                                <!--Cargamos lista de municipios por AJAX-->
                            </select>
                            <div class="loading-gif-pq-space">
                                .
                                <img id="loadMuns" class="loading-gif loading-gif-pq" src="../img/loading-gif.gif" alt="carga...">
                            </div>
                        </div>
                        <br>
                        <label for="desc1">Descripción de la empresa, y <b>actividad principal:</b></label>
                        <br>
                        <textarea id="desc1" name="desc1" placeholder="Inserte una breve descripción de su empresa y su actividad general. Añadir un eslogan pegadizo también puede servir!"></textarea>
                        <br>
                        <select name="actividad1" id="actividad1">
                            <option value="N/A">Seleccionar Actividad...</option>
                        </select>
                        <br>
                        <input type="text" id="actividadNew" name="actividadNew" placeholder="Escriba aquí si la actividad no figura en la lista">
                        <br>
                        <label for="dir1">Dirección:</label>
                        <input type="text" id="dir1" name="dir1" placeholder="Av. Destino del éxito, 33"/>

                        <br><br>
                        <input type="checkbox" id="reqCheck1" name="reqCheck1" required>
                        <label for="reqCheck1">Aceptar la <a href="../index/politica.php">política de privacidad</a> y los <a href="../index/terminos.php">términos del servicio</a>.</label>
                        <br>
                        <input name="registroEmp" id="registroEmp" type="submit" value="REGISTRAR EMPRESA">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="errores">
        <dialog id="d_error">

        </dialog>
    </div>
    <script src="../login/registro.js"></script>
</body>
</html>