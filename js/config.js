//Pagina configuración

$(document).ready(function() {
    var menuMainContainer = document.querySelector("#menu"),
    opcionesBar = $("div.menuOpciones a"),
        TIPOUSER = document.querySelector("#tipoUser").value;

    if (TIPOUSER === "demandantes") {
        menuMainContainer.style.backgroundColor = "rgba(255,255,46,0.69)";
    } else if (TIPOUSER === "empresas") {
        menuMainContainer.style.backgroundColor = "rgba(0,178,255,0.8)";
    }

    opcionesBar.each(function() {
        $(this).on("click", function(ev) {
            hideAll();
            let numDIV = $(this).prop("id");
            numDIV = numDIV.substring(numDIV.length-1);

            opcionesBar.each(function() {
                $(this).removeClass("active");
            });
            $(this).addClass("active");

            $(".menu" + numDIV).toggleClass("hidden");
        });
    });

    function hideAll() {
        $("div#menu > div").each(function() {
            $(this).addClass("hidden");
        });
    }

    document.querySelectorAll(".editToggle").forEach(function(elem) {
        elem.addEventListener("click", enableEditar);
    });

    $("button#borrarCuenta").on("click", confirmarBorrado);
    function confirmarBorrado() {
        let passwd = prompt("Buh-bye! :( Introduzca su contraseña para confirmar la eliminación de su cuenta:");
        if (passwd.length !== 0 && confirm("Si la contraseña es correcta se eliminará su cuenta junto con sus datos. ¿ESTÁ SEGURO?")) {
            comprobarAJAX(passwd);
        } else if (passwd.length == 0) {
            alert("La contraseña no puede estar vacía!");
        }
    }

    function comprobarAJAX(dato) {
        let formData = new FormData();
        formData.append("accion", "checkPW");
        formData.append("contra", dato);
        formData.append("tipoUser", TIPOUSER);

        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                let result = JSON.parse(this.responseText);
                if (result == '0') {
                    alert("La contraseña introducida no coincide! Fiu!");
                    return false;
                } else if (result == '1') {
                    alert("Se ha eliminado su cuenta correctamente! Ahora se volverá a la página principal");
                    window.location.reload();
                }
            }
        }
        xhttp.open('POST', '../../js/cargarDatos.php');
        xhttp.send(formData);
    }

    //Le pasamos eventHandler de un Button/Elemento colocado después de un Input
    function enableEditar(e) {
        editing = true;
        // if (e.target.previousElementSibling.type == "email") {
        //     alert("¡ATENCIÓN! Editando su dirección de correo. Apúntela bien, puesto que tendrá que usarla para iniciar sesión");
        // }
        let hermanoAnterior = e.target.previousElementSibling;
        let originalData = hermanoAnterior.value;
        hermanoAnterior.disabled = false;
        hermanoAnterior.focus();
        e.target.onclick = null;
        e.target.innerHTML = "Guardar Cambios";
        //Borramos el listener para editar el campo;
        e.target.removeEventListener('click', enableEditar);
        // y lo cambiamos por subir el cambio a la base de datos (UPDATE)
        e.target.addEventListener('click', function() {
            if (e.target.previousElementSibling.checkValidity()) {
                if (e.target.previousElementSibling.value == originalData) {
                    e.target.removeEventListener('click', e.event);
                    e.target.innerHTML = "Editar";
                    e.target.previousElementSibling.disabled = true;
                    e.target.addEventListener('click', enableEditar);
                    editing = false;
                    return false;
                }
                if (confirm("De ahora en adelante, usará este correo para INICIAR SESIÓN. ¿Está seguro de cambiarlo?")) {
                    //name del sibling -> Nombre del campo en BBDD ;; value del sibling -> valor a actualizar en ese campo NAME
                    let result = guardarCambio("demandante", e.target.previousElementSibling.name, e.target.previousElementSibling.value);
                    //Si se puede actualizar el campo, actualizamos el input de texto a este
                    e.target.removeEventListener('click', e.event);
                    e.target.innerHTML = "Editar...";
                    e.target.previousElementSibling.disabled = true;
                    e.target.innerHTML += '<i class="fa fa-check-square"></i>';
                    e.target.addEventListener('click', enableEditar);
                } else {
                    return false;
                }
            } else {
                //Mostrar fallo de validación HTML
                alert(e.target.previousElementSibling.validationMessage);
            }
        });
        e.target.previousElementSibling.addEventListener("keypress", clickPress);
    }

    function guardarCambio(tipoUser, campo, valor) {
        document.querySelectorAll("input[type=text]").forEach(function(elem) {
            elem.removeEventListener("keypress", clickPress);
        });
        imgTemporal = previewFoto.attr("src");
        //Funcion AJAX para guardar datos
        let xhttp = new XMLHttpRequest();
        let formVirt = new FormData();
        if (tipoUser == "empresa") {
            formVirt.append('tabla', 'empresas');
        } else {
            formVirt.append('tabla', 'demandantes');
        }
        formVirt.append('email', email.value);
        formVirt.append('editar', campo);
        formVirt.append('dato', valor);
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                if (this.responseText == '0') {
                    if (confirm('ERROR al actualizar! ¿Recargar la página?')) {
                        window.location.reload();
                    }
                    return false;
                } else {
                    editing = false;
                    return valor;
                }
            }
        }
        xhttp.open('POST', '../../js/actualizarBD.php');
        xhttp.send(formVirt);
    }
    function clickPress(event) { // Añadir funcionalidad al presionar Enter en el campo de Texto
        if (event.key == "Enter") {
            event.target.nextElementSibling.click();
        }
    }

});