<?php
session_start();
include_once '../../basedatos.php';

// Verificar si la sesión está iniciada y el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    // Redireccionar al index.php si no cumple con los requisitos
    header("Location: ../index.php");
    exit();
}

// Obtener el ID del tipo de documento a editar desde la URL
$idTipoDocumentoEditar = isset($_GET['id']) ? $_GET['id'] : null;

// Si no se proporciona un ID, redireccionar a la página de administración de tipos de documentos
if (!$idTipoDocumentoEditar) {
    header("Location: PagprincipalAdmin.php");
    exit();
}

if ($conn) {
    $idTipoDocumentoEditar = mysqli_real_escape_string($conn, $idTipoDocumentoEditar);
    
    // Consulta SQL para obtener datos del tipo de documento
    $consulta = "SELECT nombre, descripcion FROM tipos_documentos WHERE id = $idTipoDocumentoEditar";

    // Ejecutar la consulta y obtener los datos del tipo de documento
    $resultado = mysqli_query($conn, $consulta);
    $tipoDocumento = mysqli_fetch_assoc($resultado);

    // Asignar valores a las variables
    $nombreActual = $tipoDocumento['nombre'];
    $descripcionActual = $tipoDocumento['descripcion'];

    // Liberar el resultado de la consulta
    mysqli_free_result($resultado);
} else {
    // Manejar el error de conexión a la base de datos
    echo "Error de conexión a la base de datos";
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
            <a class="navbar-brand" href="PagprincipalAdmin.php">Tramites UTA</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                
             </div>
            <!-- Enlace para cerrar sesión -->
            <div class="navbar-nav ml-auto">
                <a class="nav-link" href="../../Login/DestruirSesion.php">Cerrar Sesión</a>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
        <!-- Migas de pan -->
        <nav aria-label="breadcrumb" id="breadcrumbs-container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../PagprincipalAdmin.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="Crud_Tipo_Doc.php">Cruds de Documento</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar Tipo de Documento</li>
            </ol>
        </nav>

        <div class="text-center my-4">
            <h1 class="mb-4">Editar Tipo de Documento</h1>
        </div>

        <!-- Formulario para Editar Tipo de Documento -->
        <form method="post" action="logica_editar_Tipo_doc.php">
            <input type="hidden" name="id_tipo_documento" value="<?php echo $idTipoDocumentoEditar; ?>">
            
            <div class="form-group">
                <label for="nombre_documento">Nombre del Documento</label>
                <input type="text" class="form-control" id="nombre_documento" name="nombre_documento" value="<?php echo $nombreActual; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion_documento">Descripción del Documento</label>
                <textarea class="form-control" id="descripcion_documento" name="descripcion_documento" rows="3"><?php echo $descripcionActual; ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
