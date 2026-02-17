// archivo: /modulos/asistencias/js/reporte_diario.js

//======================================================
// REPORTE DIARIO - ARCHIVO PRINCIPAL
// Inicializa el módulo y conecta eventos
// ======================================================

$(document).ready(function () {

    // Asegurar namespace global
    var RD = window.RD || {};
    window.RD = RD;

    RD.init = function () {

        console.log("RD.init(): módulo Reporte Diario iniciado");

        // Validar que estamos en la vista correcta
        console.log("RD.init(): Continuando sin validar botón");

        // -------------------------------
        // Eventos principales
        // -------------------------------
        $("#btnBuscarReporte").on("click", function () {
            if (typeof RD.cargarReporte === "function") {
                RD.cargarReporte();
            } else {
                console.error("RD.cargarReporte NO está definido");
            }
        });

        $("#btnToggleVista").on("click", function () {
            if (typeof RD.toggleVista === "function") {
                RD.toggleVista();
            } else {
                console.error("RD.toggleVista NO está definido");
            }
        });

        $("#btnExportarExcel").on("click", function () {
            if (typeof RD.exportarExcel === "function") {
                RD.exportarExcel();
            } else {
                console.error("RD.exportarExcel NO está definido");
            }
        });

        // -------------------------------
        // Cargar reporte al iniciar
        // -------------------------------
        if (typeof RD.cargarReporte === "function") {
            RD.cargarReporte();
        } else {
            console.error("RD.cargarReporte NO está definido al iniciar");
        }
    };

    // ======================================================
    // EJECUCIÓN AUTOMÁTICA AL CARGAR LA PÁGINA
    // ======================================================
    RD.init();

});
