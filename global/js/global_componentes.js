// ======================================================
//  JS: global_componentes.js
//  RESPONSABILIDAD: Componentes globales del ERP
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

var GlobalComponentes = {

    // ---------------------------------------------
    // Modal de confirmación corporativo
    // ---------------------------------------------
    confirmar: function (mensaje, callback) {
        var respuesta = confirm(mensaje);
        if (respuesta && typeof callback === "function") {
            callback();
        }
    },

    // ---------------------------------------------
    // Notificación corporativa (alert simple)
    // ---------------------------------------------
    notificar: function (mensaje) {
        alert(mensaje);
    },

    // ---------------------------------------------
    // Overlay global (si existe en el DOM)
    // ---------------------------------------------
    mostrarOverlay: function () {
        var overlay = document.getElementById("overlay-global");
        if (overlay) overlay.style.display = "flex";
    },

    ocultarOverlay: function () {
        var overlay = document.getElementById("overlay-global");
        if (overlay) overlay.style.display = "none";
    },

    // ---------------------------------------------
    // Tooltip simple
    // ---------------------------------------------
    tooltip: function (idElemento, texto) {
        var el = document.getElementById(idElemento);
        if (!el) return;

        el.setAttribute("title", texto);
    },

    // ---------------------------------------------
    // Deshabilitar UI
    // ---------------------------------------------
    bloquearUI: function () {
        document.body.style.pointerEvents = "none";
        document.body.style.opacity = "0.6";
    },

    desbloquearUI: function () {
        document.body.style.pointerEvents = "auto";
        document.body.style.opacity = "1";
    },

    // ---------------------------------------------
    // Ejecutar acción con overlay global
    // ---------------------------------------------
    ejecutarConOverlay: function (mensaje, callback) {

        var overlay = document.getElementById("overlay-global");
        var texto   = document.getElementById("overlay-global-text");

        if (overlay) overlay.style.display = "flex";
        if (texto) texto.innerHTML = mensaje;

        this.bloquearUI();

        setTimeout(function () {
            try {
                callback();
            } finally {
                GlobalComponentes.ocultarOverlay();
                GlobalComponentes.desbloquearUI();
            }
        }, 300);
    }
};

// ======================================================
// Inicialización
// ======================================================
document.addEventListener("DOMContentLoaded", function () {
    console.log("GLOBAL COMPONENTES cargado correctamente");
});
