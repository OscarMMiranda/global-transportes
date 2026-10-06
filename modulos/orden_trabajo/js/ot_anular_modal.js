// ======================================================
//  JS: ot_anular_modal.js
//  RESPONSABILIDAD: Abrir y confirmar anulación de OT
//  GLOBAL 2026 — Arquitectura Limpia (Módulo Anular OT)
// ======================================================


// ======================================================
// 1. ABRIR MODAL ANULAR OT
// ======================================================
function abrirModalAnular(id_ot) {

    console.log("OT → abrir modal ANULAR");

    // Cargar ID en el input oculto
    $('#anular_ot_id').val(id_ot);

    // Mostrar modal corporativo
    $('#modalAnularOT').modal('show');
}



// ======================================================
// 2. CONFIRMAR ANULACIÓN DE OT
// ======================================================
function confirmarAnularOT() {

    console.log("OT → confirmar anulación");

    var id_ot = $('#anular_ot_id').val();

    // Mostrar overlay corporativo
    $('#overlay-anular-ot').show();

    $.ajax({
        url: '/modulos/orden_trabajo/api/ot_anular_api.php',
        type: 'POST',
        data: { id_ot: id_ot },
        dataType: 'json',

        success: function (r) {

            if (r.ok) {

                // Recargar tabla sin perder paginación
                if (typeof tablaOT !== "undefined") {
                    tablaOT.ajax.reload(null, false);
                }

                // Cerrar modal
                $('#modalAnularOT').modal('hide');

            } else {
                alert(r.mensaje || 'Error al anular la OT');
            }
        },

        complete: function () {
            $('#overlay-anular-ot').hide();
        }
    });
}
