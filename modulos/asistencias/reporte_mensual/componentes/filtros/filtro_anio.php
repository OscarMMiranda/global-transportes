<?php
    // archivo  : /modulos/asistencias/reporte_mensual/componentes/filtros/filtro_anio.php
    // Filtro para seleccionar el año en el reporte mensual de asistencias
?>

<div class="col-md-2">
    <label for="filtro_anio">Año</label>
    <select id="filtro_anio" class="form-control">
        <option value="">Seleccione</option>
        <?php
        $anioActual = date("Y");
        for ($i = $anioActual; $i >= $anioActual - 5; $i--) {
            echo "<option value='$i'>$i</option>";
        }
        ?>
    </select>
</div>
