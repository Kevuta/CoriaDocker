<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Finalizar la sesión
session_destroy();

// Redirigir a la página de inicio o a donde desees después de destruir la sesión
header("Location: ../index.php");
exit();
?>
