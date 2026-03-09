<?php
// archivo: modulos/clientes/views/form.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}

// Variables esperadas:
// $editing, $cliente, $departamentos, $provincias, $distritos, $errorMessage
?>

<div class="container mt-4 modulo-clientes-form">

    <?php require __DIR__ . '/../componentes/tabs.php'; ?>

    <h2 class="titulo-modulo mb-4">
        <?= $editing ? 'Editar Cliente' : 'Registrar Cliente'; ?>
    </h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="index.php?action=form<?= $editing ? '&id=' . $cliente['id'] : ''; ?>">

        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($cliente['id'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>

        <!-- Nombre -->
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required
                   value="<?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <!-- RUC -->
        <div class="mb-3">
            <label class="form-label">RUC</label>
            <input type="text" name="ruc" class="form-control"
                   value="<?= htmlspecialchars($cliente['ruc'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <!-- Dirección -->
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control"
                   value="<?= htmlspecialchars($cliente['direccion'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <!-- Correo -->
        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control"
                   value="<?= htmlspecialchars($cliente['correo'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <!-- Teléfono -->
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                   value="<?= htmlspecialchars($cliente['telefono'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <!-- UBIGEO -->
        <div class="row">

            <!-- Departamento -->
            <div class="mb-3 col-md-4">
                <label class="form-label">Departamento</label>
                <select id="departamento_id" name="departamento_id" class="form-select">
                    <option value="">Seleccione...</option>
                    <?php foreach ($departamentos as $dep): ?>
                        <option value="<?= $dep['id']; ?>"
                            <?= ($editing && $cliente['departamento_id'] == $dep['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($dep['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Provincia -->
            <div class="mb-3 col-md-4">
                <label class="form-label">Provincia</label>
                <select id="provincia_id" name="provincia_id" class="form-select">
                    <option value="">Seleccione...</option>
                    <?php foreach ($provincias as $prov): ?>
                        <option value="<?= $prov['id']; ?>"
                            <?= ($editing && $cliente['provincia_id'] == $prov['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($prov['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Distrito -->
            <div class="mb-3 col-md-4">
                <label class="form-label">Distrito</label>
                <select id="distrito_id" name="distrito_id" class="form-select">
                    <option value="">Seleccione...</option>
                    <?php foreach ($distritos as $dis): ?>
                        <option value="<?= $dis['id']; ?>"
                            <?= ($editing && $cliente['distrito_id'] == $dis['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($dis['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <?= $editing ? 'Actualizar' : 'Guardar'; ?>
            </button>

            <a href="index.php?action=list" class="btn btn-secondary ms-2">
                Cancelar
            </a>
        </div>

    </form>

    <?php require __DIR__ . '/../componentes/FooterClientes.php'; ?>

</div>
