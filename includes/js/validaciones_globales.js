/* archivo: includes/js/validaciones_globales.js */

/* ============================================================
   VALIDACIONES_GLOBALES.JS — Validaciones universales
   Compatible con PHP 5.6
   ============================================================ */

/* ------------------------------
   Validación de email
--------------------------------*/
function validarEmail(email) {
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/* ------------------------------
   Validación de campos obligatorios
--------------------------------*/
function validarCamposObligatorios(formId) {
    var form = document.getElementById(formId);
    if (!form) return false;

    var inputs = form.querySelectorAll("[data-obligatorio='true']");
    var valido = true;

    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].value.trim() === "") {
            inputs[i].style.border = "1px solid red";
            valido = false;
        } else {
            inputs[i].style.border = "1px solid #ccc";
        }
    }

    return valido;
}

/* ------------------------------
   Validación de RUC (Perú)
--------------------------------*/
function validarRUC(ruc) {
    if (ruc.length !== 11) return false;
    if (!/^[0-9]+$/.test(ruc)) return false;
    return true;
}

/* ------------------------------
   Validación de fecha (YYYY-MM-DD)
--------------------------------*/
function validarFecha(fecha) {
    var regex = /^\d{4}-\d{2}-\d{2}$/;
    return regex.test(fecha);
}