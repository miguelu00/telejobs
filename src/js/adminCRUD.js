jQuery(function() {

    /*
    let tablaE = document.querySelector("#tablaEMPRESAS");
    let tablaD = document.querySelector("#tablaDEMANDANTES");

    //Obtener datos desde AJAX--getDatosAdmin.php
    var datosEmp = obtenerDatos("empresas");
    var datosDem = obtenerDatos("demandantes");

    */

    var datosRecibir = null;

    $("#tablaEMPRESAS tr button[type=button]").each(function(i, elem) {
        $(this).on("click", function(e) {
            rellenarCamposEdit(e);
        });
    });

    $("#cPostalDem").on("keyup", function(e) {
        darProvincia($(this).val());
    });

    $("#provincia1").on("click", function() {
        alert("Escriba el Código postal en el campo de arriba,\ny se actualizará este campo adecuadamente.");
    });

    let esperar3s = setTimeout(hacerEditables, 2500);

    /**
     * Si hacemos doble clic en los elementos de la tabla, estos se
     * harán editables, similar a en PHPMyAdmin.
     * Una vez terminemos, al presionar Editar todos los value's
     * de la fila que editamos se actualizarán.
     */
    function hacerEditables() {
        let elementsTabla = document.querySelectorAll("div.mainContainer div.tablaCrud table td");
        for (let elem of elementsTabla) {
            elem.addEventListener('dblclick', function(e) {
                e.stopPropagation();
                let campoTxt = document.createElement('input');
                campoTxt.setAttribute('value', e.target.innerText);
                campoTxt.setAttribute('type', 'text');
                campoTxt.setAttribute('class', 'areaTexto1');
                campoTxt.setAttribute('name', e.target.name);
                campoTxt.setAttribute('style', 'width: inherit; height: auto;');

                elem.replaceChild(campoTxt, elem.firstChild);
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
                e.target.removeEventListener('dblclick', e.event);
                //campoTxt.addEventListener('ke', actualizarTxt(campoTxt.value));
            });
        }
        let btnesAccion = document.getElementsByClassName('actions');
        for (const div of btnesAccion) {
            for (const boton of div.children) {
                boton.addEventListener('click', accion);
            }
        }
    }
    function editarOculto(e) {
        e.target.parentElement.nextElementSibling.value = e.target.innerText;
    }
    function recogerCamposEdit(numRow) {
        var datos = [];
        $("#editarP-" + numRow).parent().parent().children().even().not("td.actions").each(function(){
            //datos.push($(this).value);
            if ($(this).html().startsWith('<input')) {
                if ($(this).find('input:first').prop('name') == 'pvp') {
                    let valorCampo = $(this).find('input:first').val();
                    if (!isNaN(parseInt(valorCampo)) && !isNaN(parseFloat(valorCampo))) {
                        datos.push($(this).find('input:first').val());
                    } else {
                        alert("El precio debe ser un num. entero/decimal válido!");
                        //datos = null;
                        return false;
                    }
                } else {
                    datos.push($(this).find('input:first').val());
                }
            } else {
                datos.push($(this).html());
            }
        });
        return datos;
    }
    function accion(e) {
        //Recojo el ID del objeto a Editar de la fila
        let tipoElemento = e.target.id.substring(0,1);
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
        let accion = e.target.id.substring(1, 6);
        let id = parseInt(e.target.id.substring(7));
        let filaTDs = e.target.parentElement.parentElement
            .querySelectorAll("td:not(:first-child, :nth-child(2))");
        
        if (accion.contains('editar')) {
            //Pasaremos el ID del elemento en la fila que hemos seleccionado.
            actualizarAJAX(
                recogerCamposEdit(filaTDs), nombreTabla, id, campoID
            );
        }
        if (accion.contains('borrar')) {
            if (confirm('¿Seguro que desea borrar el ID ' + idProd + '?')) {
                borraAJAX(idProd);
            }
            //Si no, no hacer nada...
        }
    }

    /**
     * Guardará los datos de los campos de texto que se hayan editado
     */
    function recogerCamposEdit(listaTD) {
        let arrayDatosEdit = [];
        for (let dato of listaTD) {
            //clave => el atributo 'name' que guarda el nombre de la columna
            arrayDatosEdit.push(dato.getAttribute("name"));
            //valor => Ó bien el texto del elemento <td>...
            if (dato.innerText != "") {
                arrayDatosEdit.push(dato.innerText);
            } else {
                arrayDatosEdit.push(dato.children[0].value); //... o el VALOR del ELEMENTO INTERNO (input de tipo texto)
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
            datosSET += "'" + camposEdit[i+1] + "',";
        }
        //TODO - PROBAR ESTO, ELIMINAR y REPARAR REGISTRO VAMO!!!
        //cuando termine el bucle for, eliminar la coma que se queda al final
        datosSET = datosSET.substring(0, datosSET.length-1);
        $.ajax("../Repository/API.php", {
            method: "PUT",
            data: {
                tabla: tablaAEditar,
                DATOS_SET: datosSET,
                WHERE: campoID + " = " + id
            },
            success: function(data) {

            },
            error: function() {
                
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