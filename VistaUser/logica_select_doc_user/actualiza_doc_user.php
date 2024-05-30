<?php
session_start();
include_once '../../basedatos.php';

// Verificar si la sesión está iniciada y el rol es 'alumno'
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'alumno') {
    // Si no hay sesión iniciada o el rol no es 'alumno', redirigir al index.php
    header("Location: ../../index.php");
    exit();
}

// Verificar si se ha enviado el ID del documento a actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['documento_id'])) {
    $documento_id = $_POST['documento_id'];

    // Consultar la información del documento para obtener su archivo
    $sql_documento = "SELECT * FROM documentos WHERE id = $documento_id";
    $result_documento = $conn->query($sql_documento);

    if ($result_documento->num_rows > 0) {
        $row_documento = $result_documento->fetch_assoc();
        $archivo_actual = $row_documento['archivo'];

        // Verificar si se ha subido un nuevo archivo
        if (isset($_FILES['nuevo_archivo'])) {
            $nombre_archivo = $_FILES['nuevo_archivo']['name'];
            $tipo_archivo = $_FILES['nuevo_archivo']['type'];
            $tamano_archivo = $_FILES['nuevo_archivo']['size'];
            $archivo_temporal = $_FILES['nuevo_archivo']['tmp_name'];

            // Obtener la extensión del archivo
            $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);

            // Ruta donde se guardará el archivo en el servidor
            $ruta_archivo = "../../DocumentosAlumnos/" . $nombre_archivo;

            // Mover el archivo subido a la carpeta de documentos
            if (move_uploaded_file($archivo_temporal, $ruta_archivo)) {
                // Actualizar la información del documento en la base de datos
                $sql_actualizar = "UPDATE documentos SET archivo = '$nombre_archivo' WHERE id = $documento_id";
                if ($conn->query($sql_actualizar) === TRUE) {
                    // Eliminar el archivo anterior si existe
                    if (file_exists("../../DocumentosAlumnos/" . $archivo_actual)) {
                        unlink("../../DocumentosAlumnos/" . $archivo_actual);
                    }
                    // Redirigir después de actualizar el documento
                    header("Location: doc_user.php"); // Reemplaza 'ruta_de_redireccion.php' por la ruta de la página a la que deseas redirigir
                    exit(); // Asegúrate de detener la ejecución del script después de redirigir
                } else {
                    echo "Error al actualizar el documento: " . $conn->error;
                }
            } else {
                echo "Error al subir el archivo.";
            }
        } else {
            echo "No se ha subido un nuevo archivo.";
        }
    } else {
        echo "ID de documento no encontrado en la base de datos.";
    }
} else {
    echo "ID de documento no proporcionado.";
}
?>
