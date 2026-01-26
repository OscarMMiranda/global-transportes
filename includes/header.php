<?php
// archivo: /includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Global Transportes</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/css/base.css">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="pt-5">

<!-- Barra superior -->
<nav class="navbar navbar-dark bg-dark p-2">
    <div class="container-fluid d-flex justify-content-between">
        <span class="navbar-brand mb-0 h1">ERP Global Transportes</span>
        <div>
            <?php if (isset($_SESSION['usuario'])) { ?>
                <span class="text-white me-3">Bienvenido, <?= htmlspecialchars($_SESSION['usuario']); ?></span>
                <a href="/sistema/logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
            <?php } else { ?>
                <a href="/login.php" class="btn btn-success btn-sm">Iniciar Sesión</a>
            <?php } ?>
        </div>
    </div>
</nav>

<!-- Menú principal -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="/index.php">
            <img src="/img/logo.png" alt="Global Transportes" width="120">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="/nosotros.html">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="/servicios.html">Servicios</a></li>
                <li class="nav-item"><a class="nav-link" href="/contacto.html">Contacto</a></li>

                <?php if (isset($_SESSION['usuario'])) { ?>
                    <li class="nav-item"><a class="nav-link" href="/sistema/panel.php">Panel</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
