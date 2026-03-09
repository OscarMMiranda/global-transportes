<?php
// archivo: modulos/clientes/views/modal.php

// Validación estricta
if (!isset($cliente) || !is_array($cliente)) {
    echo '<p class="text-danger">Cliente no encontrado.</p>';
    return;
}

// Función segura para mostrar valores
function campo($valor, $placeholder = '<em>No registrado</em>') {
    if (!isset($valor) || $valor === '' || $valor === null) {
        return $placeholder;
    }
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
?>

<div class="container-fluid modal-cliente">
    <dl class="row mb-0">

        <dt class="col-sm-4">ID</dt>
        <dd class="col-sm-8"><?= campo($cliente['id']) ?></dd>

        <dt class="col-sm-4">Nombre</dt>
        <dd class="col-sm-8"><?= campo($cliente['nombre']) ?></dd>

        <dt class="col-sm-4">RUC</dt>
        <dd class="col-sm-8"><?= campo($cliente['ruc']) ?></dd>

        <dt class="col-sm-4">Dirección</dt>
        <dd class="col-sm-8"><?= campo($cliente['direccion']) ?></dd>

        <dt class="col-sm-4">Correo</dt>
        <dd class="col-sm-8"><?= campo($cliente['correo']) ?></dd>

        <dt class="col-sm-4">Teléfono</dt>
        <dd class="col-sm-8"><?= campo($cliente['telefono']) ?></dd>

        <dt class="col-sm-4">Departamento</dt>
        <dd class="col-sm-8"><?= campo($cliente['departamento']) ?></dd>

        <dt class="col-sm-4">Provincia</dt>
        <dd class="col-sm-8"><?= campo($cliente['provincia']) ?></dd>

        <dt class="col-sm-4">Distrito</dt>
        <dd class="col-sm-8"><?= campo($cliente['distrito']) ?></dd>

    </dl>
</div>
