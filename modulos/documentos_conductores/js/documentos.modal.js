// ======================================================
// archivo: /modulos/documentos_conductores/js/documentos.modal.js
// RESPONSABILIDAD: SOLO manejar modales de adjuntar y preview
// SIN lógica de historial
// ======================================================

function abrirModalAdjuntar(tipoId, descripcion, esReemplazo) {
    $('#tituloModalDoc').text((esReemplazo ? 'Reemplazar: ' : 'Adjuntar: ') + descripcion);
    $('#archivoDocumento').val('');
    $('#fechaVencimiento').val('');
    $('#modalAdjuntarDocumento').show();
}

function cerrarModalAdjuntar() {
    $('#modalAdjuntarDocumento').hide();
}

function mostrarPreview(ruta) {
    const ext = ruta.split('.').pop().toLowerCase();
    let html = '';

    if (ext === 'pdf') {
        html = '<embed src="' + ruta + '" type="application/pdf" width="100%" height="100%">';
    } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
        html = '<img src="' + ruta + '" style="max-width:100%; max-height:100%;">';
    } else {
        html = '<p class="text-center mt-5">No se puede previsualizar este tipo de archivo.</p>';
    }

    $('#previewContenido').html(html);
    $('#modalPreviewDocumento').show();
}

function cerrarPreview() {
    $('#modalPreviewDocumento').hide();
    $('#previewContenido').html('');
}


