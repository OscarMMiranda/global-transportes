<!-- ARCHIVO: modulos/orden_trabajo/views/ver.php -->

<?php
/** @var array $data */
?>

<table class="table table-bordered">

    <tr>
        <th>Número OT</th>
        <td><?php echo $data['numero_ot']; ?></td>
    </tr>

    <tr>
        <th>Fecha</th>
        <td><?php echo $data['fecha']; ?></td>
    </tr>

    <tr>
        <th>Semana</th>
        <td><?php echo $data['semana_formateada']; ?></td>
    </tr>

    <tr>
        <th>Cliente</th>
        <td><?php echo $data['cliente_nombre']; ?></td>
    </tr>

    <tr>
        <th>Empresa</th>
        <td><?php echo $data['empresa_nombre']; ?></td>
    </tr>

    <tr>
        <th>Tipo de Orden</th>
        <td><?php echo $data['tipo_ot_nombre']; ?></td>
    </tr>

    <tr>
        <th>Estado</th>
        <td><?php echo $data['estado_nombre']; ?></td>
    </tr>

    <tr>
        <th>OC Cliente</th>
        <td><?php echo $data['oc_cliente']; ?></td>
    </tr>

    <!-- CAMPOS SEGÚN TIPO DE ORDEN -->
    <?php if ($data['tipo_ot_nombre'] === 'IMPORTACION' || $data['tipo_ot_nombre'] === 'IMPORTACIÓN'): ?>
        <tr>
            <th>Número DAM / DUA</th>
            <td><?php echo $data['numero_dam']; ?></td>
        </tr>
    <?php endif; ?>

    <?php if ($data['tipo_ot_nombre'] === 'EXPORTACION' || $data['tipo_ot_nombre'] === 'EXPORTACIÓN'): ?>
        <tr>
            <th>Número Booking</th>
            <td><?php echo $data['numero_booking']; ?></td>
        </tr>
    <?php endif; ?>

    <?php if ($data['tipo_ot_nombre'] === 'NACIONAL'): ?>
        <tr>
            <th>Otros</th>
            <td><?php echo $data['otros']; ?></td>
        </tr>
    <?php endif; ?>

</table>

