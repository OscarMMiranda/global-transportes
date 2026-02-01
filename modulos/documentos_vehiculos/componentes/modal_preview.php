<?php
// archivo: /modulos/documentos_vehiculos/componentes/modal_preview.php
?>

<div id="modalPreviewDocumento"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.6); z-index:11000;">

    <div style="background:#fff; width:80%; height:80%; margin:40px auto; padding:10px;
                border-radius:6px; position:relative; box-shadow:0 0 15px rgba(0,0,0,0.3);
                z-index:11001;">

        <button onclick="cerrarPreview()"
                style="position:absolute; top:10px; right:10px; z-index:11002;"
                class="btn btn-danger btn-sm">Cerrar</button>

        <div id="previewContenido" style="width:100%; height:100%; overflow:auto;"></div>

    </div>
</div>

