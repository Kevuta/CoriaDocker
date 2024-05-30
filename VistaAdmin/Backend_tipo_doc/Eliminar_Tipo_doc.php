<?php
session_start();

// Verificar si la sesión está iniciada y el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    // Redireccionar al index.php si no cumple con los requisitos
    header("Location: ../index.php");
    exit();
}

include_once '../../basedatos.php';

// Verificar si se ha proporcionado un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_tipo_documento = $_GET['id'];

    // Eliminar el tipo de documento de la base de datos
    $query_eliminar_tipo_documento = "DELETE FROM tipos_documentos WHERE id = $id_tipo_documento";
    $resultado_eliminar = mysqli_query($conn, $query_eliminar_tipo_documento);

    if ($resultado_eliminar) {
        // Redireccionar a la página de ver_tipos_documentos.php si se ha eliminado correctamente
        header("Location: Crud_Tipo_doc.php");
        exit();
    } else {
        // Manejar error en la eliminación (puedes personalizar según tus necesidades)
        echo "Error al eliminar el tipo de documento.";
    }
} else {
    // Redireccionar a la página de ver_tipos_documentos.php si no se proporcionó un ID válido
    header("Location: Crud_Tipo_doc.php");
    exit();
}
?>
