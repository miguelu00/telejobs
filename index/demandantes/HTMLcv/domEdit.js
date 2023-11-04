var data = [];
recogerDatos();
setTimeout(function() {
    if (document.title.includes("Plantilla 1")) {
        let nombre = data.nombre;
        let email = data.email;

        if (data.nombre.includes(" ")) {
            let split1 = nombre.split(" ");
        }
        if (split1 !== undefined) {
            document.querySelector("#primerH1").innerHTML = split1[0] + document.querySelector("#primerH1").innerHTML;
            document.querySelector("#primerH1 span").innerHTML = split1[1];
        } else {
            document.querySelector("#primerH1").innerHTML = nombre;
        }

        document.querySelector("#email").href = "mailto:" + email + "";
        document.querySelector("#email").innerHTML = email;


    }
    if (document.title.includes("Plantilla 2")) {
        var data = recogerDatos();

    }
    if (document.title.includes("Plantilla 3")) {
        var data = recogerDatos();

    }
}, 1000);