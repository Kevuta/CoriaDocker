<?php
session_start();

// Verificar si la sesión está iniciada y el rol es 'alumno'
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'alumno') {
    // Si no hay sesión iniciada o el rol no es 'alumno', redirigir al index.php
    header("Location: ../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trámite de Inscripción</title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Enlace a tu archivo CSS personalizado -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="bg-dark text-white">
        <!-- Barra de navegación o menú utilizando Bootstrap -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="PagprincipalUser.php">Tramites UTA</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- Puedes agregar más elementos del menú aquí si es necesario -->
                </ul>
            </div>
            <!-- Enlace para cerrar sesión -->
            <div class="navbar-nav ml-auto">
                <a class="nav-link" href="../../Login/DestruirSesion.php">Cerrar Sesión</a>
            </div>
        </nav>
    </header>

   

    <main class="container mt-4">
        <div class="text-center my-4">
            <h1 class="mb-4">Cargar Documentos</h1>
        </div>
 <!-- Migas de pan -->
 <nav aria-label="breadcrumb" id="breadcrumbs-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../PagprincipalUser.php">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Enviar archivos</li>
        </ol>
    </nav>

        <!-- Formulario para cargar documentos -->
        <form method="post" action="logica_post_doc_user.php" enctype="multipart/form-data" class="mb-5">
        <div class="form-group">
    <label for="tipo_documento">Tipo de Documento</label>
    <select class="form-control" id="tipo_documento" name="tipo_documento" required>
        <option value="">Seleccionar Tipo de Documento</option>
        <?php
        // Incluir el archivo de conexión a la base de datos
        include_once '../../Basedatos.php';

        // Realizar la consulta para obtener los tipos de documentos con descripción
        $sql = "SELECT id, nombre, descripcion FROM tipos_documentos";
        $result = $conn->query($sql);

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            // Iterar sobre cada fila de resultados
            while ($row = $result->fetch_assoc()) {
                // Mostrar la opción con el nombre y la descripción del tipo de documento
                echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . " - " . $row['descripcion'] . "</option>";
            }
        } else {
            echo "<option value=''>No hay tipos de documentos disponibles</option>";
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
        ?>
    </select>
</div>
<div class="form-group">
    <label for="archivo">Archivo (solo PDF)</label>
    <input type="file" class="form-control-file" id="archivo" name="archivo" required accept=".pdf">
</div>

            <button type="submit" class="btn btn-primary">Cargar Documento</button>
        </form>
    </main>

    <!-- Pie de página utilizando Bootstrap -->
    <footer class="bg-dark text-white text-center py-2">
        <p>&copy; Tramites UTA</p>
    </footer>

    <!-- Enlaces a Bootstrap JS y Popper.js -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
