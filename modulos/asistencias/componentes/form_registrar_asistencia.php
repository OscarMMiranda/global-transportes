<?php
    // archivo: /modulos/asistencias/componentes/form_registrar_asistencia.php
?>



<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Empresa:</label>
            <select id="empresa_id" class="form-select">
                <option value="">Seleccione...</option>
                <?php
        $q = mysqli_query($conn, "SELECT id, razon_social FROM empresa ORDER BY razon_social");
while ($r = mysqli_fetch_assoc($q)) {
    echo '<option value="'.$r['id'].'">'.$r['razon_social'].'</option>';
}

                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Conductor:</label>
            <select id="conductor_id" class="form-select">
                <option value="">Seleccione ...</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Fecha:</label>
            <input type="date" id="fecha" class="form-control">
        </div>

        <div class="col-md-4">
            <label class="form-label">Tipo:</label>
            <select id="codigo_tipo" class="form-select">
                <option value="">Seleccione...</option>
                <?php
                $q = mysqli_query($conn, "SELECT codigo, descripcion FROM asistencia_tipos ORDER BY descripcion");
                while ($r = mysqli_fetch_assoc($q)) {
                    echo '<option value="'.$r['codigo'].'">'.$r['descripcion'].'</option>';
                }
                ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Observación:</label>
            <input type="text" id="observacion" class="form-control" placeholder="Opcional">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Entrada:</label>
            <input type="time" id="hora_entrada" class="form-control">
        </div>

        <div class="col-md-6">
            <label class="form-label">Salida:</label>
            <input type="time" id="hora_salida" class="form-control">
        </div>
    </div>

</div>
