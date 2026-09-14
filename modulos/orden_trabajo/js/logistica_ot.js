// ARCHIVO: /modulos/orden_trabajo/js/logistica_ot.js
// ============================================================
// INICIALIZAR MODAL: REGISTRAR VIAJE NUEVO
// ============================================================ 

//  ABRIR MODAL: REGISTRAR VIAJE NUEVO
// ============================================================

function abrirLogistica(ot_id) {

    console.log("Registrar viaje para OT:", ot_id);

    // Guardar ID de la OT en el campo oculto
    $("#rv_orden_trabajo_id").val(ot_id);

    // Limpiar campos del modal
    $("#rv_fecha_viaje").val("");
    $("#rv_semana_viaje").val("");
    $("#rv_vehiculo").val("");
    $("#rv_conductor").val("");
    $("#rv_origen").val("");
    $("#rv_destino").val("");
    $("#rv_observaciones").val("");

    // Cargar selects
    cargarVehiculos();
    cargarOrigenes();
    cargarDestinos();

    // Mostrar modal corporativo
    $("#modalRegistrarViaje").fadeIn();
    $("#modalOverlay").fadeIn();
}


// ============================================================
// CERRAR MODAL
// ============================================================
function cerrarModalViaje() {
    $("#modalRegistrarViaje").fadeOut();
    $("#modalOverlay").fadeOut();
}
