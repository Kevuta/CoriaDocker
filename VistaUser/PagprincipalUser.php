<?php
session_start();

// Verificar si la sesión está iniciada y el rol es 'alumno'
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'alumno') {
    // Si no hay sesión iniciada o el rol no es 'alumno', redirigir al index.php
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Resto de las etiquetas head -->
    <!-- ... -->
</head>
<body>
    <!-- Resto del contenido de tu página -->
    <!-- ... -->

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
    <!-- Enlace al archivo de script JavaScript -->
  
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
                
             </div>
            <!-- Enlace para cerrar sesión -->
            <div class="navbar-nav ml-auto">
                <a class="nav-link" href="../Login/DestruirSesion.php">Cerrar Sesión</a>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
    <!-- Migas de pan -->
    <nav aria-label="breadcrumb" id="breadcrumbs-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="PagprincipalUser.php">Inicio</a></li>
        </ol>
    </nav>

    <div class="text-center my-4">
        <h1 class="mb-4">Trámite de Inscripción</h1>
    </div>

    <!-- Contenido de la Página -->
    <div class="row justify-content-center">
        <div class="col-md-4 mb-3">
            <a href="perfil.html" class="btn btn-secondary btn-lg btn-block">Acceder a Perfil</a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="./backend_ingresa_doc_user/Form_ingresa_doc.php" class="btn btn-success btn-lg btn-block">Ingresar documentos requeridos</a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="./logica_select_doc_user/doc_user.php" class="btn btn-info btn-lg btn-block">Acceder al trámite</a>
        </div>
    </div>
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
