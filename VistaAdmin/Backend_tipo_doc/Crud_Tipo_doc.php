<?php
session_start();

// Verificar si la sesión está iniciada y el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    // Redireccionar al index.php si no cumple con los requisitos
    header("Location: ../index.php");
    exit();
}

include_once '../../basedatos.php';

// Obtener la lista de tipos de documentos
$query_tipos_documentos = "SELECT * FROM tipos_documentos";
$resultado_tipos_documentos = mysqli_query($conn, $query_tipos_documentos);
$tipos_documentos = mysqli_fetch_all($resultado_tipos_documentos, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trámite de Inscripción - Ver Tipos de Documentos</title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Enlace a tu archivo CSS personalizado -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="bg-dark text-white">
        <!-- Barra de navegación o menú utilizando Bootstrap -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="../PagprincipalAdmin.php">Tramites UTA</a>
            <!-- Resto del menú -->
        </nav>
    </header>

    <main class="container mt-4">
        <!-- Migas de pan -->
        <nav aria-label="breadcrumb" id="breadcrumbs-container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../PagprincipalAdmin.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tipos de Documentos</li>
            </ol>
        </nav>

        <div class="text-center my-4">
            <h1 class="mb-4">Tipos de Documentos</h1>
        </div>

        <!-- Lista de tipos de documentos -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Documento</th>
                    <th>Descripción del Documento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tipos_documentos as $tipo_documento): ?>
                    <tr>
                        <!-- Datos del tipo de documento -->
                        <td><?php echo $tipo_documento['id']; ?></td>
                        <td><?php echo $tipo_documento['nombre']; ?></td>
                        <td><?php echo $tipo_documento['descripcion']; ?></td>
                        <td>
                            <!-- Botones de Acciones -->
                            <a href="Form_Editar_tipo_doc.php?id=<?php echo $tipo_documento['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="#"class="btn btn-danger btn-sm" onclick="confirmarEliminacion(<?php echo $tipo_documento['id']; ?>)">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <!-- Pie de página utilizando Bootstrap -->
    <footer class="bg-dark text-white text-center py-2">
        <p>&copy; Tramites UTA</p>
    </footer>

    <!-- Enlaces a Bootstrap JS y Popper.js -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
    function confirmarEliminacion(idTipoDocumento) {
        var confirmacion = confirm("¿Estás seguro de que quieres eliminar este tipo de documento?");
        if (confirmacion) {
            // Si se confirma, redirigir con el parámetro confirmado=true
            window.location.href = 'Eliminar_Tipo_doc.php?id=' + idTipoDocumento + '&confirmado=true';
        }
    }
</script>


</body>
</html>
