<?php
// archivo: /modulos/vehiculos/vistas/detalle_footer.php

// Compatibilidad PHP 5.6: reemplazar ?? por isset()
$fecha_creacion      = isset($vehiculo['fecha_creacion']) ? $vehiculo['fecha_creacion'] : '—';
$fecha_actualizacion = isset($vehiculo['fecha_actualizacion']) ? $vehiculo['fecha_actualizacion'] : '—';
$usuario_modifico    = isset($vehiculo['usuario_modifico']) ? $vehiculo['usuario_modifico'] : '—';
$estado              = isset($vehiculo['estado']) ? $vehiculo['estado'] : 'SIN ESTADO';

// Clase del badge según estado
if ($estado === 'ACTIVO') {
    $claseEstado = 'bg-success';
} else {
    $claseEstado = 'bg-secondary';
}
?>

<div class="card shadow-sm border-0 mt-4 detalle-footer">
    <div class="card-body d-flex justify-content-between align-items-center">

        <!-- Información de auditoría -->
        <div class="footer-meta">
            <div class="text-muted small">
                <i class="fa-solid fa-calendar-plus me-2"></i>
                Creado: <strong><?= $fecha_creacion ?></strong>
            </div>

            <div class="text-muted small">
                <i class="fa-solid fa-calendar-check me-2"></i>
                Actualizado: <strong><?= $fecha_actualizacion ?></strong>
            </div>

            <div class="text-muted small">
                <i class="fa-solid fa-user-pen me-2"></i>
                Modificado por: <strong><?= $usuario_modifico ?></strong>
            </div>
        </div>

        <!-- Estado visual -->
        <div class="footer-status text-end">
            <span class="badge px-3 py-2 <?= $claseEstado ?>">
                <?= $estado ?>
            </span>
        </div>

    </div>
</div>