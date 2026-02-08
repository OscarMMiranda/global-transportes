<?php
// archivo: /modulos/asistencias/componentes/tabla_asistencias.php

// Este archivo espera que $asistencias venga desde index.php
// Ejemplo:
// $asistencias = obtener_asistencias($conn);
?>

<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fa-solid fa-list"></i> Lista de Asistencias
        </h5>
    </div>

    <div class="card-body">

        <?php if (empty($asistencias)): ?>
            <div class="alert alert-info">
                No hay asistencias registradas.
            </div>
        <?php else: ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="tablaAsistencias">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Conductor</th>
                        <th>Tipo</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($asistencias as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['fecha']) ?></td>
                        <td><?= htmlspecialchars($a['conductor']) ?></td>
                        <td><?= htmlspecialchars($a['tipo']) ?></td>
                        <td><?= htmlspecialchars($a['hora_entrada']) ?></td>
                        <td><?= htmlspecialchars($a['hora_salida']) ?></td>

                        <td>
                            <button class="btn btn-warning btn-sm btnEditarAsistencia"
                                    data-id="<?= $a['id'] ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

        <?php endif; ?>

    </div>
</div>
