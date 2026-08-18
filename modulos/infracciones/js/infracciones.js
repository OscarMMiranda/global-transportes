// archivo: /modulos/infracciones/js/infracciones.js
// Orquestador principal del módulo Infracciones

const DEBUG_MAIN_INF = true;

function infLog(msg) {
    if (DEBUG_MAIN_INF) console.log("INF-MAIN:", msg);
}

infLog("infracciones.js cargado correctamente");

/* ============================================================
   INICIALIZACIÓN GENERAL DEL MÓDULO
   ============================================================ */
document.addEventListener("DOMContentLoaded", function () {

    infLog("Inicializando módulo Infracciones...");

    /* ------------------------------------------------------------
       1) Evento del botón NUEVO
       ------------------------------------------------------------ */
    const btnNuevo = document.getElementById("btnNuevoInfraccion");
    if (btnNuevo) {
        btnNuevo.addEventListener("click", function () {
            if (typeof limpiarFormularioCrear === "function") {
                limpiarFormularioCrear();
            }
            if (typeof abrirModalCrear === "function") {
                abrirModalCrear();
            }
        });
    }

    /* ------------------------------------------------------------
       2) SUBMIT: CREAR INFRACCIÓN
       ------------------------------------------------------------ */
    const formCrear = document.getElementById("formCrearInfraccion");
    if (formCrear) {
        formCrear.addEventListener("submit", function (e) {
            e.preventDefault();
            infLog("Submit: Crear infracción");

            if (typeof validarFormulario === "function") {
                if (!validarFormulario("formCrearInfraccion")) return;
            }

            $.post("ajax/guardar.php", $(this).serialize(), function (res) {

                if (res.ok) {
                    Swal.fire("Éxito", "Infracción registrada correctamente", "success");
                    $("#modalCrear").modal("hide");
                    recargarTabla();
                } else {
                    Swal.fire("Error", res.msg || "No se pudo guardar", "error");
                }

            }, "json");
        });
    }

    /* ------------------------------------------------------------
       3) SUBMIT: EDITAR INFRACCIÓN
       ------------------------------------------------------------ */
    const formEditar = document.getElementById("formEditarInfraccion");
    if (formEditar) {
        formEditar.addEventListener("submit", function (e) {
            e.preventDefault();
            infLog("Submit: Editar infracción");

            if (typeof validarFormulario === "function") {
                if (!validarFormulario("formEditarInfraccion")) return;
            }

            $.post("ajax/actualizar.php", $(this).serialize(), function (res) {

                if (res.ok) {
                    Swal.fire("Actualizado", "Infracción actualizada correctamente", "success");
                    $("#modalEditar").modal("hide");
                    recargarTabla();
                } else {
                    Swal.fire("Error", res.msg || "No se pudo actualizar", "error");
                }

            }, "json");
        });
    }

    /* ------------------------------------------------------------
       4) VER INFRACCIÓN
       ------------------------------------------------------------ */
    $(document).on("click", ".btnVerInfraccion", function () {

        var id = $(this).data("id");
        infLog("Ver infracción ID: " + id);

        $.post("ajax/obtener.php", { id: id }, function (res) {

            if (!res) {
                Swal.fire("Error", "No se pudo cargar la infracción", "error");
                return;
            }

            $("#ver_codigo").text(res.codigo);
            $("#ver_descripcion").text(res.descripcion);
            $("#ver_gravedad").text(res.gravedad);
            $("#ver_puntos").text(res.puntos);
            $("#ver_porcentaje_uit").text(res.porcentaje_uit + " %");
            $("#ver_monto_base").text("S/ " + res.monto_base);
            $("#ver_entidad").text(res.entidad_nombre);

            $("#modalVer").modal("show");

        }, "json");
    });

    /* ============================================================
       5) DESACTIVAR INFRACCIÓN
       ============================================================ */
    $(document).on("click", ".btnDesactivarInfraccion", function () {

        var id = $(this).data("id");

        Swal.fire({
            title: "¿Desactivar infracción?",
            text: "La infracción quedará inactiva pero no eliminada.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, desactivar"
        }).then(function (result) {

            if (!result.isConfirmed) return;

            $.post("ajax/desactivar.php", { id: id }, function (res) {

                if (res.ok) {
                    Swal.fire("Desactivada", res.msg, "success");
                    recargarTabla();
                } else {
                    Swal.fire("Error", res.msg, "error");
                }

            }, "json");

        });
    });

    /* ============================================================
       6) REACTIVAR INFRACCIÓN
       ============================================================ */
    $(document).on("click", ".btnReactivarInfraccion", function () {

        var id = $(this).data("id");

        Swal.fire({
            title: "¿Reactivar infracción?",
            text: "La infracción volverá a estar activa.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, reactivar"
        }).then(function (result) {

            if (!result.isConfirmed) return;

            $.post("ajax/reactivar.php", { id: id }, function (res) {

                if (res.ok) {
                    Swal.fire("Reactivada", res.msg, "success");
                    recargarTabla();
                } else {
                    Swal.fire("Error", res.msg, "error");
                }

            }, "json");

        });
    });

    /* ============================================================
       7) RECUPERAR INFRACCIÓN ELIMINADA
       ============================================================ */
    $(document).on("click", ".btnRecuperarInfraccion", function () {

        var id = $(this).data("id");

        Swal.fire({
            title: "¿Recuperar infracción eliminada?",
            text: "La infracción será restaurada.",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Sí, recuperar"
        }).then(function (result) {

            if (!result.isConfirmed) return;

            $.post("ajax/reactivar.php", { id: id }, function (res) {

                if (res.ok) {
                    Swal.fire("Recuperada", res.msg, "success");
                    recargarTabla();
                } else {
                    Swal.fire("Error", res.msg, "error");
                }

            }, "json");

        });
    });

    infLog("Módulo Infracciones inicializado correctamente");
});
