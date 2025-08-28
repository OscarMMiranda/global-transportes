<?php
// modulos/vehiculos/vistas/tabla_inactivos.php
// Tabla de Vehículos Inactivos

// Asegurarse de que $vehiculos_inactivos está definido
if (! isset($vehiculos_inactivos)) {
    return;
}
?>

<div class="table-responsive">
    <table id="tabla-inactivos" class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Tipo</th>
                <th>Año</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $vehiculos_inactivos->fetch_assoc()): ?>
                <tr class="table-secondary text-muted">
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['placa'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['marca'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['modelo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['tipo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['anio'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['empresa'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <form action="index.php?action=restore" method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button
                                type="submit"
                                class="btn btn-success btn-sm"
                                onclick="return confirm('¿Restaurar este vehículo?')"
                            >
                                <i class="fas fa-undo"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>