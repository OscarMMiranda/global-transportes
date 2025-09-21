<?php if (!empty($tiposInactivos)): ?>
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr class="active">
                <th style="width: 60px;">ID</th>
                <th>Nombre</th>
                <th style="width: 160px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tiposInactivos as $tipo): ?>
                <tr class="text-muted">
                    <td><?= $tipo['id'] ?></td>
                    <td><?= ucfirst($tipo['nombre']) ?></td>
                    <td>
                        <a href="controller.php?reactivar=<?= $tipo['id'] ?>" class="btn btn-info btn-xs" onclick="return confirm('¿Reactivar este tipo?')">
                            <i class="fa fa-refresh"></i> Restaurar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-muted" style="margin-top: 10px;">
        Total: <?= count($tiposInactivos) ?> tipos inactivos
    </div>
<?php else: ?>
    <div class="alert alert-info">No hay tipos inactivos.</div>
<?php endif; ?>