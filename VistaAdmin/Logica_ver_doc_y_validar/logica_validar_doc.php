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
// Establecer la zona horaria a México
date_default_timezone_set('America/Mexico_City');

session_start();

include_once '../../basedatos.php';

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../../index.php");
    exit();
}

// Verificar si se ha enviado el formulario para validar el documento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se enviaron todos los datos necesarios
    if (isset($_POST['documento_id'], $_POST['estado_validacion'])) {
        // Obtener los datos del formulario
        $documento_id = $_POST['documento_id'];
        $estado_validacion = $_POST['estado_validacion'];

        // Obtener la fecha actual en el formato deseado
        $fecha_validacion = date('Y-m-d');

        // Preparar la consulta para actualizar la base de datos
        $sql_update = "UPDATE documentos SET fecha_validacion = ?, validado = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_update);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Establecer el estado de validación según el valor enviado
            $validado = '';
            switch ($estado_validacion) {
                case 'aprobado':
                    $validado = 'aprobado';
                    break;
                case 'rechazado':
                    $validado = 'rechazado';
                    break;
                default:
                    $validado = 'pendiente';
            }

            // Vincular los parámetros y ejecutar la consulta
            $stmt->bind_param("ssi", $fecha_validacion, $validado, $documento_id);
            if ($stmt->execute()) {
                // La actualización fue exitosa
                echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'El documento ha sido validado correctamente.',
                    onClose: () => {
                        // Redirigir a alguna página de éxito
                        window.history.back(); // Regresar a la página anterior
                    }
                });
            </script>";
            } else {
                // Hubo un error al ejecutar la consulta
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error al validar el documento.',
                            onClose: () => {
                                // Redirigir a alguna página de error
                                window.history.back(); // Regresar a la página anterior
                            }
                        });
                    </script>";
            }

            // Cerrar la consulta preparada
            $stmt->close();
        } else {
            // Hubo un error al preparar la consulta
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al preparar la consulta para validar el documento.',
                        onClose: () => {
                            // Redirigir a alguna página de error
                            window.history.back(); // Regresar a la página anterior
                        }
                    });
                </script>";
        }
    } else {
        // No se enviaron todos los datos necesarios
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se enviaron todos los datos necesarios para validar el documento.',
                    onClose: () => {
                        // Redirigir a alguna página de error
                        window.history.back(); // Regresar a la página anterior
                    }
                });
            </script>";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
