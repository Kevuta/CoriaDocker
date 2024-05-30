
<<!DOCTYPE html>
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

include_once '../../Basedatos.php'; // Incluir el archivo de conexión a la base de datos

// Verificar si la sesión está iniciada y el rol es 'alumno'
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'alumno') {
    // Si no hay sesión iniciada o el rol no es 'alumno', redirigir al index.php
    header("Location: ../../index.php");
    exit();
}

// Verificar si se ha enviado un archivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo"])) {
    // Carpeta de destino para guardar los archivos PDF
    $carpeta_destino = "../../DocumentosAlumnos/";

    // Nombre del archivo cargado y su ubicación temporal
    $nombre_archivo = $_FILES["archivo"]["name"];
    $archivo_temporal = $_FILES["archivo"]["tmp_name"];

    // Obtener la extensión del archivo
    $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);

    // Verificar si el archivo es un PDF
    if ($extension == "pdf") {
        // Mover el archivo cargado a la carpeta de destino
        if (move_uploaded_file($archivo_temporal, $carpeta_destino . $nombre_archivo)) {
            // Obtener el tipo de documento seleccionado
            $tipo_documento_id = $_POST['tipo_documento'];

            // Obtener el nombre del tipo de documento seleccionado
            $sql_tipo_documento = "SELECT nombre FROM tipos_documentos WHERE id = ?";
            $stmt_tipo_documento = $conn->prepare($sql_tipo_documento);
            $stmt_tipo_documento->bind_param("i", $tipo_documento_id);
            $stmt_tipo_documento->execute();
            $resultado_tipo_documento = $stmt_tipo_documento->get_result();

            // Verificar si se encontró el tipo de documento
            if ($resultado_tipo_documento->num_rows > 0) {
                // Obtener el nombre del tipo de documento
                $row_tipo_documento = $resultado_tipo_documento->fetch_assoc();
                $nombre_tipo_documento = $row_tipo_documento['nombre'];

                // Insertar el registro en la tabla documentos
                $sql_insert = "INSERT INTO documentos (usuario_id, tipo_documento_id, tipo_documento, archivo) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->bind_param("iiss", $_SESSION['user_id'], $tipo_documento_id, $nombre_tipo_documento, $nombre_archivo);
                if ($stmt->execute()) {
                    // Mostrar SweetAlert exitoso y redirigir
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'El archivo PDF se ha cargado correctamente y se ha registrado en la base de datos.',
                                onClose: () => {
                                    window.location.href = 'Form_ingresa_doc.php'; // Redirigir a la página anterior
                                }
                            });
                        </script>";
                } else {
                    // Mostrar SweetAlert de error en la base de datos
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un error al cargar el archivo en la base de datos.',
                                onClose: () => {
                                    window.history.back(); // Redirigir a la página anterior
                                }
                            });
                        </script>";
                }
            } else {
                // Mostrar SweetAlert de error al obtener el nombre del tipo de documento
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se encontró el nombre del tipo de documento.',
                            onClose: () => {
                                window.history.back(); // Redirigir a la página anterior
                            }
                        });
                    </script>";
            }

            // Cerrar la conexión a la base de datos
            $stmt->close();
            $conn->close();
        } else {
            // Mostrar SweetAlert de error al cargar en la carpeta de destino
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al cargar el archivo en la carpeta de destino.',
                        onClose: () => {
                            window.history.back(); // Redirigir a la página anterior
                        }
                    });
                </script>";
        }
    } else {
        // Mostrar SweetAlert de archivo no PDF
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El archivo debe ser un PDF.',
                    onClose: () => {
                        window.history.back(); // Redirigir a la página anterior
                    }
                });
            </script>";
    }
} else {
    // Mostrar SweetAlert de no envío de archivo
    echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'No se ha enviado ningún archivo.',
                onClose: () => {
                    window.history.back(); // Redirigir a la página anterior
                }
            });
        </script>";
}
?>

