// archivo: /modulos/infracciones/assets/validaciones.js

function validarFormulario(formId) {

    var f = document.getElementById(formId);

    var codigo = f.codigo.value.trim();
    var descripcion = f.descripcion.value.trim();
    var gravedad = f.gravedad.value.trim();
    var puntos = f.puntos.value.trim();
    var porcentaje = f.porcentaje_uit.value.trim();
    var entidad = f.entidad_emisora_id.value.trim();

    // Código
    if (codigo === "") {
        Swal.fire("Validación", "El código es obligatorio", "warning");
        f.codigo.focus();
        return false;
    }

    // Descripción
    if (descripcion.length < 5) {
        Swal.fire("Validación", "La descripción debe tener al menos 5 caracteres", "warning");
        f.descripcion.focus();
        return false;
    }

    // Gravedad
    if (gravedad === "") {
        Swal.fire("Validación", "Seleccione la gravedad", "warning");
        f.gravedad.focus();
        return false;
    }

    // Puntos
    if (puntos === "" || isNaN(puntos) || parseInt(puntos) < 0) {
        Swal.fire("Validación", "Los puntos deben ser un número mayor o igual a 0", "warning");
        f.puntos.focus();
        return false;
    }

    // Porcentaje UIT
    if (porcentaje === "" || isNaN(porcentaje) || parseFloat(porcentaje) <= 0) {
        Swal.fire("Validación", "El % UIT debe ser un número mayor a 0", "warning");
        f.porcentaje_uit.focus();
        return false;
    }

    // Entidad emisora
    if (entidad === "") {
        Swal.fire("Validación", "Seleccione la entidad emisora", "warning");
        f.entidad_emisora_id.focus();
        return false;
    }

    return true;
}
