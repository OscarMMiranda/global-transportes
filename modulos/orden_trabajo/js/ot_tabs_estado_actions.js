// ======================================================
//  JS: ot_tabs_estado_actions.js
//  RESPONSABILIDAD: Control de tabs de estado OT
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

var estadoOT = "TODAS";

document.addEventListener("DOMContentLoaded", function () {

    const tabs = document.querySelectorAll(".ot-tab-estado");

    if (!tabs.length) {
        console.warn("[OT] Tabs de estado no encontrados en DOM");
        return;
    }

    tabs.forEach(function (tab) {

        tab.addEventListener("click", function () {

            // Remover active de todos
            tabs.forEach(t => t.classList.remove("active"));

            // Activar el tab seleccionado
            this.classList.add("active");

            // Obtener el estado (ID o 'TODAS')
            estadoOT = this.getAttribute("data-estado");

            console.log("[OT] Estado seleccionado:", estadoOT);

            // Recargar DataTable sin resetear paginación
            if (typeof tablaOT !== "undefined" && tablaOT !== null) {
                tablaOT.ajax.reload(null, false);
            }
        });
    });

});
