//Script que recoge 3 demandantes (recientes a la fecha actual) y los ordena aleatoriamente
// Tras ello, los muestra en la barra lateral de la página

$(document).ready(function() {
    var arrayFull = [], gotData = false;
    var tout1 = null, contador = 1;
    var datos1 = {
        img: "../../img/oficina.png",
        nombreEmp: "OFICINAS LA-PERA",
        actividad: "Data Management",
        habilidadPrinc: "Oracle Developer",
        exp: "3 años"
    };
    var datos2 = {
        img: "../../img/oficina.png",
        nombreEmp: "TELECO LUIS",
        actividad: "Telecomunicaciones",
        habilidadPrinc: "Gestión de equipos y redes",
        exp: "5+ años"
    };
    var datos3 = {
        img: "../../img/oficina.png",
        nombreEmp: "NTT DATA",
        actividad: "Data Management",
        habilidadPrinc: "Oracle Developer",
        exp: "4 meses"
    };
    arrayFull.push(datos1, datos2, datos3);

    getDatos();

//si no ha podido cargarse, se vuelve a cargar
    var intv1 = setInterval(function() {
        if (!gotData) {
            getDatos();
        } else {
            clearInterval(intv1);
        }
    }, 20000);

    function getDatos() {
        $("#loadingAside").fadeOut();
        $("div.card-aside").fadeOut();
        modeloAside(arrayFull[contador-1]);
        gotData = true;
        if (contador == 3) {
            contador = 1;
            tout1 = setTimeout(getDatos, 2000);
            return false;
        }
        contador++;
        tout1 = setTimeout(getDatos, 6500);
        // $.ajax("../../js/cargarDatos.php", {
        //     type: "POST",
        //     data: {
        //         datos: "randOfertas"
        //     },
        //     success: function(datos) {
        //         gotData = true;
        //         $("#loadingAside").fadeOut();
        //         let arrDatos = JSON.parse(datos);
        //         for (obj of arrDatos) {
        //             arrayFull.push(obj);
        //         }
        //         console.log(arrayFull);
        //     }
        // });
    }

    function getImgs(arrayEmpresas) {
        $.ajax("../../js/cargarDatos.php", {
            type: "POST",
            data: {
                datos: "getImgEmpresa",
                empresas: arrayEmpresas
            },
            success: function(datos) {
                gotData = true;
                $("#loadingAside").fadeOut();
                let arrDatos = JSON.parse(datos);
                for (obj of arrDatos) {
                    arrayFull.push(obj);
                }
                console.log(arrayFull);
            }
        });
    }



    function modeloAside(data) {
        let cardContainer = document.querySelector("div.card-aside");

        let nameEmpresa = document.createElement("p");
        nameEmpresa.innerText = data.nombreEmp;

        let image = document.createElement("img");
        image.setAttribute("class", "imag");
        image.setAttribute("src", data.img);
        image.setAttribute("alt", "imagenUser");

        let br = document.createElement("br");

        let tituloOffer = document.createElement("h2");
        tituloOffer.innerText = data.actividad;

        let listaUn = document.createElement("ul");
        listaUn.setAttribute("class", "detalles");
        let item1 = document.createElement("li");
        item1.innerHTML = data.exp + " de experiencia en <em>" + data.habilidadPrinc + "</em>";
        let item2 = document.createElement("li");
        item2.innerText = "Habilidades en " + data.actividad;

        listaUn.appendChild(item1);
        listaUn.appendChild(item2);
        $.when($("div.card-aside").fadeOut()
            ).done(function() {
                $("div.card-aside").html("");
                cardContainer.append(nameEmpresa, image, br, tituloOffer, listaUn);
                $("div.card-aside").fadeIn();
            });
    }
});