<?php
// Verificar si se ha enviado un token por GET
if(isset($_GET['token'])) {
    // Obtener el token de la URL
    $token = $_GET['token'];

    // Aquí deberías comparar el token recibido con el token almacenado en tu base de datos
    // Si coinciden, marcar el correo electrónico como verificado y activar la cuenta del usuario

    // Ejemplo de código para verificar el token en la base de datos
    include_once '../Basedatos.php'; // Incluye el archivo de conexión a la base de datos

    // Consulta para verificar el token
    $sql = "SELECT usuario_id FROM tokens_verificacion WHERE token = ? AND TIMESTAMPDIFF(HOUR, creado_en, NOW()) <= 24"; // Verificar si el token ha sido creado dentro de las últimas 24 horas
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        // Token válido, actualizar el estado de verificación del correo electrónico y activar la cuenta del usuario
        $row = $result->fetch_assoc();
        $user_id = $row['usuario_id'];

        // Actualizar la columna de verificación de correo electrónico a true en la tabla usuarios
        $update_sql = "UPDATE usuarios SET verificado = true WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $user_id);
        $update_stmt->execute();

        // Eliminar el token de verificación de la tabla tokens_verificacion
        $delete_sql = "DELETE FROM tokens_verificacion WHERE token = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("s", $token);
        $delete_stmt->execute();

        echo "¡Tu correo electrónico ha sido verificado correctamente!";
    } else {
        // Token no válido, expirado o usuario no encontrado
        echo "El enlace de verificación es inválido o ha expirado.";
    }

    // Cerrar la conexión y liberar los recursos
    $stmt->close();
    if(isset($update_stmt)) {
        $update_stmt->close();
    }
    if(isset($delete_stmt)) {
        $delete_stmt->close();
    }
    $conn->close();
} else {
    // Si no se proporcionó un token en la URL, mostrar un mensaje de error
    echo "No se proporcionó un token de verificación.";
}
?>
