jQuery(function() {

    /*
    let tablaE = document.querySelector("#tablaEMPRESAS");
    let tablaD = document.querySelector("#tablaDEMANDANTES");

    //Obtener datos desde AJAX--getDatosAdmin.php
    var datosEmp = obtenerDatos("empresas");
    var datosDem = obtenerDatos("demandantes");

    */

    var datosRecibir = null;

    $("#cPostalDem").on("keyup", function(e) {
        darProvincia($(this).val());
    });

    $("#provincia1").on("click", function() {
        alert("Escriba el Código postal en el campo de arriba,\ny se actualizará este campo adecuadamente.");
    });


    //Los campos para editar se harán editables tras 3 segundos... Así, evitamos que el usuario
    // edite dichos campos antes de que cargue por completo el DOM.
    let esperar3s = setTimeout(hacerEditables, 2500);

    /**
     * Esta función habilita que: si hacemos doble clic en los elementos de la tabla, estos se
     * harán editables, similar a en PHPMyAdmin.
     * Una vez terminemos, al presionar Editar todos los value's
     * de la fila que editamos se actualizarán sobre su respectiva fila
     * en la tabla de BBDD.
     */
    function hacerEditables() {
        let elementsTabla = document.querySelectorAll("div.mainContainer div.tablaCrud table td:not(:first-child, :nth-child(2))");
        for (let elem of elementsTabla) {
            elem.addEventListener('dblclick', function(e) {
                //Hacer enabled el botón de EDITAR
                habilitarBotonEdit(e.target.parentElement.parentElement);
                //Evitar el 'event bubbling', es decir que se superpongan los eventos a los demás elementos hijos (campo de texto que se introducira en las siguientes líneas a esta)
                e.stopPropagation();
                let campoTxt = document.createElement('input');
                campoTxt.setAttribute('value', e.target.innerText);
                campoTxt.setAttribute('type', 'text');
                campoTxt.setAttribute('class', 'areaTexto1');
                campoTxt.setAttribute('name', e.target.name);
                campoTxt.setAttribute('style', 'width: inherit; height: auto;');

                if (elem.firstChild != null) {
                    elem.replaceChild(campoTxt, elem.firstChild);
                } else {
                    elem.appendChild(campoTxt);
                }
                campoTxt.removeEventListener('dblClick', e.event);

                /**
                 * let butonSave = document.createElement('button');
                 butonSave.innerHTML = "<img src='../imgs/guardar.png' alt='SAVE'/>";
                 butonSave.style.width = "15px";
                 butonSave.setAttribute('name', 'enviar' + e.target.name);
                 butonSave.addEventListener('click', function(e) {

                 campoTxt.parentElement.appendChild(butonSave);
                });
                 */
                campoTxt.removeEventListener("dblclick", e.event);
                e.target.removeEventListener('dblclick', e.event);
                //campoTxt.addEventListener('ke', actualizarTxt(campoTxt.value));
            });
        }
        $(".actions").each(function(i, elem) {
            $(this).on("click", function(e) {
                procesarAccion(e.target);
            });
        });
    }

    /**
     * Función para 'hacerEditables()'
     * Habilitará el botón de edición, y se llama una vez se ha hecho doble clic en algún campo
     */
    function habilitarBotonEdit(elemPadre) {
        elemPadre.querySelectorAll("button.actions")[0].removeAttribute("disabled");
        elemPadre.querySelectorAll("button.actions")[0].removeAttribute("aria-disabled");
        elemPadre.querySelectorAll("button.actions")[0].setAttribute("style", "color: yellow");
    }

    function procesarAccion(elemento) {
        
        let elemTarget = elemento;
        if (elemTarget.id == "") {
            //si no se detecta en este punto el ID, es porque hemos clickado en un elemento hijo del botón.
            elemTarget = elemTarget.parentElement;
        }
        let tipoElemento = elemTarget.id.substring(0,1);

        let nombreTabla = ""; let campoID = "";
        switch (tipoElemento) {
            case "E": nombreTabla = "empresas"; campoID = "id_EMP";
            break;
            case "D": nombreTabla = "demandantes"; campoID = "id_DEM";
            break;
            case "O": nombreTabla = "ofertas_trab"; campoID = "ID_Oferta";
            break;
            case "H": nombreTabla = "habilidades"; campoID = "IDHabil";
            break;
        }
        let accion = elemTarget.id.substring(1, 7);
        //Recojo el ID del objeto a Editar de la fila
        let id = parseInt(elemTarget.id.substring(8));
        let filaTDs = elemTarget.parentElement.parentElement
            .querySelectorAll("td:not(:first-child, :nth-child(2))");
        
        if (accion.includes("editar")) {
            //Le pasaremos los campos del <TR> que estamos editando
            let arrayDatosEdit = recogerCamposEdit(filaTDs);
            if (arrayDatosEdit == null) {
                return null;
            }
            //Pasaremos el ID del elemento en la fila que hemos seleccionado.
            actualizarAJAX(
                arrayDatosEdit, nombreTabla, id, campoID
            );
        }
        if (accion.includes("borrar")) {
            if (confirm('¿Seguro que desea borrar el elemento de ' + nombreTabla + ' con ID ' + id + '?'
            + '\nESTA ACCIÓN NO SE PODRÁ DESHACER!')) {
                borraAJAX(nombreTabla, id, campoID);
            }
            //Si no, no hacer nada...
        }
    }

    let columnasNumericas = ["id_EMP", "id_DEM", "ID_Oferta", "IDHabil", "tlf", "cPostal", "confirm", "cv_visible"];
    /**
     * Guardará los datos de los campos de texto que se hayan editado
     */
    function recogerCamposEdit(listaTD) {
        let arrayDatosEdit = [];
        for (let dato of listaTD) {
            //clave => el atributo 'name' que guarda el nombre de la columna
            arrayDatosEdit.push(dato.getAttribute("name"));
            //valor => Ó bien el texto del elemento <td>...
            if (!dato.innerHTML.includes("<input")) {
                //(si el campo a editar es un campo numérico, no dejar que se almacene otro tipo de dato)
                if (columnasNumericas.includes(dato.getAttribute("name"))) {
                    if (isNaN(parseInt(dato.innerText))) {
                        alert("ERROR. Ha insertado un valor no-numérico en un campo sólo para números!");
                        return null;
                    }
                }
                arrayDatosEdit.push(dato.innerText);
            //... o el VALOR del ELEMENTO INTERNO (<input> de tipo texto)
            } else { 
                if (columnasNumericas.includes(dato.getAttribute("name"))) {
                    if (isNaN(parseInt(dato.children[0].value)) && dato.children[0].value != "") {
                        alert("ERROR. Ha insertado un valor no-numérico en un campo sólo para números!");
                        return null;
                    }
                }
                arrayDatosEdit.push(dato.children[0].value);
            }
        }

        return arrayDatosEdit;
    }

    /**
     * Actualizará en la base de datos los datos correspondientes a los campos que se han editado
     * 
     * @param camposEdit - Un Array ASOCIATIVO con los datos de los campos editados
     * al dar doble click && resto de campos sin editar.
     * @param tablaAEditar - La TABLA en BBDD sobre la que se realizará el UPDATE
     * @param id - El ID del elemento a actualizar en la @tablaAEditar indicada
     */
    function actualizarAJAX(camposEdit, tablaAEditar, id, campoID) {
        let datosSET = "";
        for (let i=0; i<camposEdit.length-1; i+=2) {
            datosSET += camposEdit[i];
            datosSET += "=";
            if (isNaN(parseInt(camposEdit[i+1])) || camposEdit[i+1] == "") {
                datosSET += "'" + camposEdit[i+1] + "',";
            } else {
                datosSET += camposEdit[i+1];
            }
        }
        //TODO - PROBAR ESTO, ELIMINAR y REPARAR REGISTRO VAMO!!!
        //cuando termine el bucle for, eliminar la coma que se queda al final
        datosSET = datosSET.substring(0, datosSET.length-1);
        $.ajax("../Repositories/API.php", {
            method: "POST",
            data: {
                tabla: tablaAEditar,
                DATOS_SET: datosSET,
                WHERE: campoID + " = " + id,
                accion: "PATCH"
            },
            success: function(data) {
                alert("tabla " + tablaAEditar.toUpperCase() + " actualizada!");
            },
            error: function(data, status) {
                alert("ERROR al actualizar || " + status);
            }
        });
    }

    /**
     * Eliminará en la base de datos la fila con el ID que se ha especificado.
     * 
     * @param tablaBorrar - La TABLA en BBDD sobre la que se realizará el DELETE.
     * @param id - El ID del elemento a hacerle DELETE.
     * @param campoID - Es el campo correspondiente para el ID del elemento que queremos borrar.
     */
    function borraAJAX(tablaBorrar, id, campoID) {
        $.ajax("../Repositories/API.php", {
            method: "POST",
            data: {
                tabla: tablaBorrar,
                WHERE: campoID + "=" + id,
                accion: "DELETE"
            },
            success: function(data) {
                alert("Se ha borrado la entrada " + id + " de " + tablaBorrar.toUpperCase());
            },
            error: function(data) {
                alert("ERROR al borrar || " + data);
            }
        });
    }

    function confirmarDelete(id, tipo) {
        let respuesta = "";
        if (tipo == "empresa") {
            respuesta = prompt("¿Seguro que quieres eliminar la empresa con ID." + id + "?\n" +
            "Esta acción no se podrá deshacer! [SI / NO]");
        } else if (tipo == "demandante") {
            respuesta = prompt("¿Seguro que quieres eliminar el demandante con ID." + id + "?\n" +
            "Esta acción no se podrá deshacer! [SI / NO]");
        }

        return respuesta;
    }
    
    //Poblar datos de las dos tablas: Empresas y Demandantes (usando AJAX)
    function obtenerDatos(tabla, id, campoID) {
        $.ajax("../Repositories/API.php", {
            type : "GET",
            data : {
                "tabla": tabla,
                "WHERE": "'" + campoID + "'=" + id
            },
            success : function(datosRecibidos){
                datosRecibir = (JSON.parse(datosRecibidos)).data;
            },
            error: function() {
                console.log("SE HA PRODUCIDO UN ERROR! Puede que el servicio" +
                    " de Base de Datos no esté activo." + 
                    " Si el problema persiste, póngase en contacto con un" +
                    " Administrador del Sistema ó con el desarrollador");
                    alert("SE HA PRODUCIDO UN ERROR! Puede que el servicio" +
                    " de Base de Datos no esté activo." + 
                    " Si el problema persiste, póngase en contacto con un" +
                    " Administrador del Sistema ó con el desarrollador");
                    return;
            }
        });
    }

    /**
     * Petición a servicio web del Catastro Nacional a través de AJAX, a esta función se le llama directamente desde *darProvincia(cpostal)*
     */
    function setMunicipios(provincia) {
        $.ajax('pedirMunip.php', { //Petición AJAX al fichero .PHP que se conectará al servicio online del Catastro (municipios)
            type: "POST",
            dataType: 'json', //si no lo especificamos, jquery intentará adivinar el tipo
            data: {
                prov: provincia
            },
            beforeSend: function () {
                $("#loadMuns").fadeIn();
            },
            complete: function () {
                setTimeout(function() {
                    $("#loadMuns").fadeOut();
                }, 1000);
            },
            success: function (data) {
                $(".listaMunips").each(function(e) {
                    $(this).html(''); //Vaciamos la lista de municipios; en esta función usamos identificador por CLASE para que los elementos duplicados también presenten estos datos
                });
                for (const provi of data) { //Rellenamos la lista con los datos del Catastro (obtenidos a través del fichero .PHP)
                    document.querySelectorAll(".listaMunips").forEach(function(elem) {
                        elem.innerHTML += '<option>' + provi + '</option>';
                    });
                }
            }
        })
    }

    /**
     * A partir de los dos primeros núms. del Codigo postal (prefijo), sacamos las provincias que le corresponden a dichos número.
     */
    function darProvincia(cpostal) {
        const cp_provincias = {
            1: "\u00C1lava", 2: "Albacete", 3: "Alicante", 4: "Almer\u00EDa", 5: "\u00C1vila",
            6: "Badajoz", 7: "Baleares", 8: "Barcelona", 9: "Burgos", 10: "C\u00E1ceres",
            11: "C\u00E1diz", 12: "Castell\u00F3n", 13: "Ciudad Real", 14: "C\u00F3rdoba", 15: "Coruña",
            16: "Cuenca", 17: "Gerona", 18: "Granada", 19: "Guadalajara", 20: "Guip\u00FAzcoa",
            21: "Huelva", 22: "Huesca", 23: "Ja\u00E9n", 24: "Le\u00F3n", 25: "L\u00E9rida",
            26: "La Rioja", 27: "Lugo", 28: "Madrid", 29: "M\u00E1laga", 30: "Murcia",
            31: "Navarra", 32: "Orense", 33: "Asturias", 34: "Palencia", 35: "Las Palmas",
            36: "Pontevedra", 37: "Salamanca", 38: "Santa Cruz de Tenerife", 39: "Cantabria", 40: "Segovia",
            41: "Sevilla", 42: "Soria", 43: "Tarragona", 44: "Teruel", 45: "Toledo",
            46: "Valencia", 47: "Valladolid", 48: "Vizcaya", 49: "Zamora", 50: "Zaragoza",
            51: "Ceuta", 52: "Melilla"
        };
        if (cpostal.length == 5 && cpostal <= 52999 && cpostal >= 1000) {
            $(".provincia1").each(function(e) {
                $(this).val(cp_provincias[parseInt(cpostal.substring(0, 2))]);
            });
            //También introducimos una lista de municipios de la provincia sugerida (AJAX + servicio web catastro)
            setMunicipios($(".provincia1").val());
        } else {
            $(".provincia1").each(function() {
                $(this).val("---");
            });
            $(".listaMunips").each(function(){
                $(this).html("");
            });
        }
    }
});