<?php 
    // archivo: modulos/clientes/views/form.php

    if (!defined('GT_APP')) { define('GT_APP', true); } 
?>

<form id="formCliente">

    <?php if ($editing): ?>
        <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
    <?php endif; ?>

    <!-- Nombre -->
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required
               value="<?= htmlspecialchars($cliente['nombre']) ?>">
    </div>

    <!-- RUC -->
    <div class="mb-3">
        <label class="form-label">RUC</label>
        <input type="text" name="ruc" class="form-control"
               value="<?= htmlspecialchars($cliente['ruc']) ?>">
    </div>

    <!-- Dirección -->
    <div class="mb-3">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" class="form-control"
               value="<?= htmlspecialchars($cliente['direccion']) ?>">
    </div>

    <!-- Correo -->
    <div class="mb-3">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" class="form-control"
               value="<?= htmlspecialchars($cliente['correo']) ?>">
    </div>

    <!-- Teléfono -->
    <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control"
               value="<?= htmlspecialchars($cliente['telefono']) ?>">
    </div>

    <!-- UBIGEO -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Departamento</label>
            <select id="departamento_id" name="departamento_id" class="form-select">
                <option value="">Seleccione...</option>
                <?php foreach ($departamentos as $dep): ?>
                    <option value="<?= $dep['id'] ?>"
                        <?= ($editing && $cliente['departamento_id'] == $dep['id']) ? 'selected' : '' ?>>
                        <?= $dep['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Provincia</label>
            <select id="provincia_id" name="provincia_id" class="form-select">
                <option value="">Seleccione...</option>
                <?php foreach ($provincias as $prov): ?>
                    <option value="<?= $prov['id'] ?>"
                        <?= ($editing && $cliente['provincia_id'] == $prov['id']) ? 'selected' : '' ?>>
                        <?= $prov['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Distrito</label>
            <select id="distrito_id" name="distrito_id" class="form-select">
                <option value="">Seleccione...</option>
                <?php foreach ($distritos as $dis): ?>
                    <option value="<?= $dis['id'] ?>"
                        <?= ($editing && $cliente['distrito_id'] == $dis['id']) ? 'selected' : '' ?>>
                        <?= $dis['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="mt-3 text-end">
        <button type="submit" class="btn btn-success">
            <?= $editing ? 'Actualizar' : 'Guardar' ?>
        </button>
    </div>

</form>
