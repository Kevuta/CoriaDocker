<?php
// Obtener el token de la URL
$token = $_GET['token'] ?? null;

// Verificar si el token está presente
if (!$token) {
    // El token no está presente, redirigir al index
    header("Location: ../Index.php");
    exit(); // Asegúrate de terminar la ejecución del script después de redirigir
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        /* Estilo adicional para centrar el formulario */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .card {
            width: 400px;
            border: none;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            font-weight: bold;
            padding: 20px;
            border-bottom: none;
        }
        .card-body {
            padding: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h2>Restablecer Contraseña</h2>
        </div>
        <div class="card-body">
            <form action="Logic_restablecer_contraseña.php" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="form-group">
                    <label for="password">Nueva Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Restablecer Contraseña</button>
            </form>
        </div>
    </div>
</body>
</html>
