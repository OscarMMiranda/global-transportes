<?php
    // archivo: /modulos/asistencias/componentes/formulario_registro.php
?>

<div class="form-bloque">

    <!-- EMPRESA -->
    <label>Empresa:</label>
    <select id="empresa_id">
        <option value="">Seleccione...</option>
        <?php
        $q = mysqli_query($conexion, "SELECT id, nombre FROM empresa ORDER BY nombre");
        while ($r = mysqli_fetch_assoc($q)) {
            echo '<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
        }
        ?>
    </select>

    <!-- CONDUCTOR -->
    <label>Conductor:</label>
    <select id="conductor_id">
        <option value="">Seleccione empresa...</option>
    </select>

    <!-- FECHA -->
    <label>Fecha:</label>
    <input type="date" id="fecha">

    <!-- TIPO DE ASISTENCIA -->
    <label>Tipo:</label>
    <select id="codigo_tipo">
        <option value="">Seleccione...</option>
        <?php
        $q = mysqli_query($conexion, "SELECT codigo, descripcion FROM asistencia_tipos ORDER BY descripcion");
        while ($r = mysqli_fetch_assoc($q)) {
            echo '<option value="'.$r['codigo'].'">'.$r['descripcion'].'</option>';
        }
        ?>
    </select>

    <!-- HORA ENTRADA -->
    <label>Entrada:</label>
    <input type="time" id="hora_entrada">

    <!-- HORA SALIDA -->
    <label>Salida:</label>
    <input type="time" id="hora_salida">

    <!-- OBSERVACIÓN -->
    <label>Observación:</label>
    <input type="text" id="observacion" placeholder="Opcional">

    <!-- BOTONES -->
    <button id="btnRegistrar">Registrar asistencia</button>
    <button id="btnVacaciones" class="btn-secundario">Registrar vacaciones</button>

</div>


