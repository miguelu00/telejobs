jQuery(function() {
    let tablaE = document.querySelector("#tablaEMPRESAS");
    let tablaD = document.querySelector("#tablaDEMANDANTES");

    //Obtener datos desde AJAX--getDatosAdmin.php
    var datosEmp = obtenerDatos("empresas");
    var datosDem = obtenerDatos("demandantes");

//Bucles para construir las tablas con los datos de ambas entidades Empresas y Demandantes
    
        
    

    function editarCampos(e) {
        //Recojo el ID del objeto a Editar de la fila
        let id = parseInt(e.target.id.substring(6));
        let fila = e.target.parentElement.parentElement.children;

        for (td of fila) {
            //Sacar valor y poner en un Input
            
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
                    return;
                }

                return (JSON.parse(datosRecibidos)).data;
            }
        })
    }
});