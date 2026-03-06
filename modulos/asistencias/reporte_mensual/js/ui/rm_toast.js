// ============================================================
//  archivo: /modulos/asistencias/reporte_mensual/js/ui/rm_toast.js
// RESPONSABILIDAD: Notificaciones estándar del módulo RM
// ============================================================

function rm_toast(tipo, mensaje) {

    // Si tienes SweetAlert2 cargado (lo tienes)
    if (typeof Swal !== "undefined") {

        let icono = (tipo === "error") ? "error" : "success";

        Swal.fire({
            icon: icono,
            title: mensaje,
            timer: 1800,
            showConfirmButton: false,
            position: "top-end",
            toast: true
        });

        return;
    }

    // Fallback profesional si SweetAlert no está disponible
    console.log("TOAST:", tipo.toUpperCase(), mensaje);
}

window.rm_toast = rm_toast;
