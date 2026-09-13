// archivo: registrar_viaje.js
// ============================================================
// LÓGICA COMPLETA DEL MODAL REGISTRAR VIAJE
// ============================================================

function abrirRegistrarViaje(ot_id) {

    $("#rv_orden_trabajo_id").val(ot_id);

    $.post("/modulos/orden_trabajo/controllers/GetDatosOTController.php",
        { orden_trabajo_id: ot_id },
        function (r) {

            if (!r || !r.ok) {
                alert("No se pudo obtener datos de la OT.");
                return;
            }

            $("#rv_fecha_viaje").val(r.fecha_ot);

            var numeroViaje = parseInt(r.cantidad_viajes, 10) + 1;
            var numeroViajeFormateado = numeroViaje.toString().padStart(2, "0");

            $("#tituloRegistrarViaje").text("Registrar Viaje — Viaje N° " + numeroViajeFormateado);

            var fecha = new Date(r.fecha_ot);
            var year = fecha.getFullYear();
            var semanaISO = parseInt(r.semana_ot, 10);

            $("#rv_semana_viaje").val("S" + semanaISO.toString().padStart(2, "0") + "-" + year);

            $("#rv_vehiculo").empty();
            $("#rv_conductor").val("");
            $("#rv_origen").empty();
            $("#rv_destino").empty();
            $("#rv_observaciones").val("");

            // Cargar ubicaciones
            $.post("/modulos/orden_trabajo/controllers/GetUbicacionesController.php", {},
                function (u) {

                    $("#rv_origen").append('<option value="">Seleccione...</option>');
                    $("#rv_destino").append('<option value="">Seleccione...</option>');

                    if (u.ok) {
                        u.data.forEach(function(item){
                            var texto = item.nombre + " (" + item.tipo + ")";
                            $("#rv_origen").append('<option value="' + item.id + '">' + texto + '</option>');
                            $("#rv_destino").append('<option value="' + item.id + '">' + texto + '</option>');
                        });
                    }

                    $("#modalRegistrarViaje").addClass("modal-visible");
                    $("#modalOverlay").addClass("modal-visible");
                },
            "json");

            // Cargar vehículos
            $.post("/modulos/orden_trabajo/controllers/GetVehiculosController.php",
                { fecha_viaje: $("#rv_fecha_viaje").val() },
                function (v) {

                    $("#rv_vehiculo").append('<option value="">Seleccione...</option>');

                    if (v.ok) {
                        v.data.forEach(function(item){
                            $("#rv_vehiculo").append(
                                '<option value="' + item.id + '">' + item.placa + '</option>'
                            );
                        });
                    }
                },
            "json");
        },
    "json");
}

// CAMBIO DE VEHÍCULO → CARGAR CONDUCTOR
$("#rv_vehiculo").on("change", function () {

    var vehiculo_id = $(this).val();
    var fecha_viaje = $("#rv_fecha_viaje").val();

    if (!vehiculo_id) {
        $("#rv_conductor").val("");
        return;
    }

    $.post("/modulos/orden_trabajo/controllers/GetConductorPorVehiculo.php",
        { vehiculo_id: vehiculo_id, fecha_viaje: fecha_viaje },
        function (c) {

            if (!c || !c.ok) {
                $("#rv_conductor").val("Sin conductor asignado");
                return;
            }

            $("#rv_conductor").val(c.nombre);
        },
    "json");
});

// GUARDAR VIAJE
$("#btnGuardarViaje").on("click", function () {

    var titulo = $("#tituloRegistrarViaje").text();
    var numeroViaje = titulo.split("N° ")[1];

    var data = {
        orden_trabajo_id: $("#rv_orden_trabajo_id").val(),
        vehiculo_id: $("#rv_vehiculo").val(),
        fecha_viaje: $("#rv_fecha_viaje").val(),
        semana_viaje: $("#rv_semana_viaje").val(),
        numero_viaje: numeroViaje,
        origen: $("#rv_origen").val(),
        destino: $("#rv_destino").val(),
        observaciones: $("#rv_observaciones").val()
    };

    if (!data.vehiculo_id) { alert("Debe seleccionar un vehículo."); return; }

    $.post("/modulos/orden_trabajo/controllers/RegistrarViajeController.php",
        data,
        function (r) {

            if (!r || !r.ok) {
                alert(r.msg || "Error al registrar viaje");
                return;
            }

            alert("Viaje registrado correctamente");

            cerrarModalViaje();

            if (typeof cargarViajesOT === "function") {
                cargarViajesOT(data.orden_trabajo_id);
            }
        },
    "json");
});

function cerrarModalViaje() {
    $("#modalRegistrarViaje").removeClass("modal-visible");
    $("#modalOverlay").removeClass("modal-visible");
}
