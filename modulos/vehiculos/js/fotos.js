// archivo: /modulos/vehiculos/js/fotos.js

console.log("📸 fotos.js cargado correctamente");

// ---------------------------------------------------------
// ABRIR MODAL AGREGAR FOTO
// ---------------------------------------------------------
$(document).on("click", "#btnAgregarFoto", function () {
    console.log("✔ Botón Agregar Foto detectado");

    const idVehiculo = $("#modalVerVehiculo").data("id");
    $("#foto_id_vehiculo").val(idVehiculo);

    $("#formAgregarFoto")[0].reset();
    $("#modalAgregarFoto").modal("show");
});

// ---------------------------------------------------------
// GUARDAR FOTO
// ---------------------------------------------------------
$(document).on("click", "#btnGuardarFoto", function () {

    console.log("✔ Guardar foto presionado");

    let formData = new FormData($("#formAgregarFoto")[0]);

    $.ajax({
        url: "/modulos/vehiculos/acciones/agregar_foto.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (resp) {

            console.log("📡 RESPUESTA agregar_foto.php:", resp);

            try { 
                resp = JSON.parse(resp); 
            } catch (e) {
                alert("Error inesperado en la respuesta del servidor");
                return;
            }

            if (resp.success) {
                $("#modalAgregarFoto").modal("hide");

                const idVehiculo = $("#modalVerVehiculo").data("id");
                cargarTabFotos(idVehiculo); // recargar fotos
            } else {
                alert("Error: " + resp.msg);
            }
        },

        error: function(xhr){
            console.log("❌ ERROR AJAX:", xhr.responseText);
            alert("Error de comunicación con el servidor");
        }
    });
});
