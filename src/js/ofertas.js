//Lado cliente - EMPRESA
//Script encargado de CONTROLAR LAS ACCIONES referentes a las OFERTAS DE EMPLEO [CRUD + AJAX]
const MAXEXPERIENCIA = 5;
jQuery(function() {
    let selected = [], toggle = false, cambios = false; //recolectar nodos según se SELECCIONE SU CHECKBOX
    let defaultTitle = $("nav h3").html(), nombreEMP = document.querySelector("#nomEmp").value;
    var contador1 = 2, contador2 = 1, addPlaceholder = document.querySelector("#addExp");
    var dialogoError = $("#dialogoError"), toggleBack = false, toggleExperiencia = true;

    $("#crearOferta").on("click", abrirCreador); //Abrir/cerrar menú de crear ofertas
    $("#addExp").on("click", agregarExp); //agregar experiencia con botón (+)
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

    function abrirCreador() {
        limpiarEstilos();
        (!toggle) ? toggle=true : toggle=false;
        if (toggle) {
            $("#crearOferta").addClass("active");
            $("#creador").fadeIn();
            $("nav h3").html("Creando oferta de Empleo para " + "<b>" + nombreEMP + "</b>");
            return true;
        }
        $("#creador").fadeOut();
        $("nav h3").html(defaultTitle);

    }

    function agregarExp(e) {
        e.preventDefault();
        if (contador1 < MAXEXPERIENCIA+1) {
            let contenedorExp = document.querySelector("#cajaExperiencia");

            contenedorExp.append(crearModeloExp(contador1, e.target));
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
                e.target.remove();
                document.querySelector("#cajaExperiencia div:first-child").appendChild(addPlaceholder);
            }
        }
        reordenarDivs();
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
     * Le damos los ID y otros atributos para que se mantengan ordenados 
     * los elementos del DIV para "experiencia".
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
                        mostrarSelectHabilidades(JSON.parse(data), idCampo);
                    },
                    error: function() {
                        //Códigos 300/400...
                        mostrarError("ERROR AJAX. Pongase en contacto con el Administrador!");
                    }
                })
            break;
        }
    }

    function mostrarSelectHabilidades(data, idcampo) {
        data = data.data;
        for (datos of data) {
            $("#cajaSkills div select#hab" + idcampo).html(
                $("#cajaSkills div select#hab" + idcampo).html() + 
                "<option value='" + datos.IDHabil + "'>" + datos.nombre + " - " + "<b>" + datos.tipo + "</b>"
                + "</option>"
            );
        }
    }

    //Función que añade un dialog (si no existe) al documento, e introduce dentro
    // un mensaje de ERROR
    function mostrarError2(mensaje) {
        let dialogErr = $("#dialogoError");
        if (dialogErr == null) {
            dialogErr = document.createElement("dialog");
        }

        dialogErr.attr("style", "z-index: 4; position: absolute; top: 10%; left: 70%;");
        dialogErr.attr("id", "dialogoError");
        dialogErr.html("<b>ERROR: </b>" + mensaje);
    }

    //Crear el modelo para los input de Experiencia; devolver un nodoHTML
    function crearModeloExp(numElem, botonAdd) {
        let label = document.createElement("label");
        label.setAttribute("for", "experiencia" + numElem);
        label.innerText = "Experiencia en ";

        let input = document.createElement("input");
        input.setAttribute("type", "text");
        input.setAttribute("id", "experiencia" + numElem);

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
});