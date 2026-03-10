<?php
    // archivo: /modulos/clientes/views/list.php
if (!defined('GT_APP')) {
    define('GT_APP', true);
}
?>

<div class="container mt-4 modulo-clientes-list">

    <!-- Título del módulo -->
    <?php require __DIR__ . '/../componentes/header_titulo.php'; ?>

    <!-- Botonera -->
    <div class="acciones-modulo mb-3 mt-3">

        <button class="btn btn-success" onclick="abrirModalCliente();">
    Registrar Cliente
</button>

        <a href="index.php?action=trash" class="btn btn-danger">Papelera</a>
        <a href="<?= BASE_URL ?>modulos/erp_dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
    </div>

     <!-- Tabs -->
    <?php require __DIR__ . '/../componentes/tabs.php'; ?>

    <!-- Mensaje -->
    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-<?= $_GET['msg'] === 'ok' ? 'success' : 'danger' ?>">
            <?= $_GET['msg'] === 'ok' ? 'Operación exitosa.' : 'Ocurrió un error.' ?>
        </div>
    <?php endif; ?>

    <!-- Tabla -->
    <?php require __DIR__ . '/../componentes/TablaClientes.php'; ?>

    <!-- Footer -->
    <?php require __DIR__ . '/../componentes/FooterClientes.php'; ?>

</div>
