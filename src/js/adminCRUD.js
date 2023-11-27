jQuery(function() {

    /*
    let tablaE = document.querySelector("#tablaEMPRESAS");
    let tablaD = document.querySelector("#tablaDEMANDANTES");

    //Obtener datos desde AJAX--getDatosAdmin.php
    var datosEmp = obtenerDatos("empresas");
    var datosDem = obtenerDatos("demandantes");

    */
    
    let campoCodPostalDem = document.querySelector("#cPostalDem");
    campoCodPostalDem.on("keyup", function(e) {
        darProvincia($(this).val());
    });
    let campoCodPostalEmp = document.querySelector("#cPostalEmp");
    campoCodPostalEmp.on("keyup", function(e) {
        darProvincia($(this).val());
    });

    $("#provincia1").on("click", function() {
        alert("Escriba el Código postal en el campo de arriba,\ny se actualizará este campo adecuadamente.");
    });

    let consultaDems = obtenerDatos("demandantes");


    /**
     * Listener para el evento en que hagamos clic en un botón de la tabla,
     * eligirá qué hacer dependiendo del ID que tenga (incluye el ID del elemento y su acción)
     */
    function editarCampos(e) {
        //Recojo el ID del objeto a Editar de la fila
        let tipoElemento = e.target.id.substring(0,1);
        let accion = e.target.id.substring(1, 6);
        let id = parseInt(e.target.id.substring(7));
        let filaTDs = e.target.parentElement.parentElement.children;

        switch (accion) {
            case "editar":
                
                for (td of filaTDs) {
                //Sacar valor y poner en el formulario del Modal de edición correspondiente
                    let datosArray = sacarDatosID(tipoElemento, id);
                }
            break;
            case "borrar":

                borrarElemID(tipoElemento, id);
        }
        
    }
    
    //tipoElemento -> si se trata de Empresa (E) ó Demandante (D)
    function sacarDatosID(tipoElemento, id) {
        //Si el ID es -1 (menos uno), sacar todos los datos de la tabla. En caso contrario, los del ID pasado
        let conID = (id != -1) ? true : false;
        if (conID) {
            if (tipoElemento == "D") {
                let datos = obtenerDatos("demandantes", id);
                if (datos.length > 0 && datos.length == 1) {
                    return datos;
                } else {
                    return null;
                }
            }
            if (tipoElemento == "E") {
                let datos = obtenerDatos("empresas", id);
                if (datos.length > 0 && datos.length == 1) {
                    return datos;
                } else {
                    return null;
                }
            }
        }

        if (tipoElemento == "D") {
            let datos = obtenerDatos("demandantes");
            if (datos.length > 0 && datos.length == 1) {
                return datos;
            } else {
                return null;
            }
        }
        if (tipoElemento == "E") {
            let datos = obtenerDatos("empresas");
            if (datos.length > 0 && datos.length == 1) {
                return datos;
            } else {
                return null;
            }
        }
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
    function obtenerDatos(tabla) {
        $.ajax("getDatosAdmin.php", {
            type : "POST",
            data : {
                "select": tabla
            },
            success : function(datosRecibidos){
                if (datosRecibidos === -1) {
                    //mostrarError(""SE HA PRODUCIDO UN ERROR! Puede que el servicio" +
                    //" de Base de Datos no esté activo." + 
                    //" Si el problema persiste, póngase en contacto con un" +
                    //" Administrador del Sistema ó con el desarrollador");
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


                return (JSON.parse(datosRecibidos)).data;
            }
        });
    }

    //Poblar datos de las dos tablas: Empresas y Demandantes (usando AJAX)
    function obtenerDatos(tabla, id) {
        $.ajax("getDatosAdmin.php", {
            type : "POST",
            data : {
                "select": tabla,
                "id": id
            },
            success : function(datosRecibidos){
                if (datosRecibidos === -1) {
                    //mostrarError(""SE HA PRODUCIDO UN ERROR! Puede que el servicio" +
                    //" de Base de Datos no esté activo." + 
                    //" Si el problema persiste, póngase en contacto con un" +
                    //" Administrador del Sistema ó con el desarrollador");
                    console.log("SE HA PRODUCIDO UN ERROR! Puede que el servicio" +
                    " de Base de Datos no esté activo." + 
                    " Si el problema persiste, póngase en contacto con un" +
                    " Administrador del Sistema ó con el desarrollador");
                    return;
                }

                return (JSON.parse(datosRecibidos)).data;
            }
        })
    }

    //Petición a servicio web del Catastro Nacional a través de AJAX, a esta función se le llama directamente desde *darProvincia(cpostal)*
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

    //A partir de los dos primeros núms. del Codigo postal (prefijo), sacamos las provincias que le corresponden a dichos número.
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