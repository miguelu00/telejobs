<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/estilos.css"/>
    <link rel="stylesheet" href="../../css/demandante.css"/>
    <script src="../../js/jquery-3.6.3.js"></script>
    <title>Generador de CV - TELEJOBS Empleo</title>
    <style>
        body {
            margin: 10px;
            padding: 0;

        }
        div.main {
            padding: 5px;
            width: 100%;
            height: 100%;
            display: flex;
            flex-flow: row nowrap;
        }
        div.main textarea {
            width: 45%;
            height: 80%;
            font: 18px 'Courier New', sans-serif;
            resize: vertical;
        }
        div.main iframe {
            width: 45%;
            min-height: 70%;
            max-height: 90%;

            overflow: scroll;
        }
        section label > img {
            width: 350px;
            height: auto;
        }
    </style>
</head>
<body class="body-genCV">

    <!-- NAVBAR AQUI -->
    <ul class="barra-nav">
        <li class="logo-emp">
            <a href="index.php">
                <img id="img-logo" class="img-nav no-border" src="../../logo/telejobsDEM_logoNAV.png" alt="logoEmp"/>
            </a>
        </li>
        <li><a class="activo" href="index.php">Bolsa de Empleo</a></li>
        <li><a href="">Ver mis Ofertas (0)</a></li>
        <li><a href="generadorCV.php">Generador de CV <img class="img-nav-pq" src="../../img/register.png" alt="Regsitro..."></a></li>
        <div>
            <a class="userImg usermenu-toggle">
                <img src="../../img/fotosUser/<?php echo $_SESSION['userData']['foto'] ?>" alt="usericon"/>
            </a>
        </div>
    </ul>

    <label for="codig">CÓDIGO DEL CV ACTUAL</label><br>
    <div class="main">
        <textarea name="codig" id="codig" cols="30" rows="10">
        </textarea>
        <iframe id="output" title="Output HTML" allowfullscreen="true" frameborder="2" srcdoc=""></iframe>
    </div>

    <br>
    <p>[<b>Ctrl-h</b> (dentro del Código) para actualizar la Vista previa!]</p>
    <br>
    <p>Seleccione una plantilla: </p>
    <section>
        <input type="radio" id="plantilla1" value="plantilla1" name="selectPlantilla"/> 
        <label title="Plantilla Xpress" for="plantilla1">
            <img src="../../img/cv_example1.png" alt="Plantilla1"/>
        </label>
        <input type="radio" id="plantilla2" value="plantilla2" name="selectPlantilla"/>
        <label title="Plantilla Bootstrap" for="plantilla2">
            <img src="" alt="Plantilla2"/>
        </label>
        <input type="radio" id="plantilla3" value="plantilla3" name="selectPlantilla"/>
        <label title="Plantilla en blanco. Con Bootstrap" for="plantilla3">
            <img src="" alt="Plantilla3"/>
        </label>
    </section>
<script>
    $(document).ready(function() {
        var htmlTags = ["html", "a", "title", "head", "label", "input", "pre", "h1", "h2", "h3", "h4", "h5", "h6", ""];
        document.querySelector("textarea").addEventListener("keydown", function(e) {
            //console.clear();
            //console.log("Key => " + e.key);
            if (e.ctrlKey && e.key == "h") {
                e.preventDefault();
                mostrarCV();
            }
            // fuente: https://stackoverflow.com/questions/6637341/use-tab-to-indent-in-textarea
            if (e.key == "Tab") {
                e.preventDefault();
                var start = this.selectionStart;
                var end = this.selectionEnd;

                //settear el valor del textarea al value HASTA EL CURSOR + tabulador + TEXTO TRAS EL CURSOR
                this.value = this.value.substring(0, start) +
                    "\t" + this.value.substring(end);

                // Colocar cursor/puntero del texto al final, y luego a la posición tras la tabulación
                this.selectionStart = this.selectionEnd = start + 1;
            }
        });

        function mostrarCV() {
            if (!checkCodigo(document.querySelector("textarea").value)) {
                return false;
                //El error se muestra a través de la función checkCodigo(codigo)
            } else {
                let newIFrame = document.querySelector("#output").cloneNode();
                newIFrame.srcdoc = document.querySelector("textarea").value;
                document.querySelector("#output").innerHTML = "";
                document.querySelector("#output").remove();
                document.querySelector("div.main").append(newIFrame);
            }
        }
        //No permitir NADA de código JavaScript escrito por el usuario. Sólo usar HTML puro y CSS
        function checkCodigo(codigo) {
            if (codigo.includes("<script>") || codigo.includes("<iframe") || codigo.includes("<script defer>") || codigo.includes("<script async>")) {
                alert("ERROR. No se permite el uso de scripts escritos por el usuario ó codigo potencialmente malicioso. Rogamos su comprensión y gracias por usar nuestros servicios."
                    + "\nElimine el código script que ha escrito y vuelva a intentarlo!");
                return false;
            }
            return true;
        }

        $("input[type='radio']").on("click", function() {
            let idSelected = $(this).attr("id").substring($(this).attr("id").search("[0-9]"), $(this).attr("id").length);
            leerHTML("HTMLcv/plantilla" + idSelected + ".html");
        });

        //Esta función leerá de cada plantilla, la página a cargar (formato HTML), buscando para ello en la carpeta de demandantes -> HTMLcv
        function leerHTML(pagACargar) {
            fetch(pagACargar)
            .then(response => response.text())
            .then(text => $("textarea#codig").val(text));
            mostrarCV();
        }
    });
</script>
</body>
</html>