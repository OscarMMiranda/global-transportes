<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";
$conn = getConnection();

$modo = "nuevo";
$vehiculo = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $modo = "editar";
    $id = intval($_GET['id']);

    $q = $conn->query("SELECT * FROM vehiculos WHERE id = $id LIMIT 1");
    $vehiculo = $q->fetch_assoc();
}
?>

<form id="formVehiculo">

    <?php if ($modo === "editar"): ?>
        <input type="hidden" name="id" value="<?= $vehiculo['id'] ?>">
    <?php endif; ?>

    <div class="row g-3">

        <!-- PLACA -->
        <div class="col-md-4">
            <label class="form-label">Placa</label>
            <input type="text" name="placa" class="form-control"
                   value="<?= $modo === 'editar' ? htmlspecialchars($vehiculo['placa']) : '' ?>"
                   required>
        </div>

        <!-- MARCA -->
        <div class="col-md-4">
            <label class="form-label">Marca</label>
            <select name="marca_id" class="form-select" required>
                <option value="">Seleccione...</option>
                <?php
                $q = $conn->query("SELECT id, nombre FROM marca_vehiculo ORDER BY nombre");
                while ($r = $q->fetch_assoc()):
                ?>
                    <option value="<?= $r['id'] ?>"
                        <?= ($modo === 'editar' && $vehiculo['marca_id'] == $r['id']) ? 'selected' : '' ?>>
                        <?= $r['nombre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- ESTADO -->
        <div class="col-md-4">
            <label class="form-label">Estado</label>
            <select name="estado_id" class="form-select" required>
                <option value="">Seleccione...</option>
                <?php
                $q = $conn->query("SELECT id, nombre FROM estado_vehiculo ORDER BY nombre");
                while ($r = $q->fetch_assoc()):
                ?>
                    <option value="<?= $r['id'] ?>"
                        <?= ($modo === 'editar' && $vehiculo['estado_id'] == $r['id']) ? 'selected' : '' ?>>
                        <?= $r['nombre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- CONFIGURACIÓN -->
        <div class="col-md-4">
            <label class="form-label">Configuración</label>
            <select name="configuracion_id" class="form-select" required>
                <option value="">Seleccione...</option>
                <?php
                $q = $conn->query("SELECT id, nombre FROM configuracion_vehiculo ORDER BY nombre");
                while ($r = $q->fetch_assoc()):
                ?>
                    <option value="<?= $r['id'] ?>"
                        <?= ($modo === 'editar' && $vehiculo['configuracion_id'] == $r['id']) ? 'selected' : '' ?>>
                        <?= $r['nombre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- TIPO (FALTABA) -->
        <div class="col-md-4">
            <label class="form-label">Tipo</label>
            <select name="tipo_id" class="form-select">
                <option value="">Seleccione...</option>
                <?php
                $q = $conn->query("SELECT id, nombre FROM tipo_vehiculo ORDER BY nombre");
                while ($r = $q->fetch_assoc()):
                ?>
                    <option value="<?= $r['id'] ?>"
                        <?= ($modo === 'editar' && $vehiculo['tipo_id'] == $r['id']) ? 'selected' : '' ?>>
                        <?= $r['nombre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- MODELO -->
        <div class="col-md-4">
            <label class="form-label">Modelo</label>
            <input type="text" name="modelo" class="form-control"
                   value="<?= $modo === 'editar' ? htmlspecialchars($vehiculo['modelo']) : '' ?>"
                   required>
        </div>

        <!-- AÑO -->
        <div class="col-md-4">
            <label class="form-label">Año</label>
            <input type="number" name="anio" class="form-control"
                   value="<?= $modo === 'editar' ? htmlspecialchars($vehiculo['anio']) : '' ?>">
        </div>

        <!-- OBSERVACIONES -->
        <div class="col-md-12">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3"><?= 
                $modo === 'editar' ? htmlspecialchars($vehiculo['observaciones']) : '' 
            ?></textarea>
        </div>

    </div>

    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary">
            <?= $modo === "editar" ? "Guardar cambios" : "Registrar vehículo" ?>
        </button>
    </div>

</form>