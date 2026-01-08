<?php
// archivo: /modulos/documentos/componentes/formularios/form_filtros.php

// ✅ Ruta corregida (4 niveles arriba)
require_once __DIR__ . '/../../../../includes/config.php';

$conn = getConnection();

// Obtener tipos de documento activos
$tipos = [];
$stmt = $conn->prepare("SELECT id, nombre FROM tipos_documento WHERE estado = 1 ORDER BY nombre ASC");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $tipos[] = $row;
    }
    $stmt->close();
}
?>
<form id="formFiltros" class="mb-3">
    <div class="row">
        <!-- Tipo de documento -->
        <div class="col-md-4">
            <label for="filtroTipo" class="form-label">Tipo de documento</label>
            <select id="filtroTipo" name="tipo_documento_id" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= htmlspecialchars($t['id']) ?>">
                        <?= htmlspecialchars($t['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Entidad -->
        <div class="col-md-4">
            <label for="filtroEntidad" class="form-label">Entidad</label>
            <select id="filtroEntidad" name="entidad_tipo" class="form-select">
                <option value="">Todas</option>
                <option value="vehiculo">Vehículo</option>
                <option value="conductor">Conductor</option>
                <option value="empresa">Empresa</option>
                <option value="empleado">Empleado</option>
                <option value="cliente">Cliente</option>
            </select>
        </div>

        <!-- Estado de vigencia -->
        <div class="col-md-4">
            <label for="filtroEstado" class="form-label">Estado</label>
            <select id="filtroEstado" name="estado" class="form-select">
                <option value="">Todos</option>
                <option value="vigente">Vigente</option>
                <option value="por_vencer">Por vencer</option>
                <option value="vencido">Vencido</option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col text-end">
            <button type="button" id="btnAplicarFiltros" class="btn btn-primary">
                Aplicar filtros
            </button>
            <button type="reset" id="btnResetFiltros" class="btn btn-secondary">
                Limpiar
            </button>
        </div>
    </div>
</form>