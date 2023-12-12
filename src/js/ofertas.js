//Lado cliente - EMPRESA
//Script encargado de CONTROLAR LAS ACCIONES referentes a las OFERTAS DE EMPLEO [CRUD + AJAX]
const MAXEXPERIENCIA = 5;
const MAXHABILIDADES = 5;
jQuery(function() {
    let selected = [], toggle = false, cambios = false; //recolectar nodos según se SELECCIONE SU CHECKBOX
    let defaultTitle = $("nav h3").html(), nombreEMP = document.querySelector("#nomEmp").value;
    var contador1 = 2, contador2 = 1, addPlaceholder = document.querySelector("#addExp"), addHabilsPlaceholder = document.querySelector("#addHabil");
    var dialogoError = $("#dialogoError"), toggleBack = false, toggleExperiencia = true;

    /**
     * Se almacenará en esta variable las habilidades tomadas por AJAX/BBDD
     */
    var datosHabils = null;
    var datosOfertas = null;

    //Mostrar las ofertas generadas por la empresa actual en pantalla


    $("#crearOferta").on("click", abrirCreador); //Abrir/cerrar menú de crear ofertas
    $("#addExp").on("click", agregarExp); //agregar experiencia con botón (+)
    $("#addHabil").on("click", agregarHabil); //agregar habilidades con el botón (+)
    $("#enableEXP").on("click", function() {
        //Invertir toggleExperiencia
        toggleExperiencia = (toggleExperiencia) ? false : true;
        //Todos los elementos en #cajaExperiencia serán habilitados/deshabilitados específicamente
        $("#cajaExperiencia").each(function() {
            $(this).each(function() {
                $(this).slideToggle(toggleExperiencia);
            });
        });
    });//Desactivar div de "Experiencia"

    /**
     * Evento del botón para crear una nueva Oferta de Empleo
     */
    $("#confirmarOferta").on("click", function(e) {
        e.preventDefault();
        if (camposVacios()) {
            $("#warnMenu1").fadeIn();
            setTimeout(function() {
                $("#warnMenu1").fadeOut();
            }, 2500);
        } else {
            //Recoger datos y generar la nueva oferta de empleo.
            let conEXP = false; let experiencia = [];
            let habilidades = [];
            let horario = []; let fechaFin = [];
            let nombrePuesto = $("#puesto1").val();
            //si se ha habilitado la experiencia, recoger dichos datos
            if ($("#enableEXP").prop("checked")) {
                conEXP = true;
                $("#cajaExperiencia div").each(function(i, elem) {
                    i = i+1;
                    let expNombre = $("#experiencia" + i).val();
                    let expTiempo = $("#time" + i).val();
                    if (expNombre != "") {
                        experiencia.push(expNombre + "_" + expTiempo);
                    }
                });
            }
            $("#cajaSkills div").each(function(i, elem) {
                i = i+1;
                let habil = $("#hab" + i).val();
                habilidades.push(habil);
            });

            horario.push($("#horarioInicio").val());
            horario.push($("#horarioFin").val());

            fechaFin = $("#dateFinish").val().split("-");
            fechaFin = "" + fechaFin[2] + "/" + fechaFin[1] + "/" + fechaFin[0];

            let datosOferta = ["null", $("#idEmp").val(), "'" + nombrePuesto + "'", "'" + horario[0] + "-" + horario[1] + "'", "'" + experiencia.join(",") + "'","'" +  habilidades.join(",") + "'", "'" + fechaFin + "'"];
            crearYSubirOferta(datosOferta, conEXP);
        }
    });

     /**
     * Insertar una oferta de trabajo nueva en la BBDD, a través
     * de llamada a la API de telejobs
     */
    function crearYSubirOferta(datosOferta, conEXP) {
        let datosOf = datosOferta.join(",");
        $.ajax("../../Repositories/API.php", {
            method: 'POST',
            data: {
                tabla: "ofertas_trab",
                CAMPOS: "ID_Oferta, id_EMP, puesto, horario, exp_requerida, habilidades_ped, fecha_limite",
                DATOS: datosOf
            },
            success: function(data) {
                console.log(JSON.parse(data));
                alert("Oferta creada!");
            },
            error: function(data) {
                mostrarError("ERROR al generar la oferta deseada!");
            }
        });
    }

    /**
     * Comprobar si alguno de los campos de texto del menú con ID "creador"
     * están vacíos
     * 
     * Devuelve un booleano: TRUE si existe algún campo vacío, FALSE si no.
     */
    function camposVacios() {
        let vacios = false;
        $("#creador input[type=text]").each(function(i, elem) {
            if (elem.value == "") {
                vacios = true;
            }
        });
        if ($("#creador input[type=date]").val() == "") {
            vacios = true;
        }
        return vacios;
    }

    /**
     * Abrir/cerrar el menú para generar ofertas
     */
    function abrirCreador() {
        limpiarEstilos();
        //Marcar como activado/desactivado el menú para crear ofertas de trabajo
        (!toggle) ? toggle=true : toggle=false;
        if (toggle) {
            $("#crearOferta").addClass("active");
            $("#creador").fadeIn();
            $("#mostrarOfertas").fadeOut();
            $("nav h3").html("Creando oferta de Empleo para " + "<b>" + nombreEMP + "</b>");
            return true;
        }
        $("#creador").fadeOut();
        $("#mostrarOfertas").fadeIn();
        $("nav h3").html(defaultTitle);

    }

    function agregarExp(e) {
        e.preventDefault();
        if (contador1 < MAXEXPERIENCIA+1) {
            let contenedorExp = document.querySelector("#cajaExperiencia");

            contenedorExp.append(crearModeloExp(contador1, e.target)); //le pasamos el estado del contador2, y el botón de agregar
            e.target.remove();
            contador1++;

        } else {
            mostrarError("Máximo 5 entradas");
        }
        reordenarDivs();
    }
    function quitarExp(e) {
        e.preventDefault();
        if (contador1 > 2) {
            let contenedorExp = document.querySelector("#cajaExperiencia");

            if (e.target.parentElement.querySelector("input[type=text]").value != "") {
                alert("Vacía el campo antes de eliminar!");
                lastDiv.querySelector("input[type=text]").focus();
                return false;
            }
            e.target.parentElement.remove();
            contador1--;
            if (contador1 == 2) {
                //Esto quiere decir que ya hemos llegado al último input de experiencia
                e.target.remove(); // asi que eliminamos el botón de quitar Exp.
                document.querySelector("#cajaExperiencia div:first-child").appendChild(addPlaceholder);
            }
        }
        reordenarDivs();
    }

    function agregarHabil(e) {
        e.preventDefault();
        if (contador2 < MAXHABILIDADES) {
            let contenedorHabs = document.querySelector("#cajaSkills");
            contador2++;
            contenedorHabs.append(crearModeloHabils(contador2, e.target)); //le pasamos el estado del contador2, y el botón de agregar
            mostrarSelectHabilidades(datosHabils, "hab" + contador2);
            e.target.remove();
        } else {
            mostrarError("Máximo " + MAXHABILIDADES + " habilidades!");
        }
    }
    function quitarHabil(e) {
        e.preventDefault();
        if (contador2 > 1) {
            contador2--;
            e.target.parentElement.remove();
            if (contador2 == 1) {
                e.target.remove(); //eliminamos el botón de quitar Habilidades
                document.querySelector("#cajaSkills div:first-child").appendChild(addHabilsPlaceholder);
            }
        }
        reordenarDivs2();
    }

    /**
     * Funcionalidad botón Atrás
     */
    $("a#backBtn").on("click", backFunct);
    function backFunct(e) {
        if (!$("#crearOferta").hasClass("active") && !$("#trashOferta").hasClass("active") && !$("#avisarUser").hasClass("active")) {
            e.target.href = "../empresa/";
        } else {
            e.preventDefault();
            limpiarEstilos();
            $(".main-view > div").each(function() {
                $(this).addClass("hidden");
            });
        }
    }

    /**
     * Le damos los ID y otros atributos a los elementos del DIV
     * para mantenerlos ordenados en secuencia en el apartado de Experiencia.
     */
    function reordenarDivs() {
        let i = 0;
        document.querySelectorAll("#cajaExperiencia > div").forEach(function(elem) {
            i++;
            elem.querySelector("label").setAttribute("for", "experiencia" + i);
            elem.querySelector("input").setAttribute("id", "experiencia" + i);

            elem.querySelector("label:last-of-type").setAttribute("for", "time" + i);
            elem.querySelector("select").setAttribute("name", "time" + i);
            elem.querySelector("select").setAttribute("id", "time" + i);
        });
    }

    /**
     * Le damos los ID y otros atributos a los elementos del DIV de habilidades,
     * para que estos atributos se mantengan ordenados en secuencia.
     */
    function reordenarDivs2() {
        let i = 0;
        document.querySelectorAll("#cajaSkills > div").forEach(function(elem) {
            i++;
            elem.querySelector("label").setAttribute("for", "hab" + i);
            elem.querySelector("label").innerHTML = "Habilidad " + i;
            elem.querySelector("select").setAttribute("id", "hab" + i);
        });
    }

    /**
     * Pide y recoge los datos a JQuery, que hará una petición a la API de telejobs.
     * 
     * @param tabla - La tabla de mySQL sobre la que queremos realizar la consulta.
     * @param idCampo - El ID del campos sobre el que se plasmaran los datos de la consulta a la API.
     */
    function hacerQuery(tabla, idCampo) {
        switch (tabla) {
            case "habilidades":
                $.ajax("../../Repositories/API.php", {
                    type: 'GET',
                    data: {
                        tabla: "habilidades"
                    },
                    success: function(data) {
                        //Códigos 200...
                        datosHabils = JSON.parse(data);
                    },
                    error: function() {
                        //Códigos 300/400...
                        mostrarError("ERROR AJAX. Pongase en contacto con el Administrador!");
                    }
                });
                break;
            case "ofertas_trab":
                $.ajax("../../Repositories/API.php", {
                    type: 'GET',
                    data: {
                        tabla: "ofertas_trab"
                    },
                    success: function(data) {
                        datosOfertas = JSON.parse(data);
                    },
                    error: function() {
                        mostrarError("ERROR AJAX. Pongase en contacto con el Administrador!");
                    }
                });
        }
    }

    /**
     * Mostrará en el select indicado con id 'idCampo', los datos recogidos en el parámetro 'data'.
     * Y devolverá dichos datos.
     * 
     * @param data - Los datos (recogidos por AJAX) que se agregarán al select
     * @param idcampo - El ID del elemento <select> que queremos rellenar con dichos datos
     */
    function mostrarSelectHabilidades(data, idcampo) {
        let data2 = data.data;
        for (datos of data2) {
            $("#cajaSkills div select#" + idcampo).html(
                $("#cajaSkills div select#" + idcampo).html() + 
                "<option value='" + datos.IDHabil + "'>" + datos.nombre + " - " + "<b>" + datos.tipo + "</b>"
                + "</option>"
            );
        }
        return data;
    }

    /**
     * Función que añade un dialog (si no existe) al documento, e introduce dentro
     * un mensaje de ERROR
     * 
     * @param mensaje - El mensaje de Error que queremos que aparezca
     */ 
    function mostrarError2(mensaje) {
        let dialogErr = $("#dialogoError");
        if (dialogErr == null) {
            dialogErr = document.createElement("dialog");
        }

        //Recolocamos el z-index para que se mantenga encima del resto de elementos.
        dialogErr.attr("style", "z-index: 4; position: absolute; top: 10%; left: 70%;");
        dialogErr.attr("id", "dialogoError");
        dialogErr.html("<b>ERROR: </b>" + mensaje);
        document.body.appendChild(dialogErr);
    }

    /**
     * Crear la vista para los input de Habilidades; devuelve un nodoHTML (div equivalente a una entrada de habilidad)
     */
    function crearModeloHabils(numElem, botonAdd) {
        let label = document.createElement("label");
        label.setAttribute("for", "hab" + numElem);
        label.innerText = "Habilidad " + numElem;

        let select = document.createElement("select");
        select.setAttribute("id", "hab" + numElem);
        select.setAttribute("name", "hab" + numElem);

        let btnAdd = botonAdd.cloneNode(true);
        btnAdd.setAttribute("name", "addHabil");
        btnAdd.addEventListener("click", agregarHabil);

        let btnQuitar = document.createElement("button");
        btnQuitar.setAttribute("name", "quitarHabil");
        btnQuitar.innerText = "-";
        btnQuitar.style.fontSize = "18px";
        btnQuitar.style.color = "red";
        btnQuitar.addEventListener("click", quitarHabil);

        let divContainer = document.createElement("div");
        divContainer.append(label, select, btnAdd, btnQuitar);

        return divContainer;
    }

    /**
     * Crear la vista para los input de Experiencia; devuelve un nodoHTML (div equivalente a una entrada de experiencia)
     */
    function crearModeloExp(numElem, botonAdd) {
        let label = document.createElement("label");
        label.setAttribute("for", "experiencia" + numElem);
        label.innerText = "Experiencia en ";

        let input = document.createElement("input");
        input.setAttribute("type", "text");
        input.setAttribute("id", "experiencia" + numElem);
        input.setAttribute("name", "experiencia" + numElem);

        let label2 = document.createElement("label");
        label2.setAttribute("for", "time" + numElem);
        label2.innerText = "durante ";

        let select = document.createElement("select");
        select.setAttribute("name", "time" + numElem);
        select.setAttribute("id", "time" + numElem);

        select.innerHTML = "" +
            "<option value=\"6mes\">&ge; 6 meses</option>" +
            "<option value=\"1year\">Un año</option>" +
            "<option value=\"2year\">Dos años</option>" +
            "<option value=\"3year\">Tres años</option>" +
            "<option value=\"4year\">Cuatro años</option>" +
            "<option value=\"5plus\">Cinco o más</option>" +
            "<option value=\"10plus\">Diez o más años</option>";

        //add boton ; crear copia que tenga el mismo evento
        let divContenedor = document.createElement("div");
        let btn = botonAdd.cloneNode(true);
        btn.addEventListener("click", agregarExp);
        let btnLess = document.createElement("button");
        btnLess.setAttribute("id", "lessExp");
        btnLess.style.color = "red";
        btnLess.style.fontSize = "18px";
        btnLess.innerText = "-";
        btnLess.addEventListener("click", quitarExp);
        divContenedor.append(
            label, input, label2, select, btn, btnLess
        );
        return divContenedor;
    }

    function mostrarOfertas(datos) {
        
    }

    function limpiarEstilos() {
        $("#crearOferta").removeClass("active");
        $("#trashOferta").removeClass("active");
        $("#avisarUser").removeClass("active");
    }

    function mostrarError(texto) {
        dialogoError.html(texto + "<br><h5 class='grayOut'>[Click para cerrar]</h5>");
        dialogoError.show();
        document.querySelector("#dialogoError").addEventListener('click', function(e) {
            dialogoError.hide();
            document.querySelector("#dialogoError").removeEventListener("click", this);
        });
    }

    
    hacerQuery("habilidades", "hab" + contador2);
    hacerQuery("ofertas_trab", "mostrarOfertas");

    //esperamos a que terminen las tareas asíncronas, antes de quitar los gif de carga
    while (datosHabils == null) {
        
    }
    mostrarSelectHabilidades(datosHabils, "hab" + contador2);
    while (datosOfertas == null) {

    }
    mostrarOfertas(datosOfertas);
});