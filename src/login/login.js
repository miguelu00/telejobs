//AJAX - iniciar sesión
$(document).ready(function() {
    let dialogoError = document.querySelector("dialog.errores"), OK = false;
    let tipoUsuario = (document.title.endsWith("Empleo")) ? 4 : 3
    let tout;
    const checkearLogin = function(e) {
        //comprobar usuario y contraseña; almacenar datos
        let formularioElems = e.target.elements,
            datos = [];

        for (elem of formularioElems) {
            if (elem.id == "username") {
                if (elem.value == "") {
                    mostrarError("Introduce un email y contraseña válidos!");
                    elem.focus();
                }
                datos.push(elem.value);
            }
            if (elem.id == "passwd") {
                if (elem.value == "") {
                    mostrarError("La contraseña debe ser válida!");
                    elem.focus();
                }
                datos.push(elem.value);
            }
        }
        comprobarAJAX(datos[0], datos[1], e);
        e.preventDefault();
        //Deberemos esperar a que AJAX termine, antes de enviar el formulario
        tout = setTimeout(hacerLogin, 1000);
    }
    document.querySelector("form").addEventListener("submit", checkearLogin);

    let btnAdminBackdoor = document.querySelector("input#inADMIN");
    btnAdminBackdoor.addEventListener("click", function(e) {

        let user = document.querySelector("#username").value;
        let passwd = document.querySelector("#passwd").value;

        if (user == "" || passwd == "") {
            e.preventDefault();
            return;
        }

        document.location.replace("login.php?inADMIN=true&username=" + user + "&passwd=" + passwd);
    });
//Elimina el evento para comprobar datos en AJAX, SÓLO si los datos están CORRECTOS
    function hacerLogin() {
        if (OK) {
            document.querySelector("form").removeEventListener("submit", checkearLogin);
            document.querySelector("input[type=submit]").click();
        }
    }

    function probarSQL() {
        $.ajax('../js/cargarDatos.php', {
            type: 'POST',
            data: {
                datos: "testSQL"
            },
            success: function(data) {
                console.log(data);
            }
        })
    }

    function comprobarAJAX(email, passwd) {
        var retorno = null;
        $.ajax('../Utiles/checkLogin.php', {
            type: "POST",
            data: {
                'correo': email,
                'passwd': passwd
            },
            complete: function() {
                //$(".loading-gif").hide();
                //Usamos keys() para ver el núm. de Datos que nos devuelve data:{} en AJAX
                if (Object.keys(retorno).length == 2) {
                    if (retorno.tipoUser != tipoUsuario) {
                        mostrarError("La cuenta indicada no pertenece al grupo de registro correcto!");
                        OK=false;
                        return false;
                    } else {
                        OK=true;
                    }
                    if (retorno.loginCorrecto != "1") {
                        mostrarError("Contraseña incorrecta!");
                        OK=false;
                        return false;
                    } else {
                        OK=true;
                    }
                }
                if (Object.keys(retorno).length == 1) {
                    mostrarError("ERROR. Esa cuenta de correo no existe");
                    OK=false;
                }
            },
            success: function(data) {
                //Como en el servidor devolvemos un array JSON, necesitaremos parse()
                retorno = JSON.parse(data);
            }
        });
    }
    function mostrarError(texto) {
        dialogoError.innerHTML = texto;
        dialogoError.innerHTML += "<br><h5 class='grayOut'>[Click para cerrar]</h5>"
        dialogoError.show();
        dialogoError.addEventListener('click', function(e) {
            dialogoError.removeEventListener(this, e.event);
            dialogoError.close();
        });
        document.querySelectorAll("input[type=email], input[type=password]").forEach(
            function(elem) {
                elem.addEventListener("click", function() {
                    dialogoError.close();
                });
            }
        );
    }
});