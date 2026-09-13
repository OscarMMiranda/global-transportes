<!--  Archivo: /modulos/orden_trabajo/views/editar.php -->

<?php
/** @var array $data */
/** @var array $clientes */
/** @var array $empresas */
/** @var array $tipos_ot */
/** @var array $estados */
?>

<div class="row">

    <div class="col-md-4 mb-3">
        <label>Número OT</label>
        <input type="text" name="numero_ot" class="form-control" value="<?php echo $data['numero_ot']; ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label>Fecha</label>
        <input type="date" name="fecha" class="form-control" value="<?php echo $data['fecha']; ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label>Semana</label>
        <input type="number" name="semana_ot" class="form-control" value="<?php echo $data['semana_ot']; ?>">
    </div>

    <div class="col-md-6 mb-3">
        <label>Cliente</label>
        <select name="cliente_id" class="form-control">
            <?php foreach ($clientes as $c) { ?>
                <option value="<?php echo $c['id']; ?>" <?php echo ($c['id'] == $data['cliente_id']) ? 'selected' : ''; ?>>
                    <?php echo $c['nombre']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Empresa</label>
        <select name="empresa_id" class="form-control">
            <?php foreach ($empresas as $e) { ?>
                <option value="<?php echo $e['id']; ?>" <?php echo ($e['id'] == $data['empresa_id']) ? 'selected' : ''; ?>>
                    <?php echo $e['razon_social']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Tipo de Orden</label>
        <select name="tipo_ot_id" id="editar_tipo_ot" class="form-control">
            <?php foreach ($tipos_ot as $t) { ?>
                <option value="<?php echo $t['id']; ?>" <?php echo ($t['id'] == $data['tipo_ot_id']) ? 'selected' : ''; ?>>
                    <?php echo $t['nombre']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Estado</label>
        <select name="estado_id" class="form-control">
    <?php foreach ($estados as $es) { ?>
        <option value="<?php echo $es['id']; ?>"
			<?php echo ($es['id'] == $data['estado_ot']) ? 'selected' : ''; ?>

            >
            <?php echo $es['nombre']; ?>
        </option>
    <?php } ?>
</select>

    </div>

    <!-- CAMPOS DINÁMICOS -->
    <div id="campo_importacion" class="col-md-6 mb-3" style="display:none;">
        <label>Número DAM / DUA</label>
        <input type="text" name="numero_dam" class="form-control" value="<?php echo $data['numero_dam']; ?>">
    </div>

    <div id="campo_exportacion" class="col-md-6 mb-3" style="display:none;">
        <label>Número Booking</label>
        <input type="text" name="numero_booking" class="form-control" value="<?php echo $data['numero_booking']; ?>">
    </div>

    <div id="campo_nacional" class="col-md-6 mb-3" style="display:none;">
        <label>Otros</label>
        <input type="text" name="otros" class="form-control" value="<?php echo $data['otros']; ?>">
    </div>

</div>

<script>
    function mostrarCampos(tipo) {
        document.getElementById('campo_importacion').style.display = (tipo == 'IMPORTACION' || tipo == 'IMPORTACIÓN') ? 'block' : 'none';
        document.getElementById('campo_exportacion').style.display = (tipo == 'EXPORTACION' || tipo == 'EXPORTACIÓN') ? 'block' : 'none';
        document.getElementById('campo_nacional').style.display = (tipo == 'NACIONAL') ? 'block' : 'none';
    }

    mostrarCampos('<?php echo $data['tipo_ot_nombre']; ?>');

    document.getElementById('editar_tipo_ot').addEventListener('change', function () {
        mostrarCampos(this.options[this.selectedIndex].text);
    });
</script>
