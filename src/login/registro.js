jQuery(function() {
    // REGISTRO - Manejar selector modo de registro, validar campos
    const toggleBtn = document.querySelector('.toggle-btn');
    let activo = false, sigPagina = false;
    const textoBtn = document.querySelectorAll('.elec-mode span');
    const form = document.querySelector('form'); //PRIMER FORMULARIO
    const regExpPW = new RegExp(/^(?=.*[A-Z])(?=.*\d).+$/);
    let dialogoError = document.querySelector("#d_error");
    dialogoError.close();
//let campErrors = [];

    form.onsubmit = validarCampos;
    $("#cpos1").on('keyup', function() {
        darProvincia($(this).val())
    });
    $("#cpos2").on('keyup', function() {
        darProvincia($(this).val())
    });
//Establecer un valor por defecto para el botón de elección EMPRESA/DEMANDANTE
    toggleBtn.value = "empresa";
//Cambiar valor/estilos a través de CLICK del botón
    toggleBtn.addEventListener('click', () => {
        toggleBtn.classList.toggle('active');

        //El registro para demandantes tendrá 3 pasos;
        // (EMAIL, DATOS, CV)
        if (activo) {
            toggleBtn.value = "empresa";
            $(".tip").text('1/2');
            activo = false;
        } else {
            toggleBtn.value = "demandante";
            $(".tip").text('1/3');
            activo = true;
        }
        cambiarClaseTxt(textoBtn);
    });
    jQuery(function() {
        cargarHabilidades();
        cargarActividades();
    });

    function cambiarClaseTxt(arrTxts) {
        for (const spantext of arrTxts) {
            spantext.classList.toggle('inactive');
            spantext.classList.toggle('active');
        }
    }

    function validarCampos(e) {
        e.preventDefault();
        $("#resetRegistro").fadeIn();

        let todoBien = true;
        let email = $("#email").val(),
            correosOcultos = document.querySelectorAll("input[type=hidden]");
        correosOcultos[0].value = email;
        correosOcultos[1].value = email;
        let contras = form.querySelectorAll('input[type="password"]');
        //Primero, comprobamos el email
        for (const dato of ['.com', '.es', '.net', '.org', '.gov', '.mx', '.ong', '.ngo']) {
            if (email.endsWith(dato)) {
                todoBien = true;
                break;
            }
            todoBien = false;
        }
        if(!todoBien) {
            mostrarError("Introduce un email válido!");
            efectoShake(); //animación hecha con CSS + JS
            return;
        }
        // Comprobación contraseñas
        if (contras[0].value != contras[1].value) {
            todoBien = false;
            mostrarError('Las contraseñas deben coincidir!');
            efectoShake();
            return;
        }

        if (!regExpPW.test(contras[0].value)) {
            todoBien = false;
            mostrarError("ERROR. La contraseña debe contener al menos UNA <b>letra mayúscula</b> y UN <b>número</b>");
            efectoShake();
            return;
        }

        if (!todoBien) {
            efectoShake()
            return;
        } else {
            sigPagina = true;
            checkAjax();
        }

        //Si todo ha ido bien, se indica al usuario que se ha avanzado a la segunda Fase del registro.
        if (sigPagina) {
            $("#h1Registro").text('Datos adicionales...');
            $(".tip").text('2/2');
        }
    }

    function efectoShake() {
        document.querySelector('.ventanaMain').style.animation = "shake 0.4s forwards";
        setTimeout(function() {
            document.querySelector('.ventanaMain').style.animation = "";
        }, 450);
    }

    function loadNextDem() {
        setTimeout(function() {
            $("#divOculto1").fadeIn();
        }, 50);
        applyNIFEvento();
    }

    function loadNextEmp() {
        setTimeout(function() {
            $("#divOculto2").fadeIn();
        }, 100);
    }

    function applyNIFEvento() {
        $("#nif1").on("keyup", function() {
            let valor = $(this).val();
            if (!isNaN(parseInt(valor)) && valor.length == 8) {
                valor = parseInt($(this).val());
                $(this).val(valor + getLetraDNI(valor) + "");
            }
        });
    }

//hacer que se pueda descartar la selección en los radio input
    $('input[type="radio"]').on("mousedown", function() {
        if (this.checked) {
            $(this).on("mouseup",function(e) {
                var radio = this;
                setTimeout(function() {
                    radio.checked = false;
                }, 5);
                $(this).off("mouseup");
            });
        }
    });

//Chequeamos el email (que no esté registrado) y validamos el password a través de ajax
    function checkAjax() {
        let email = document.querySelector("#email").value;
        let contra = document.querySelector("#password").value;

        $.ajax('../login/checkReg.php', {
            type: "POST",
            data: {
                'correo': email,
                'password': contra,
                'tipoReg': toggleBtn.value
            },
            beforeSend: function() {
                $(".loading-gif").show();
            },
            complete: function() {
                $(".loading-gif").hide();
            },
            success: function(data) {
                $("fieldset.registro").fadeOut();
                switch(data) {
                    case "1":
                        if (toggleBtn.value == "empresa") {
                            loadNextEmp();
                            return true;
                        }
                        if (toggleBtn.value == "demandante") {
                            loadNextDem();
                            return true;
                        }
                        return false;
                    case "3":
                        if (toggleBtn.value == "empresa") {
                            mostrarError("ERROR. Ya existe una cuenta con esa dirección de correo!" +
                                "Intenta <a href='../login/enterprise.php'>iniciar sesión</a> si aún no has completado tu registro!");
                        }
                        if (toggleBtn.value == "demandante") {
                            mostrarError("ERROR. Ya existe una cuenta con esa dirección de correo!" +
                                "Intenta <a href='../login/jobs.php'>iniciar sesión</a> si aún no has completado tu registro!");
                        }
                        return false;
                }
            },
            error: function() {
                mostrarError("ERROR DE CONEXIÓN a mySQL. Comprueba tu conexión e intentalo de nuevo. Si el problema persiste, póngase en contacto con el Administrador en <b>mafvpersonal@gmail.com</b>.");
            }
        });
        //TODO Devolver TRUE/FALSE de forma NO ASÍNCRONA [ESE ES EL FALLO!!!]
        //TODO chequea tb la Conexion de xampp a la BASE DE DATO

        // $.post("checkReg.php", {
        //     correo: email,
        //     password: contra
        // }, function(data, status) {
        //     result = data;
        // if (result != null && result == 1) {
        //     return true;
        // } else {
        //     if (result == "2") {
        //         alert("La contraseña debe contener al menos UNA letra mayúscula y UN número");
        //     }
        //     if (result == "3") {
        //         alert("ERROR. Ese correo ya existe en nuestra Base de Datos!");
        //     }
        //     return false;
        // }
        // })
    }

    function mostrarError(texto) {
        efectoShake();
        dialogoError.innerHTML = texto;
        dialogoError.innerHTML += "<br><h5 class='grayOut'>[Click para cerrar]</h5>"
        dialogoError.show();
        dialogoError.addEventListener('click', function(e) {
            dialogoError.removeEventListener(this, e.event);
            dialogoError.close();
        });
    }
//Petición a servicio web del Catastro Nacional a través de AJAX
    function setMunicipios(provincia) {
        $.ajax('pedirMunip.php', {
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
                    $(this).html('');
                });
                for (const provi of data) {
                    document.querySelectorAll(".listaMunips").forEach(function(elem) {
                        elem.innerHTML += '<option>' + provi + '</option>';
                    });
                }
            }
        })
    }

    /**
     * Cargará, a través de una llamada a la API de telejobs, las
     * actividades que realizan las empresas
     */
    function cargarActividades() {
        $.ajax('../Repositories/API.php', {
            type: "GET",
            data: {
                tabla: "empresas",
                CAMPOS: "actividad_p",
                registro: "true"
            },
            beforeSend: function() {
                //$("#loadHabils").fadeIn();
            },
            complete: function() {
                //setTimeout(function() {
                //    $("#loadHabils").fadeOut();
                //}, 800);
            },
            success: function(data) {
                let select = $("#actividad1");
                data = JSON.parse(data);
                data = data.data;
                if (data != null) {
                    for (dato of data) {
                        select.html(select.html() + "<option value='" + dato.actividad_p + "'>" + dato.actividad_p + "</option>");
                    }
                }
            }
        });
    }

    /**
     * Cargará, a través de una llamada a la API de telejobs, las
     * habilidades que se pedirán de los empleados.
     */
    function cargarHabilidades() {
        $.ajax('../Repositories/API.php', {
            type: "GET",
            data: {
                datos: "habilidades",
                registro: "true"
            },
            beforeSend: function() {
                $("#loadHabils").fadeIn();
            },
            complete: function() {
                setTimeout(function() {
                    $("#loadHabils").fadeOut();
                }, 800);
            },
            success: function(data) {
                let select = $("#habils"), datos = JSON.parse(data);
                select.html("");
                //Sacamos los index (ID) de los datos, como value
                for (const dato of datos) {
                    let salida = "";
                    salida = "<option value='" + dato.IDHabil + "'>" + dato.nombre + "</option>";
                    select.html(select.html() + salida);
                }
            }
        });
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
            setMunicipios($(".provincia1:eq(1)").val());
        } else {
            $(".provincia1").each(function() {
                $(this).val("---");
            });
            $(".listaMunips").each(function(){
                $(this).html("");
            });
        }
    }
//Mediante el operador mód. Sacamos la letra de DNI correspondiente
    function getLetraDNI(dni) {
        let caracteres = "TRWAGMYFPDXBNJZSQVHLCKE";
        return caracteres.charAt(dni%23);
    }
});