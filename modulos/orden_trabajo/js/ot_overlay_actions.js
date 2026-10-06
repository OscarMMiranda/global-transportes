// ======================================================
//  JS: ot_overlay_actions.js
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Control del overlay corporativo OT
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

var OTOverlay = {

    overlayId: "ot-overlay-global",
    textId: "ot-overlay-text",

    mostrar: function (mensaje) {
        var overlay = document.getElementById(this.overlayId);
        var texto   = document.getElementById(this.textId);

        if (!overlay) return;

        if (mensaje && texto) {
            texto.innerHTML = mensaje;
        }

        overlay.style.display = "flex";
    },

    ocultar: function () {
        var overlay = document.getElementById(this.overlayId);
        if (!overlay) return;

        overlay.style.display = "none";
    },

    bloquearUI: function () {
        document.body.style.pointerEvents = "none";
        document.body.style.opacity = "0.6";
    },

    desbloquearUI: function () {
        document.body.style.pointerEvents = "auto";
        document.body.style.opacity = "1";
    },

    ejecutarConOverlay: function (mensaje, callback) {
        this.mostrar(mensaje);
        this.bloquearUI();

        setTimeout(function () {
            try {
                callback();
            } finally {
                OTOverlay.ocultar();
                OTOverlay.desbloquearUI();
            }
        }, 300);
    }
};

// ======================================================
//  Inicialización
// ======================================================

document.addEventListener("DOMContentLoaded", function () {
    console.log("OT Overlay cargado correctamente");
});
