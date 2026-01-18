// archivo: /modulos/vehiculos/js/acciones.js

console.log("📦 acciones.js inicializado");

// ---------------------------------------------------------
// DESACTIVAR VEHÍCULO
// ---------------------------------------------------------
$(document).on("click", ".btn-desactivar", function () {
    const id = $(this).data("id");

    confirmarAccion(
        "Desactivar vehículo",
        "¿Está seguro de que desea desactivar este vehículo? Ya no aparecerá en las listas activas.",
        function () {
            $.ajax({
                url: "/modulos/vehiculos/acciones/desactivar.php",
                type: "POST",
                data: { id: id },
                dataType: "json",

                success: function (r) {
                    if (r.ok) {
                        notifyWarning(
                            "Vehículo desactivado",
                            "El vehículo ya no aparece en las listas activas."
                        );

                        // 🔥 Recargar ambas tablas
                        VehiculosDT.reloadActivos();
                        VehiculosDT.reloadInactivos();

                    } else {
                        notifyError("No se pudo desactivar", r.msg);
                    }
                },

                error: function () {
                    notifyError("Error de comunicación", "No se pudo completar la operación.");
                }
            });
        }
    );
});


// ---------------------------------------------------------
// RESTAURAR VEHÍCULO
// ---------------------------------------------------------
$(document).on("click", ".btn-restaurar", function () {
    const id = $(this).data("id");

    confirmarAccion(
        "Restaurar vehículo",
        "¿Desea restaurar este vehículo? Volverá a estar disponible en el sistema.",
        function () {
            $.ajax({
                url: "/modulos/vehiculos/acciones/restaurar.php",
                type: "POST",
                data: { id: id },
                dataType: "json",

                success: function (r) {
                    if (r.ok) {
                        notifySuccess(
                            "Vehículo restaurado",
                            "El vehículo vuelve a estar disponible."
                        );

                        // 🔥 Recargar ambas tablas
                        VehiculosDT.reloadActivos();
                        VehiculosDT.reloadInactivos();

                    } else {
                        notifyError("No se pudo restaurar", r.msg);
                    }
                },

                error: function () {
                    notifyError("Error de comunicación", "No se pudo completar la operación.");
                }
            });
        }
    );
});


// ---------------------------------------------------------
// ELIMINAR VEHÍCULO
// ---------------------------------------------------------
$(document).on("click", ".btn-eliminar", function () {
    const id = $(this).data("id");

    confirmarAccion(
        "Eliminar vehículo",
        "Esta acción eliminará el vehículo permanentemente. ¿Desea continuar?",
        function () {
            $.ajax({
                url: "/modulos/vehiculos/acciones/eliminar.php",
                type: "POST",
                data: { id: id },
                dataType: "json",

                success: function (r) {
                    if (r.ok) {
                        notifyWarning(
                            "Vehículo eliminado",
                            "El registro fue eliminado permanentemente."
                        );

                        // 🔥 Recargar tabla de inactivos
                        VehiculosDT.reloadInactivos();

                    } else {
                        notifyError("No se pudo eliminar", r.msg);
                    }
                },

                error: function () {
                    notifyError("Error de comunicación", "No se pudo completar la operación.");
                }
            });
        }
    );
});