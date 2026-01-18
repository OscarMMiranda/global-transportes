// archivo: /modulos/mantenimiento/tipo_vehiculo/js/notificaciones.js
console.log("📦 notificaciones.js inicializado");

// ---------------------------------------------------------
// SISTEMA DE NOTIFICACIONES FLOTANTES TIPO ERP
// ---------------------------------------------------------

function crearContenedorNotificaciones() {
    if ($("#contenedorNotificaciones").length === 0) {
        $("body").append(`
            <div id="contenedorNotificaciones" 
                 style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 99999;
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                 ">
            </div>
        `);
    }
}

function mostrarNotificacion(tipo, titulo, mensaje) {
    crearContenedorNotificaciones();

    var colores = {
        success: "#28a745",
        warning: "#ffc107",
        error:   "#dc3545",
        info:    "#17a2b8"
    };

    var iconos = {
        success: "fa-check-circle",
        warning: "fa-exclamation-triangle",
        error:   "fa-times-circle",
        info:    "fa-info-circle"
    };

    var id = "notif_" + Date.now();

    var html = `
        <div id="${id}" 
             style="
                min-width: 280px;
                max-width: 350px;
                padding: 15px 18px;
                border-radius: 6px;
                color: white;
                background: ${colores[tipo]};
                box-shadow: 0 4px 10px rgba(0,0,0,0.25);
                display: flex;
                align-items: flex-start;
                gap: 12px;
                opacity: 0;
                transform: translateX(50px);
                transition: all .3s ease;
             ">
            <i class="fa ${iconos[tipo]}" style="font-size: 22px;"></i>
            <div style="flex: 1;">
                <strong style="font-size: 15px;">${titulo}</strong>
                <div style="font-size: 14px; margin-top: 3px;">${mensaje}</div>
            </div>
        </div>
    `;

    $("#contenedorNotificaciones").append(html);

    // Animación de entrada
    setTimeout(function () {
        $("#" + id).css({
            opacity: 1,
            transform: "translateX(0)"
        });
    }, 50);

    // Auto-cerrar
    setTimeout(function () {
        cerrarNotificacion(id);
    }, 4000);
}

function cerrarNotificacion(id) {
    var el = $("#" + id);

    el.css({
        opacity: 0,
        transform: "translateX(50px)"
    });

    setTimeout(function () {
        el.remove();
    }, 300);
}

// ---------------------------------------------------------
// FUNCIONES PÚBLICAS
// ---------------------------------------------------------

function notifySuccess(titulo, mensaje) {
    mostrarNotificacion("success", titulo, mensaje);
}

function notifyWarning(titulo, mensaje) {
    mostrarNotificacion("warning", titulo, mensaje);
}

function notifyError(titulo, mensaje) {
    mostrarNotificacion("error", titulo, mensaje);
}

function notifyInfo(titulo, mensaje) {
    mostrarNotificacion("info", titulo, mensaje);
}