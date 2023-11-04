<?php
//Este script se encarga de avisar al usuario sobre cómo confirmar el
// registro de su cuenta a través de Correo Electrónico.

//Importar librerias para PHPMailer; Exception se necesita, aunque no se utilice
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//todo DATOS NECESARIOS
$mailDestino = $_REQUEST['mail'];
$nombreDestino = $_REQUEST['nombre'];
$tipoRegistro = $_REQUEST['tipo']; //EMPRESA - DEMANDANTE

$token = password_hash($mailDestino, PASSWORD_DEFAULT);//codigo aleatorio para Permitir registro

?>
<html>
    <head>
        <title>Enviando correo... - TELEJOBS</title>
        <style>
            body {
                text-align: center;
                background-color:  #4CAF50;
            }
            a {
                border: 2px solid greenyellow;
                width: 2vw;
                height: auto;
                padding: 4px;
            }
            div {
                border: 2px solid greenyellow;
                border-radius: 10px;
                width: fit-content;
                height: auto;
                padding: 7px;
            }
            div.ok {
                background-color: #4CAF50;
            }
            div.error {
                background-color: #fd2e1b;
            }
            a.button-back {
                background-color: #45a049;
                color: black;
                font-weight: bold;

                padding: 5px;
                width: 3vw;
                height: auto;
            }
        </style>
        <script src="../js/jquery-3.6.3.js"></script>
    </head>
<body>
<?php
//Cargar el autoload de Composer
require "../../vendor/autoload.php";
//Crear objeto PHPMailer, y usar sus diversas opciones/settings
$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 0;
    $mail->isSMTP();

    $mail->Host = "smtp.gmail.com:465";
    $mail->SMTPAuth = true;
    $mail->Username = "mafvpersonal@gmail.com";
    $mail->Password = "biqzylicugbgkdic";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    //Remitentes/Destinatario -- to/from
    $mail->setFrom("telejobs@noReply.com", "TELEJOBS - Correo Automático");
    $mail->addAddress($mailDestino, $nombreDestino);
    //$mail->addAddress($otroEmail); //el nombre es opcional
    $mail->addReplyTo("mafvpersonal@gmail.com", "CORREO PARA PROBLEMAS");

    //Archivos adjuntos - se puede usar para la confirmación de las ofertas etc etc. Currículums
//    $mail->addAttachment("path/to/file");
//    $mail->addAttachment("path/otro/file"); ...

    //Contenido del Email
    $mail->isHTML(true);
    $mail->Subject = "TELEJOBS - Confirma tu nueva cuenta!";
    $mail->Body = "<img src='https://i.ibb.co/CWDhwzR/Telejobs-300px.png' alt='LOGO Telejobs'/>
    <br>
    <h2>Bienvenida/o a TELEJOBS, <b>{$nombreDestino}</b>!</h2>
    <br>
    <p>Para completar su registro como {$tipoRegistro}, deberá <br>clicar en el siguiente enlace:</p>
    
    <h4><a href='http://localhost/PROYECTO/login/login.php?confirmarCorreo={$mailDestino}&tipoRegistro={$tipoRegistro}&token={$token}'>Confirmar registro</a></h4>
    <br><p>Para cualquier consulta, conctacte con el Administrador en mafvpersonal@gmail.com<br>Gracias por elegir TELEJOBS</p>";
    $mail->AltBody = "BIENVENIDA/O A TELEJOBS!
    \n
    Para continuar su registro como {$tipoRegistro}; deberá acceder a la dirección siguiente
    con su navegador preferido:
    \n Enlace: http://localhost/PROYECTO/login/login.php?confirmarCorreo={$mailDestino}&tipoRegistro={$tipoRegistro}&token={$token}
     Gracias por elegir TELEJOBS. Para cualquier consulta, contactar con el administrador en mafvpersonal@gmail.com";

    $mail->send();
    echo "<div class='ok'>Se le ha enviado un mensaje a su correo electrónico. <br>
    Haga clic en el enlace que contiene para completar el registro en TELEJOBS!</div><br>
    *Una vez completado el registro podrá logearse en su cuenta de {$tipoRegistro}
    
    <br><br> <a class='button-back' href='../index.php?recordatorioComprobarMail=true'> &lt; Volver a la página de bienvenida</a>";
} catch (Exception $mailerException) {
    echo "ERROR al generar su correo de confirmación!<br>Lo sentimos :( intente crear su cuenta de nuevo o póngase en contacto con el Administrador!";
    echo "<br><br>
        <dialog open>ERROR Log: <a id='mostrarE'>Mostrar</a>
        <br><div><p>{$mailerException}</p></div></dialog>";
}

?>
</body>
<script>
    $("dialog div:eq(0)").hide();
    document.querySelector("#mostrarE").addEventListener("click", function(e) {
        $("#mostrarE").text("Ocultar");
        $("dialog div:eq(0)").show();
        e.target.addEventListener("click", function(evt) {
            $("#mostrarE").text("Mostrar");
            $("dialog div:eq(0)").hide();
        });
        e.target.removeEventListener("click", e.event);
        e.target.addEventListener("click", e.event);
    });
</script>
</html>
