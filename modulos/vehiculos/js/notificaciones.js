// archivo: /modulos/vehiculos/js/notificaciones.js
// Sistema de notificaciones flotantes tipo ERP

function crearNotificacion(tipo, titulo, mensaje) {
    const id = "notif_" + Date.now();

    const colores = {
        success: "bg-success text-white",
        error: "bg-danger text-white",
        warning: "bg-warning text-dark",
        info: "bg-info text-dark"
    };

    const iconos = {
        success: "fa-circle-check",
        error: "fa-circle-xmark",
        warning: "fa-triangle-exclamation",
        info: "fa-circle-info"
    };

    const claseColor = colores[tipo] || colores.info;
    const icono = iconos[tipo] || iconos.info;

    const html = `
        <div id="${id}" class="notificacion shadow rounded ${claseColor}" 
             style="
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 99999;
                min-width: 280px;
                max-width: 350px;
                padding: 12px 16px;
                margin-bottom: 10px;
                opacity: 0;
                transform: translateY(-10px);
                transition: all 0.3s ease;
             ">
            <div class="d-flex align-items-start">
                <i class="fa-solid ${icono} fs-4 me-3"></i>
                <div>
                    <strong>${titulo}</strong><br>
                    <span style="font-size: 0.9rem;">${mensaje}</span>
                </div>
            </div>
        </div>
    `;

    $("body").append(html);

    // Animación de entrada
    setTimeout(() => {
        $("#" + id).css({
            opacity: 1,
            transform: "translateY(0)"
        });
    }, 50);

    // Auto-cierre a los 4 segundos
    setTimeout(() => {
        $("#" + id).css({
            opacity: 0,
            transform: "translateY(-10px)"
        });

        setTimeout(() => {
            $("#" + id).remove();
        }, 300);
    }, 4000);
}

// -----------------------------
// Funciones públicas
// -----------------------------

function notifySuccess(titulo, mensaje) {
    crearNotificacion("success", titulo, mensaje);
}

function notifyError(titulo, mensaje) {
    crearNotificacion("error", titulo, mensaje);
}

function notifyWarning(titulo, mensaje) {
    crearNotificacion("warning", titulo, mensaje);
}

function notifyInfo(titulo, mensaje) {
    crearNotificacion("info", titulo, mensaje);
}