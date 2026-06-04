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

    infLog("Módulo Infracciones inicializado correctamente");
});
