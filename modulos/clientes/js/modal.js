// archivo: /modulos/clientes/assets/modal.js

console.log("DEBUG MODAL: modal.js cargado correctamente");

// ===============================
// FUNCIÓN: VER CLIENTE
// ===============================
function verCliente(id) {

    console.log("DEBUG MODAL: Abriendo modal VER CLIENTE ID:", id);

    $.ajax({
        url: "/modulos/clientes/ajax/ver_cliente.php",
        type: "GET",
        data: { id: id },
        dataType: "json",

        success: function (response) {

            console.log("DEBUG MODAL: Respuesta recibida:", response);

            // VALIDACIÓN REAL
            if (!response || typeof response !== "object") {
                console.error("DEBUG MODAL ERROR: Respuesta inválida en ver_cliente.php");
                Swal.fire("Error", "Respuesta inválida del servidor.", "error");
                return;
            }

            if (response.error) {
                console.error("DEBUG MODAL ERROR:", response.error);
                Swal.fire("Error", response.error, "error");
                return;
            }

            // LLENAR CAMPOS
            $("#ver_id").text(response.id);
            $("#ver_nombre").text(response.nombre);
            $("#ver_tipo_cliente").text(response.tipo_cliente);
            $("#ver_ruc").text(response.ruc);
            $("#ver_direccion").text(response.direccion);
            $("#ver_telefono").text(response.telefono);
            $("#ver_correo").text(response.correo);
            $("#ver_estado").text(response.estado);

            // MOSTRAR MODAL
            new bootstrap.Modal(document.getElementById("modalVerCliente")).show();
        },

        error: function (xhr, status, error) {
            console.error("DEBUG MODAL ERROR AJAX:", error);
            Swal.fire("Error", "No se pudo obtener los datos del cliente.", "error");
        }
    });
}

// ===============================
// EVENTO: BOTÓN VER
// ===============================
$(document).on("click", ".btnVerCliente", function () {
    var id = $(this).data("id");
    console.log("DEBUG ACC: Clic en VER cliente ID:", id);
    verCliente(id);
});
