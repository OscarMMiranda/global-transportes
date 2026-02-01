<?php
// archivo: /modulos/documentos_conductores/componentes/modal_preview.php
?>

<div id="modalPreviewDocumento"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); z-index:10000;">

    <div style="background:#fff; width:80%; height:80%; margin:40px auto; padding:10px;
                border-radius:6px; position:relative; box-shadow:0 4px 12px rgba(0,0,0,0.25);">

        <button type="button"
                onclick="cerrarPreview()"
                style="position:absolute; top:10px; right:10px;"
                class="btn btn-sm btn-secondary">
            Cerrar
        </button>

        <div id="previewContenido" style="width:100%; height:100%; overflow:auto;"></div>

    </div>
</div>


<style>
#modalPreviewDocumento {
    z-index: 999999 !important;
}

#modalPreviewDocumento > div {
    height: 90vh !important;
}

#previewContenido {
    height: 100% !important;
}

#previewContenido embed,
#previewContenido img {
    height: 100% !important;
    width: 100% !important;
}
</style>

