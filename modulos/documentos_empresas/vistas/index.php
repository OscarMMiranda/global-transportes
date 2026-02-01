<?php
// archivo: /modulos/documentos_empresas/vistas/index.php

require_once __DIR__ . '/../../../includes/header_panel.php';
require_once __DIR__ . '/../../../includes/componentes/topbar_global.php';
require_once __DIR__ . '/../../../includes/componentes/navbar_global.php';
?>

<div class="container-fluid mt-4">

    <div class="card">

        <?php include __DIR__ . '/../componentes/header_empresas.php'; ?>
        <?php include __DIR__ . '/../componentes/tabla_empresas.php'; ?>

    </div>

</div>

<?php
// ESTE ES EL CORRECTO — IGUAL QUE EN VEHÍCULOS
require_once __DIR__ . '/../../../includes/footer_panel.php';
?>

<!-- Scripts del módulo -->
<script src="/modulos/documentos_empresas/js/tabla_empresas.js?v=<?= time() ?>"></script>
