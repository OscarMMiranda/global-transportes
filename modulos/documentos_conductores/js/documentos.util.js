// archivo: /modulos/documentos_conductores/js/documentos.util.js


function escapeHtml(text) {
    return text
        .replace(/&/g, "&amp;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

function notificar(tipo, mensaje) {
    var cont = $('#notificacionesERP');

    var div = $('<div class="notificacion ' + tipo + '">' + mensaje + '</div>');
    cont.append(div);

    setTimeout(function () {
        div.addClass('mostrar');
    }, 10);

    setTimeout(function () {
        div.removeClass('mostrar');
        setTimeout(function () { div.remove(); }, 300);
    }, 3000);
}
