// /modulos/documentos_empresas/js/documentos_empresas.js

$(document).ready(function () {

    console.log("documentos_empresas.js cargado correctamente");

    // Evento submit del formulario de subir documento
    $("#formSubirDocumento").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "/modulos/documentos_empresas/acciones/subir_documento_empresa.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            timeout: 20000,

            success: function (resp) {
                console.log("RESPUESTA DEL SERVIDOR:", resp);

                // Si el servidor devolvió JSON válido
                if (resp && resp.success) {

                    alert("Documento guardado correctamente.");

                    // Cerrar modal
                    $("#modalSubirDocumento").modal("hide");

                    // Recargar tabla activa
                    let tablaActiva = $(".tab-pane.active table").attr("id");
                    $("#" + tablaActiva).DataTable().ajax.reload(null, false);

                } else {
                    alert("Error: " + (resp.message || "Respuesta inválida del servidor"));
                }
            },

            error: function (xhr, status, error) {
                console.log("ERROR AJAX:", xhr.responseText, status, error);
                alert("Error en el servidor. No se pudo guardar el documento.");
            },

            complete: function () {
                console.log("AJAX COMPLETADO");
            }
        });
    });

});
