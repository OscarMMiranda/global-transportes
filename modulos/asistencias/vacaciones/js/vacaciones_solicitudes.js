// archivo: /modulos/asistencias/vacaciones/js/vacaciones_solicitudes.js

// ============================================================
// JS: SOLICITUDES DE VACACIONES
// Archivo: vacaciones_solicitudes.js
// ============================================================

$(document).ready(function () {

    console.log("vacaciones_solicitudes.js cargado correctamente");

    // Cargar solicitudes al iniciar la vista
    cargarSolicitudes();

    // Botón aplicar filtros
    $("#btnFiltrarSolicitudes").on("click", function () {
        cargarSolicitudes();
    });

    // Botón abrir modal para nueva solicitud
    $("#btnNuevaSolicitud").on("click", function () {
        limpiarFormularioSolicitud();
        $("#modalSolicitarVacaciones").modal("show");
    });

    // Botón enviar solicitud
    $("#btnEnviarSolicitud").on("click", function () {
        enviarSolicitud();
    });

});


// ============================================================
// FUNCIÓN PRINCIPAL: CARGAR SOLICITUDES
// ============================================================
function cargarSolicitudes() {

    var empresa   = $("#filtroEmpresa").val();
    var conductor = $("#filtroConductor").val();
    var estado    = $("#filtroEstadoSolicitud").val();

    $("#loaderSolicitudes").removeClass("d-none");

    $.ajax({
        url: "ajax/ajax_solicitudes.php",
        type: "POST",
        data: {
            empresa: empresa,
            conductor: conductor,
            estado: estado
        },
        dataType: "json",

        success: function (response) {

            var tbody = $("#tablaSolicitudesVacaciones tbody");
            tbody.empty();

            if (!response || response.length === 0) {
                tbody.append(
                    '<tr><td colspan="8" class="text-center text-muted">No se encontraron solicitudes</td></tr>'
                );
                return;
            }

            for (var i = 0; i < response.length; i++) {

                var s = response[i];

                var claseEstado = obtenerClaseEstadoSolicitud(s.estado);

                tbody.append(
                    '<tr>' +
                        '<td>' + s.fecha_solicitud + '</td>' +
                        '<td>' + s.conductor + '</td>' +
                        '<td>' + s.empresa + '</td>' +
                        '<td>' + s.fecha_inicio + '</td>' +
                        '<td>' + s.fecha_fin + '</td>' +
                        '<td>' + s.dias + '</td>' +
                        '<td class="' + claseEstado + '">' + s.estado + '</td>' +
                        '<td class="text-center">' +
                            generarBotonesAccion(s) +
                        '</td>' +
                    '</tr>'
                );
            }
        },

        error: function (xhr, status, error) {
            console.error("Error AJAX:", error);
            alert("Error al cargar las solicitudes.");
        },

        complete: function () {
            $("#loaderSolicitudes").addClass("d-none");
        }
    });
}


// ============================================================
// FUNCIÓN: GENERAR BOTONES DE ACCIÓN
// ============================================================
function generarBotonesAccion(s) {

    var html = '';

    // Ver detalle
    html += '<button class="btn btn-sm btn-outline-primary me-1" onclick="verSolicitud(' + s.id_solicitud + ')">' +
                '<i class="fa-solid fa-eye"></i>' +
            '</button>';

    // Si está pendiente → permitir aprobar/rechazar
    if (s.estado === "Pendiente") {

        html += '<button class="btn btn-sm btn-outline-success me-1" onclick="aprobarSolicitud(' + s.id_solicitud + ')">' +
                    '<i class="fa-solid fa-check"></i>' +
                '</button>';

        html += '<button class="btn btn-sm btn-outline-danger" onclick="rechazarSolicitud(' + s.id_solicitud + ')">' +
                    '<i class="fa-solid fa-xmark"></i>' +
                '</button>';
    }

    return html;
}


// ============================================================
// FUNCIÓN: LIMPIAR FORMULARIO DE SOLICITUD
// ============================================================
function limpiarFormularioSolicitud() {
    $("#solicitudConductor").val("");
    $("#solicitudFechaInicio").val("");
    $("#solicitudFechaFin").val("");
    $("#solicitudDias").val("");
    $("#solicitudObservacion").val("");
}


