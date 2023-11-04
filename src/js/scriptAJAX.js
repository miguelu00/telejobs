$(document).ready(function () {
    limpiarDivDatos();
    cargarDemandantesRecientes();
    //cargarDatosPHP();
});
/*
    function cargarDatosPHP() {
        let selectC = $("");
        $.ajax('../../js/cargarDatos.php', {
            dataType: 'json',
            data: {
                datos: "usuarioSesion"
            },
            success: function(respuesta) {
                if (respuesta) {
                    //es decir, si no es nulo lo que nos devuelva el servidor...
                    selectC.html(''); // ...vaciamos el select
                    //jquery ya se encarga de parsear el objeto JSON, por lo que podremos aprovechar los datos directamente

                    for (let index in respuesta) {
                        selectC.html(selectC.html() + respuesta[index] +
                            "<br>");
                        if (index.startsWith("id")) {
                            checkCookie(respuesta[index]); //Comprobamos y almacenamos cookie para el ID del Usuario
                        }
                    }
                }
            },
            error: function(xhr){

            }
        });
    }
*/
    function cargarDemandantesRecientes() {
        let selectC = $(".ajaxDemand");
        $.ajax('cargarDatos.php', {
            dataType: 'json',
            data: {
                datos: "demandantes",
            },
            success: function(respuesta) {
                if (respuesta) {
                    //es decir, si no es nulo lo que nos devuelva el servidor...
                    selectC.html(''); // ...vaciamos el select
                    //jquery ya se encarga de parsear el objeto JSON, por lo que podremos aprovechar el string
                    let selectCateg = document.querySelector('#categorias');

                    for (let categ of respuesta) {
                        //Insertaremos una opción por cada categoría
                        let opcion = '<option value="' + categ + '">' + categ + '</option>';
                        selectCateg.innerHTML += opcion;
                    }

                    //Una vez terminado, agrego un listener para el select
                    selectCateg.addEventListener('change', cargarCategoria);
                    cargarCategoria(selectCateg.firstChild.value);
                }
            },
            error: function(xhr){

            }
        });

    }

    function limpiarDivDatos() {
        $("#data1").html("");
    }

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie(idData) {
    let user = getCookie("idUser");
    if (user != "") {

    } else {
        setCookie("idUser", idData, 0);
    }
}