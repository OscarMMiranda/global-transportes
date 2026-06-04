// archivo: modulos/vehiculos/js/tab_fotos.js

console.log("📸 tab_fotos.js cargado");

// ---------------------------------------------------------
// CARGAR TAB FOTOS
// ---------------------------------------------------------
function cargarTabFotos(idVehiculo) {
    console.log("📷 Cargando TAB Fotos para vehículo:", idVehiculo);

    $.ajax({
        url: '/modulos/vehiculos/acciones/ver_fotos.php',
        type: 'POST',
        data: { id: idVehiculo },
        dataType: 'json',

        success: function (resp) {
            if (resp.success) {
                $('#tab-fotos').html(resp.html);
            } else {
                $('#tab-fotos').html('<div class="text-danger">Error al cargar fotos.</div>');
            }
        },

        error: function (xhr) {
            console.log("❌ ERROR AJAX TAB Fotos:", xhr.responseText);
            $('#tab-fotos').html('<div class="text-danger">Error de comunicación.</div>');
        }
    });
}

// ---------------------------------------------------------
// RECARGAR FOTOS AL ABRIR EL MODAL DEL VEHÍCULO
// ---------------------------------------------------------
$("#modalVerVehiculo").on("shown.bs.modal", function () {
    const idVehiculo = $(this).data("id");
    console.log("🚚 Modal abierto → cargando fotos del vehículo:", idVehiculo);
    cargarTabFotos(idVehiculo);
});

// ---------------------------------------------------------
// CARGAR FOTOS AL CAMBIAR A LA PESTAÑA FOTOS
// ---------------------------------------------------------
$('a[href="#tab-fotos"]').on('shown.bs.tab', function () {
    const idVehiculo = $("#modalVerVehiculo").data("id");
    console.log("📁 Pestaña Fotos activada → recargando fotos");
    cargarTabFotos(idVehiculo);
});

// ---------------------------------------------------------
// ABRIR MODAL AGREGAR FOTO
// ---------------------------------------------------------
$(document).on("click", "#btnAgregarFoto", function () {
    console.log("✔ Botón Agregar Foto detectado");

    const idVehiculo = $("#modalVerVehiculo").data("id");
    $("#foto_id_vehiculo").val(idVehiculo);

    $("#alertaFoto").addClass("d-none").text(""); // limpiar alerta
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
        dataType: "json",

        success: function (resp) {

            console.log("📡 RESPUESTA agregar_foto.php:", resp);

            if (resp.success) {

                $("#modalAgregarFoto").modal("hide");

                const idVehiculo = $("#modalVerVehiculo").data("id");
                cargarTabFotos(idVehiculo); // recargar fotos

            } else {

                // Mostrar alerta elegante dentro del modal
                $("#alertaFoto")
                    .removeClass("d-none")
                    .hide()
                    .text(resp.msg)
                    .fadeIn(200);
            }
        },

        error: function (xhr) {
            console.log("❌ ERROR AJAX:", xhr.responseText);

            $("#alertaFoto")
                .removeClass("d-none")
                .hide()
                .text("Error de comunicación con el servidor")
                .fadeIn(200);
        }
    });
});

// ---------------------------------------------------------
// VISOR DE FOTO
// ---------------------------------------------------------
$(document).on("click", ".btn-ver-foto", function () {
    const img = $(this).data("img");
    $("#imgVisor").attr("src", img);
    $("#visorFoto").show();
});

$(document).on("click", "#cerrarVisorFoto", function () {
    $("#visorFoto").hide();
    $("#imgVisor").attr("src", "");
});

// ---------------------------------------------------------
// ELIMINAR FOTO
// ---------------------------------------------------------
$(document).on("click", ".btn-eliminar-foto", function () {
    const idFoto = $(this).data("id");
    const idVehiculo = $("#modalVerVehiculo").data("id");

    if (!confirm("¿Seguro que deseas eliminar esta foto?")) {
        return;
    }

    $.ajax({
        url: "/modulos/vehiculos/acciones/eliminar_foto.php",
        type: "POST",
        data: { id_foto: idFoto },
        dataType: "json",
        success: function (resp) {
            if (resp.success) {
                cargarTabFotos(idVehiculo);
            } else {
                $("#alertaFoto")
                    .removeClass("d-none")
                    .hide()
                    .text(resp.msg)
                    .fadeIn(200);
            }
        },
        error: function (xhr) {
            console.log("❌ ERROR AJAX eliminar_foto:", xhr.responseText);
            $("#alertaFoto")
                .removeClass("d-none")
                .hide()
                .text("Error de comunicación al eliminar la foto")
                .fadeIn(200);
        }
    });
});

// ---------------------------------------------------------
// EDITAR DESCRIPCIÓN DE FOTO
// ---------------------------------------------------------
$(document).on("click", ".btn-editar-foto", function () {
    const idFoto = $(this).data("id");
    const descActual = $(this).data("desc") || "";
    const idVehiculo = $("#modalVerVehiculo").data("id");

    const nuevaDesc = prompt("Nueva descripción de la foto:", descActual);
    if (nuevaDesc === null) {
        return; // cancelado
    }

    $.ajax({
        url: "/modulos/vehiculos/acciones/actualizar_descripcion_foto.php",
        type: "POST",
        data: { 
            id_foto: idFoto,
            descripcion: nuevaDesc
        },
        dataType: "json",
        success: function (resp) {
            if (resp.success) {
                cargarTabFotos(idVehiculo);
            } else {
                $("#alertaFoto")
                    .removeClass("d-none")
                    .hide()
                    .text(resp.msg)
                    .fadeIn(200);
            }
        },
        error: function (xhr) {
            console.log("❌ ERROR AJAX actualizar_descripcion_foto:", xhr.responseText);
            $("#alertaFoto")
                .removeClass("d-none")
                .hide()
                .text("Error de comunicación al actualizar la descripción")
                .fadeIn(200);
        }
    });
});
