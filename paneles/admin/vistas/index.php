<?php
// archivo: /paneles/admin/vistas/index.php

// Esta vista asume que el controlador ya definió:
// - $tarjetas (array con las tarjetas del dashboard)
// - Sesión validada
?>

<?php include __DIR__ . '/../componentes/header.php'; ?>
<?php include __DIR__ . '/../componentes/navbar.php'; ?>

<div class="container mt-4">

    <h2 class="mb-4">Panel de Administración</h2>

    <!-- Tarjetas del dashboard -->
    <?php include __DIR__ . '/../componentes/tarjetas.php'; ?>

</div>

<?php include __DIR__ . '/../componentes/footer.php'; ?>