// ============================================================
// FUNCIÓN: ENVIAR SOLICITUD
// ============================================================
function enviarSolicitud() {

    var conductor = $("#solicitudConductor").val();
    var inicio    = $("#solicitudFechaInicio").val();
    var fin       = $("#solicitudFechaFin").val();
    var obs       = $("#solicitudObservacion").val();

    if (conductor === "" || inicio === "" || fin === "") {
        alert("Complete todos los campos obligatorios.");
        return;
    }

    $("#loaderEnviarSolicitud").removeClass("d-none");

    $.ajax({
        url: "ajax/ajax_solicitudes_guardar.php",
        type: "POST",
        data: {
            conductor: conductor,
            inicio: inicio,
            fin: fin,
            observacion: obs
        },
        dataType: "json",

        success: function (response) {

            if (response.ok) {
                alert("Solicitud registrada correctamente.");
                $("#modalSolicitarVacaciones").modal("hide");
                cargarSolicitudes();
            } else {
                alert("No se pudo registrar la solicitud.");
            }
        },

        error: function (xhr, status, error) {
            console.error("Error AJAX:", error);
            alert("Error al registrar la solicitud.");
        },

        complete: function () {
            $("#loaderEnviarSolicitud").addClass("d-none");
        }
    });
}


// ============================================================
// FUNCIÓN: OBTENER CLASE CSS SEGÚN ESTADO
// ============================================================
function obtenerClaseEstadoSolicitud(estado) {

    estado = estado.toLowerCase();

    if (estado === "pendiente") return "text-warning fw-bold";
    if (estado === "aprobado")  return "text-success fw-bold";
    if (estado === "rechazado") return "text-danger fw-bold";

    return "";
}


// ============================================================
// FUNCIÓN: VER SOLICITUD (abre modal de aprobación)
// ============================================================
function verSolicitud(id) {
    $("#aprobarSolicitudID").val(id);
    $("#modalAprobarVacaciones").modal("show");
}


// ============================================================
// FUNCIÓN: APROBAR SOLICITUD
// ============================================================
function aprobarSolicitud(id) {

    if (!confirm("¿Aprobar esta solicitud?")) return;

    $.ajax({
        url: "ajax/ajax_solicitudes_aprobar.php",
        type: "POST",
        data: { id_solicitud: id },
        dataType: "json",

        success: function (response) {
            if (response.ok) {
                alert("Solicitud aprobada.");
                cargarSolicitudes();
            } else {
                alert("No se pudo aprobar la solicitud.");
            }
        },

        error: function () {
            alert("Error al aprobar la solicitud.");
        }
    });
}


// ============================================================
// FUNCIÓN: RECHAZAR SOLICITUD
// ============================================================
function rechazarSolicitud(id) {

    if (!confirm("¿Rechazar esta solicitud?")) return;

    $.ajax({
        url: "ajax/ajax_solicitudes_rechazar.php",
        type: "POST",
        data: { id_solicitud: id },
        dataType: "json",

        success: function (response) {
            if (response.ok) {
                alert("Solicitud rechazada.");
                cargarSolicitudes();
            } else {
                alert("No se pudo rechazar la solicitud.");
            }
        },

        error: function () {
            alert("Error al rechazar la solicitud.");
        }
    });
}

// ============================================================
// ABRIR MODAL DE APROBAR SOLICITUD
// ============================================================

$(document).on("click", ".btnAprobarSolicitud", function () {

    let id = $(this).data("id");

    // Guardamos el ID en un input oculto dentro del modal
    $("#aprobacion_id").val(id);

    // Cargar datos de la solicitud antes de abrir el modal
    cargarDatosSolicitud(id);

    // Abrir modal
    $("#modalAprobarVacaciones").modal("show");
});

// ============================================================
// CARGAR DATOS DE LA SOLICITUD PARA APROBAR
// ============================================================
function cargarDatosSolicitud(id) {

    $.ajax({
        url: "../vacaciones/ajax/ajax_solicitudes_detalle.php",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (data) {

            if (!data.success) {
                alert("No se pudo cargar la solicitud.");
                return;
            }

            // Llenar campos del modal
            $("#aprobacion_id").val(id);
            $("#aprob_conductor").text(data.conductor);
            $("#aprob_fechas").text(data.fecha_inicio + " → " + data.fecha_fin);
            $("#aprob_dias").text(data.dias);
            $("#aprob_comentario").text(data.comentario);

            // Abrir modal
            $("#modalAprobarVacaciones").modal("show");
        },
        error: function () {
            alert("Error al obtener los datos de la solicitud.");
        }
    });
}
