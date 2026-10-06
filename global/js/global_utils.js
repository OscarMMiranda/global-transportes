// ======================================================
//  JS: global_utils.js
//  RESPONSABILIDAD: Funciones utilitarias globales del ERP
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

var GlobalUtils = {

    // ---------------------------------------------
    // Validación básica de campos vacíos
    // ---------------------------------------------
    isEmpty: function (value) {
        return (
            value === null ||
            value === undefined ||
            value === "" ||
            (typeof value === "string" && value.trim() === "")
        );
    },

    // ---------------------------------------------
    // Formateo de fecha DD-MM-YYYY
    // ---------------------------------------------
    formatoFecha: function (fechaISO) {
        if (!fechaISO) return "";
        var partes = fechaISO.split("-");
        if (partes.length !== 3) return fechaISO;
        return partes[2] + "-" + partes[1] + "-" + partes[0];
    },

    // ---------------------------------------------
    // Mostrar mensaje corporativo
    // ---------------------------------------------
    mensaje: function (texto) {
        alert(texto);
    },

    // ---------------------------------------------
    // Loader global (si existe en el DOM)
    // ---------------------------------------------
    mostrarLoader: function () {
        var loader = document.getElementById("loader-global");
        if (loader) loader.style.display = "block";
    },

    ocultarLoader: function () {
        var loader = document.getElementById("loader-global");
        if (loader) loader.style.display = "none";
    },

    // ---------------------------------------------
    // Petición POST corporativa
    // ---------------------------------------------
    post: function (url, data, callback) {

        fetch(url, {
            method: "POST",
            body: new URLSearchParams(data)
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            callback(res);
        })
        .catch(function (err) {
            console.error("Error en POST:", err);
            callback({ success: false, message: "Error de comunicación" });
        });
    }
};

// ======================================================
// Inicialización
// ======================================================
document.addEventListener("DOMContentLoaded", function () {
    console.log("GLOBAL UTILS cargado correctamente");
});
