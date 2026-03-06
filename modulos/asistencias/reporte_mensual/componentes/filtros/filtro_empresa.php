<?php
    // archivo  : /modulos/asistencias/reporte_mensual/componentes/filtros/filtro_empresa.php
    // Filtro de selección de empresa para el reporte mensual de asistencias

$empresa_id_raw = isset($_POST['empresa_id']) ? $_POST['empresa_id'] : "";

if ($empresa_id_raw === "" || $empresa_id_raw === "0" || $empresa_id_raw === "ALL") {
    $empresa_id = 0; // 0 = todas las empresas
} else {
    $empresa_id = (int)$empresa_id_raw;
}
?>

<div class="col-md-3">
    <div class="form-group filtro-grupo">
        <label for="filtro_empresa" class="control-label">
            <i class="fa fa-building"></i> Empresa
        </label>

        <select id="filtro_empresa"
                name="filtro_empresa"
                class="form-control filtro-select">

            <option value="">Todas las empresas</option>

            <?php foreach ($empresas as $e): ?>
                <option value="<?php echo htmlspecialchars($e['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($e['razon_social'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>

        </select>
    </div>
</div>