<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'empleado') {
    header("Location: login.php");
    exit();
}
$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Empleado - Global Transportes</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
<header>
    <div class="contenedor">
        <div class="logo">
            <a href="../index.html"><img src="../img/logo.png" alt="Logo Global Transportes" class="logo-img"></a>
        </div>
        <h1>Bienvenido, <?= htmlspecialchars($usuario) ?></h1>
    </div>
</header>

<main class="contenido">
    <div class="panel">
        <h2>Panel de Empleado</h2>
        <p>Selecciona una opción para continuar:</p>
        <div class="opciones-panel">
            <a href="ordenes_trabajo.php" class="boton-accion">Órdenes de Trabajo</a>
            <a href="mi_perfil.php" class="boton-accion">Mi Perfil</a>
            <a href="logout.php" class="boton-accion salir">Cerrar Sesión</a>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
