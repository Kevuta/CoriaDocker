<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargar la clase PHPMailer
require './PHPMailer/PHPMailer.php';
require './PHPMailer/SMTP.php';
require './PHPMailer/Exception.php';

// Crear una instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'zkevinrm@gmail.com';
    $mail->Password = 'sbdm gtys ptvx tbpf';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Configuración del remitente y destinatario
    $mail->setFrom('zkevinrm@gmail.com', 'Keivn Ramirez');
    $mail->addAddress('ramirezmartinez.kevindejesus@utacapulco.edu.mx', 'hola xd');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Asunto del Correo';
    $mail->Body = 'Este es el cuerpo del correo';

    // Enviar el correo
    $mail->send();
    echo 'Correo enviado correctamente.';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>