// ======================================================
//  ARCHIVO: ot_form_actions.js
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Acciones del formulario Nueva OT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 1.0)
// ======================================================

$(document).ready(function () {

    // ======================================================
    //  EVENTO: Guardar Nueva OT
    // ======================================================
    $(document).on('click', '#btnGuardarNuevaOT', function () {

        console.log("[OT] → Guardar Nueva OT");

        // Validación corporativa
        var form = $('#formNuevaOT');

        if (form.length === 0) {
            console.error("[OT] ERROR: El formulario no existe en el DOM");
            alert("Error interno: formulario no encontrado.");
            return;
        }

        // Serializar datos
        var datos = form.serialize();

        // Overlay corporativo
        $('#overlay-nueva-ot').removeClass('d-none');

        $.post('/modulos/orden_trabajo/api/ot_guardar_api.php', datos, function (resp) {

            $('#overlay-nueva-ot').addClass('d-none');

            if (!resp.ok) {
                console.warn("[OT] Error al guardar:", resp.msg);
                mostrarErrorNuevaOT(resp.msg);
                return;
            }

            // Éxito corporativo
            console.log("[OT] → OT creada correctamente");

            // Cerrar modal
            $('#modal-nueva-ot').modal('hide');

            // Recargar tabla corporativa
            if (typeof cargarTablaOT === "function") {
                cargarTablaOT();
            }

            // Notificación corporativa
            mostrarToastCorp("Orden de Trabajo creada correctamente");

        }, 'json');

    });

});


// ======================================================
//  FUNCIÓN: Mostrar error corporativo en el modal
// ======================================================
function mostrarErrorNuevaOT(msg) {
    var alerta = $('#alertaNuevaOT');

    if (alerta.length === 0) {
        console.error("[OT] ERROR: No existe #alertaNuevaOT en el modal");
        alert(msg);
        return;
    }

    alerta.removeClass('d-none').text(msg);
}
