// archivo: /modulos/orden_trabajo/js/modales.js

// ===============================
// 🔵 VALIDAR ID
// ===============================
function validarID(id) {
    if (!id || isNaN(id)) {
        alert("ID inválido");
        return false;
    }
    return true;
}

// ===============================
// 🔵 ABRIR MODAL VER OT
// ===============================
function abrirModalVer(id) {

    if (!validarID(id)) return;

    $("#ver_id_ot").val(id);

    $("#loaderVerOT").show();
    $("#contenidoVerOT").hide();

    if (typeof cargarDatosVerOT === "function") {
        cargarDatosVerOT(id);
    } else {
        console.error("❌ cargarDatosVerOT() no está definido.");
    }

    var modal = new bootstrap.Modal(document.getElementById("modalVerOT"));
    modal.show();
}

// ===============================
// 🔵 ABRIR MODAL EDITAR OT
// ===============================
function abrirModalEditar(id) {

    if (!validarID(id)) return;

    $("#editar_id_ot").val(id);

    $("#loaderEditarOT").show();
    $("#formEditarOT").hide();

    if (typeof editarOT === "function") {
        editarOT(id);
    } else {
        console.error("❌ editarOT() no está definido.");
    }

    var modal = new bootstrap.Modal(document.getElementById("modalEditarOT"));
    modal.show();
}

// ===============================
// 🔵 ABRIR MODAL ANULAR OT
// ===============================
function abrirModalAnular(id) {

    if (!validarID(id)) return;

    $("#anular_id_ot").val(id);

    var modal = new bootstrap.Modal(document.getElementById("modalAnularOT"));
    modal.show();
}

// ===============================
// 🔵 ABRIR MODAL ELIMINAR OT
// ===============================
function abrirModalEliminar(id) {

    if (!validarID(id)) return;

    $("#eliminar_id_ot").val(id);

    var modal = new bootstrap.Modal(document.getElementById("modalEliminarOT"));
    modal.show();
}
