$(document).ready(function() {
    var divData = $("#ajaxSearch");
    //Dependiendo de la página desde la que se esté llamando
    if (document.title.endsWith("Empresas")) {
        limpiarDivDatos();
        getDatos();
    }

    function limpiarDivDatos() {
        divData.html("");
    }
//Iterar por los demandantes, pero si nos pasamos del límite del Spinner salir del bucle
    function mostrarDems(numSpinner, demandantes) {
        var contador = 0;
        for (elem of demandantes) {
            contador++;

            if (contador == numSpinner) {
                addPagina();
                break;
            }
        }
    }

    function addPagina() {
        contadorPags++;
    }
    function goToNext() {
        if (pagActual == contadorPags) {
            $("");
        }
    }

    function getDatos() {
        $.ajax("../../js/cargarDatos.php", {
            data: {
                accion: 'getDemandantes'
            },
            success: function(result) {

            }
        })
    }
});