<?php
session_start();
include_once '../../basedatos.php';

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../../index.php");
    exit();
}

if (isset($_GET['usuario_id'])) {
    $usuario_id = $_GET['usuario_id'];

    // Consulta SQL para obtener los documentos del usuario
    $sql_documentos = "SELECT * FROM documentos WHERE usuario_id = $usuario_id";
    $result_documentos = $conn->query($sql_documentos);

    // Consulta SQL para obtener el nombre y el correo del usuario
    $sql_usuario = "SELECT nombre, email FROM usuarios WHERE id = $usuario_id";
    $result_usuario = $conn->query($sql_usuario);

    // Verificar si se encontró el usuario
    if ($result_usuario->num_rows > 0) {
        $row_usuario = $result_usuario->fetch_assoc();
        $nombre_usuario = $row_usuario['nombre'];
        $correo_usuario = $row_usuario['email'];
    } else {
        $nombre_usuario = "Usuario no encontrado";
        $correo_usuario = "Correo no encontrado";
    }
} else {
    // Redireccionar si no se proporciona el ID del usuario
    header("Location: ../../index.php");
    exit();
}

// Establecer la zona horaria a México
date_default_timezone_set('America/Mexico_City');


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos del Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/6720e4bdbe.js" crossorigin="anonymous"></script>
</head>
<body>
    <header class="bg-dark text-white">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="../PagprincipalAdmin.php">Tramites UTA</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                </ul>
            </div>
            <div class="navbar-nav ml-auto">
                <a class="nav-link" href="../../Login/DestruirSesion.php">Cerrar Sesión</a>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
    <nav aria-label="breadcrumb" id="breadcrumbs-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../PagprincipalAdmin.php">Inicio</a></li>
            <li class="breadcrumb-item"><a href="../Busqueda_alumnos-logica/Busqueda_alumnos.php">Busqueda de usuarios (alumnos)</a></li>
            <li class="breadcrumb-item active" aria-current="page">Documentos del Usuario</li>
        </ol>
        </nav>

        <div class="text-center my-4">
    <h1 class="mb-4">Documentos del Usuario</h1>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información del Usuario</h5>
                    <p class="card-text"><strong>Nombre del usuario:</strong> <?php echo $nombre_usuario; ?></p>
                    <p class="card-text"><strong>Correo del usuario:</strong> <?php echo $correo_usuario; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>


        <table class="table">
        <thead>
        <tr>
            <th>Tipo de Documento</th>
            <th>Nombre del Archivo</th>
            <th>Estado de Validación</th>
            <th>Fecha de Validación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result_documentos->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['tipo_documento']; ?></td>
                <td><?php echo $row['archivo']; ?></td>
                <td><?php echo $row['validado'] == 'aprobado' ? 'Aprobado' : ($row['validado'] == 'rechazado' ? 'Rechazado' : 'Pendiente'); ?></td>
                <td><?php echo $row['fecha_validacion'] ? $row['fecha_validacion'] : 'Pendiente'; ?></td>
                <td>
    <div class="btn-group" role="group" aria-label="Acciones">
        <a href="../../DocumentosAlumnos/<?php echo $row['archivo']; ?>" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-eye"></i> Ver
        </a>
        <a href="../../DocumentosAlumnos/<?php echo $row['archivo']; ?>" download class="btn btn-outline-success">
            <i class="fas fa-download"></i> Descargar
        </a>
        <!-- Botón para abrir modal de validación -->
        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modalValidar<?php echo $row['id']; ?>">
            <i class="fas fa-file-circle-check"></i> Validar
        </button>
    </div>
</td>


                    </tr>

                    <!-- Modal para validar el documento -->
                    <div class="modal fade" id="modalValidar<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalValidarLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalValidarLabel">Validar Documento</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Formulario para agregar fecha de validación -->
                                    <form action="logica_validar_doc.php" method="POST">
         


                                        <!-- Opciones para aceptar o rechazar el documento -->
                                        <div class="form-group">
                                            <label for="estado_validacion">Estado de Validación</label>
                                            <select class="form-control" id="estado_validacion" name="estado_validacion" required>
                                                <option value="aprobado">Aprobado</option>
                                                <option value="rechazado">Rechazado</option>
                                                <option value="pendiente">Pendiente</option>
                                            </select>
                                        </div>
                                        <!-- Campo oculto para enviar el ID del documento -->
                                        <input type="hidden" name="documento_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <footer class="bg-dark text-white text-center py-2">
        <p>&copy; Tramites UTA</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
   
</body>
</html>
