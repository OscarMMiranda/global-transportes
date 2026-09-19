// logistica_ot.js
// Funciones robustas para abrir/cerrar modal y cargar selects usando catalogos.js

// Evitar múltiples bindings: siempre quitar handlers previos
function bindOnce(selector, event, handler) {
    $(document).off(event, selector);
    $(document).on(event, selector, handler);
}

// Abre modal corporativo para registrar viaje
function abrirLogistica(ot_id) {

    console.log("abrirLogistica:", ot_id);

    // Guardar ID de la OT
    $("#rv_orden_trabajo_id").val(ot_id);

    // Limpiar campos
    $("#rv_fecha_viaje").val("");
    $("#rv_semana_viaje").val("");
    $("#rv_vehiculo").html("<option value=''>Cargando...</option>");
    $("#rv_conductor").val("");
    $("#rv_origen").html("<option value=''>Cargando...</option>");
    $("#rv_destino").html("<option value=''>Cargando...</option>");
    $("#rv_observaciones").val("");

    // Cargar selects usando las funciones de catalogos.js
    // Estas funciones deben existir: cargarVehiculosSelect, cargarOrigenesSelect, cargarDestinosSelect
    // Si no existen, usamos cargarCatalogo genérico (catalogos.js)
    if (typeof cargarVehiculosSelect === "function") {
        cargarVehiculosSelect("#rv_vehiculo");
    } else {
        // ejemplo: endpoint que devuelve lista de vehiculos en JSON [{id,nombre}]
        cargarCatalogo("/modulos/papeletas/controllers/GetVehiculosController.php", {}, "#rv_vehiculo", "id", "placa");
    }

    if (typeof cargarOrigenesSelect === "function") {
        cargarOrigenesSelect("#rv_origen");
    } else {
        cargarCatalogo("/modulos/orden_trabajo/controllers/OrigenesController.php", {}, "#rv_origen", "id", "nombre");
    }

    if (typeof cargarDestinosSelect === "function") {
        cargarDestinosSelect("#rv_destino");
    } else {
        cargarCatalogo("/modulos/orden_trabajo/controllers/DestinosController.php", {}, "#rv_destino", "id", "nombre");
    }

    // Cuando selecciona vehículo, pedir conductor (endpoint real)
    bindOnce("#rv_vehiculo", "change", function () {
        var vehiculo_id = $(this).val();
        if (!vehiculo_id) {
            $("#rv_conductor").val("");
            return;
        }
        $.post("/modulos/papeletas/acciones/lista_conductores.php", { vehiculo_id: vehiculo_id }, function (res) {
            if (res && res.length > 0) {
                // ajusta según la estructura real (res[0].nombre o res[0].nombres)
                var nombre = res[0].nombre || (res[0].nombres ? (res[0].nombres + " " + (res[0].apellidos||"")) : "");
                $("#rv_conductor").val(nombre);
            } else {
                $("#rv_conductor").val("");
            }
        }, "json").fail(function () {
            $("#rv_conductor").val("");
        });
    });

    // Calcular semana al cambiar fecha (si tienes endpoint)
    bindOnce("#rv_fecha_viaje", "change", function () {
        var fecha = $(this).val();
        if (!fecha) { $("#rv_semana_viaje").val(""); return; }
        $.post("/modulos/orden_trabajo/controllers/CalcularSemanaController.php", { fecha: fecha }, function (r) {
            if (r && r.semana) $("#rv_semana_viaje").val(r.semana);
        }, "json").fail(function () {
            $("#rv_semana_viaje").val("");
        });
    });

    // Mostrar modal y overlay con z-index seguro
    $("#modalOverlay").css({ "z-index": 1050 }).fadeIn(150);
    $("#modalRegistrarViaje").css({ "z-index": 1060 }).fadeIn(150);
}

// Cerrar modal
function cerrarModalViaje() {
    $("#modalRegistrarViaje").fadeOut(120);
    $("#modalOverlay").fadeOut(120);
}

// Botón guardar (ejemplo): evita múltiples binds
bindOnce("#btnGuardarViaje", "click", function (e) {
    e.preventDefault();
    // Aquí llamarías al controlador RegistrarViajeController.php con los campos
    // Validaciones mínimas
    var orden_trabajo_id = $("#rv_orden_trabajo_id").val();
    var vehiculo_id = $("#rv_vehiculo").val();
    var fecha_viaje = $("#rv_fecha_viaje").val();
    if (!orden_trabajo_id || !vehiculo_id || !fecha_viaje) {
        Swal.fire("Atención", "Complete OT, vehículo y fecha.", "warning");
        return;
    }
    // enviar via AJAX...
});
