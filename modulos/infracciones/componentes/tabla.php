<?php
// archivo: /modulos/infracciones/componentes/tabla.php
?>

<?php
/** @var array $lista */
?>

<!-- BOTÓN CREAR -->
<button id="btnNuevoInfraccion" class="btn btn-success mb-3">
    <i class="fa fa-plus"></i> Nueva Infracción
</button>

<!-- TABLA DE INFRACCIONES -->
<div class="card">
    <div class="card-body table-responsive">

        <table id="tablaInfracciones" class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">Código</th>
                    <th style="width: 150px;">Descripción</th>
                    <th style="width: 80px;">Gravedad</th>
                    <th style="width: 80px;">Monto</th>
                    <th style="width: 70px;">Puntos</th>
                    <th style="width: 150px;">Entidad Emisora</th>

                    <!-- ANCHO REAL DE ACCIONES -->
                    <th style="width: 200px; white-space: nowrap;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($lista as $row){ ?>

                    <?php if (isset($row['estado']) && $row['estado'] !== 'Activo') continue; ?>

                    <tr>

                        <td class="text-center">
                            <?php echo $row['codigo']; ?>
                        </td>

                        <td>
                            <?php echo $row['descripcion']; ?>
                        </td>

                        <td class="text-center">
                            <?php echo $row['gravedad']; ?>
                        </td>

                        <td class="text-end">
                            <?php echo number_format($row['monto_base'], 2); ?>
                        </td>

                        <td class="text-center">
                            <?php echo $row['puntos']; ?>
                        </td>

                        <td>
                            <?php echo $row['entidad_nombre']; ?>
                        </td>

                        <!-- COLUMNA DE ACCIONES -->
                        <td class="text-center acciones-columna">

                            <button class="btn btn-info btn-sm accion-btn"
                                    onclick="verInfraccion('<?php echo $row['id']; ?>')">
                                <i class="fa fa-eye"></i>
                            </button>

                            <button class="btn btn-primary btn-sm accion-btn"
                                    onclick="editarInfraccion('<?php echo $row['id']; ?>')">
                                <i class="fa fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-sm accion-btn"
                                    onclick="eliminarInfraccion('<?php echo $row['id']; ?>')">
                                <i class="fa fa-trash"></i>
                            </button>

                        </td>

                    </tr>

                <?php } ?>
            </tbody>

        </table>

    </div>
</div>
