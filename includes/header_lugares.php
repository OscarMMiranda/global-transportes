<?php

// session_start(); // Iniciar sesión para gestionar autenticación

?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Global Transportes</title>
    
    <!-- Enlace a Bootstrap para mejor diseño -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/base.css"> <!-- Tus estilos personalizados -->
</head>
<body>

    <!-- Barra superior de sesión -->
    <nav class="navbar navbar-dark bg-primary p-2">
        <div class="container-fluid d-flex justify-content-between">
            <span class="navbar-brand mb-0 h1">ERP Global Transportes</span>
            <div>
                <?php if (isset($_SESSION['usuario'])) { ?>
                    <span class="text-white me-3">Bienvenido, <?= htmlspecialchars($_SESSION['usuario']); ?></span>
                    <a href="../../sistema/logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                <?php } else { ?>
                <a href="../../../login.php" class="btn btn-success btn-sm">Iniciar Sesión</a>
            <?php } ?>
        </div>
    </div>
</nav>

<!-- Menú de navegación dentro del ERP -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="../panel.php">
            <img src="../../img/logo.png" alt="Global Transportes"  width="100" >
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuERP">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuERP">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../../sistema/panel_admin.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="../vehiculos/vehiculos.php">Vehículos</a></li>
                <li class="nav-item"><a class="nav-link" href="../clientes/clientes.php">Clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="../documentos/documentos.php">Documentos</a></li>
                <li class="nav-item"><a class="nav-link" href="../lugares/lugares.php">Cancelar</a></li>
                <!-- 
                    <li class="nav-item"><a class="nav-link" href="../sistema/historial_bd.php">Historial BD</a></li>
                -->
                <li class="nav-item"><a class="nav-link" href="../sistema/ayuda.php">Ayuda</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">