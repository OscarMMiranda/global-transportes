// archivo: /modulos/papeletas/js/papeletas.util.js

const DEBUG_PAP_UTIL = true;
function plogUtil(msg) { if (DEBUG_PAP_UTIL) console.log("PAP-UTIL:", msg); }

/* ============================================================
   CARGAR SELECT DE VEHÍCULOS (JSON → OPTIONS)
   ============================================================ */
function cargarVehiculosSelect() {
    return $.post('/modulos/papeletas/acciones/lista_vehiculos.php', function(data) {

        let html = "<option value=''>-- Seleccione vehículo --</option>";

        $.each(data, function(i, v) {
            html += "<option value='" + v.id + "'>" + v.placa + "</option>";
        });

        // CREAR
        $("#vehiculoNuevaSelect").html(html);

        // EDITAR
        $("#edit_vehiculo_id").html(html);

    }, 'json');
}

/* ============================================================
   CARGAR SELECT DE CONDUCTORES (JSON → OPTIONS)
   ============================================================ */
function cargarConductoresSelect() {
    return $.post('/modulos/papeletas/acciones/lista_conductores.php', function(data) {

        let html = "<option value=''>-- Sin conductor --</option>";

        $.each(data, function(i, v) {
            html += "<option value='" + v.id + "'>" + v.nombre + "</option>";
        });

        // CREAR
        $("#conductorNuevaSelect").html(html);

        // EDITAR
        $("#edit_conductor_id").html(html);

    }, 'json');
}

/* ============================================================
   CARGAR SELECT DE ENTIDADES EMISORAS (JSON → OPTIONS)
   ============================================================ */
function cargarEntidadEmisoraSelect() {
    return $.post('/modulos/papeletas/acciones/lista_entidades.php', function(data) {

        let html = "<option value=''>-- Seleccione entidad --</option>";

        $.each(data, function(i, v) {
            html += "<option value='" + v.id + "'>" + v.nombre + "</option>";
        });

        // CREAR
        $("#entidadEmisoraSelect").html(html);

        // EDITAR
        $("#edit_entidad_emisora_id").html(html);

    }, 'json');
}

/* ============================================================
   CARGAR INFRACCIONES POR ENTIDAD (para edición)
   ============================================================ */
function cargarInfraccionesPorEntidad(entidad_id) {

    return $.post('/modulos/infracciones/acciones/listar_por_entidad.php',
        { entidad_id: entidad_id },
        function(html){
            $("#edit_infraccion_id").html(html);
        }
    );
}

/* ============================================================
   LIMPIAR INFRACCIONES AL ABRIR EL MODAL DE CREACIÓN
   ============================================================ */
function limpiarInfraccionesSelect() {
    $("#infraccionSelect").html("<option value=''>-- Seleccione entidad --</option>");
}

/* ============================================================
   RECARGAR INFRACCIONES AL CAMBIAR ENTIDAD (EDICIÓN)
   ============================================================ */
$(document).on("change", "#edit_entidad_emisora_id", function() {

    var entidad_id = $(this).val();

    if (!entidad_id) {
        $("#edit_infraccion_id").html("<option value=''>-- Seleccione infracción --</option>");
        return;
    }

    cargarInfraccionesPorEntidad(entidad_id).done(function() {
        // limpiar selección anterior
        $("#edit_infraccion_id").val("");
    });

});
