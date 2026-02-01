// ======================================================
// archivo: /modulos/documentos_conductores/js/documentos.main.js
// RESPONSABILIDAD: Inicialización general del módulo
// ======================================================

var idConductorActual = 0;
var nombreConductorActual = '';
var tipoDocumentoActual = null;
var descripcionDocumentoActual = '';

$(document).ready(function () {

    // Datos del conductor desde el DOM
    idConductorActual = $('#conductor_id').val();
    nombreConductorActual = $('#conductor_nombre').text();

    console.log('conductor_id desde DOM:', idConductorActual);
    console.log('conductor_nombre desde DOM:', nombreConductorActual);

    // Cargar documentos del conductor
    cargarDocumentosConductor(idConductorActual);

    // Guardar documento
    $('#btnGuardarDoc').on('click', guardarDocumento);

    // Adjuntar/Reemplazar documento
    $(document).on('click', '.boton-adjuntar', function () {

        tipoDocumentoActual = $(this).data('tipo');
        descripcionDocumentoActual = $(this).data('desc');

        $('#nombreConductorModal').text('Conductor: ' + nombreConductorActual);

        abrirModalAdjuntar(
            tipoDocumentoActual,
            descripcionDocumentoActual,
            $(this).hasClass('btn-warning')
        );
    });

    // Ver documento
    $(document).on('click', '.btn-ver-doc', function () {
        mostrarPreview($(this).data('ruta'));
    });

    // ======================================================
    // ÚNICO handler oficial para HISTORIAL
    // ======================================================
    $(document).on('click', '.btn-historial', function () {
        const tipo = $(this).data('tipo');
        const desc = $(this).data('desc');
        abrirHistorial(tipo, desc);
    });

});
