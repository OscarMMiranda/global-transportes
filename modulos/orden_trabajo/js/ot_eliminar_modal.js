// ======================================================
//  JS: ot_eliminar_modal.js
//  RESPONSABILIDAD: Abrir y confirmar eliminación de OT
//  GLOBAL 2026 — Arquitectura Limpia (Módulo Eliminar OT)
// ======================================================


// ======================================================
// 1. ABRIR MODAL ELIMINAR OT
// ======================================================
function abrirModalEliminar(id_ot) {

    console.log("OT → abrir modal ELIMINAR");

    // Cargar ID en el input oculto
    $('#eliminar_ot_id').val(id_ot);

    // Mostrar modal corporativo
    $('#modalEliminarOT').modal('show');
}



// ======================================================
// 2. CONFIRMAR ELIMINACIÓN DE OT
// ======================================================
function confirmarEliminarOT() {

    console.log("OT → confirmar eliminación");

    var id_ot = $('#eliminar_ot_id').val();

    // Mostrar overlay corporativo
    $('#overlay-eliminar-ot').show();

    $.ajax({
        url: '/modulos/orden_trabajo/api/ot_eliminar_api.php',
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
                $('#modalEliminarOT').modal('hide');

            } else {
                alert(r.mensaje || 'Error al eliminar la OT');
            }
        },

        complete: function () {
            $('#overlay-eliminar-ot').hide();
        }
    });
}
