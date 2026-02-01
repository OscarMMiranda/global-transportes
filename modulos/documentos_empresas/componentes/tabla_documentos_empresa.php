<?php
// archivo: /modulos/documentos_empresas/componentes/tabla_documentos_empresa.php
?>

<div class="container-fluid mt-4">

    <!-- TÍTULO -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="mb-1">
                Documentos de la empresa: 
                <strong><?= htmlspecialchars($empresa['razon_social']) ?></strong>
            </h4>
            <p class="mb-0">
                RUC: <?= htmlspecialchars($empresa['ruc']) ?><br>
                Dirección: <?= htmlspecialchars($empresa['direccion']) ?>
            </p>
        </div>
    </div>

    <!-- CONTENEDOR PARA LAS PESTAÑAS -->
    <div id="contenedorTabs"></div>

    <!-- MODAL HISTORIAL -->
    <?php include __DIR__ . '/../componentes/modal_historial.php'; ?>

    <!-- MODAL PREVIEW -->
    <?php include __DIR__ . '/../componentes/modal_preview.php'; ?>

    <!-- MODAL SUBIR -->
    <?php include __DIR__ . '/../componentes/modal_subir_documento.php'; ?>

</div>

