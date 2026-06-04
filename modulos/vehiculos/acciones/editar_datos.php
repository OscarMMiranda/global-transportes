<?php
// archivo: /modulos/vehiculos/acciones/editar_datos.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID inválido</div>";
    exit;
}

$id = intval($_GET['id']);

// Obtener datos del vehículo
$q = $conn->query("SELECT * FROM vehiculos WHERE id = $id LIMIT 1");
$vehiculo = $q->fetch_assoc();

if (!$vehiculo) {
    echo "<div class='alert alert-danger'>Vehículo no encontrado</div>";
    exit;
}
?>

<form id="formEditarDatos">

    <input type="hidden" name="id" value="<?= $vehiculo['id'] ?>">

    <div class="row g-3">

        <!-- PLACA -->
        <div class="col-md-4">
            <label class="form-label">Placa</label>
            <input type="text" name="placa" class="form-control"
                   value="<?= htmlspecialchars($vehiculo['placa']) ?>" required>
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
                        <?= ($vehiculo['marca_id'] == $r['id']) ? 'selected' : '' ?>>
                        <?= $r['nombre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- MODELO -->
        <div class="col-md-4">
            <label class="form-label">Modelo</label>
            <input type="text" name="modelo" class="form-control"
                   value="<?= htmlspecialchars($vehiculo['modelo']) ?>" required>
        </div>

        <!-- AÑO -->
        <div class="col-md-4">
            <label class="form-label">Año</label>
            <input type="number" name="anio" class="form-control"
                   value="<?= htmlspecialchars($vehiculo['anio']) ?>">
        </div>

        <!-- TIPO -->
        <div class="col-md-4">
            <label class="form-label">Tipo</label>
            <select name="tipo_id" class="form-select">
                <option value="">Seleccione...</option>
                <?php
                $q = $conn->query("SELECT id, nombre FROM tipo_vehiculo ORDER BY nombre");
                while ($r = $q->fetch_assoc()):
                ?>
                    <option value="<?= $r['id'] ?>"
                        <?= ($vehiculo['tipo_id'] == $r['id']) ? 'selected' : '' ?>>
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
                        <?= ($vehiculo['configuracion_id'] == $r['id']) ? 'selected' : '' ?>>
                        <?= $r['nombre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- EMPRESA -->
        <div class="col-md-4">
            <label class="form-label">Empresa</label>
            <select name="empresa_id" class="form-select" required>
                <option value="">Seleccione...</option>
                <?php
                $q = $conn->query("SELECT id, razon_social FROM empresa ORDER BY razon_social");
                while ($r = $q->fetch_assoc()):
                ?>
                    <option value="<?= $r['id'] ?>"
                        <?= ($vehiculo['empresa_id'] == $r['id']) ? 'selected' : '' ?>>
                        <?= $r['razon_social'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

    </div>

    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary">
            Guardar cambios
        </button>
    </div>

</form>

<script>
$("#formEditarDatos").on("submit", function(e){
    e.preventDefault();

    $.post("/modulos/vehiculos/acciones/guardar_datos.php", $(this).serialize(), function(r){

        if (r.ok) {
            notifySuccess("Datos actualizados", "Los datos técnicos fueron guardados correctamente.");
        } else {
            notifyError("Error", r.msg || "No se pudo guardar.");
        }

    }, "json");
});
</script>
