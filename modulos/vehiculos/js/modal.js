// archivo: /modulos/vehiculos/js/modal.js

console.log("📦 modal.js inicializado");

// ---------------------------------------------------------
// VER VEHÍCULO
// ---------------------------------------------------------
$(document).on("click", ".btn-view", function () {

    const id = $(this).data("id");

    $.ajax({
        url: "/modulos/vehiculos/acciones/ver.php",
        type: "GET",
        data: { id: id },
        dataType: "json",

        success: function (v) {

            if (v.error) {
                notifyError("Error al cargar", v.error);
                return;
            }

            $("#v_placa").text(v.placa);
            $("#v_marca").text(v.marca_nombre);
            $("#v_modelo").text(v.modelo);
            $("#v_anio").text(v.anio);

            $("#v_tipo").text(v.tipo_nombre);
            $("#v_estado").text(v.estado_nombre);
            $("#v_configuracion").text(v.configuracion_nombre);
            $("#v_empresa").text(v.empresa_nombre);

            $("#v_observaciones").text(v.observaciones || "—");

            $("#btnFichaCompleta").attr("href", "/modulos/vehiculos/vistas/detalle.php?id=" + v.id);

            $("#modalVerVehiculo").modal("show");
        },

        error: function (xhr) {
            console.log("❌ Error AJAX:", xhr.responseText);
            notifyError("Error al cargar", "No se pudieron obtener los datos del vehículo.");
        }
    });
});


// ---------------------------------------------------------
// NUEVO VEHÍCULO
// ---------------------------------------------------------
$(document).on("click", ".btn-nuevo", function () {

    $("#modalVehiculoTitulo").text("Nuevo vehículo");

    $("#modalVehiculoBody").html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-3">Cargando formulario...</p>
        </div>
    `);

    $("#modalVehiculoBody").load(
        "/modulos/vehiculos/vistas/formulario.php",
        function () {
            $("#modalVehiculo").modal("show");
        }
    );
});


// ---------------------------------------------------------
// EDITAR VEHÍCULO
// ---------------------------------------------------------
$(document).on("click", ".btn-edit", function () {

    const id = $(this).data("id");

    $("#modalVehiculoTitulo").text("Editar vehículo");

    $("#modalVehiculoBody").html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-3">Cargando datos...</p>
        </div>
    `);

    $("#modalVehiculoBody").load(
        "/modulos/vehiculos/vistas/formulario.php?id=" + id,
        function () {
            $("#modalVehiculo").modal("show");
        }
    );
});


// ---------------------------------------------------------
// AGREGAR CONFIGURACIÓN
// ---------------------------------------------------------
$(document).on("click", ".btn-add-config", function () {
    $("#formConfig")[0].reset();
    $("#modalConfig").modal("show");
});


// ---------------------------------------------------------
// GUARDAR NUEVA CONFIGURACIÓN
// ---------------------------------------------------------
$(document).on("submit", "#formConfig", function (e) {
    e.preventDefault();

    $.ajax({
        url: "/modulos/vehiculos/acciones/crear_configuracion.php",
        type: "POST",
        data: $(this).serialize(),
        dataType: "json",

        success: function (r) {
            if (r && r.ok) {
                notifySuccess("Configuración creada", "La nueva configuración está disponible.");

                // Agregar al select
                var $select = $("#configuracion_id");
                if ($select.length) {
                    $select.append(
                        $('<option>', {
                            value: r.id,
                            text: r.nombre,
                            selected: true
                        })
                    );
                }

                $("#modalConfig").modal("hide");
            } else {
                notifyError("No se pudo crear", r.msg || "Error desconocido");
            }
        },

        error: function (xhr) {
            console.error("❌ Error AJAX:", xhr.responseText);
            notifyError("Error de comunicación", "No se pudo guardar la configuración.");
        }
    });
});