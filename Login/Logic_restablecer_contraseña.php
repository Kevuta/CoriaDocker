<?php
// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el token y la nueva contraseña del formulario
    $token = $_POST['token'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Verificar si las contraseñas coinciden
    if ($newPassword !== $confirmPassword) {
        echo '<script>alert("Las contraseñas no coinciden.");</script>';
        echo '<script>';
        echo 'var currentUrl = window.location.href;';
        echo 'var tokenIndex = currentUrl.indexOf("token=");';
        echo 'if (tokenIndex !== -1) {';
        echo '    var token = currentUrl.substring(tokenIndex);'; // Obtener el token de la URL actual
        echo '    window.location.href = "Form_restablecer_contra.php?" + token;';
        echo '} else {';
        echo '    window.history.back();'; // Volver a la página anterior si no se encuentra el token en la URL
        echo '}';
        echo '</script>';
        exit();
    }

    // Conectar a la base de datos (debes tener esta configuración previamente)
    include_once '../basedatos.php';

    // Verificar si el token es válido
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE reset_token = ? AND reset_token_timestamp >= NOW() - INTERVAL 24 HOUR");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Token válido, obtener el ID del usuario
        $stmt->bind_result($usuario_id);
        $stmt->fetch();
        $stmt->close();

        // Actualizar la contraseña del usuario
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE usuarios SET contrasena = ?, reset_token = NULL, reset_token_timestamp = NULL WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $usuario_id);
        
        if ($updateStmt->execute()) {
            // Mostrar mensaje de éxito y redirigir al usuario
            echo '<script>alert("Contraseña restablecida exitosamente.");</script>';
            echo '<script>window.location.href = "../Index.php";</script>';
            exit();
        } else {
            echo '<script>alert("Error al restablecer la contraseña. Por favor, inténtelo de nuevo.");</script>';
            echo '<script>';
            echo 'var currentUrl = window.location.href;';
            echo 'var tokenIndex = currentUrl.indexOf("token=");';
            echo 'if (tokenIndex !== -1) {';
            echo '    var token = currentUrl.substring(tokenIndex);'; // Obtener el token de la URL actual
            echo '    window.location.href = "Form_restablecer_contra.php?" + token;';
            echo '} else {';
            echo '    window.history.back();'; // Volver a la página anterior si no se encuentra el token en la URL
            echo '}';
            echo '</script>';
            
            exit();
        }
    } else {
        // El token es inválido o ha expirado, o el usuario no existe en la base de datos
        echo '<script>alert("El token de restablecimiento de contraseña es inválido o ha expirado.");</script>';
        echo '<script>';
        echo 'var currentUrl = window.location.href;';
        echo 'var tokenIndex = currentUrl.indexOf("token=");';
        echo 'if (tokenIndex !== -1) {';
        echo '    var token = currentUrl.substring(tokenIndex);'; // Obtener el token de la URL actual
        echo '    window.location.href = "Form_restablecer_contra.php?" + token;';
        echo '} else {';
        echo '    window.history.back();'; // Volver a la página anterior si no se encuentra el token en la URL
        echo '}';
        echo '</script>';
        
        exit();
    }
} else {
    // Si el usuario intenta acceder directamente a este script sin enviar el formulario, redirigirlo
    header("Location: ../Index.php");
    exit();
}
?>
