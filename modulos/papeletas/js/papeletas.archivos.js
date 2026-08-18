// archivo: /modulos/papeletas/js/papeletas.archivos.js

/* ============================================================
   UTILIDADES GENERALES
   ============================================================ */

// Spinner corporativo
var spinner = '<div class="py-4 text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>';

// Cargar listado de archivos
function cargarArchivos(id) {

    $("#contenedorArchivosPapeleta").html(spinner);

    $.post('/modulos/papeletas/acciones/ver_archivos.php', { id: id }, function(res){

        if (!res.success) {
            $("#contenedorArchivosPapeleta").html(
                "<div class='text-danger text-center py-4'>" + res.html + "</div>"
            );
            return;
        }

        $("#contenedorArchivosPapeleta").html(res.html);

    }, 'json');
}

// Validar archivo antes de subir
function validarArchivo(inputFile) {

    if (!inputFile || inputFile.length === 0) {
        Swal.fire("Error", "Debe seleccionar un archivo", "error");
        return false;
    }

    var archivo = inputFile[0];

    // Validar tamaño (5 MB)
    if (archivo.size > 5 * 1024 * 1024) {
        Swal.fire("Error", "El archivo supera el límite de 5 MB", "error");
        return false;
    }

    // Validar extensión
    var ext = archivo.name.split('.').pop().toLowerCase();
    var permitidos = ["pdf", "jpg", "jpeg", "png"];

    if ($.inArray(ext, permitidos) === -1) {
        Swal.fire("Error", "Formato no permitido", "error");
        return false;
    }

    return true;
}


/* ============================================================
   ABRIR MODAL Y CARGAR ARCHIVOS
   ============================================================ */
$(document).on("click", ".btnVerArchivos", function() {

    var id = $(this).data("id");

    $("#archivos_papeleta_id").val(id);
    cargarArchivos(id);

    $("#modalVerArchivos").modal("show");
});


/* ============================================================
   LIMPIAR MODAL AL CERRAR
   ============================================================ */
$('#modalVerArchivos').on('hidden.bs.modal', function () {
    $("#archivos_papeleta_id").val("");
    $("#contenedorArchivosPapeleta").html("");
});


/* ============================================================
   SUBIR ARCHIVO
   ============================================================ */
$(document).on("submit", "#formSubirArchivo", function(e){
    e.preventDefault();

    var archivoInput = $("#archivo")[0].files;

    // Validación previa
    if (!validarArchivo(archivoInput)) {
        return;
    }

    var formData = new FormData(this);

    $("#btnSubirArchivo").prop("disabled", true);

    $.ajax({
        url: '/modulos/papeletas/acciones/subir_archivo.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',

        success: function(res){

            $("#btnSubirArchivo").prop("disabled", false);

            if (!res.success) {
                Swal.fire("Error", res.msg, "error");
                return;
            }

            Swal.fire("Éxito", "Archivo subido correctamente", "success");

            cargarArchivos($("#archivos_papeleta_id").val());

            $("#archivo").val("");
            $("#descripcion_archivo").val("");
        }
    });
});


/* ============================================================
   ELIMINAR ARCHIVO
   ============================================================ */
$(document).on("click", ".btnEliminarArchivo", function() {

    var id = $(this).data("id");

    Swal.fire({
        title: "Confirmar",
        text: "¿Eliminar este archivo?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then(function(result){

        if (!result.isConfirmed) return;

        $.post('/modulos/papeletas/acciones/eliminar_archivo.php', { id: id }, function(res){

            if (!res.success && !res.ok) {
                Swal.fire("Error", res.msg, "error");
                return;
            }

            Swal.fire("Eliminado", "El archivo fue marcado como eliminado.", "success");

            cargarArchivos($("#archivos_papeleta_id").val());

        }, 'json');

    });

});
