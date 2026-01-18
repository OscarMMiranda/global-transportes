<?php
// archivo: /modulos/mantenimiento/index.php

session_start();

// Depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// Configuración y conexión
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/permisos.php';

$conn = getConnection();

// Solo admins
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    header('Location: /login.php');
    exit;
}

// Cargar definición de módulos
$modulos = require __DIR__ . '/modulos.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mantenimiento de Datos – Global Transportes</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Estilos del dashboard -->
    <link rel="stylesheet" href="../../css/base.css">
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/componentes/header.css">
    <link rel="stylesheet" href="../../css/componentes/footer.css">
    <link rel="stylesheet" href="../../css/componentes/tarjetas.css">
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <?php include __DIR__ . '/componentes/layout/header.php'; ?>

    <!-- MAIN -->
    <main class="dashboard-container flex-fill container-fluid px-4 py-4">
        <section class="dashboard-section">
            <h2 class="h5 mb-4">Seleccione la tabla a actualizar</h2>

            <div class="row row-cols-1 row-cols-md-2 g-4 dashboard-cards">

                <?php foreach ($modulos as $mod): ?>
                    <?php
                        $archivoTarjeta = __DIR__ . '/componentes/tarjetas/' . $mod['archivo_tarjeta'];
                        if (file_exists($archivoTarjeta)) {
                            // Cada tarjeta puede usar $mod si lo necesita
                            $moduloConfig = $mod;
                            include $archivoTarjeta;
                        }
                    ?>
                <?php endforeach; ?>

            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <?php include __DIR__ . '/componentes/layout/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>