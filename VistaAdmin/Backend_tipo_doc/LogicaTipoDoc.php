<?php
// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Incluir archivo de conexión a la base de datos
    include_once '../../basedatos.php';

    // Obtener los datos del formulario
    $nombre_documento = mysqli_real_escape_string($conn, $_POST['nombre_documento']);
    $descripcion_documento = mysqli_real_escape_string($conn, $_POST['descripcion_documento']);

    // Insertar el nuevo tipo de documento en la base de datos
    $query = "INSERT INTO tipos_documentos (nombre, descripcion) VALUES ('$nombre_documento', '$descripcion_documento')";
    mysqli_query($conn, $query);

    // Redireccionar de nuevo a la página de tipos de documento
    header("Location: ../pagPrincipalAdmin.php");
    exit();
} else {
    // Si no se ha enviado el formulario, redireccionar al inicio
    header("Location: ../pagPrincipalAdmin.php");
    exit();
}
?>
