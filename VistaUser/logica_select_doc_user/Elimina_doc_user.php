<?php

session_start();
include_once '../../basedatos.php';
// Verificar si la sesión está iniciada y el rol es 'alumno'
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'alumno') {
    // Si no hay sesión iniciada o el rol no es 'alumno', redirigir al index.php
    header("Location: ../../index.php");
    exit();
}

// Verificar si se ha enviado el ID del documento a eliminar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $documento_id = $_GET['id'];

    // Agrega un var_dump para imprimir el valor de documento_id
    var_dump($documento_id);

    // Consultar la información del documento para obtener su archivo
    $sql_documento = "SELECT * FROM documentos WHERE id = $documento_id";
    $result_documento = $conn->query($sql_documento);

    if ($result_documento->num_rows > 0) {
        $row_documento = $result_documento->fetch_assoc();
        $archivo = $row_documento['archivo'];

        // Eliminar el archivo del sistema de archivos
        $ruta_archivo = "../../DocumentosAlumnos/" . $archivo;
        if (file_exists($ruta_archivo)) {
            unlink($ruta_archivo);
        }

        // Realizar la eliminación del documento en la base de datos
        $sql_eliminar = "DELETE FROM documentos WHERE id = $documento_id";
        if ($conn->query($sql_eliminar) === TRUE) {
            // Redirigir después de eliminar el documento
            header("Location: doc_user.php"); // Reemplaza 'ruta_de_redireccion.php' por la ruta de la página a la que deseas redirigir
            exit(); // Asegúrate de detener la ejecución del script después de redirigir
        } else {
            echo "Error al eliminar el documento: " . $conn->error;
        }
    } else {
        echo "ID de documento no encontrado en la base de datos.";
    }
} else {
    echo "ID de documento no proporcionado.";
}
