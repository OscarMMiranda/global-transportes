<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// archivo: /modulos/vehiculos/vistas/detalle.php

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<div class='alert alert-danger'>ID inválido</div>");
}

$id = intval($_GET['id']);
?>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Estilos del módulo -->
<link rel="stylesheet" href="/modulos/vehiculos/css/detalle.css">

<div class="container py-4">

    <!-- Encabezado modular -->
    <?php include __DIR__ . "/detalle_header.php"; ?>

    <!-- Pestañas -->
    <?php include __DIR__ . "/detalle_tabs.php"; ?>

    <!-- Contenedores de pestañas -->
    <?php include __DIR__ . "/detalle_body.php"; ?>

    <!-- Footer -->
    <?php include __DIR__ . "/detalle_footer.php"; ?>

</div>

<!-- jQuery necesario para .load() -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS (NECESARIO para que las pestañas funcionen) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Variable global para JS -->
<script>
    const VEHICULO_ID = <?= $id ?>;
</script>

<!-- Lógica del módulo -->
<script src="../js/detalle.js"></script>