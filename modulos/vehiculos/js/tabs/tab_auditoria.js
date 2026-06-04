// archivo: modulos/vehiculos/js/tabs/tab_auditoria.js

function cargarTabAuditoria() {

    if (TAB_CARGADO.auditoria) return;

    $("#tab-auditoria").html(loaderHTML("Cargando auditoría..."));

    $.get("/modulos/vehiculos/acciones/editar_auditoria.php", { id: ID_VEHICULO_EDITAR })
        .done(function (resp) {
            $("#tab-auditoria").html(resp);
            TAB_CARGADO.auditoria = true;
        })
        .fail(function () {
            $("#tab-auditoria").html(errorHTML("Error al cargar auditoría"));
        });
}
