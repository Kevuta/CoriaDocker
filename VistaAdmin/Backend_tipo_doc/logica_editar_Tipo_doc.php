<?php
session_start();
include_once '../../basedatos.php';
// Verificar si la sesión está iniciada y el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    // Redireccionar al index.php si no cumple con los requisitos
    header("Location: ../index.php");
    exit();
}

// Verificar si se ha enviado el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $idTipoDocumentoEditar = mysqli_real_escape_string($conn, $_POST['id_tipo_documento']);
    $nombreNuevo = mysqli_real_escape_string($conn, $_POST['nombre_documento']);
    $descripcionNueva = mysqli_real_escape_string($conn, $_POST['descripcion_documento']);

    // Actualizar los datos en la base de datos
    $consultaUpdate = "UPDATE tipos_documentos SET nombre = '$nombreNuevo', descripcion = '$descripcionNueva' WHERE id = $idTipoDocumentoEditar";

    // Ejecutar la consulta de actualización
    if (mysqli_query($conn, $consultaUpdate)) {
        // Redireccionar a la página de tipo de documento con un mensaje de éxito
        header("Location: Crud_Tipo_doc.php?mensaje=Actualización exitosa&type=success");
        exit();
    } else {
        // Manejar el error de actualización
        echo "Error al actualizar el tipo de documento: " . mysqli_error($conn);
        exit();
    }
}


?>
