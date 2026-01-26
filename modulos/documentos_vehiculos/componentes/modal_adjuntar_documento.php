<?php
// archivo: /modulos/documentos_vehiculos/componentes/modal_adjuntar_documento.php
?>

<div id="modalAdjuntarDocumento"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); z-index:9999;">

    <div style="background:#fff; width:450px; margin:80px auto; padding:20px; border-radius:6px;
                box-shadow:0 4px 12px rgba(0,0,0,0.25);">

        <h5 id="tituloModalDoc" class="mb-3">Adjuntar documento</h5>

        <form id="formAdjuntarDocumento" enctype="multipart/form-data">

            <div class="form-group mb-3">
                <label for="archivoDocumento" class="fw-bold">Archivo</label>
                <input type="file" id="archivoDocumento" name="archivo" class="form-control"
                       accept=".pdf,.jpg,.jpeg,.png,.webp">
            </div>

            <div class="form-group mb-3">
                <label for="fechaVencimiento" class="fw-bold">Fecha de vencimiento</label>
                <input type="date" id="fechaVencimiento" name="fecha_vencimiento" class="form-control">
            </div>

            <div class="text-end mt-4">
                <button type="button" id="btnGuardarDoc" class="btn btn-primary px-4">Guardar</button>
                <button type="button" class="btn btn-secondary px-4" onclick="cerrarModalAdjuntar()">Cancelar</button>
            </div>

        </form>

    </div>
</div>
