// ======================================================
//  JS: ot_filtro_estado_actions.js
//  RESPONSABILIDAD: Control del filtro corporativo por estado
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.8)
// ======================================================

// Estado global del módulo OT
var estadoOT = "TODAS";

document.addEventListener("DOMContentLoaded", function () {

    var tabs = document.querySelectorAll(".ot-tab-estado");

    if (!tabs.length) {
        console.warn("[OT] No se encontraron tabs de estado");
        return;
    }

    tabs.forEach(function (tab) {

        tab.addEventListener("click", function () {

            estadoOT = (this.getAttribute("data-estado") || "TODAS")
                .toString()
                .trim()
                .toUpperCase();

            console.log("[OT] Estado seleccionado:", estadoOT);

            tabs.forEach(function (t) {
                t.classList.remove("active");
            });

            this.classList.add("active");

            if (typeof tablaOT !== "undefined" && tablaOT !== null) {

                tablaOT.ajax.reload(function (json) {

                    console.log(
                        "[OT] Registros recibidos:",
                        json.data.length
                    );

                }, true);

            }

        });

    });

    console.log("[OT] Tabs de estado cargados correctamente");

});