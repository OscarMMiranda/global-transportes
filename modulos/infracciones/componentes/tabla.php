<?php
// archivo: /modulos/infracciones/componentes/tabla.php
?>

<?php /** @var array $lista */ ?>

<div class="card">
    <div class="card-body table-responsive">

        <table id="tablaInfracciones" class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th style="width: 60px;">Código</th>
                    <th style="width: 250px;">Descripción</th>
                    <th style="width: 100px;">Gravedad</th>
                    <th style="width: 100px;">Monto</th>
                    <th style="width: 80px;">Puntos</th>
                    <th style="width: 180px;">Entidad Emisora</th>
                    <th style="width: 220px; white-space: nowrap;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($lista as $row) { ?>

                    <?php
                    // Mostrar solo infracciones activas
                    if (isset($row['estado']) && $row['estado'] !== 'Activo') {
                        continue;
                    }
                    ?>

                    <tr>

                        <!-- Código -->
                        <td class="text-center">
                            <?php echo htmlspecialchars($row['codigo']); ?>
                        </td>

                        <!-- Descripción -->
                        <td>
                            <?php echo htmlspecialchars($row['descripcion']); ?>
                        </td>

                        <!-- Gravedad -->
                        <td class="text-center">
                            <?php echo htmlspecialchars($row['gravedad']); ?>
                        </td>

                        <!-- Monto Base -->
                        <td class="text-end">
                            <?php echo number_format($row['monto_base'], 2); ?>
                        </td>

                        <!-- Puntos -->
                        <td class="text-center">
                            <?php echo intval($row['puntos']); ?>
                        </td>

                        <!-- Entidad Emisora -->
                        <td>
                            <?php echo htmlspecialchars($row['entidad_nombre']); ?>
                        </td>

                        <!-- Acciones -->
                        <td class="text-center acciones-columna">

                            <!-- VER -->
                            <button class="btn btn-info btn-sm accion-btn btnVerInfraccion"
                                    data-id="<?php echo $row['id']; ?>"
                                    title="Ver infracción">
                                <i class="fa fa-eye"></i>
                            </button>

                            <!-- EDITAR -->
                            <button class="btn btn-primary btn-sm accion-btn btnEditarInfraccion"
                                    data-id="<?php echo $row['id']; ?>"
                                    title="Editar infracción">
                                <i class="fa fa-edit"></i>
                            </button>

                            <!-- ELIMINAR (solo admin) -->
                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1) { ?>
                                <button class="btn btn-danger btn-sm accion-btn btnEliminarInfraccion"
                                        data-id="<?php echo $row['id']; ?>"
                                        title="Eliminar infracción">
                                    <i class="fa fa-trash"></i>
                                </button>
                            <?php } ?>

                        </td>

                    </tr>

                <?php } ?>
            </tbody>

        </table>

    </div>
</div>
