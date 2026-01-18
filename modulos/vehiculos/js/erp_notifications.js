// archivo: /modulos/vehiculos/js/erp_notifications.js

// Función principal para mostrar notificaciones


function showErpNotification(type, title, message, timeout) {
    timeout = timeout || 4000;

    var container = document.getElementById('erpNotifications');
    if (!container) {
        console.error('No se encontró #erpNotifications en el DOM');
        return;
    }

    var toast = document.createElement('div');
    toast.classList.add('erp-toast');

    switch (type) {
        case 'success':
            toast.classList.add('erp-toast-success');
            break;
        case 'error':
            toast.classList.add('erp-toast-error');
            break;
        case 'info':
            toast.classList.add('erp-toast-info');
            break;
        case 'warning':
            toast.classList.add('erp-toast-warning');
            break;
        default:
            toast.classList.add('erp-toast-info');
    }

    var html = '';
    if (title) {
        html += '<div class="erp-toast-title">' + title + '</div>';
    }
    if (message) {
        html += '<p class="erp-toast-body">' + message + '</p>';
    }

    toast.innerHTML = html;
    container.appendChild(toast);

    // Animación de entrada
    setTimeout(function () {
        toast.classList.add('erp-toast-show');
    }, 10);

    // Auto-remover
    setTimeout(function () {
        toast.classList.remove('erp-toast-show');
        setTimeout(function () {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 250);
    }, timeout);
}

// Helpers
function notifySuccess(title, message, timeout) {
    showErpNotification('success', title, message, timeout);
}

function notifyError(title, message, timeout) {
    showErpNotification('error', title, message, timeout);
}

function notifyInfo(title, message, timeout) {
    showErpNotification('info', title, message, timeout);
}

function notifyWarning(title, message, timeout) {
    showErpNotification('warning', title, message, timeout);
}