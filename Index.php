<?php
session_start();

// Verificar si hay una sesión iniciada
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    // Si hay una sesión iniciada, redirigir según el rol
    if ($_SESSION['rol'] === 'administrador') {
        header("Location: VistaAdmin/PagPrincipalAdmin.php");
        exit();
    } elseif ($_SESSION['rol'] === 'alumno') {
        header("Location: VistaUser/PagPrincipalUser.php");
        exit();
    }
}
// Verificar si hay un mensaje de error de contraseñas no coincidentes
if (isset($_SESSION['error_message'])) {
    echo '<script>';
    echo 'Swal.fire("Error", "' . $_SESSION['error_message'] . '", "error");';
    echo '</script>';

    // Limpiar la variable de sesión
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <link rel="stylesheet" href="./Login/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card text-center" style="background-color: rgba(128, 128, 128, 0.8);">
         
            <div class="card-body">
                <h2 class="card-title">Iniciar Sesión</h2>
                <!-- Modificación aquí: apuntamos al archivo autenticacionlogin.php -->
                <form id="login-form" action="./Login/autenticacionlogin.php" method="post">
                    <!-- Campos de inicio de sesión (por ejemplo, email y contraseña) -->
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </form>

                <!-- Modificación aquí: apuntamos al archivo registro.php -->
                <form id="register-form" style="display:none;" action="./Login/registro.php" method="post">
                    <!-- Campos de registro (por ejemplo, nombre, email, contraseñas) -->
                    <h2>Registrarse</h2>
                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="register-email">Correo Electrónico:</label>
                        <input type="email" id="register-email" name="register-email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Contraseña:</label>
                        <input type="password" id="register-password" name="register-password" class="form-control" required>
                    </div>
                    <!-- Nuevo campo para confirmar contraseña -->
                    <div class="form-group">
                        <label for="confirm-password">Confirmar Contraseña:</label>
                        <input type="password" id="confirm-password" name="confirm-password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Registrarse</button>
                </form>

                <!-- Botón para "olvidar mi contraseña" -->
                <a href="./Login/Form_pedircorreo.php" class="btn btn-danger mt-3">¿Olvidaste tu contraseña?</a>

                <!-- Botón para cambiar entre formularios de inicio de sesión y registro -->
                <button id="switch-form" class="btn btn-dark mt-3" type="button">¿No tienes cuenta? Regístrate aquí.</button>

            </div>
        </div>
    </div>
</body>
</html>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="./Login/loginJs.js"></script>
</body>
</html>
