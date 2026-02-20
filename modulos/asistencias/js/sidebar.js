// archivo: /modulos/asistencias/js/sidebar.js
//
// ============================================================
//  ACCIONES DEL SIDEBAR Y TARJETAS DEL MÓDULO ASISTENCIAS
// ============================================================

$(document).ready(function () {

    // ------------------------------------------------------------
    //  REGISTRAR ASISTENCIA (SIDEBAR)
    // ------------------------------------------------------------
    $("#btnSidebarRegistrarAsistencia").on("click", function (e) {
        e.preventDefault();
        $("#modalRegistrarAsistencia").modal("show");
    });

    // ------------------------------------------------------------
    //  REGISTRAR ASISTENCIA (TARJETA)
    // ------------------------------------------------------------
    $(document).on("click", ".btnRegistrarAsistencia", function (e) {
        e.preventDefault();
        $("#modalRegistrarAsistencia").modal("show");
    });

    // ------------------------------------------------------------
    //  MODIFICAR ASISTENCIA
    // ------------------------------------------------------------
    $("#btnSidebarModificarAsistencia").on("click", function (e) {
        e.preventDefault();
        $("#modalModificarAsistencia").modal("show");
    });

    // ------------------------------------------------------------
    //  VACACIONES
    // ------------------------------------------------------------
    $("#btnSidebarVacaciones").on("click", function (e) {
        e.preventDefault();
        $("#modalVacaciones").modal("show");
    });

    // ------------------------------------------------------------
    //  PERMISOS
    // ------------------------------------------------------------
    $("#btnSidebarPermisos").on("click", function (e) {
        e.preventDefault();
        $("#modalPermisos").modal("show");
    });

    // ------------------------------------------------------------
    //  REPORTE DIARIO
    // ------------------------------------------------------------
    $("#btnSidebarReporteDiario").on("click", function (e) {
        e.preventDefault();
        window.location.href = "reporte_diario.php";
    });


    // LIMPIAR FORMULARIO AL ABRIR EL MODAL
    $("#modalRegistrarAsistencia").on("show.bs.modal", function () {

		// Selects	
		$("#empresa_id").val("");
		$("#conductor_id").val("");
		$("#codigo_tipo").val("");

		// Inputs
		$("#fecha").val("");
		$("#observacion").val("");
		$("#hora_entrada").val("");
		$("#hora_salida").val("");

	});






});
