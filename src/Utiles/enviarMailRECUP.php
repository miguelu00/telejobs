<?php
//Este script se encarga de avisar al usuario sobre cómo recuperar/eliminar el
// registro de su cuenta a través de Correo Electrónico.
session_start();

//Importar librerias para PHPMailer; Exception se necesita, aunque no se utilice en este script
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//todo DATOS NECESARIOS
$mailDestino = $_REQUEST['mail'];
$token = $_REQUEST['token'];


//Cargar el autoload de Composer
require "../vendor/autoload.php";
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
    $mail->addAddress($mailDestino);
    //$mail->addAddress($otroEmail); //el nombre es opcional
    $mail->addReplyTo("mafvpersonal@gmail.com", "CORREO PARA PROBLEMAS");

    //Archivos adjuntos - se puede usar para la confirmación de las ofertas etc etc. Currículums
//    $mail->addAttachment("path/to/file");
//    $mail->addAttachment("path/otro/file"); ...

    //Contenido del Email
    $mail->isHTML(true);
    $mail->Subject = "TELEJOBS - Confirma tu nueva cuenta!";
    if (isset($_REQUEST['noConfirm'])) {
        $mail->Body = "<img src='https://i.ibb.co/CWDhwzR/Telejobs-300px.png' alt='LOGO Telejobs'/>
    <br>
    <h2>RECUPERACIÓN DE CUENTA TELEJOBS</h2>
    <br>
    <p>Ha recibido este correo porque necesita recuperar el acceso a su cuenta de TELEJOBS ó ha tenido algún otro problema. <br>
    Si esto es cierto, y ha sido suya la intención tiene <b>dos opciones:</b></p>
    
    <h4><a href='http://telejobs.net/login/login.php?eliminarCuenta={$mailDestino}&token={$token}'>ABORTAR REGISTRO</a></h4>
    <h4><a href='http://telejobs.net/login/login.php?continuarRegistro={$mailDestino}&token={$token}'>Recuperación CUENTA</a></h4>
    <br><p>Para cualquier consulta, conctacte con el Administrador en mafvpersonal@gmail.com<br>Gracias por elegir TELEJOBS</p><br><br>
    Puede ignorar este correo si no ha pedido nada de esto.";
    } else {
        $mail->Body = "<img src='https://i.ibb.co/CWDhwzR/Telejobs-300px.png' alt='LOGO Telejobs'/>
    <br>
    <h2>RECUPERACIÓN DE CUENTA TELEJOBS</h2>
    <br>
    <p>Ha recibido este correo porque necesita recuperar el acceso a su cuenta de TELEJOBS ó ha tenido algún otro problema. <br>
    Si esto es cierto, y ha sido suya la intención tiene <b>dos opciones:</b></p>
    
    <h4><a href='http://telejobs.net/login/login.php?continuarRegistro={$mailDestino}&token={$token}'>Recuperación CUENTA</a></h4>
    <br><p>Para cualquier consulta, conctacte con el Administrador en mafvpersonal@gmail.com<br>Gracias por elegir TELEJOBS</p><br><br>
    Puede ignorar este correo si no ha pedido nada de esto.";
    }
    $mail->AltBody = "RECUPERAR SU CUENTA EN TELEJOBS!
    \n
    Ha recibido este correo porque necesita recuperar el acceso a su cuenta de TELEJOBS ó ha tenido algún otro problema.
    \n
    Si esto es cierto, y ha sido suya la intención haga clic en el siguiente enlace:
    \n Enlace: http://telejobs.net/login/login.php?confirmarCorreo={$mailDestino}&tipoRegistro={$tipoRegistro}&token={$token}
    \n\n Puede eliminar este correo si no ha pedido nada de esto.
    \n\n
     Gracias por elegir TELEJOBS. Para cualquier consulta, contactar con el administrador en mafvpersonal@gmail.com";

    if ($mail->send()) {
        $_SESSION['emailSent'] = true;
    }
} catch (Exception $mailerException) {

}

?>