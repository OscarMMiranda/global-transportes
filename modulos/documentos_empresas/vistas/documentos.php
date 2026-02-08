<?php
// archivo: /modulos/documentos_empresas/vistas/documentos.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/header_panel.php';
require_once __DIR__ . '/../../../includes/componentes/topbar_global.php';
require_once __DIR__ . '/../../../includes/componentes/navbar_global.php';

// ============================================================
// VALIDAR ID
// ============================================================
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger m-4'>ID de empresa inválido.</div>";
    require_once __DIR__ . '/../../../includes/footer_panel.php';
    exit;
}

$empresa_id = intval($_GET['id']);
$conn = getConnection();

// ============================================================
// OBTENER DATOS DE LA EMPRESA
// ============================================================
$sql = $conn->prepare("
    SELECT id, razon_social, ruc, direccion
    FROM empresa
    WHERE id = ?
");
$sql->bind_param("i", $empresa_id);
$sql->execute();
$res = $sql->get_result();

if ($res->num_rows == 0) {
    echo "<div class='alert alert-danger m-4'>La empresa no existe.</div>";
    require_once __DIR__ . '/../../../includes/footer_panel.php';
    exit;
}

$empresa = $res->fetch_assoc();
?>

<div class="container-fluid mt-4">

    <!-- TÍTULO -->
    <div class="card mb-1">
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

    <!-- CONTENEDOR PARA LAS PESTAÑAS DINÁMICAS -->
    <div id="contenedorTabs"></div>

    <!-- MODAL HISTORIAL -->
    <?php include __DIR__ . '/../componentes/modal_historial.php'; ?>

    <!-- MODAL PREVIEW -->
    <?php include __DIR__ . '/../componentes/modal_preview.php'; ?>

    <!-- MODAL SUBIR -->
    <?php include __DIR__ . '/../componentes/modal_subir_documento.php'; ?>

</div>

<?php
require_once __DIR__ . '/../../../includes/footer_panel.php';
?>

<!-- JS DEL MÓDULO -->
<script src="/modulos/documentos_empresas/js/documentos_tabs.js?v=<?= time() ?>"></script>
<script src="/modulos/documentos_empresas/js/documentos_empresas.js?v=<?= time() ?>"></script>
<script src="/modulos/documentos_empresas/js/documentos_historial.js?v=<?= time() ?>"></script>
