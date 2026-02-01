<?php
// archivo: /modulos/documentos_vehiculos/vistas/index.php

// Rutas seguras y compatibles con PHP 5.6 e IIS
require_once __DIR__ . '/../../../includes/header_panel.php';
require_once __DIR__ . '/../../../includes/componentes/topbar_global.php';
require_once __DIR__ . '/../../../includes/componentes/navbar_global.php';

?>

<div class="container-fluid mt-4">

    <?php require_once __DIR__ . '/../componentes/titulo_documentos.php'; ?>

    <?php require_once __DIR__ . '/../componentes/tabla_documentos_vehiculos.php'; ?>

    <?php require_once __DIR__ . '/../componentes/modal_historial.php'; ?>

    <?php require_once __DIR__ . '/../componentes/modal_preview.php'; ?>



    

</div>

<?php
require_once __DIR__ . '/../../../includes/footer_panel.php';
?>

<script src="/modulos/documentos_vehiculos/js/documentos_vehiculos.js?v=<?= time() ?>"></script>
<script src="/modulos/documentos_vehiculos/js/documentos.js?v=<?= time() ?>"></script>

<script src="/modulos/documentos_vehiculos/js/documentos_historial.js?v=<?= time() ?>"></script>

