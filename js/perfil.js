$(document).ready(function() {
    let toggle = false, dialogoError = document.querySelector("dialog.mensajes"),
        email = document.querySelector("#emailUser"), previewFoto = $("#previewFoto"),
        imgTemporal = previewFoto.attr("src"), editing = false;

    const TIPOUSER = document.querySelector("#tipoUser").value;

    previewFoto.on("mouseover", function() {
        $(".flex-profile .img-edit div i").css("opacity", "1");
    });
    previewFoto.on("mouseleave", function() {
        $(".flex-profile .img-edit div i").css("opacity", "0");
    });

    $('#editProfile').on('change',procesarIMG); //Listener para cambiar foto de perfil

    document.querySelectorAll(".editToggle").forEach(function(elem) {
        elem.onclick = enableEditar;
    });

    function mostrarError(texto) {
        dialogoError.innerHTML = texto;
        dialogoError.innerHTML += "<br><h5 class='grayOut'>[Click para cerrar]</h5>"
        dialogoError.show();
        dialogoError.addEventListener('click', function(e) {
            dialogoError.removeEventListener(this, e.event);
            dialogoError.close();
        });
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
                //name del sibling -> Nombre del campo en BBDD ;; value del sibling -> valor a actualizar en ese campo NAME
                let result = guardarCambio("demandante", e.target.previousElementSibling.name, e.target.previousElementSibling.value);
                //Si se puede actualizar el campo, actualizamos el input de texto a este
                e.target.removeEventListener('click', e.event);
                e.target.innerHTML = "Editar...";
                e.target.previousElementSibling.disabled = true;
                e.target.innerHTML += '<i class="fa fa-check-square"></i>';
                e.target.addEventListener('click', enableEditar);
            } else {
                //Mostrar fallo de validación HTML
                mostrarError(e.target.previousElementSibling.validationMessage);
            }
        });
        e.target.previousElementSibling.addEventListener("keypress", clickPress);
    }
    function clickPress(event) { // Añadir funcionalidad al presionar Enter en el campo de Texto
        if (event.key == "Enter") {
            event.target.nextElementSibling.click();
        }
    }

//Valida el TIPO y TAMAÑO de la imágen subida, y si es correcta la sube como imágen temporal al servidor
    function procesarIMG() {
        let ficheroTMPimg = document.querySelector('#editProfile').files[0];
        if ((ficheroTMPimg.type !== "image/jpeg") && (ficheroTMPimg.type !== "image/png")) {
            mostrarError("Sólo se admiten ficheros PNG/JPEG como foto de perfil!");
            //ficheroTMPimg.reset();
            return false;
        }
        if (ficheroTMPimg.size > 5242880) {
            mostrarError("El fichero debe ser menor a 5MB.!");
            //ficheroTMPimg.reset();
            return false;
        }
        var Data = new FormData(document.querySelector("#formIMG"));
        Data.append('accion', 'subirFotoTEMP');
        Data.append('dato', ficheroTMPimg.name);
        Data.append('editar', 'foto');
        Data.append('email', email.value);
        if (TIPOUSER == "empresa") {
            Data.append('tabla', 'empresas');
        } else {
            Data.append('tabla', 'demandantes');
        }
        $.ajax({
            url: "../../js/actualizarBD.php",
            data: Data,
            processData: false,
            contentType: false,
            type:'POST',
            success: function(data){
                if(data != "0"){
                    alert(data);
                    $("#opcsImagen").slideDown();
                    previewFoto.attr("src", data);
                } else { //if (!data.success) ...
                    mostrarError("Error SERVER. Fallo al subir el fichero de Imagen. Inténtelo de nuevo más tarde..."); //error backend handler
                }
            },
            error: function(data){
                mostrarError("ERROR AJAX: " + data + ".<br> Inténtelo de nuevo! Si el error persiste, póngase en contacto con el Admin => mafvpersonal@gmail.com."); //error handler Ej:404 status
            }
        });
    }
//Activar la foto de perfil, y asignarsela al Usuario. Mostrar msj de confirmación
    function setFoto(pathToFoto) {
        var Data = new FormData();
        Data.append("accion", "confirmarFoto");
        Data.append("dato", pathToFoto);
        Data.append('editar', 'foto');
        Data.append('email', email.value);
        if (TIPOUSER == "empresa") {
            Data.append('tabla', 'empresas');
        } else {
            Data.append('tabla', 'demandantes');
        }
        $.ajax({
            url: "../../js/actualizarBD.php",
            data: Data,
            processData: false,
            contentType: false,
            type:'POST',
            success: function(data){
                if(data != "0"){
                    alert("Imagen actualizada correctamente!");
                    $("#opcsImagen").slideUp();
                } else { //if (!data.success) ...
                    mostrarError("Error al actualizar la BBDD. Inténtelo de nuevo más tarde."); //error backend handler
                }
            },
            error: function(data){
                mostrarError("ERROR AJAX: " + data + ".<br> Inténtelo de nuevo! Si el error persiste, póngase en contacto con el Admin => mafvpersonal@gmail.com."); //error handler Ej:404 status
            }
        });
    }

    $("#resetIMG").on("click", function() {
        $("#opcsImagen").slideUp();
    });

    $("#sendIMG").on("click", function(e) {
        setFoto(previewFoto.attr("src").substring(3));
    });

    //EVITAR QUE EL USUARIO salga sin guardar
    window.addEventListener("beforeunload", function(e) {
        if (editing) {
            var confirmationMessage = 'Hay cambios sin guardar. '
                + '¿Está seguro de salir de la página?';

            e.returnValue = confirmationMessage; //Gecko + IE // (e || window.event).returnValue = confirmationMessage;
            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
        }
    });
});