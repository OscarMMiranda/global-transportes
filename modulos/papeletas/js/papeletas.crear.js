// archivo: /modulos/papeletas/js/papeletas.crear.js

const DEBUG_PAP_CREAR = true;
function plogCrear(msg) { if (DEBUG_PAP_CREAR) console.log("PAP-CREAR:", msg); }

/* ============================================================
   ABRIR MODAL NUEVA PAPELETA
   ============================================================ */
$(document).on("click", "#btnNuevaPapeleta", function () {

    $("#formNuevaPapeleta")[0].reset();

    $.when(
        cargarVehiculosSelect(),
        cargarConductoresSelect(),
        cargarEntidadEmisoraSelect()
    ).done(function () {

        // limpiar infracciones al abrir el modal
        $("#infraccionSelect").html("<option value=''>-- Seleccione entidad --</option>");

        $("#modalNuevaPapeleta").modal("show");
    });
});

/* ============================================================
   CAMBIO DE ENTIDAD → CARGAR INFRACCIONES
   ============================================================ */
$(document).on("change", "#entidadEmisoraSelect", function () {

    let entidad_id = $(this).val();

    if (!entidad_id) {
        $("#infraccionSelect").html("<option value=''>-- Seleccione entidad --</option>");
        return;
    }

    $.post('/modulos/infracciones/acciones/listar_por_entidad.php',
        { entidad_id: entidad_id },
        function(html) {
            $("#infraccionSelect").html(html);
        }
    );
});

/* ============================================================
   GUARDAR NUEVA PAPELETA
   ============================================================ */
$(document).on("click", "#btnGuardarPapeleta", function () {

    let codigo = $("input[name='codigo_papeleta']").val().trim();
    let vehiculo = $("#vehiculoNuevaSelect").val();
    let entidad = $("#entidadEmisoraSelect").val();
    let infraccion = $("#infraccionSelect").val();
    let fecha = $("input[name='fecha_infraccion']").val();

    if (codigo === "") return Swal.fire("Advertencia", "Debe ingresar el número de papeleta", "warning");
    if (!vehiculo) return Swal.fire("Advertencia", "Debe seleccionar un vehículo", "warning");
    if (!entidad) return Swal.fire("Advertencia", "Debe seleccionar una entidad emisora", "warning");
    if (!infraccion) return Swal.fire("Advertencia", "Debe seleccionar una infracción", "warning");
    if (fecha === "") return Swal.fire("Advertencia", "Debe ingresar la fecha de infracción", "warning");

    $.post('/modulos/papeletas/acciones/guardar.php',
        $("#formNuevaPapeleta").serialize(),
        function(res) {

            if (res.ok) {
                Swal.fire("Éxito", "Papeleta registrada correctamente", "success");
                $("#modalNuevaPapeleta").modal("hide");
                cargarPapeletas();
            } else {
                Swal.fire("Error", res.msg || "No se pudo registrar", "error");
            }

        }, 'json'
    );
});
