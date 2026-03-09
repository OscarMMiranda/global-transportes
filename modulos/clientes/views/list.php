<?php
  //  archivo: /modulos/clientes/views/list.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}
?>

<div class="container mt-4 modulo-clientes-list">

    <!-- Tabs de estado -->
    <?php require __DIR__ . '/../componentes/tabs.php'; ?>

    <!-- Título -->
    <h2 class="titulo-modulo mb-4">
        Gestión de Clientes
    </h2>

    <!-- Botonera -->
    <div class="acciones-modulo mb-3">
        <a href="index.php?action=form" class="btn btn-success">
            Registrar Cliente
        </a>

        <a href="index.php?action=trash" class="btn btn-danger">
            Papelera
        </a>

        <a href="<?= BASE_URL ?>modulos/erp_dashboard.php" class="btn btn-secondary">
            Volver al Dashboard
        </a>
    </div>

    <!-- Mensaje -->
    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-<?= $_GET['msg'] === 'ok' ? 'success' : 'danger' ?>">
            <?= $_GET['msg'] === 'ok' ? 'Operación exitosa.' : 'Ocurrió un error.' ?>
        </div>
    <?php endif; ?>

    <!-- Tabla corporativa -->
    <?php require __DIR__ . '/../componentes/TablaClientes.php'; ?>

    <!-- Footer corporativo (JS del módulo) -->
    <?php require __DIR__ . '/../componentes/FooterClientes.php'; ?>

</div>
