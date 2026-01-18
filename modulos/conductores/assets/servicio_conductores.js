// archivo: /modulos/conductores/assets/servicio_conductores.js
// SERVICIOS AJAX DEL MÓDULO CONDUCTORES

export function listarConductores(estado) {
    return $.get(`/modulos/conductores/acciones/listar.php?estado=${estado}`);
}

export function obtenerConductor(id) {
    return $.get(`/modulos/conductores/acciones/obtener.php?id=${id}`);
}

export function registrarConductor(data) {
    return $.post('/modulos/conductores/acciones/registrar.php', data);
}

export function actualizarConductor(id, data) {
    return $.post(`/modulos/conductores/acciones/actualizar.php?id=${id}`, data);
}

export function desactivarConductor(id) {
    return $.post(`/modulos/conductores/acciones/desactivar.php?id=${id}`);
}

export function restaurarConductor(id) {
    return $.post(`/modulos/conductores/acciones/restaurar.php?id=${id}`);
}

export function eliminarConductor(id) {
    return $.post(`/modulos/conductores/acciones/eliminar.php?id=${id}`);
}