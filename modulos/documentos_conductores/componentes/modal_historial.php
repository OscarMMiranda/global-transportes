<!-- ======================================================
    archivo: /modulos/documentos_conductores/componentes/modal_historial.php

     MODAL HISTORIAL — VERSIÓN FINAL
====================================================== -->



<div id="modalHistorial"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.45); z-index:9999;">

    <div style="background:#fff; width:700px; margin:60px auto; padding:20px;
                border-radius:6px; box-shadow:0 4px 12px rgba(0,0,0,0.25);">

        <h5 id="tituloHistorialDoc" class="mb-3">Historial del documento</h5>

        <div id="modalHistorialContenido">
            <p class="text-muted">Cargando…</p>
        </div>

        <div class="text-end mt-3">
            <button class="btn btn-secondary" onclick="cerrarHistorial()">Cerrar</button>
        </div>

    </div>
</div>
