// ======================================================
// JS: ot_crear_guardar.js
// RESPONSABILIDAD: Guardar OT
// ======================================================

$(document).on(
    'click',
    '#ot-btn-guardar-crear',
    function() {

        if (!validarNuevaOT()) {
            return;
        }

        guardarNuevaOT();
    }
);

function guardarNuevaOT() {

    // AJAX guardar
}