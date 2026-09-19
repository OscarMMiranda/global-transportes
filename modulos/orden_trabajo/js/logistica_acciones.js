// ============================================================
// ARCHIVO: logistica_acciones.js
// RESPONSABILIDAD: Acciones corporativas de logística OT (modalRegistrarViaje)
// ============================================================

console.log("LOGISTICA ACCIONES JS CARGADO");

(function ($) {
    "use strict";

    // ============================================================
    // ABRIR modalRegistrarViaje
    // ============================================================
    function abrirLogistica(otId) {
        console.log("abrirLogistica -> otId:", otId);

        $("#rv_orden_trabajo_id").val(otId);

        $.post(
            "/modulos/orden_trabajo/acciones/obtener_logistica.php",
            { ot_id: otId },
            function (resp) {

                if (!resp || !resp.ok) {
                    Swal.fire({ icon: "error", title: "Error", text: resp.msg || "No se pudo cargar la logística." });
                    return;
                }

                const data = resp.ot || resp;

                // ============================================================
                // 1. FECHA DE LA ORDEN (ESCENARIO A)
                // ============================================================
                const fechaViaje = resp.fecha_viaje || data.fecha_viaje || "";
                $("#rv_fecha_viaje").val(fechaViaje);

                // ============================================================
                // 2. CALCULAR SEMANA SEGÚN FECHA DE LA OT
                // ============================================================
                const fechaObj = new Date(fechaViaje);
                const inicioAno = new Date(fechaObj.getFullYear(), 0, 1);
                const dias = Math.floor((fechaObj - inicioAno) / (24 * 60 * 60 * 1000));
                const semana = Math.ceil((dias + inicioAno.getDay() + 1) / 7);

                $("#rv_semana_viaje").val(semana);

                // ============================================================
                // 3. CARGAR SELECTS (ya existe fecha_viaje)
                // ============================================================
                cargarVehiculosSelect("#rv_vehiculo");
                cargarOrigenesSelect("#rv_origen");
                cargarDestinosSelect("#rv_destino");

                // ============================================================
                // 4. SETEAR OTROS CAMPOS
                // ============================================================
                $("#rv_orden_vehiculo_id").val(data.orden_vehiculo_id || "");
                $("#rv_conductor").val(data.conductor_nombre || data.conductor || "");
                $("#rv_observaciones").val(data.observaciones || "");

                // ============================================================
                // 5. MOSTRAR MODAL
                // ============================================================
                $("#modalRegistrarViaje").show();
                $("#modalOverlay").show();
            },
            "json"
        );
    }

    window.abrirLogistica = abrirLogistica;

    // ============================================================
    // CERRAR modalRegistrarViaje
    // ============================================================
    function cerrarModalViaje() {

        $("#rv_orden_trabajo_id").val("");
        $("#rv_orden_vehiculo_id").val("");
        $("#rv_fecha_viaje").val("");
        $("#rv_semana_viaje").val("");
        $("#rv_vehiculo").val("");
        $("#rv_conductor").val("");
        $("#rv_origen").val("");
        $("#rv_destino").val("");
        $("#rv_observaciones").val("");

        $("#modalRegistrarViaje").hide();
        $("#modalOverlay").hide();
    }

    window.cerrarModalViaje = cerrarModalViaje;

    // ============================================================
    // GUARDAR VIAJE
    // ============================================================
    $(document).on("click", "#btnGuardarViaje", function () {

        let payload = {
            orden_trabajo_id: $("#rv_orden_trabajo_id").val(),
            fecha_viaje: $("#rv_fecha_viaje").val(),
            semana_viaje: $("#rv_semana_viaje").val(),
            origen: $("#rv_origen").val(),
            destino: $("#rv_destino").val(),
            observaciones: $("#rv_observaciones").val()
        };

        console.log("guardarViaje -> payload:", payload);

        if (!payload.orden_trabajo_id || !payload.fecha_viaje || !payload.semana_viaje) {
            Swal.fire({ icon: "warning", title: "Validación", text: "Complete fecha y semana antes de guardar." });
            return;
        }

        $.post(
            "/modulos/orden_trabajo/controllers/CrearViajeController.php",
            payload,
            function (resp) {

                if (!resp || !resp.ok) {
                    Swal.fire({ icon: "error", title: "Error", text: resp.msg || "Error al crear viaje." });
                    return;
                }

                Swal.fire({ icon: "success", title: "Viaje registrado", text: "Viaje creado correctamente." });

                cerrarModalViaje();

                if (window.tablaOT) {
                    tablaOT.ajax.reload(null, false);
                }
            },
            "json"
        );
    });

    // ============================================================
    // CARGAR CONDUCTOR AUTOMÁTICAMENTE AL ELEGIR VEHÍCULO
    // ============================================================
    $(document).on("change", "#rv_vehiculo", function () {

        let vehiculo_id = $(this).val();
        let fecha_viaje = $("#rv_fecha_viaje").val();

        if (!vehiculo_id || !fecha_viaje) {
            $("#rv_conductor").val("");
            return;
        }

        $.post(
            "/modulos/orden_trabajo/controllers/GetConductorPorVehiculo.php",
            { vehiculo_id: vehiculo_id, fecha_viaje: fecha_viaje },
            function (resp) {

                if (!resp || !resp.ok) {
                    $("#rv_conductor").val("");
                    return;
                }

                $("#rv_conductor").val(resp.nombre);
                $("#rv_conductor_id").val(resp.conductor_id);
            },
            "json"
        );
    });

    // ============================================================
    // CERRAR MODAL AL HACER CLICK EN OVERLAY
    // ============================================================
    $(document).on("click", "#modalOverlay", function () {
        cerrarModalViaje();
    });

})(jQuery);
