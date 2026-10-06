// ======================================================
//  JS: ot_importar_modal.js
//  RESPONSABILIDAD: Abrir y procesar importación de OT
//  GLOBAL 2026 — Arquitectura Limpia (Módulo Importar OT)
// ======================================================


// ======================================================
// 1. ABRIR MODAL IMPORTAR OT
// ======================================================
function abrirModalImportarOT() {

    console.log("OT → abrir modal IMPORTAR");

    // Limpiar formulario
    if ($('#formImportarOT').length) {
        $('#formImportarOT')[0].reset();
    }

    // Ocultar resultado previo
    $('#resultadoImportacionOT').hide();
    $('#resultadoImportacionOTDetalle').html('');

    // Mostrar modal corporativo
    $('#modalImportarOT').modal('show');
}



// ======================================================
// 2. PROCESAR IMPORTACIÓN DE OT
// ======================================================
function procesarImportacionOT() {

    console.log("OT → procesar importación");

    var formData = new FormData($('#formImportarOT')[0]);

    // Mostrar overlay corporativo
    $('#overlay-importar-ot').show();

    $.ajax({
        url: '/modulos/orden_trabajo/api/ot_importar_api.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',

        success: function (r) {

            // Mostrar resultado
            $('#resultadoImportacionOT').show();
            $('#resultadoImportacionOTDetalle').html(r.mensaje);

            // Recargar tabla si todo salió bien
            if (r.ok && typeof tablaOT !== "undefined") {
                tablaOT.ajax.reload(null, false);
            }
        },

        complete: function () {
            $('#overlay-importar-ot').hide();
        }
    });
}
