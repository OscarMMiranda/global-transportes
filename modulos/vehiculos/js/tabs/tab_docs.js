// archivo: /modulos/vehiculos/js/tabs/tab_docs.js

function cargarTabDocs() {

    if (TAB_CARGADO.docs) return;

    $("#tab-docs").html(loaderHTML("Cargando documentos..."));

    $.get("/modulos/vehiculos/acciones/editar_documentos.php", { id: ID_VEHICULO_EDITAR })
        .done(function (resp) {
            $("#tab-docs").html(resp);
            TAB_CARGADO.docs = true;
        })
        .fail(function () {
            $("#tab-docs").html(errorHTML("Error al cargar documentos"));
        });
}


// ======================================================
//  ABRIR MODAL + CARGAR TIPOS DE DOCUMENTO (AJAX JSON)
// ======================================================

function abrirSubirDocumento(idVehiculo) {

    $("#doc_entidad_id").val(idVehiculo);

    $.ajax({
        url: "/modulos/vehiculos/acciones/get_tipos_documento.php",
        type: "GET",
        dataType: "json", // ← CLAVE: jQuery convierte la respuesta automáticamente
        success: function (r) {

            if (!r.success) {
                console.error("ERROR SQL:", r.error);
                console.error("SQL:", r.sql);
                alert("Error cargando tipos de documento");
                return;
            }

            let html = "<option value=''>Seleccione...</option>";

            r.data.forEach(function (t) {
                html += "<option value='" + t.id + "'>" + t.nombre + "</option>";
            });

            $("#selectTipoDocumento").html(html);
        },
        error: function () {
            alert("Error al cargar tipos de documento.");
        }
    });

    const modal = new bootstrap.Modal(document.getElementById("modalSubirDocumento"));
    modal.show();
}


// ======================================================
//  GUARDAR DOCUMENTO (FORMDATA + JSON)
// ======================================================

function guardarDocumento() {

    let form = document.getElementById("formSubirDocumento");
    let data = new FormData(form);

    $.ajax({
        url: "/modulos/vehiculos/acciones/guardar_documento.php",
        type: "POST",
        data: data,
        contentType: false,
        processData: false,
        success: function (resp) {

            // RESPUESTA JSON DEL BACKEND
            try {
                var r = JSON.parse(resp);

                if (r.success) {
                    alert("Documento guardado correctamente.");
                    cargarTabDocs(); // recargar tab
                    bootstrap.Modal.getInstance(document.getElementById("modalSubirDocumento")).hide();
                } else {
                    alert("Error: " + r.message);
                }

            } catch (e) {
                console.error("RESPUESTA NO JSON:", resp);
                alert("Error inesperado al procesar la respuesta.");
            }
        },
        error: function () {
            alert("Error al enviar el formulario.");
        }
    });
}

function editarDocumento(idDocumento) {

    $.ajax({
        url: "/modulos/vehiculos/acciones/get_documento.php",
        type: "GET",
        data: { id: idDocumento },
        dataType: "json",
        success: function(r) {

            if (!r.success) {
                alert("Error cargando documento");
                return;
            }

            let d = r.data;

            $("#edit_doc_id").val(d.id);
            $("#edit_numero").val(d.numero);
            $("#edit_fecha_inicio").val(d.fecha_inicio);
            $("#edit_fecha_vencimiento").val(d.fecha_vencimiento);
            $("#edit_observaciones").val(d.observaciones);

            $.ajax({
                url: "/modulos/vehiculos/acciones/get_tipos_documento.php",
                type: "GET",
                dataType: "json",
                success: function(r2) {

                    let html = "<option value=''>Seleccione...</option>";

                    r2.data.forEach(t => {
                        html += `<option value="${t.id}">${t.nombre}</option>`;
                    });

                    $("#edit_tipo_documento").html(html);
                    $("#edit_tipo_documento").val(d.tipo_documento_id);
                }
            });

            const modal = new bootstrap.Modal(document.getElementById("modalEditarDocumento"));
            modal.show();
        }
    });
}

function guardarEdicionDocumento() {

    let form = document.getElementById("formEditarDocumento");
    let data = new FormData(form);

    $.ajax({
        url: "/modulos/vehiculos/acciones/guardar_edicion_documento.php",
        type: "POST",
        data: data,
        contentType: false,
        processData: false,
        success: function(resp) {

            let r = JSON.parse(resp);

            if (r.success) {
                alert("Documento actualizado.");
                cargarTabDocs();
                bootstrap.Modal.getInstance(document.getElementById("modalEditarDocumento")).hide();
            } else {
                alert("Error al actualizar.");
            }
        }
    });
}
