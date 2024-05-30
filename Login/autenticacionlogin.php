<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> 
</head>
<body>
  
</body>
</html>
<?php
session_start();
include_once '../basedatos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consultar la base de datos para el usuario verificado
    $stmt = $conn->prepare("SELECT id, contrasena, rol, verificado FROM usuarios WHERE email = ? AND verificado = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Verificar la existencia del usuario verificado
    if ($user) {
        // Verificar la contraseña
        if (password_verify($password, $user['contrasena']) || $password === $user['contrasena']) {
            // Contraseña válida, iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['rol'] = $user['rol'];

            // Redireccionar según el rol
            if ($user['rol'] == 'administrador') {
                echo '<script>';
                echo 'Swal.fire("Inicio de sesión exitoso", "¡Bienvenido!", "success").then(function() { window.location.href = "../VistaAdmin/PagPrincipalAdmin.php"; });';
                echo '</script>';
                exit(); // Importante salir después de redirigir para evitar ejecución adicional
            } elseif ($user['rol'] == 'alumno') {
                echo '<script>';
                echo 'Swal.fire("Inicio de sesión exitoso", "¡Bienvenido!", "success").then(function() { window.location.href = "../VistaUser/PagPrincipalUser.php"; });';
                echo '</script>';
                exit(); // Importante salir después de redirigir para evitar ejecución adicional
            }
        } else {
            // Contraseña incorrecta
            echo '<script>';
            echo 'Swal.fire("Error", "Correo electrónico o contraseña incorrectos.", "error").then(function() { window.location.href = "../index.php"; });';
            echo '</script>';
        }
    } else {
        // Usuario no encontrado o no verificado
        echo '<script>';
        echo 'Swal.fire("Error", "Correo electrónico o contraseña incorrectos o la cuenta aún no ha sido verificada.", "error").then(function() { window.location.href = "../index.php"; });';
        echo '</script>';
    }

    $conn->close();
} else {
    // Redireccionar a la página de inicio si se intenta acceder directamente a este script
    header("Location: ../index.php");
    exit();
}
?>
