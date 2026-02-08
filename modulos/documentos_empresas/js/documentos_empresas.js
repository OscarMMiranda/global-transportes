$(document).ready(function () {

    console.log("documentos_empresas.js cargado correctamente");

    const urlParams = new URLSearchParams(window.location.search);
    const empresa_id = urlParams.get("id");

    // ============================================================
    // ABRIR MODAL PARA ADJUNTAR O REEMPLAZAR
    // ============================================================
    $(document).on("click", ".btn-subir", function () {

        const tipo_id = $(this).data("tipo");
        const nombre  = $(this).data("nombre");

        $("#tipo_documento_id").val(tipo_id);
        $("#empresa_id").val(empresa_id);

        $("#tituloModalSubir").text("Subir documento: " + nombre);
        $("#modalSubirDocumento").modal("show");
    });

    // ============================================================
    // PREVIEW PDF
    // ============================================================
    $(document).on("click", ".btn-preview", function () {

        const url = $(this).data("url");

        $("#previewContenidoEmpresa").html(
            '<embed src="' + url + '" type="application/pdf" style="width:100%; height:100%;">'
        );

        $("#modalPreview").modal("show");
    });

    // ============================================================
    // GUARDAR DOCUMENTO
    // ============================================================
    $(document).on("submit", "#formSubirDocumento", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append("empresa_id", empresa_id);

        $.ajax({
            url: "../acciones/subir_documento_empresa.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",

            beforeSend: function () {
                $("#btnGuardarDocumento").prop("disabled", true).text("Guardando...");
            },

            success: function (json) {

                if (json.success) {

                    alert("Documento guardado correctamente.");

                    $("#modalSubirDocumento").modal("hide");
                    $("#formSubirDocumento")[0].reset();

                    $.fn.dataTable
                        .tables({ visible: true, api: true })
                        .ajax.reload(null, false);

                } else {
                    alert(json.message || "Error desconocido.");
                }
            },

            error: function (xhr) {
                console.error("ERROR AJAX:", xhr.responseText);
                alert("Error en el servidor. No se pudo guardar el documento.");
            },

            complete: function () {
                $("#btnGuardarDocumento").prop("disabled", false).text("Guardar");
            }
        });
    });

});

// ============================================================
// ABRIR HISTORIAL
// ============================================================
$(document).on("click", ".btn-historial", function () {

    const empresa_id = $(this).data("empresa");
    const tipo_documento_id = $(this).data("tipo");

    console.log("HISTORIAL → empresa:", empresa_id, "tipo:", tipo_documento_id);

    cargarHistorial(empresa_id, tipo_documento_id);
});
