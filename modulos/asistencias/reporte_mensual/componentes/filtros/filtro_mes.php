<?php
    // archivo  : /modulos/asistencias/reporte_mensual/componentes/filtros/filtro_mes.php
    // Filtro para seleccionar el mes en el reporte mensual de asistencias
?>

<div class="col-md-2">
    <label for="filtro_mes">Mes</label>
    <select id="filtro_mes" class="form-control">
        <option value="">Seleccione</option>
        <?php
        $meses = array(
            1  => 'Enero',
            2  => 'Febrero',
            3  => 'Marzo',
            4  => 'Abril',
            5  => 'Mayo',
            6  => 'Junio',
            7  => 'Julio',
            8  => 'Agosto',
            9  => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        );

        foreach ($meses as $num => $nombre) {
            echo "<option value='$num'>$nombre</option>";
        }
        ?>
    </select>
</div>
