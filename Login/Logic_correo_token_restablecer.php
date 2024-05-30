<?php
// Incluir la biblioteca PHPMailer
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/Exception.php';
require '../PHPMailer/SMTP.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el correo electrónico del formulario
    $email = $_POST['email'];

    // Conectar a la base de datos (debes tener esta configuración previamente)
    include_once '../basedatos.php';

    // Verificar si el correo electrónico existe en la base de datos
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // El correo electrónico existe en la base de datos, generar un token único
        $token = bin2hex(random_bytes(32));

        // Insertar el token en la tabla usuarios
        $stmt = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_token_timestamp = CURRENT_TIMESTAMP WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        if ($stmt->execute()) {
            // Inicializar PHPMailer
            $mail = new PHPMailer();

            // Configurar el servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zkevinrm@gmail.com'; // Cambiar por tu dirección de correo electrónico
            $mail->Password = 'sbdm gtys ptvx tbpf'; // Cambiar por tu contraseña de correo electrónico
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configurar remitente y destinatario
            $mail->setFrom('zkevinrm@gmail.com',  'servicios escolares'); // Cambiar por tu dirección de correo electrónico y nombre
            $mail->addAddress($email); // Agregar el correo electrónico proporcionado por el usuario

            // Establecer el asunto y el cuerpo del correo electrónico
            $mail->Subject = 'Restablecer contraseña';
            $mail->Body = 'Haga clic en el siguiente enlace para restablecer su contraseña: <a href="https://localhost/coria-trabajo/Login/Form_restablecer_contra.php?token=' . $token . '">Restablecer contraseña</a>';

            if ($mail->send()) {
                // Mensaje de alerta en JavaScript
                echo '<script>';
                echo 'alert("Se ha enviado un correo electrónico con las instrucciones para restablecer tu contraseña.");';
                echo 'window.location.href = "../Index.php";'; // Redireccionar a otra página
                echo '</script>';
            } else {
                // Mensaje de alerta en JavaScript en caso de error
                echo '<script>';
                echo 'alert("Error al enviar el correo electrónico: ' . $mail->ErrorInfo . '");';
                echo 'window.location.href = "../Index.php";'; // Redireccionar a otra página
                echo '</script>';
            }
            
            $conn->close();
        } else {
            // Error al actualizar el token en la base de datos
            echo '<script>alert("Error al generar el token.");</script>';
            echo '<script>window.location.href = "../Index.php";</script>';
            exit();
        }
    } else {
        // El correo electrónico no existe en la base de datos
        echo '<script>alert("El correo electrónico proporcionado no está registrado.");</script>';
        echo '<script>window.location.href = "../Index.php";</script>';
        exit();
    }
} else {
    // Si el formulario no se envió correctamente, redireccionar a alguna página
    header("Location: ../Index.php");
    exit();
}
?>
