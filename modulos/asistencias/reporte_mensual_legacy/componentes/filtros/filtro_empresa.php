<?php
    // archivo  : /modulos/asistencias/reporte_mensual/componentes/filtros/filtro_empresa.php
    // Filtro de selección de empresa para el reporte mensual de asistencias
?>

<div class="col-md-3">
    <label>Empresa</label>
    <select id="filtro_empresa" class="form-control">
        <option value="">Todas las empresas</option>

        <?php foreach ($empresas as $e): ?>
            <option value="<?= $e['id'] ?>"><?= $e['razon_social'] ?></option>
        <?php endforeach; ?>
    </select>
</div>
