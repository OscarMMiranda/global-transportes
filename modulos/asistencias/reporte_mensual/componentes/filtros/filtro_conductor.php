<?php
    // archivo  : /modulos/asistencias/reporte_mensual/componentes/filtros/filtro_conductor.php
    // Filtro para seleccionar el conductor en el reporte mensual de asistencias
?>

<div class="col-md-3">
    <label for="filtro_conductor">Conductor</label>
    <select id="filtro_conductor" class="form-control">
        <option value="">Todos</option>
        <?php
        $cn = $GLOBALS['db'];

        $sql = "SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre 
                FROM conductores 
                WHERE activo = 1 
                ORDER BY apellidos ASC, nombres ASC";

        $rs = $cn->query($sql);

        if (!$rs) {
            die("<pre>ERROR SQL: " . $cn->error . "</pre>");
        }

        while ($r = $rs->fetch_assoc()) {
            echo "<option value='{$r['id']}'>{$r['nombre']}</option>";
        }
        ?>
    </select>
</div>
