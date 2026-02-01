<?php
// archivo: /modulos/documentos_conductores/vistas/index.php

require_once __DIR__ . '/../../../includes/header_panel.php';
require_once __DIR__ . '/../../../includes/componentes/topbar_global.php';
require_once __DIR__ . '/../../../includes/componentes/navbar_global.php';
?>

<div class="container-fluid mt-4">

    <?php require_once __DIR__ . '/../componentes/titulo_documentos.php'; ?>

    <?php require_once __DIR__ . '/../componentes/tabla_documentos_conductores.php'; ?>

</div>

<div id="notificacionesERP"></div>

<link rel="stylesheet" href="/modulos/documentos_conductores/css/documentos.css?v=<?= time() ?>">
<link rel="stylesheet" href="/modulos/documentos_conductores/css/notificaciones.css?v=<?= time() ?>">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/modulos/documentos_conductores/js/listado.js?v=<?= time() ?>"></script>

<?php require_once __DIR__ . '/../../../includes/footer_panel.php'; ?>
