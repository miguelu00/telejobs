//Buscar en la bbdd con AJAX, si se encuntra el correo introducido, enviar un correo electrónico
// Una vez terminado el proceso con AJAX, deshabilitar los botones de envío y el campo de Texto,
//  y hacer comprobación de si se ha tratado de enviar la recuperación ya.
$(document).ready(function() {
    $("input[type=submit]").on("click", chequeoEmail);
});

function chequeoEmail(e) {
    if (!e.target.parentElement.parentElement.checkValidity()) {
        mostrarError(e.target.parentElement.parentElement.validationMessage);
        return false;
    }
    e.preventDefault();
    let emailDOM = $("input#username").val();

    $.ajax('recuperacionCheck.php', {
        data: {
            accion: 'checkEmail',
            correo: emailDOM
        },
        success: function(data) {
            if (data == "1") {
                $("dialog.errores").attr("open", "true");
            } else if (data == "0") {
                $("dialog.errores").html("ERROR al chequear su email; compruebe que lo ha escrito correctamente y que está registrado en TELEJOBS!");
                $("dialog.errores").attr("open", "true");
            }
        },
        error: function() {
            //Si aparece este error, quizás signifique que el servidor BBDD no está correctamente operativo.
            $("dialog.errores").html("ERROR FATAL. Póngase en contacto con el Administrador mafvpersonal@gmail.com");
            $("dialog.errores").attr("open", "true");
        }
    });

    $("input#username").attr("disabled", "true");
    $("input[type=reset]").attr("disabled", "true");
    $("input[type=submit]").attr("disabled", "true");
}