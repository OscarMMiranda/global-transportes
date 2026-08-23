<?php
/**
 * archivo: /modulos/orden_trabajo/views/list.php
 * 
 * @var array $semanas
 * @var string $semana_sel
 */
?>

<div class="container mt-4">

    <h3>Órdenes de Trabajo</h3>
    <p>Gestión, control y seguimiento de órdenes por semana</p>

    <div class="row mb-3">
        <div class="col-md-3">
            <label>Semana</label>
            <select id="filtro_semana" class="form-control">
                <option value="">-- Todas --</option>
                <?php foreach ($semanas as $s): ?>
                    <option value="<?= $s['semana']; ?>"
                        <?= ($semana_sel == $s['semana']) ? 'selected' : ''; ?>>
                        <?= $s['semana']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-9 text-end">
            <button class="btn btn-success">Crear</button>
            <button class="btn btn-warning">Anular</button>
            <button class="btn btn-danger">Eliminar</button>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <button class="nav-link btn-estado active" data-estado="ACTIVA">Activas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link btn-estado" data-estado="ANULADA">Anuladas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link btn-estado" data-estado="ELIMINADA">Eliminadas</button>
        </li>
    </ul>

    <table id="tablaOT" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Número OT</th>
                <th>Fecha</th>
                <th>Semana</th>
                <th>Cliente</th>
                <th>Vehículo</th>
                <th>Tipo OT</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>

</div>

<script src="js/listado.js"></script>
