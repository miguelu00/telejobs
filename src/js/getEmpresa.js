jQuery(function() {
    var fechaHoraActual = new Date();
    var datosRecibir = null;

    /**
     * Rellenar una vista de oferta mediante los datos de la Oferta pasados
     * por parámetro.
     * 
     * @returns HTMLElement - un elemento DIV que representa la vista de una oferta de trabajo.
     */
    function crearModeloOferta(datosOferta) {

        let habils = datosOferta.habilidades_ped.split(",");

        let divMain = document.createElement("div");

        let divSlider = document.createElement("div");
        divSlider.classList.add("cardOferta");
        divSlider.classList.add("slider");

        let h4_slider = document.createElement("h4");
        let p_slider = document.createElement("p");

        if (fechaAntesDe(datosOferta.fecha_limite)) {
            p_slider.classList.add("red");
        }
        p_slider.classList.add("offer-status");

        divSlider.appendChild(h4_slider);
        divSlider.appendChild(p_slider);

        let divSlidee = document.createElement("div");
        let divInfo_slidee = document.createElement("div");

        divInfo_slidee.classList.add("offer-info");

        let a_profile = document.createElement("a");
        a_profile.href = "verEmpresa.php?" + datosOferta.id_EMP;

        let h3_slidee = document.createElement("h3");
        h3_slidee.appendChild(a_profile);

        let details_slidee = document.createElement("details");
        let summary_details = document.createElement("summary");

        summary_details.innerHTML = "Habilidades: <span style='color: green;'>Con " + habils.length + " habilidad(es)</span>";

        details_slidee.appendChild(summary_details);

        let div_details = document.createElement("div");
        let ul_div = document.createElement("ul");

        for (const habil of habils) {
            ul_div.innerHTML += "<li><b>Nº" + habil + "</b></li>";
        }

        div_details.appendChild(ul_div);

        details_slidee.appendChild(div_details);

        divInfo_slidee.appendChild(h3_slidee);
        divInfo_slidee.appendChild(details_slidee);

        divSlidee.appendChild(divInfo_slidee);

        divMain.appendChild(divSlider);
        divMain.appendChild(divSlidee);

        return divMain;
    }

    /**
     * Retornará si la fecha pasada por parámetro es posterior a la
     * fecha actual del sistema.
     * 
     * No es estricto con los días.
     * @returns <b>boolean</b> - TRUE si la fecha actual aún no se ha pasado de la fecha indicada; 
     * FALSE en caso contrario.
     */
    function fechaAntesDe(fecha) {
        let dia = fechaHoraActual.getDate();
        let mes = fechaHoraActual.getMonth();
        let anio = fechaHoraActual.getFullYear();
        
        let arrayFecha = fecha.split("/");
        if (anio > parseInt(arrayFecha[2])) {
            return false;
        }
        if (mes > parseInt(arrayFecha[1])) {
            return false;
        }
        if (dia > parseInt(arrayFecha[0])) {
            return false;
        }
        return true;
    }

    function obtenerDatos(tabla) {
        $.ajax("../Repositories/API.php", {
            type : "GET",
            data : {
                "tabla": tabla
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
});