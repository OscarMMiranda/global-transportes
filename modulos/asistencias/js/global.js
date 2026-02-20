//  archivo: /modulos/asistencias/js/global.js
//
// ============================================================
//  MÓDULO GLOBAL DEL SISTEMA DE ASISTENCIAS
//  Este archivo se carga SOLO en index.php
//  NO inicializa módulos específicos
//  NO ejecuta lógica de submódulos
//  Provee utilidades globales seguras
// ============================================================

(function (window) {

    // Namespace global seguro
    const GT = window.GT || {};
    window.GT = GT;

    // ------------------------------------------------------------
    //  LOG CONTROLADO
    // ------------------------------------------------------------
    GT.log = function (msg) {
        if (typeof console !== "undefined") {
            console.log("[GT] " + msg);
        }
    };

    // ------------------------------------------------------------
    //  TOAST CORPORATIVO GLOBAL
    // ------------------------------------------------------------
    GT.toast = function (tipo, mensaje) {

        let clase = (tipo === "success")
            ? "toast-success"
            : (tipo === "warning")
                ? "toast-warning"
                : "toast-error";

        let $toast = $("#toast_global");

        // Crear contenedor si no existe
        if ($toast.length === 0) {
            $("body").append(`
                <div id="toast_global"
                     style="
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        padding: 12px 18px;
                        border-radius: 6px;
                        color: #fff;
                        font-size: 14px;
                        display: none;
                        z-index: 99999;
                     ">
                </div>
            `);
            $toast = $("#toast_global");
        }

        // Colores corporativos
        let colores = {
            "toast-success": "#28a745",
            "toast-warning": "#ffc107",
            "toast-error": "#dc3545"
        };

        $toast
            .removeClass()
            .css("background", colores[clase])
            .text(mensaje)
            .fadeIn(200);

        setTimeout(() => {
            $toast.fadeOut(300);
        }, 2500);
    };

    // ------------------------------------------------------------
    //  FORMATEAR FECHA (YYYY-MM-DD)
    // ------------------------------------------------------------
    GT.formatoFecha = function (fecha) {
        if (!fecha) return "";
        const f = new Date(fecha);
        if (isNaN(f.getTime())) return fecha;
        return f.toISOString().split("T")[0];
    };

    // ------------------------------------------------------------
    //  FORMATEAR HORA (HH:MM:SS)
    // ------------------------------------------------------------
    GT.formatoHora = function (hora) {
        if (!hora) return "00:00:00";
        return hora.length === 5 ? hora + ":00" : hora;
    };

    // ------------------------------------------------------------
    //  VALIDAR VACÍO
    // ------------------------------------------------------------
    GT.vacio = function (valor) {
        return valor === null || valor === undefined || valor === "";
    };

    // ------------------------------------------------------------
    //  AJAX GLOBAL SEGURO
    // ------------------------------------------------------------
    GT.ajax = function (opciones) {

        if (!opciones || !opciones.url) {
            console.error("GT.ajax: falta URL");
            return;
        }

        $.ajax({
            url: opciones.url,
            type: opciones.type || "POST",
            data: opciones.data || {},
            dataType: opciones.dataType || "json",
            beforeSend: opciones.beforeSend || function () { },
            success: opciones.success || function () { },
            error: opciones.error || function (xhr) {
                console.error("GT.ajax error:", xhr.responseText);
                GT.toast("error", "Error de comunicación con el servidor");
            }
        });
    };

    GT.log("global.js cargado correctamente");

})(window);
