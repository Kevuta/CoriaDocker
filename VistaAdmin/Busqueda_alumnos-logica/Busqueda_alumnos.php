<?php
session_start();
// Verificar si la sesión está iniciada y el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    // Redireccionar al index.php si no cumple con los requisitos
    header("Location: ../../index.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
include_once '../../basedatos.php';

// Realizar la consulta para obtener la lista de usuarios (alumnos)
$sql = "SELECT * FROM usuarios WHERE rol = 'alumno'";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
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
        <!-- Migas de pan -->
        <nav aria-label="breadcrumb" id="breadcrumbs-container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../PagprincipalAdmin.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Busquea de usuarios</li>
            </ol>
        </nav>

    <main class="container mt-4">
        <div class="text-center my-4">
            <h1 class="mb-4">Lista de Usuarios (Alumnos)</h1>
        </div>
        
        <!-- Formulario de búsqueda -->
        <form id="formBusqueda" class="mb-4">
            <div class="form-group">
                <label for="correo">Buscar por correo electrónico:</label>
                <input type="text" class="form-control" id="correo" name="correo">
            </div>
        </form>

        <div class="row" id="resultados">
            <?php
            // Verificar si hay usuarios para mostrar
            if ($resultado->num_rows > 0) {
                // Iterar sobre cada fila de resultado
                while ($fila = $resultado->fetch_assoc()) {
                    ?>
                    <div class="col-md-4 mb-3 usuario"> <!-- Agregar la clase 'usuario' para realizar la búsqueda -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $fila['nombre']; ?></h5>
                                <p class="card-text correo"><?php echo $fila['email']; ?></p> <!-- Agregar la clase 'correo' para realizar la búsqueda -->
                                <a href="../Logica_ver_doc_y_validar/ver_doc_user.php?usuario_id=<?php echo $fila['id']; ?>" class="btn btn-primary">Ver documentos</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Si no hay usuarios, mostrar un mensaje
                echo "<p>No hay usuarios para mostrar.</p>";
            }
            ?>
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
    <!-- Script de búsqueda con JavaScript -->
    <script>
        // Función para realizar la búsqueda de manera dinámica mientras el usuario escribe
        document.getElementById("correo").addEventListener("input", function() {
            var input = this.value.toLowerCase(); // Convertir a minúsculas para comparación
            var usuarios = document.querySelectorAll(".usuario"); // Obtener todos los elementos de usuario
            usuarios.forEach(function(usuario) {
                var correo = usuario.querySelector(".correo").textContent.toLowerCase(); // Obtener el correo electrónico del usuario y convertir a minúsculas para comparación
                if (correo.includes(input) || input === "") { // Mostrar el usuario si el correo coincide con la búsqueda o si no hay nada en el campo de búsqueda
                    usuario.style.display = "block";
                } else {
                    usuario.style.display = "none"; // Ocultar el usuario si no coincide con la búsqueda
                }
            });
        });
    </script>
</body>
</html>
