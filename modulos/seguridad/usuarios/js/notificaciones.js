// archivo: /modulos/seguridad/usuarios/js/notificaciones.js
// Componente de notificaciones flotantes ERP

function notificar(mensaje, tipo) {
    tipo = tipo || "info";

    var cont = $("#notificaciones-container");
    if (cont.length === 0) {
        $("body").append('<div id="notificaciones-container" class="notificaciones-container"></div>');
        cont = $("#notificaciones-container");
    }

    var icono = "";
    if (tipo === "success") icono = "<i class='fa-solid fa-circle-check'></i>";
    else if (tipo === "error") icono = "<i class='fa-solid fa-circle-xmark'></i>";
    else if (tipo === "warning") icono = "<i class='fa-solid fa-triangle-exclamation'></i>";
    else icono = "<i class='fa-solid fa-circle-info'></i>";

    var $n = $("<div class='notificacion " + tipo + "'>" +
        "<div class='icono'>" + icono + "</div>" +
        "<div class='mensaje'>" + mensaje + "</div>" +
        "<div class='cerrar'>&times;</div>" +
        "</div>");

    cont.append($n);

    setTimeout(function () {
        $n.addClass("mostrar");
    }, 10);

    $n.find(".cerrar").on("click", function () {
        cerrarNotificacion($n);
    });

    setTimeout(function () {
        cerrarNotificacion($n);
    }, 4000);
}

function cerrarNotificacion($n) {
    $n.removeClass("mostrar");
    setTimeout(function () {
        $n.remove();
    }, 200);
}