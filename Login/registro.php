<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Agrega estos enlaces a SweetAlert2 en tu encabezado HTML -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>
<body>
    
</body>
</html>
<?php
// Incluir la clase PHPMailer y la excepción
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/Exception.php';
require '../PHPMailer/SMTP.php'; 

// Importar las clases necesarias en el espacio de nombres PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
include_once '../basedatos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombre = $_POST['name'];
    $email = $_POST['register-email'];
    $password = $_POST['register-password'];
    $confirmPassword = $_POST['confirm-password'];
    $rol = 'alumno'; // Por defecto, el nuevo registro es un alumno

   // Verificar si el correo electrónico está registrado pero no verificado
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND verificado = 0");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result(); // Almacenar el resultado para verificar el número de filas afectadas
if ($stmt->num_rows > 0) {
    // El correo electrónico está registrado pero no verificado
    $stmt->bind_result($usuario_id); // Enlazar el resultado de la consulta al ID del usuario
    $stmt->fetch(); // Obtener el valor del ID del usuario
    $stmt->close(); // Cerrar la declaración preparada

    // Generar un nuevo token de verificación
    $token = bin2hex(random_bytes(32));

    // Actualizar el token en la tabla tokens_verificacion
    $stmt_update = $conn->prepare("UPDATE tokens_verificacion SET token = ? WHERE usuario_id = ?");
    $stmt_update->bind_param("si", $token, $usuario_id);
    $stmt_update->execute();
    $stmt_update->close();
    
    // Enviar el correo electrónico de verificación nuevamente
    try {
        // Inicializar un nuevo objeto PHPMailer
        $mail = new PHPMailer(true);


            // Configurar el servidor SMTP y el remitente
            // (Esta configuración debe realizarse aquí para que solo se realice si el registro en la base de datos fue exitoso)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zkevinrm@gmail.com'; // Cambia esto por tu dirección de correo electrónico
            $mail->Password = 'sbdm gtys ptvx tbpf'; // Cambia esto por tu contraseña
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('zkevinrm@gmail.com', 'servicios escolares'); // Cambia esto por tu dirección de correo electrónico y tu nombre
            $mail->addAddress($email, $nombre); // Agrega el destinatario
            $mail->isHTML(true); // Habilitar contenido HTML
            $mail->Subject = 'Verificación de correo electrónico';
            $mail->Body = 'Por favor, haga clic en el siguiente enlace para verificar su correo electrónico: <a href="https://localhost/coria-trabajo/Login/Verificar-token.php?token=' . $token . '">Verificar correo electrónico</a>';

            // Enviar el correo electrónico
            $mail->send();

            // Éxito al enviar el correo de verificación nuevamente
            echo '<script>';
            echo 'Swal.fire("Correo de verificación enviado", "Se ha enviado un nuevo correo electrónico de verificación.", "success").then(function() { window.location.href = "../index.php"; });';
            echo '</script>';
        } catch (Exception $e) {
            // Error al enviar el correo electrónico
            echo '<script>';
            echo 'Swal.fire("Error", "Error al enviar el correo electrónico de verificación. Por favor, inténtelo de nuevo.", "error").then(function() { window.location.href = "../index.php"; });';
            echo '</script>';
        }

        exit(); // Terminar el script
    }
    $stmt->close(); // Cerrar la declaración preparada

    // Verificar si el correo electrónico está registrado y verificado
    $stmt_verificado = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND verificado = 1");
    $stmt_verificado->bind_param("s", $email);
    $stmt_verificado->execute();
    $stmt_verificado->store_result(); // Almacenar el resultado para verificar el número de filas afectadas
    if ($stmt_verificado->num_rows > 0) {
        // El correo electrónico está registrado y verificado
        echo '<script>';
        echo 'Swal.fire("Correo ya registrado", "El correo electrónico ya está registrado y verificado.", "warning").then(function() { window.location.href = "../index.php"; });';
        echo '</script>';
        exit(); // Terminar el script
    }
    $stmt_verificado->close(); // Cerrar la declaración preparada

    // El correo electrónico no está registrado o está verificado, continuar con el proceso de registro normal

    // Verificar que la contraseña y la confirmación de la contraseña coincidan
    if ($password !== $confirmPassword) {
        // Mostrar mensaje de error directamente en index.php
        echo '<script>';
        echo 'Swal.fire("Error", "La contraseña y la confirmación de la contraseña no coinciden.", "error").then(function() { window.location.href = "../index.php"; });';
        echo '</script>';
        // Terminar el script sin hacer más acciones
        exit();
    }

    // Hash de la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertar datos en la tabla usuarios
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $hashedPassword, $rol);

    if ($stmt->execute()) {
        // Obtener el ID del usuario recién insertado
        $usuario_id = $stmt->insert_id;

        // Generar un token de verificación
        $token = bin2hex(random_bytes(32));

        // Insertar el token en la tabla tokens_verificacion
        $stmt = $conn->prepare("INSERT INTO tokens_verificacion (usuario_id, token) VALUES (?, ?)");
        $stmt->bind_param("is", $usuario_id, $token);

        // Ejecutar la consulta para insertar el token
        if ($stmt->execute()) {
            try {
                // Inicializar un nuevo objeto PHPMailer
                $mail = new PHPMailer(true);

                // Configurar el servidor SMTP y el remitente
                // (Esta configuración debe realizarse aquí para que solo se realice si el registro en la base de datos fue exitoso)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'zkevinrm@gmail.com'; // Cambia esto por tu dirección de correo electrónico
                $mail->Password = 'sbdm gtys ptvx tbpf'; // Cambia esto por tu contraseña
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('zkevinrm@gmail.com', 'servicios escolares'); // Cambia esto por tu dirección de correo electrónico y tu nombre
                $mail->addAddress($email, $nombre); // Agrega el destinatario
                $mail->isHTML(true); // Habilitar contenido HTML
                $mail->Subject = 'Verificación de correo electrónico';
                $mail->Body = 'Por favor, haga clic en el siguiente enlace para verificar su correo electrónico: <a href="http://localhost/coria-trabajo/Login/Verificar-token.php?token=' . $token . '">Verificar correo electrónico</a>';

                // Enviar el correo electrónico
                $mail->send();

                // Éxito al registrar y enviar el correo
                echo '<script>';
                echo 'Swal.fire("Registro exitoso", "¡Bienvenido! Se ha enviado un correo electrónico de verificación.", "success").then(function() { window.location.href = "../index.php"; });';
                echo '</script>';
            } catch (Exception $e) {
                // Error al enviar el correo electrónico
                echo '<script>';
                echo 'Swal.fire("Error", "Error al enviar el correo electrónico de verificación. Por favor, inténtelo de nuevo.", "error").then(function() { window.location.href = "../index.php"; });';
                echo '</script>';
            }
        } else {
            // Error al insertar el token
            echo '<script>';
            echo 'Swal.fire("Error", "Error al registrar. Por favor, inténtelo de nuevo.", "error").then(function() { window.location.href = "../index.php"; });';
            echo '</script>';
        }
    } else {
        // Error al registrar
        echo '<script>';
        echo 'Swal.fire("Error", "Error al registrar. Por favor, inténtelo de nuevo.", "error").then(function() { window.location.href = "../index.php"; });';
        echo '</script>';
    }
     // Cerrar la declaración preparada
     $stmt->close();
     $conn->close();
 } else {
     // Redireccionar a la página de inicio si se intenta acceder directamente a este script
     header("Location: ../index.php");
     exit();
 }
 ?>

