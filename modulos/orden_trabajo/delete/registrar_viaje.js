// ============================================================
// ARCHIVO: registrar_viaje.js
// RESPONSABILIDAD: Modal Registrar Viaje (Corporativo 2026)
// ============================================================

console.log("registrar_viaje.js cargado");


(function ($) {
    "use strict";

    // ============================================================
    // ABRIR MODAL REGISTRAR VIAJE
    // ============================================================
    window.abrirRegistrarViaje = function (ot_id) {

        console.log("abrirRegistrarViaje -> OT:", ot_id);

        $("#registrar_viaje_ot_id").val(ot_id);

        $.post(
            "/modulos/orden_trabajo/controllers/GetDatosOTController.php",
            { orden_trabajo_id: ot_id },
            function (r) {

                if (!r || !r.ok) {
                    Swal.fire("Error", "No se pudo obtener datos de la OT.", "error");
                    return;
                }

                // Fecha de la OT
                $("#rv_fecha_viaje").val(r.fecha_ot);

                // Número de viaje
                var numeroViaje = parseInt(r.cantidad_viajes, 10) + 1;
                $("#rv_numero_viaje").val(numeroViaje);

                // Semana corporativa
                var fechaObj = new Date(r.fecha_ot);
                var inicioAno = new Date(fechaObj.getFullYear(), 0, 1);
                var dias = Math.floor((fechaObj - inicioAno) / (24 * 60 * 60 * 1000));
                var semana = Math.ceil((dias + inicioAno.getDay() + 1) / 7);
                $("#rv_semana_viaje").val(semana);

                // Limpiar selects
                $("#rv_vehiculo").empty();
                $("#rv_conductor").val("");
                $("#rv_origen").empty();
                $("#rv_destino").empty();
                $("#rv_observaciones").val("");

                // Cargar ubicaciones
                $.post(
                    "/modulos/orden_trabajo/controllers/GetUbicacionesController.php",
                    {},
                    function (u) {

                        $("#rv_origen").append('<option value="">Seleccione...</option>');
                        $("#rv_destino").append('<option value="">Seleccione...</option>');

                        if (u.ok) {
                            u.data.forEach(function (item) {
                                var texto = item.nombre + " (" + item.tipo + ")";
                                $("#rv_origen").append('<option value="' + item.id + '">' + texto + '</option>');
                                $("#rv_destino").append('<option value="' + item.id + '">' + texto + '</option>');
                            });
                        }
                    },
                    "json"
                );

                // Cargar vehículos disponibles
                $.post(
                    "/modulos/orden_trabajo/controllers/GetVehiculosController.php",
                    { fecha_viaje: r.fecha_ot },
                    function (v) {

                        $("#rv_vehiculo").append('<option value="">Seleccione...</option>');

                        if (v.ok) {
                            v.data.forEach(function (item) {
                                $("#rv_vehiculo").append(
                                    '<option value="' + item.id + '">' + item.placa + '</option>'
                                );
                            });
                        }

                        // ============================================================
                        // ABRIR MODAL NUEVO (Bootstrap)
                        // ============================================================
                        $('#modalRegistrarViajeNuevo').modal('show');
                    },
                    "json"
                );
            },
            "json"
        );
    };

    // ============================================================
    // CAMBIO DE VEHÍCULO → CARGAR CONDUCTOR
    // ============================================================
    $(document).on("change", "#rv_vehiculo", function () {

        var vehiculo_id = $(this).val();
        var fecha_viaje = $("#rv_fecha_viaje").val();

        if (!vehiculo_id) {
            $("#rv_conductor").val("");
            return;
        }

        $.post(
            "/modulos/orden_trabajo/controllers/GetConductorPorVehiculo.php",
            { vehiculo_id: vehiculo_id, fecha_viaje: fecha_viaje },
            function (c) {

                if (!c || !c.ok) {
                    $("#rv_conductor").val("Sin conductor asignado");
                    return;
                }

                $("#rv_conductor").val(c.nombre);
                $("#rv_conductor_id").val(c.conductor_id);
            },
            "json"
        );
    });

    // ============================================================
    // GUARDAR VIAJE CORPORATIVO
    // ============================================================
    $(document).on("click", "#btnGuardarViaje", function () {

        var payload = {
            orden_trabajo_id: $("#registrar_viaje_ot_id").val(),
            vehiculo_id: $("#rv_vehiculo").val(),
            conductor_id: $("#rv_conductor_id").val(),
            fecha_viaje: $("#rv_fecha_viaje").val(),
            semana_viaje: $("#rv_semana_viaje").val(),
            origen_id: $("#rv_origen").val(),
            destino_id: $("#rv_destino").val(),
            observaciones: $("#rv_observaciones").val()
        };

        console.log("Guardar viaje ->", payload);

        if (!payload.vehiculo_id) {
            Swal.fire("Validación", "Debe seleccionar un vehículo.", "warning");
            return;
        }

        $.post(
            "/modulos/orden_trabajo/controllers/CrearViajeCorporativoController.php",
            payload,
            function (r) {

                if (!r || !r.ok) {
                    Swal.fire("Error", r.msg || "Error al registrar viaje.", "error");
                    return;
                }

                Swal.fire("Viaje registrado", "Viaje creado correctamente.", "success");

                $('#modalRegistrarViajeNuevo').modal('hide');

                if (typeof cargarViajesOT === "function") {
                    cargarViajesOT(payload.orden_trabajo_id);
                }
            },
            "json"
        );
    });

})(jQuery);
