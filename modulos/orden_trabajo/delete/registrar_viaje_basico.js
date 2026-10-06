console.log("registrar_viaje_basico.js cargado");

(function($){

    "use strict";

    // ABRIR MODAL
    window.abrirRegistrarViaje = function(ot_id){

        console.log("abrirRegistrarViaje ->", ot_id);

        $("#rv_ot_id").val(ot_id);

        $.post(
            "/modulos/orden_trabajo/controllers/GetDatosOTBasico.php",
            { orden_trabajo_id: ot_id },
            function(r){

                console.log("Respuesta controlador:", r);

                if(!r || !r.ok){
                    Swal.fire("Error", r.msg || "No se pudo obtener datos de la OT", "error");
                    return;
                }

                $("#rv_fecha_viaje").val(r.fecha_ot);
                $("#rv_semana_viaje").val(r.semana_ot);

                $("#modalRegistrarViajeBasico").modal("show");
            },
            "json"
        );
    };

    // GUARDAR VIAJE BÁSICO
    $("#btnGuardarViajeBasico").on("click", function(){

        var payload = {
            orden_trabajo_id: $("#rv_ot_id").val(),
            fecha_viaje: $("#rv_fecha_viaje").val(),
            semana_viaje: $("#rv_semana_viaje").val()
        };

        console.log("Guardar viaje básico ->", payload);

        $.post(
            "/modulos/orden_trabajo/controllers/CrearViajeBasicoController.php",
            payload,
            function(r){

                console.log("Respuesta guardar:", r);

                if(!r || !r.ok){
                    Swal.fire("Error", r.msg || "No se pudo guardar", "error");
                    return;
                }

                Swal.fire("OK", "Viaje registrado", "success");
                $("#modalRegistrarViajeBasico").modal("hide");
            },
            "json"
        );
    });

})(jQuery);
