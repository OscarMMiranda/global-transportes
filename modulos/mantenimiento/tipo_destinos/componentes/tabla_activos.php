<?php
// archivo : /modulos/mantenimiento/tipo_destinos/componentes/tabla_activos.php
// Componente: Tabla de tipos de destino activos
?>

<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr class="active">
            <th style="width: 60px;">ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th style="width: 160px;">Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($tiposActivos as $tipo): ?>
            <tr data-id="<?= $tipo['id'] ?>"
                data-descripcion="<?= htmlspecialchars($tipo['descripcion']) ?>">

                <td><?= $tipo['id'] ?></td>

                <td class="nombre">
                    <?= htmlspecialchars(ucfirst($tipo['nombre'])) ?>
                </td>

                <td>
                    <span title="<?= htmlspecialchars($tipo['descripcion']) ?>">
                        <?= htmlspecialchars($tipo['descripcion']) ?>
                    </span>
                </td>

                <td>
                    <button class="btn btn-warning btn-xs" onclick="editarTipo(<?= $tipo['id'] ?>)">
                        <i class="fa fa-pencil"></i> Editar
                    </button>

                    <button class="btn btn-danger btn-xs" onclick="eliminarTipo(<?= $tipo['id'] ?>)">
                        <i class="fa fa-trash"></i> Eliminar
                    </button>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
