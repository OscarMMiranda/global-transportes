// ======================================================
//  JS: ot_modals_viajes.js
//  RESPONSABILIDAD: Modal REGISTRAR VIAJE
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

function abrirRegistrarViaje(id) {

    if (!id) return;

    $("#modalRegistrarViaje").modal("show");
    loaderOn("loaderRegistrarViaje");
    limpiarMensajes("#msgRegistrarViaje");

    $.ajax({
        url: "/modulos/orden_trabajo/api/ot_viaje_form_api.php",
        type: "POST",
        data: { id: id },
        dataType: "json",

        success: function (r) {

            loaderOff("loaderRegistrarViaje");

            if (!r.ok) {
                mostrarError(r.msg, "#msgRegistrarViaje");
                return;
            }

            var d = r.data;

            $("#viaje_ot_id").val(d.id);
            $("#viaje_numero_ot").text(d.numero_ot);

            cargarSelect("#viaje_tipo", r.tipos_viaje, "");
            cargarSelect("#viaje_vehiculo", r.vehiculos, "");
            cargarSelect("#viaje_conductor", r.conductores, "");

            mostrarOk("Formulario cargado correctamente", "#msgRegistrarViaje");
        },

        error: function () {
            loaderOff("loaderRegistrarViaje");
            mostrarError("Error de comunicación con el servidor", "#msgRegistrarViaje");
        }
    });
}


function guardarViaje() {

    limpiarMensajes("#msgRegistrarViaje");
    loaderOn("loaderRegistrarViaje");

    var formData = {
        ot_id: $("#viaje_ot_id").val(),
        tipo: $("#viaje_tipo").val(),
        vehiculo: $("#viaje_vehiculo").val(),
        conductor: $("#viaje_conductor").val()
    };

    $.ajax({
        url: "/modulos/orden_trabajo/api/ot_viaje_guardar_api.php",
        type: "POST",
        data: formData,
        dataType: "json",

        success: function (r) {

            loaderOff("loaderRegistrarViaje");

            if (!r.ok) {
                mostrarError(r.msg, "#msgRegistrarViaje");
                return;
            }

            mostrarOk("Viaje registrado correctamente", "#msgRegistrarViaje");

            if (typeof tablaOT !== "undefined" && tablaOT !== null) {
                tablaOT.ajax.reload(null, false);
            }
        },

        error: function () {
            loaderOff("loaderRegistrarViaje");
            mostrarError("Error de comunicación con el servidor", "#msgRegistrarViaje");
        }
    });
}
